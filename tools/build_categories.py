"""
Fase 2: Clustering de embeddings -> categorías semánticas.
Requiere que build_embeddings.py haya terminado.

1. Carga todos los embeddings desde la BD.
2. KMeans con k=28 (ajustable con --k).
3. Para cada cluster llama a GPT-4.1-mini con títulos representativos
   y genera nombre + descripción de la categoría.
4. Inserta en tabla categories y actualiza articles.category_id.
"""
import argparse
import json
import os
import sys

import numpy as np
import psycopg2
from openai import OpenAI
from sklearn.cluster import KMeans

DB = dict(host="127.0.0.1", port=5432, dbname="edtk_eduteka",
          user="edtk_root_eduteka", password="5up3rS4j123@!",
          options="-c client_encoding=UTF8")

client = OpenAI(api_key=os.environ["OPENAI_API_KEY"])


def label_cluster(titles_sample: list[str]) -> dict:
    joined = "\n".join(f"- {t}" for t in titles_sample)
    prompt = (
        "Eres un experto en taxonomía educativa. "
        "A continuación tienes una muestra de títulos de artículos que pertenecen al mismo grupo temático.\n\n"
        f"{joined}\n\n"
        "Genera un JSON con exactamente dos campos:\n"
        '  "name": nombre corto de la categoría (máx 6 palabras, en español),\n'
        '  "description": descripción breve de qué abarca (máx 25 palabras, en español).\n'
        "Responde únicamente con el JSON, sin texto adicional."
    )
    for attempt in range(4):
        try:
            resp = client.chat.completions.create(
                model="gpt-4.1-mini",
                messages=[{"role": "user", "content": prompt}],
                response_format={"type": "json_object"},
                max_tokens=120,
                temperature=0.2,
            )
            return json.loads(resp.choices[0].message.content)
        except Exception as e:
            print(f"Error al etiquetar cluster (intento {attempt+1}): {e}")
    return {"name": f"Categoría sin etiquetar", "description": ""}


def main():
    parser = argparse.ArgumentParser()
    parser.add_argument("--k", type=int, default=28, help="Número de clusters (categorías)")
    args = parser.parse_args()

    conn = psycopg2.connect(**DB)
    cur = conn.cursor()

    print("Cargando embeddings...")
    cur.execute("""
        SELECT e.article_id, a.title, e.embedding
        FROM embeddings e
        JOIN articles a ON a.id = e.article_id
        ORDER BY e.article_id;
    """)
    rows = cur.fetchall()
    if not rows:
        print("No hay embeddings en la BD. Ejecuta build_embeddings.py primero.")
        sys.exit(1)

    article_ids = [r[0] for r in rows]
    titles = [r[1] for r in rows]
    X = np.array([r[2] for r in rows], dtype=np.float32)

    print(f"{len(rows)} embeddings cargados. Corriendo KMeans k={args.k}...")
    km = KMeans(n_clusters=args.k, random_state=42, n_init="auto")
    labels = km.fit_predict(X)
    print("Clustering terminado.")

    # Para cada cluster: tomar los 8 títulos más cercanos al centroide
    clusters: dict[int, list[str]] = {c: [] for c in range(args.k)}
    for idx, c in enumerate(labels):
        clusters[c].append(titles[idx])

    # Etiquetar cada cluster con GPT-4.1-mini
    category_ids: dict[int, int] = {}
    print(f"Etiquetando {args.k} categorías con GPT-4.1-mini...")
    for c in range(args.k):
        cluster_titles = clusters[c]
        # Centroid-nearest: compute distances, pick top 8
        centroid = km.cluster_centers_[c]
        member_indices = [i for i, lbl in enumerate(labels) if lbl == c]
        dists = np.linalg.norm(X[member_indices] - centroid, axis=1)
        top_idx = np.argsort(dists)[:8]
        sample_titles = [titles[member_indices[i]] for i in top_idx]

        label = label_cluster(sample_titles)
        name = label.get("name", f"Categoría {c+1}")
        desc = label.get("description", "")
        print(f"  Cluster {c:2d} ({len(cluster_titles):4d} arts): {name}")

        cur.execute(
            """
            INSERT INTO categories (name, description)
            VALUES (%s, %s)
            ON CONFLICT (name) DO UPDATE SET description = EXCLUDED.description
            RETURNING id;
            """,
            (name, desc),
        )
        cat_id = cur.fetchone()[0]
        category_ids[c] = cat_id

    conn.commit()

    # Asignar category_id a cada artículo
    print("Actualizando articles.category_id...")
    for idx, (article_id, cluster_label) in enumerate(zip(article_ids, labels)):
        cur.execute(
            "UPDATE articles SET category_id = %s WHERE id = %s;",
            (category_ids[cluster_label], article_id),
        )
    conn.commit()

    cur.execute("SELECT COUNT(*) FROM categories;")
    n_cat = cur.fetchone()[0]
    cur.execute("SELECT COUNT(*) FROM articles WHERE category_id IS NOT NULL;")
    n_art = cur.fetchone()[0]
    print(f"\nCategorías creadas: {n_cat}  Artículos categorizados: {n_art}")
    conn.close()


if __name__ == "__main__":
    main()
