---
name: update-postal-formats
description: Updates the $formats array in src/Validator.php from Wikipedia's postal codes list via bin/parse.php. Use when user says 'update formats', 'refresh postal codes', 'sync from Wikipedia', or 'formats are outdated'. Explains the parse.php workflow, MyAdmin dependency requirement, and how to paste output into Validator.php. Do NOT use for adding a single country manually — that is a direct edit to src/Validator.php.
---
# update-postal-formats

## Critical

- `bin/parse.php` **cannot run standalone** — it requires `getcurlpage()` and other functions from the parent MyAdmin environment. It must be run from within a MyAdmin install where the MyAdmin bootstrap resolves.
- The script also queries the `country_t` MySQL table via `$GLOBALS['tf']->db` to fill in countries not found on Wikipedia. The MyAdmin DB connection must be active.
- Never paste raw Wikipedia wikitext into `src/Validator.php` — always run the parser to get properly converted PHP array entries.
- Format symbols: `#` = digit `[0-9]`, `@` = letter `[a-zA-Z]`. The parser converts Wikipedia's `N` → `#` and `A` → `@` automatically.

## Instructions

1. **Confirm the MyAdmin environment is available.**
   Verify the parser script exists:
   ```bash
   ls bin/parse.php
   ```
   If missing, the package is not installed — run `composer install` first.

2. **Run the parser from the MyAdmin root.**
   The script must be invoked from within the MyAdmin web context or via a bootstrap that initialises `$GLOBALS['tf']`. Typical invocation:
   ```bash
   php bin/parse.php
   ```
   The script fetches `https://en.wikipedia.org/wiki/Special:Export/List_of_postal_codes`, parses the wikitext table, merges with `country_t` DB rows, and prints sorted PHP array lines to stdout.

3. **Capture the output.**
   Redirect to a temp file to review before pasting:
   ```bash
   php bin/parse.php > /tmp/new_formats.php
   ```
   Each output line looks like:
   ```php
        'US' => ['#####', '#####-####'], // United States
        'AE' => [], // United Arab Emirates
   ```
   Verify the output is not empty and contains known countries like `'US'`, `'CA'`, `'GB'`.

4. **Replace the `$formats` array body in `src/Validator.php`.**
   Open `src/Validator.php`. The array starts at:
   ```php
       protected $formats = [
           'AD' => ['AD###'], // Andorra
   ```
   and ends with the closing `];` before the next property or method.
   Replace everything between `protected $formats = [` and its closing `];` with the parser output.
   Preserve the existing indentation style (tabs, not spaces — per `.scrutinizer.yml`).

5. **Verify the file is valid PHP.**
   ```bash
   php -l src/Validator.php
   ```
   Must output `No syntax errors detected`.

6. **Run the test suite.**
   ```bash
   vendor/bin/phpunit tests/ -v
   ```
   All tests must pass. If a previously-passing country now fails, compare the old and new format strings for that country in `$formats`.

7. **Review any countries with empty arrays `[]`.**
   An empty array means no postal system. Confirm intentional before committing — check Wikipedia directly for that ISO code if uncertain.

## Examples

**User says:** "The postal formats are outdated, can you refresh them from Wikipedia?"

**Actions taken:**
1. Confirmed `bin/parse.php` exists.
2. Ran `php bin/parse.php > /tmp/new_formats.php` — output contained ~250 country entries.
3. Opened `src/Validator.php`, located `protected $formats = [` at line 35.
4. Replaced the array body with contents of `/tmp/new_formats.php`.
5. Ran `php -l src/Validator.php` → `No syntax errors detected`.
6. Ran `vendor/bin/phpunit tests/ -v` → all tests passed.

**Result:** `$formats` in `src/Validator.php` updated with current Wikipedia data. Example diff:
```php
-        'AF' => ['####'], // Afghanistan
+        'AF' => ['####', '####'], // Afghanistan, Notes: | 2011 ...
```

## Common Issues

**`require /path/to/functions.inc.php: No such file or directory`**
- You are running `bin/parse.php` outside the MyAdmin environment. The MyAdmin bootstrap must be available. Run from within a MyAdmin environment using `php bin/parse.php`.

**`Call to undefined function getcurlpage()`**
- `function_requirements('getcurlpage')` failed to load the function. Verify the MyAdmin bootstrap loaded successfully and that `getcurlpage` exists in the MyAdmin function files.

**`Call to a member function query() on null` (DB error)**
- `$GLOBALS['tf']->db` is not initialised. The script requires an active MyAdmin DB connection. Run it through a MyAdmin bootstrap or CLI entry point that sets up `$GLOBALS['tf']`.

**Empty output from `bin/parse.php`**
- Wikipedia fetch failed or returned unexpected content. Check network access from the server to `en.wikipedia.org`. Try `curl -s 'https://en.wikipedia.org/wiki/Special:Export/List_of_postal_codes' | head -50` to verify the endpoint is reachable.

**PHPUnit test failures after update**
- A specific country's format changed. Compare the failing test's expected pattern against the new `$formats` entry. If Wikipedia's new data is correct, update the test in `tests/Detain/Tests/ZipZapper/ValidatorTest.php`. If the new format looks wrong (e.g. a literal ISO code was not replaced), check the `get_codes_from()` logic in `bin/parse.php` — it replaces `CC` with the ISO code, `N` with `#`, and `A` with `@`.

**Tabs vs spaces warning from linter**
- `src/Validator.php` uses tab indentation (`.scrutinizer.yml` enforces this). If your editor auto-converted to spaces, run:
  ```bash
  unexpand --first-only src/Validator.php > /tmp/v.php && mv /tmp/v.php src/Validator.php
  ```
