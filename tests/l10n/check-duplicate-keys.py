#!/usr/bin/env python3
"""Fail if any l10n catalog contains a duplicate translation key.

Both ``JSON.parse`` (JS) and PHP ``json_decode`` silently collapse duplicate
object keys (last value wins), so a catalog can carry the same key twice and
still "validate" while being malformed. That is exactly how the duplicate
``de``/``de_DE`` empty-state keys reached master (see PR #562). This checker
inspects *all* key/value pairs before deduplication via ``object_pairs_hook``
and reports any key that appears more than once.

Covers both catalog formats:
  * ``l10n/<lang>.json`` -- a plain JSON object.
  * ``l10n/<lang>.js``   -- ``OC.L10N.register("notes", { ... }, "plural...");``
                            where the ``{ ... }`` object literal is JSON.

Exit code 0 when every catalog is clean, 1 when duplicates are found (or a
catalog cannot be parsed).
"""

import json
import sys
from pathlib import Path

# Repo root is two levels up from tests/l10n/.
REPO_ROOT = Path(__file__).resolve().parents[2]
L10N_DIR = REPO_ROOT / "l10n"


def make_pairs_hook(collector):
    """Build an object_pairs_hook that appends duplicate keys to ``collector``.

    The hook runs for *every* object in the document (including nested ones such
    as the ``translations`` wrapper in the .json catalogs) and fires before
    Python collapses duplicate keys, so it sees all of them.
    """
    def hook(pairs):
        seen = set()
        for key, _value in pairs:
            if key in seen:
                collector.append(key)
            else:
                seen.add(key)
        return dict(pairs)
    return hook


def extract_js_object(text):
    """Return the JSON object literal embedded in an OC.L10N.register call.

    The catalog is ``OC.L10N.register("notes", { ... }, "plural...");``. We take
    the first ``{`` and its matching ``}`` (tracking string literals so braces
    inside translations are ignored) and return that substring.
    """
    start = text.find("{")
    if start == -1:
        raise ValueError("no '{' found in .js catalog")

    depth = 0
    in_string = False
    escaped = False
    for i in range(start, len(text)):
        ch = text[i]
        if in_string:
            if escaped:
                escaped = False
            elif ch == "\\":
                escaped = True
            elif ch == '"':
                in_string = False
            continue
        if ch == '"':
            in_string = True
        elif ch == "{":
            depth += 1
        elif ch == "}":
            depth -= 1
            if depth == 0:
                return text[start:i + 1]
    raise ValueError("unbalanced braces in .js catalog")


def duplicates_in_file(path):
    """Return the list of duplicate keys in one catalog file.

    Collects duplicates from every object in the document, so a duplicate inside
    the nested ``translations`` object of a .json catalog is caught too.
    """
    text = path.read_text(encoding="utf-8")
    if path.suffix == ".js":
        text = extract_js_object(text)
    dups = []
    json.loads(text, object_pairs_hook=make_pairs_hook(dups))
    return dups


def main():
    catalogs = sorted(
        p for p in L10N_DIR.iterdir()
        if p.suffix in (".js", ".json")
    )
    if not catalogs:
        print(f"error: no catalogs found in {L10N_DIR}", file=sys.stderr)
        return 1

    failures = []
    for path in catalogs:
        rel = path.relative_to(REPO_ROOT)
        try:
            dups = duplicates_in_file(path)
        except (ValueError, json.JSONDecodeError) as exc:
            failures.append(f"{rel}: could not parse ({exc})")
            continue
        if dups:
            for key in dups:
                failures.append(f"{rel}: duplicate key {json.dumps(key, ensure_ascii=False)}")

    if failures:
        print("Duplicate translation keys detected:", file=sys.stderr)
        for line in failures:
            print(f"  {line}", file=sys.stderr)
        print(
            f"\n{len(failures)} problem(s) across {len(catalogs)} catalog files.",
            file=sys.stderr,
        )
        return 1

    print(f"OK: no duplicate keys in {len(catalogs)} l10n catalog files.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
