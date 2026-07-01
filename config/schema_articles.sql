-- Esquema para artículos, categorías, etiquetas y embeddings
-- Las categorías se derivan de clustering sobre los embeddings (1 categoría por artículo).
-- Las etiquetas son muchos-a-muchos (varias etiquetas por artículo).

CREATE TABLE IF NOT EXISTS articles (
    id              INTEGER PRIMARY KEY,
    slug            TEXT NOT NULL UNIQUE,
    title           TEXT NOT NULL,
    file_path       TEXT NOT NULL,
    body            TEXT NOT NULL,
    original_category TEXT,
    original_tags   TEXT,
    article_date    DATE,
    category_id     INTEGER,
    created_at      TIMESTAMP NOT NULL DEFAULT now(),
    updated_at      TIMESTAMP NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS categories (
    id              SERIAL PRIMARY KEY,
    name            TEXT NOT NULL UNIQUE,
    description     TEXT,
    created_at      TIMESTAMP NOT NULL DEFAULT now()
);

ALTER TABLE articles
    ADD CONSTRAINT fk_articles_category
    FOREIGN KEY (category_id) REFERENCES categories(id);

CREATE TABLE IF NOT EXISTS tags (
    id              SERIAL PRIMARY KEY,
    name            TEXT NOT NULL UNIQUE,
    created_at      TIMESTAMP NOT NULL DEFAULT now()
);

CREATE TABLE IF NOT EXISTS article_tags (
    article_id      INTEGER NOT NULL REFERENCES articles(id) ON DELETE CASCADE,
    tag_id          INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (article_id, tag_id)
);

CREATE TABLE IF NOT EXISTS embeddings (
    id              SERIAL PRIMARY KEY,
    article_id      INTEGER NOT NULL UNIQUE REFERENCES articles(id) ON DELETE CASCADE,
    model           TEXT NOT NULL,
    dimensions      INTEGER NOT NULL,
    embedding       DOUBLE PRECISION[] NOT NULL,
    created_at      TIMESTAMP NOT NULL DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_articles_category_id ON articles(category_id);
CREATE INDEX IF NOT EXISTS idx_article_tags_tag_id ON article_tags(tag_id);
