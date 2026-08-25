-- Migracion inicial: replica el schema real de produccion (edukatic.co / edtk_eduteka),
-- extraido con pg_dump --schema-only el 2026-08-25. No se reescribe desde cero: se
-- reorganiza el DDL real tal cual corre hoy en el VPS, para no introducir divergencias.

CREATE EXTENSION IF NOT EXISTS vector WITH SCHEMA public;

-- ---------------------------------------------------------------------------
-- articles
-- ---------------------------------------------------------------------------

CREATE TABLE public.articles (
    id                   integer NOT NULL,
    slug                 text NOT NULL,
    title                text NOT NULL,
    file_path            text NOT NULL,
    body                 text NOT NULL,
    original_category    text,
    original_tags        text,
    article_date         date,
    category_id          integer,
    created_at           timestamp without time zone DEFAULT now() NOT NULL,
    updated_at           timestamp without time zone DEFAULT now() NOT NULL,
    author               text,
    summary              text,
    estado               character varying(20) DEFAULT 'publicado'::character varying NOT NULL,
    rag_status           character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    rag_error_detail     text,
    rag_chunk_count      integer DEFAULT 0 NOT NULL,
    rag_indexed_at       timestamp with time zone,
    content_hash         character(64),
    chat_sugerencias     jsonb,
    portada_status       character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    portada_generated_at timestamp with time zone,
    portada_error        text,
    portada_origen       character varying(20),
    CONSTRAINT articles_pkey PRIMARY KEY (id),
    CONSTRAINT articles_slug_key UNIQUE (slug),
    CONSTRAINT articles_estado_check CHECK (((estado)::text = ANY ((ARRAY['borrador'::character varying, 'publicado'::character varying, 'archivado'::character varying])::text[]))),
    CONSTRAINT articles_rag_status_check CHECK (((rag_status)::text = ANY ((ARRAY['pending'::character varying, 'processing'::character varying, 'ready'::character varying, 'error'::character varying])::text[]))),
    CONSTRAINT articles_portada_status_check CHECK (((portada_status)::text = ANY ((ARRAY['pending'::character varying, 'ready'::character varying, 'error'::character varying])::text[])))
);

CREATE INDEX idx_articles_category_id ON public.articles USING btree (category_id);
CREATE INDEX idx_articles_estado ON public.articles USING btree (estado);
CREATE INDEX idx_articles_rag_status ON public.articles USING btree (rag_status);
CREATE INDEX idx_articles_portada_status ON public.articles USING btree (portada_status);

-- ---------------------------------------------------------------------------
-- categories / tags / article_tags
-- ---------------------------------------------------------------------------

CREATE TABLE public.categories (
    id          integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name        text NOT NULL,
    description text,
    created_at  timestamp without time zone DEFAULT now() NOT NULL,
    CONSTRAINT categories_name_key UNIQUE (name)
);

ALTER TABLE public.articles
    ADD CONSTRAINT fk_articles_category FOREIGN KEY (category_id) REFERENCES public.categories(id);

CREATE TABLE public.tags (
    id         integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name       text NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    CONSTRAINT tags_name_key UNIQUE (name)
);

CREATE TABLE public.article_tags (
    article_id integer NOT NULL REFERENCES public.articles(id) ON DELETE CASCADE,
    tag_id     integer NOT NULL REFERENCES public.tags(id) ON DELETE CASCADE,
    CONSTRAINT article_tags_pkey PRIMARY KEY (article_id, tag_id)
);

CREATE INDEX idx_article_tags_tag_id ON public.article_tags USING btree (tag_id);

-- ---------------------------------------------------------------------------
-- embeddings (legado, array plano) y embeddings_small (pgvector, en uso real)
-- ---------------------------------------------------------------------------

-- Tabla legada: el mismo enfoque con el que arranco este proyecto (array de
-- doubles, sin indice de similitud). Se mantiene por continuidad historica del
-- schema real; el RAG en produccion ya no la usa, usa embeddings_small.
CREATE TABLE public.embeddings (
    id         integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    article_id integer NOT NULL REFERENCES public.articles(id) ON DELETE CASCADE,
    model      text NOT NULL,
    dimensions integer NOT NULL,
    embedding  double precision[] NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    CONSTRAINT embeddings_article_id_key UNIQUE (article_id)
);

-- La real: un embedding por articulo, text-embedding-3-small (1536 dim), HNSW coseno.
CREATE TABLE public.embeddings_small (
    id         bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    article_id integer NOT NULL REFERENCES public.articles(id) ON DELETE CASCADE,
    model      text NOT NULL,
    dimensions integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    embedding  public.vector(1536) NOT NULL,
    CONSTRAINT embeddings_small_article_id_key UNIQUE (article_id)
);

