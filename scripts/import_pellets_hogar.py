#!/usr/bin/env python3
"""Import pellets-hogar into Lena using Spanish API data + LOCAL image copies (no re-download when possible)."""

from __future__ import annotations

import html as html_lib
import json
import re
import shutil
import time
import unicodedata
from pathlib import Path
from urllib.parse import unquote, urlparse

import requests

ROOT = Path(__file__).resolve().parents[1]
OUT_PHP = ROOT / "config" / "pellets_hogar_products.php"
MEDIA_ROOT = ROOT / "public" / "wp-content" / "uploads" / "pellets-hogar"
RAW_CACHE = ROOT / "storage" / "app" / "imports" / "pellets-hogar" / "raw_products.json"
URL_MAP_FILE = Path("/tmp/ph_url_to_local.json")
HERI_TRASH = Path("/var/www/html/Heri-Brennholz/storage/app/trash-pellets-hogar-media")
HERI_RECOVERED = Path("/tmp/pellets-hogar-recovered.json")

BASE = "https://www.pellets-hogar.com/wp-json/wc/store/v1/products"
PER_PAGE = 20
ID_OFFSET = 900000

CATEGORY_MAP = {
    "pellets": "pellets-de-madera",
    "troncos-de-lena": "lena",
    "lena-de-encina": "lena",
    "briquetas-y-astillas-de-madera": "madera-densificada",
    "lena-densificada": "madera-densificada",
    "estufas-de-pellet": "estufas-de-pellets",
    "estufas-de-lena": "cocinas-de-lena",
    "barbacoa": "madera-densificada",
    "sin-categorizar": "pellets-de-madera",
}

SESSION = requests.Session()
SESSION.headers.update({"User-Agent": "LenasElMolarImporter/1.0", "Accept": "application/json"})

stats = {"copied": 0, "reused_lena": 0, "downloaded": 0, "missing": 0}


def slugify(text: str) -> str:
    text = unquote(text or "")
    text = unicodedata.normalize("NFKD", text)
    text = text.encode("ascii", "ignore").decode("ascii").lower()
    return re.sub(r"[^a-z0-9]+", "-", text).strip("-") or "producto"


def money(prices: dict, key: str) -> float | None:
    raw = prices.get(key)
    if raw in (None, ""):
        return None
    return round(int(raw) / (10 ** int(prices.get("currency_minor_unit") or 2)), 2)


def strip_html(text: str | None) -> str:
    if not text:
        return ""
    t = html_lib.unescape(text)
    t = re.sub(r"<br\s*/?>", "\n", t, flags=re.I)
    t = re.sub(r"</p\s*>", "\n\n", t, flags=re.I)
    t = re.sub(r"</li\s*>", "\n", t, flags=re.I)
    t = re.sub(r"<li[^>]*>", "- ", t, flags=re.I)
    t = re.sub(r"<[^>]+>", "", t)
    return re.sub(r"\n{3,}", "\n\n", t).strip()


def build_local_image_index() -> dict[str, str]:
    """Map remote image URL -> local filesystem path."""
    mapping: dict[str, str] = {}
    if URL_MAP_FILE.exists():
        mapping.update(json.loads(URL_MAP_FILE.read_text()))
    if HERI_RECOVERED.exists() and HERI_TRASH.exists():
        data = json.loads(HERI_RECOVERED.read_text())
        for p in data.get("products", []):
            for img in p.get("images") or []:
                src = img.get("source")
                rel = (img.get("file") or "").replace("assets/images/", "")
                local = HERI_TRASH / rel
                if src and local.is_file():
                    mapping[src] = str(local)
    # Also index by remote basename for fuzzy lookups
    by_base: dict[str, str] = {}
    for src, local in list(mapping.items()):
        base = Path(urlparse(src).path).name
        if base:
            by_base[base] = local
    # Index Heri trash + existing Lena downloads by remote-looking basenames isn't enough;
    # keep basename map separately returned via side channel
    mapping["__by_base__"] = by_base  # type: ignore[assignment]
    return mapping


def fetch_products() -> list[dict]:
    if RAW_CACHE.exists():
        data = json.loads(RAW_CACHE.read_text(encoding="utf-8"))
        if len(data) >= 100:
            print(f"Using Spanish cache ({len(data)}): {RAW_CACHE}")
            return data
    products: list[dict] = []
    page, total_pages = 1, 1
    while page <= total_pages:
        url = f"{BASE}?per_page={PER_PAGE}&page={page}"
        print(f"GET {url}")
        r = SESSION.get(url, timeout=120)
        r.raise_for_status()
        total_pages = int(r.headers.get("X-WP-TotalPages") or 1)
        batch = r.json()
        products.extend(batch)
        print(f" page {page}/{total_pages} → {len(batch)}")
        page += 1
        time.sleep(0.3)
    RAW_CACHE.parent.mkdir(parents=True, exist_ok=True)
    RAW_CACHE.write_text(json.dumps(products, ensure_ascii=False, indent=2), encoding="utf-8")
    return products


