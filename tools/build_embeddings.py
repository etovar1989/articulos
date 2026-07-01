import os
import time

import psycopg2
import tiktoken
from openai import OpenAI

DB = dict(host="127.0.0.1", port=5432, dbname="edtk_eduteka",
          user="edtk_root_eduteka", password="5up3rS4j123@!",
          options="-c client_encoding=UTF8")
MODEL = "text-embedding-3-large"
MAX_TOKENS_PER_ITEM = 6000
MAX_TOKENS_PER_BATCH = 200000
MAX_ITEMS_PER_BATCH = 64

client = OpenAI(api_key=os.environ["OPENAI_API_KEY"])
enc = tiktoken.get_encoding("cl100k_base")


def truncate(text, max_tokens):
    tokens = enc.encode(text)
    if len(tokens) <= max_tokens:
        return text
    return enc.decode(tokens[:max_tokens])


def make_batches(rows):
    batch, batch_tokens = [], 0
    for article_id, text in rows:
        text = truncate(text, MAX_TOKENS_PER_ITEM)
        ntok = len(enc.encode(text))
        if batch and (batch_tokens + ntok > MAX_TOKENS_PER_BATCH or len(batch) >= MAX_ITEMS_PER_BATCH):
            yield batch
            batch, batch_tokens = [], 0
        batch.append((article_id, text))
        batch_tokens += ntok
    if batch:
        yield batch


def main():
    conn = psycopg2.connect(**DB)
    cur = conn.cursor()
    cur.execute(
        """
        SELECT a.id, a.title || E'\\n\\n' || a.body
        FROM articles a
        LEFT JOIN embeddings e ON e.article_id = a.id
        WHERE e.id IS NULL
        ORDER BY a.id;
        """
    )
    rows = cur.fetchall()
    print(f"Articulos pendientes de embedding: {len(rows)}")

    done = 0
    for batch in make_batches(rows):
        ids = [aid for aid, _ in batch]
        texts = [t for _, t in batch]
        for attempt in range(5):
            try:
                resp = client.embeddings.create(model=MODEL, input=texts)
                break
            except Exception as e:
                wait = 2 ** attempt
                print(f"Error en batch (intento {attempt+1}): {e}. Reintentando en {wait}s")
                time.sleep(wait)
        else:
            print(f"Fallo definitivo en batch con ids {ids}")
            continue

        for (article_id, _), item in zip(batch, resp.data):
            vec = item.embedding
            cur.execute(
                """
                INSERT INTO embeddings (article_id, model, dimensions, embedding)
                VALUES (%s, %s, %s, %s)
                ON CONFLICT (article_id) DO UPDATE SET
                    model = EXCLUDED.model,
                    dimensions = EXCLUDED.dimensions,
                    embedding = EXCLUDED.embedding,
                    created_at = now();
                """,
                (article_id, MODEL, len(vec), vec),
            )
        conn.commit()
        done += len(batch)
        print(f"Progreso: {done}/{len(rows)}")

    conn.close()
    print("Listo.")


if __name__ == "__main__":
    main()