-- m/ef_construction tal cual produccion; hnsw.ef_search se fija POR CONSULTA
-- (SET LOCAL), nunca aqui, ver articulos/lib/busqueda.php::buscar_articulos_similares().
CREATE INDEX idx_embeddings_small_hnsw ON public.embeddings_small
    USING hnsw (embedding public.vector_cosine_ops) WITH (m = '16', ef_construction = '64');

-- ---------------------------------------------------------------------------
-- Cache de embeddings de CONSULTA (busqueda y chat general comparten esta tabla)
-- ---------------------------------------------------------------------------

CREATE TABLE public.query_embeddings (
    hash                text NOT NULL,
    created_at          timestamp with time zone DEFAULT now() NOT NULL,
    sintesis_respuesta  text,
    sintesis_articulos  jsonb,
    embedding           public.vector(1536) NOT NULL,
    CONSTRAINT query_embeddings_pkey PRIMARY KEY (hash)
);

-- ---------------------------------------------------------------------------
-- Trigger: marca un articulo para reindexar cuando cambia title/body
-- ---------------------------------------------------------------------------

CREATE FUNCTION public.marcar_pendiente_reindex() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
  IF NEW.title IS DISTINCT FROM OLD.title
     OR NEW.body IS DISTINCT FROM OLD.body THEN
    NEW.rag_status := 'pending';
    NEW.content_hash := encode(sha256(convert_to(coalesce(NEW.title,'') || coalesce(NEW.body,''), 'UTF8')), 'hex');
  END IF;
  NEW.updated_at := now();
  RETURN NEW;
END;
$$;

CREATE TRIGGER trg_articles_marca_pendiente
    BEFORE UPDATE ON public.articles
    FOR EACH ROW EXECUTE FUNCTION public.marcar_pendiente_reindex();

-- ---------------------------------------------------------------------------
-- Observabilidad: uso de IA, logs de chat y de busqueda
-- ---------------------------------------------------------------------------

CREATE TABLE public.ai_usage (
    id         bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    origen     text NOT NULL,
    kind       text NOT NULL,
    tokens_in  integer,
    tokens_out integer,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    ip         inet,
    article_id integer REFERENCES public.articles(id) ON DELETE SET NULL
);

CREATE INDEX idx_usage_article ON public.ai_usage USING btree (article_id);
CREATE INDEX idx_usage_ip_created ON public.ai_usage USING btree (ip, created_at);

-- Chat sobre UN articulo especifico (articulos/chat.php) — sin recuperacion,
-- el articulo entero ya es el contexto.
CREATE TABLE public.chat_log (
    id         bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    article_id integer NOT NULL REFERENCES public.articles(id) ON DELETE CASCADE,
    pregunta   text NOT NULL,
    respuesta  text NOT NULL,
    tokens_in  integer,
    tokens_out integer,
    ip         inet,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);

CREATE INDEX idx_chat_log_article ON public.chat_log USING btree (article_id);
CREATE INDEX idx_chat_log_created ON public.chat_log USING btree (created_at DESC);

-- Chat general RAG (articulos/lib/chat_general.php) — sobre todo el corpus.
CREATE TABLE public.chat_general_log (
    id                   bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    ip                   inet,
    pregunta             text NOT NULL,
    pregunta_condensada  text,
    respuesta            text NOT NULL,
    grounding            text,
    articulos_citados    jsonb,
    tokens_in            integer,
    tokens_out           integer,
    created_at           timestamp with time zone DEFAULT now() NOT NULL
);

CREATE INDEX idx_chat_general_created ON public.chat_general_log USING btree (created_at DESC);

CREATE TABLE public.busqueda_log (
    id            bigint GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    consulta      text NOT NULL,
    n_resultados  integer NOT NULL,
    con_sintesis  boolean DEFAULT false NOT NULL,
    ip            inet,
    created_at    timestamp with time zone DEFAULT now() NOT NULL
);

CREATE INDEX idx_busqueda_log_created ON public.busqueda_log USING btree (created_at);
CREATE INDEX idx_busqueda_log_consulta_norm ON public.busqueda_log USING btree (lower(TRIM(BOTH FROM consulta)));

-- ---------------------------------------------------------------------------
-- Configuracion clave/valor (parametros editables desde el admin)
-- ---------------------------------------------------------------------------

CREATE TABLE public.configuracion (
    clave       text NOT NULL,
    valor       text NOT NULL,
    descripcion text,
    updated_at  timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT configuracion_pkey PRIMARY KEY (clave)
);
