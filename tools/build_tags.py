"""
Fase 3: Extracción de etiquetas por artículo con GPT-4.1-mini.
Requiere que articles ya estén en la BD.

Envía título + cuerpo (primeros ~800 tokens) a GPT-4.1-mini y pide
3-7 etiquetas relevantes en español. Inserta en tags y article_tags.
Procesa en batches concurrentes (hilos) para terminar en tiempo razonable.
"""
import json
import os
import sys
import time
from concurrent.futures import ThreadPoolExecutor, as_completed

import psycopg2
import psycopg2.pool
import tiktoken
from openai import OpenAI

DB = dict(host="127.0.0.1", port=5432, dbname="edtk_eduteka",
          user="edtk_root_eduteka", password="5up3rS4j123@!",
          options="-c client_encoding=UTF8")

client = OpenAI(api_key=os.environ["OPENAI_API_KEY"])
enc = tiktoken.get_encoding("cl100k_base")
MAX_BODY_TOKENS = 800
CONCURRENCY = 8


def truncate(text, max_tokens):
    tokens = enc.encode(text)
    return enc.decode(tokens[:max_tokens]) if len(tokens) > max_tokens else text


def extract_tags(article_id: int, title: str, body: str) -> tuple[int, list[str]]:
    body_short = truncate(body, MAX_BODY_TOKENS)
    prompt = (
        "Eres un experto en educación y tecnología. "
        "Dado el siguiente artículo, extrae entre 3 y 7 etiquetas temáticas relevantes.\n\n"
        f"Título: {title}\n\nContenido:\n{body_short}\n\n"
        "Responde únicamente con un JSON: {\"tags\": [\"etiqueta1\", \"etiqueta2\", ...]}.\n"
        "Las etiquetas deben ser en español, minúsculas, concretas y útiles para búsqueda."
    )
    for attempt in range(4):
        try:
            resp = client.chat.completions.create(
                model="gpt-4.1-mini",
                messages=[{"role": "user", "content": prompt}],
                response_format={"type": "json_object"},
                max_tokens=120,
                temperature=0.0,
            )
            data = json.loads(resp.choices[0].message.content)
            tags = [t.strip().lower() for t in data.get("tags", []) if t.strip()]
            return article_id, tags
        except Exception as e:
            wait = 2 ** attempt
            time.sleep(wait)
    return article_id, []


def upsert_tags(conn, article_id: int, tags: list[str]):
    with conn.cursor() as cur:
        for tag_name in tags:
            if not tag_name:
                continue
            cur.execute(
                "INSERT INTO tags (name) VALUES (%s) ON CONFLICT (name) DO NOTHING;",
                (tag_name,),
            )
            cur.execute("SELECT id FROM tags WHERE name = %s;", (tag_name,))
            tag_id = cur.fetchone()[0]
            cur.execute(
                "INSERT INTO article_tags (article_id, tag_id) VALUES (%s, %s) ON CONFLICT DO NOTHING;",
                (article_id, tag_id),
            )
    conn.commit()


def main():
    conn_main = psycopg2.connect(**DB)
    cur = conn_main.cursor()

    # Solo artículos que aún no tienen etiquetas
    cur.execute("""
        SELECT a.id, a.title, a.body
        FROM articles a
        LEFT JOIN article_tags at2 ON at2.article_id = a.id
        WHERE at2.article_id IS NULL
        ORDER BY a.id;
    """)
    rows = cur.fetchall()
    print(f"Artículos pendientes de etiquetado: {len(rows)}")
    conn_main.close()

    if not rows:
        print("Todos los artículos ya tienen etiquetas.")
        sys.exit(0)

    # Crear pool de conexiones para escrituras concurrentes
    pool = psycopg2.pool.ThreadedConnectionPool(2, CONCURRENCY + 2, **DB)

    done = 0
    errors = 0
    with ThreadPoolExecutor(max_workers=CONCURRENCY) as executor:
        futures = {executor.submit(extract_tags, aid, title, body): aid
                   for aid, title, body in rows}
        for fut in as_completed(futures):
            article_id, tags = fut.result()
            conn = pool.getconn()
            try:
                upsert_tags(conn, article_id, tags)
                done += 1
            except Exception as e:
                errors += 1
                print(f"  Error guardando tags de articulo {article_id}: {e}")
            finally:
                pool.putconn(conn)
            if done % 50 == 0:
                print(f"  Progreso: {done}/{len(rows)} artículos etiquetados")

    pool.closeall()

    conn = psycopg2.connect(**DB)
    cur = conn.cursor()
    cur.execute("SELECT COUNT(*) FROM tags;")
    n_tags = cur.fetchone()[0]
    cur.execute("SELECT COUNT(DISTINCT article_id) FROM article_tags;")
    n_art = cur.fetchone()[0]
    conn.close()
    print(f"\nEtiquetas únicas creadas: {n_tags}")
    print(f"Artículos etiquetados: {n_art}  Errores: {errors}")


if __name__ == "__main__":
    main()