def pick_category(product: dict) -> str:
    for c in product.get("categories") or []:
        slug = c.get("slug")
        if slug in CATEGORY_MAP and slug != "sin-categorizar":
            return CATEGORY_MAP[slug]
    if product.get("categories"):
        slug = product["categories"][0].get("slug")
        if slug in CATEGORY_MAP:
            return CATEGORY_MAP[slug]
    name = (product.get("name") or "").lower()
    if "estufa" in name and "pellet" in name:
        return "estufas-de-pellets"
    if "estufa" in name:
        return "cocinas-de-lena"
    if "briqueta" in name or "densific" in name:
        return "madera-densificada"
    if "pellet" in name:
        return "pellets-de-madera"
    return "lena"


def resolve_local(src: str, index: dict) -> Path | None:
    if src in index and src != "__by_base__":
        p = Path(index[src])
        if p.is_file():
            return p
    by_base = index.get("__by_base__") or {}
    base = Path(urlparse(src).path).name
    if base in by_base:
        p = Path(by_base[base])
        if p.is_file():
            return p
    return None


def place_images(product: dict, slug: str, index: dict) -> list[str]:
    dest_dir = MEDIA_ROOT / slug
    dest_dir.mkdir(parents=True, exist_ok=True)
    paths: list[str] = []
    for idx, img in enumerate(product.get("images") or []):
        src = img.get("src")
        if not src:
            continue
        ext = Path(urlparse(src).path).suffix.lower() or ".jpg"
        if ext not in {".jpg", ".jpeg", ".png", ".webp", ".gif"}:
            ext = ".jpg"
        filename = f"{idx + 1:02d}{ext}"
        dest = dest_dir / filename
        rel = f"wp-content/uploads/pellets-hogar/{slug}/{filename}"

        if dest.is_file() and dest.stat().st_size > 500:
            stats["reused_lena"] += 1
            paths.append(rel)
            continue

        local = resolve_local(src, index)
        if local:
            shutil.copy2(local, dest)
            stats["copied"] += 1
            paths.append(rel)
            continue

        # Last resort: download only if we truly don't have it
        print(f"  download missing {src}")
        try:
            r = SESSION.get(src, timeout=90)
            r.raise_for_status()
            dest.write_bytes(r.content)
            stats["downloaded"] += 1
            paths.append(rel)
        except Exception as exc:  # noqa: BLE001
            print(f"  MISSING {exc}")
            stats["missing"] += 1
    return paths


def php_escape(s: str) -> str:
    return s.replace("\\", "\\\\").replace("'", "\\'")


def write_php(entries: list[dict]) -> None:
    lines = ["<?php", "", "return ["]
    for e in entries:
        lines.append("    [")
        lines.append(f"        'id' => {e['id']},")
        lines.append(f"        'title' => '{php_escape(e['title'])}',")
        lines.append("        'hover_image' => '',")
        lines.append(f"        'old_price' => {'null' if not e['old_price'] else chr(39)+e['old_price']+chr(39)},")
        lines.append(f"        'price' => '{e['price']}',")
        lines.append(f"        'category' => '{e['category']}',")
        lines.append("        'images' => [")
        for img in e["images"]:
            lines.append(f"            '{php_escape(img)}',")
        lines.append("        ],")
        lines.append(f"        'in_stock' => {'true' if e['in_stock'] else 'false'},")
        lines.append("        'color' => '',")
        lines.append(f"        'short_description' => '{php_escape(e['short_description'])}',")
        lines.append(f"        'description' => '{php_escape(e['description'])}',")
        lines.append(f"        'ref' => '{php_escape(e['ref'])}',")
        lines.append(f"        'slug' => '{php_escape(e['slug'])}',")
        lines.append("    ],")
    lines.append("];")
    lines.append("")
    OUT_PHP.write_text("\n".join(lines), encoding="utf-8")
    print(f"Wrote {OUT_PHP} ({len(entries)} products)")


def main() -> None:
    index = build_local_image_index()
    nmap = len([k for k in index if k != "__by_base__"])
    print(f"Local image URL map: {nmap} entries")

    products = fetch_products()
    used_slugs: set[str] = set()
    entries: list[dict] = []

    for i, product in enumerate(products, 1):
        name = product.get("name") or ""
        print(f"[{i}/{len(products)}] {name}")
        slug = slugify(name) or slugify(unquote(product.get("slug") or ""))
        base, n = slug, 2
        while slug in used_slugs:
            slug = f"{base}-{n}"
            n += 1
        used_slugs.add(slug)

        prices = product.get("prices") or {}
        price = money(prices, "price") or 0.0
        regular = money(prices, "regular_price") or price
        sale = money(prices, "sale_price") or price
        current = sale or price
        old_price = f"{regular:.2f}" if regular > current else None

        images = place_images(product, slug, index)
        short = strip_html(product.get("short_description"))
        desc = strip_html(product.get("description")) or short or name

        entries.append(
            {
                "id": ID_OFFSET + int(product["id"]),
                "title": name,
                "old_price": old_price,
                "price": f"{current:.2f}",
                "category": pick_category(product),
                "images": images,
                "in_stock": bool(product.get("is_in_stock", True)),
                "short_description": short,
                "description": desc,
                "ref": product.get("sku") or f"PH-{product['id']}",
                "slug": slug,
            }
        )

    write_php(entries)
    print(
        f"Images: copied={stats['copied']} reused_lena={stats['reused_lena']} "
        f"downloaded={stats['downloaded']} missing={stats['missing']}"
    )


if __name__ == "__main__":
    main()
