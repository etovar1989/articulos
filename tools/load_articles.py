import glob
import os
import re

import psycopg2

DB = dict(host="127.0.0.1", port=5432, dbname="edtk_eduteka", user="edtk_root_eduteka", password="5up3rS4j123@!")


def parse_article(path):
    with open(path, encoding="utf-8") as fh:
        content = fh.read()
    parts = content.split("---", 2)
    if len(parts) < 3:
        return None
    frontmatter, body = parts[1], parts[2]

    def field(name):
        m = re.search(rf"^{name}:\s*(.*)$", frontmatter, re.MULTILINE)
        return m.group(1).strip().strip('"') if m else None

    article_id = field("id")
    if not article_id:
        return None

    slug = os.path.splitext(os.path.basename(path))[0]
    title = field("title") or slug
    category = field("category")
    tags = field("tags")
    date = field("date")

    return {
        "id": int(article_id),
        "slug": slug,
        "title": title,
        "file_path": path,
        "body": body.strip(),
        "original_category": category,
        "original_tags": tags,
        "article_date": date if date else None,
    }


def main():
    files = sorted(glob.glob("articulos/doc/*.md"))
    records = []
    skipped = 0
    for f in files:
        rec = parse_article(f)
        if rec is None:
            skipped += 1
            continue
        records.append(rec)

    print(f"Parseados: {len(records)}  Omitidos (sin frontmatter valido): {skipped}")

    conn = psycopg2.connect(**DB)
    cur = conn.cursor()
    upserted = 0
    for rec in records:
        cur.execute(
            """
            INSERT INTO articles (id, slug, title, file_path, body, original_category, original_tags, article_date)
            VALUES (%(id)s, %(slug)s, %(title)s, %(file_path)s, %(body)s, %(original_category)s, %(original_tags)s, %(article_date)s)
            ON CONFLICT (id) DO UPDATE SET
                slug = EXCLUDED.slug,
                title = EXCLUDED.title,
                file_path = EXCLUDED.file_path,
                body = EXCLUDED.body,
                original_category = EXCLUDED.original_category,
                original_tags = EXCLUDED.original_tags,
                article_date = EXCLUDED.article_date,
                updated_at = now();
            """,
            rec,
        )
        upserted += 1
    conn.commit()
    cur.execute("SELECT count(*) FROM articles;")
    total = cur.fetchone()[0]
    print(f"Upserted: {upserted}  Total en tabla articles: {total}")
    conn.close()


if __name__ == "__main__":
    main()
