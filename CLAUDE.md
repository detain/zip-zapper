# Zip Zapper

PHP library for validating postal/ZIP codes by country.

## Commands

```bash
composer install                          # install deps
composer test                             # run the test suite (PHPUnit 9)
vendor/bin/phpunit tests/ -v              # run all tests (verbose)
vendor/bin/phpunit tests/ -v --coverage-clover coverage.xml --whitelist src/  # with coverage
php bin/parse.php                         # regenerate country format data from Wikipedia
```

## Architecture

- **Autoload**: PSR-4 `Detain\ZipZapper\` → `src/`
- **Core class**: `src/Validator.php` — country format registry + validation logic
- **Exception**: `src/ValidationException.php` — thrown on invalid country code
- **Tests**: `tests/Detain/Tests/ZipZapper/ValidatorTest.php` — PHPUnit test cases
- **Parser**: `bin/parse.php` — scrapes Wikipedia postal codes list to regenerate `$formats` data

## Key Classes

**`Detain\ZipZapper\Validator`** (`src/Validator.php`):
- `$formats` — associative array keyed by ISO 3166-1 alpha-2 country code, values are arrays of format strings
- `$zipNames` — country-specific postal code naming; each entry `['name' => '...', 'acronym_text' => '...']`
- `isValid($countryCode, $postalCode, $ignoreSpaces = false)` — validates code against all formats for country
- `getFormats($countryCode)` — returns format array for country
- `hasCountry($countryCode)` — returns bool
- `getZipName($countryCode)` — returns localized name (defaults to `'Postal Code'`)
- `getZipAcronym($countryCode)` — returns acronym expansion (defaults to `''`, never throws)
- `getCountries()` — returns all registered ISO 3166-1 alpha-2 country codes as `string[]`
- `getFormatPattern($format, $ignoreSpaces)` — converts format string to regex: `#` → `\d`, `@` → `[a-zA-Z]`

**`Detain\ZipZapper\ValidationException`** (`src/ValidationException.php`):
- Extends `\Exception`, thrown by `isValid()` and `getFormats()` when country code not in `$formats`

## Format String Convention

| Symbol | Matches |
|--------|------------------|
| `#` | `0-9` (digit) |
| `@` | `a-zA-Z` (letter) |
| ` ` | literal space (or optional with `$ignoreSpaces`) |

Example formats: `'@#@ #@#'` (Canada), `'### ##'` (Sweden), `'@## @#@#'` (Ireland Eircode)

## Adding a New Country

1. Add entry to `$formats` in `src/Validator.php` keyed by ISO 3166-1 alpha-2 code
2. Use `#` for digits, `@` for letters in format strings; empty array `[]` means no postal system
3. Optionally add to `$zipNames` if the country has a named variant
4. Add test cases to `tests/Detain/Tests/ZipZapper/ValidatorTest.php`

Example:
```php
'XX' => ['#####', '#####-####'], // Country Name
```

Optional `$zipNames` entry:
```php
'XX' => ['name' => 'Local Name', 'acronym_text' => 'Full acronym expansion'],
```

## Updating Format Data from Wikipedia

`bin/parse.php` fetches `https://en.wikipedia.org/wiki/Special:Export/List_of_postal_codes`, parses the wikitext table, and outputs PHP array entries for `$formats`. Run it and paste output into `src/Validator.php`.

Note: `bin/parse.php` requires `getcurlpage()` and other functions from the parent MyAdmin environment — it cannot run standalone.

## Coding Conventions

- Indentation: tabs (per `.scrutinizer.yml`)
- Properties: camelCase (`$zipNames`, `$formats`)
- Methods: camelCase (`isValid`, `getFormats`, `hasCountry`, `getZipName`)
- No closing PHP tag in class files
- Doc comments: required on public methods (Scrutinizer enforces `parameter_doc_comments`, `return_doc_comments`)
- `phpunit.xml.dist` configures test suite bootstrap (PHPUnit 9 syntax: `<coverage>/<include>` replaces `<filter>/<whitelist>`)

## Testing Patterns

```php
// Test valid code
$this->assertTrue($validator->isValid('US', '81301'));
// Test invalid code
$this->assertFalse($validator->isValid('CZ', '60200'));
// Test with space-ignore flag
$this->assertTrue($validator->isValid('CZ', '60200', true));
// Test zip name
$this->assertEquals('ZIP code', $validator->getZipName('US'));
// Test zip acronym
$this->assertEquals('Zone Improvement Plan', $validator->getZipAcronym('US'));
$this->assertEquals('', $validator->getZipAcronym('CA')); // no acronym registered
// Test format retrieval
$this->assertEquals(['#####', '#####-####'], $validator->getFormats('US'));
// Test country list
$this->assertContains('US', $validator->getCountries());
```

## CI

- `.travis.yml` — tests on PHP 5.4–7.2; coverage via phpdbg on PHP 7.0
- `.scrutinizer.yml` — Scrutinizer CI with coverage upload
- `.codeclimate.yml` — Code Climate with phpmd and duplication checks
- `.bettercodehub.yml` — BetterCodeHub PHP analysis

<!-- caliber:managed:pre-commit -->
## Before Committing

**IMPORTANT:** Before every git commit, you MUST ensure Caliber syncs agent configs with the latest code changes.

First, check if the pre-commit hook is already installed:
```bash
grep -q "caliber" .git/hooks/pre-commit 2>/dev/null && echo "hook-active" || echo "no-hook"
```

- If **hook-active**: the hook handles sync automatically — just commit normally. Tell the user: "Caliber will sync your agent configs automatically via the pre-commit hook."
- If **no-hook**: run Caliber manually before committing:
  1. Tell the user: "Caliber: Syncing agent configs with your latest changes..."
  2. Run: `caliber refresh && git add CLAUDE.md .claude/ .cursor/ .cursorrules .github/copilot-instructions.md .github/instructions/ AGENTS.md CALIBER_LEARNINGS.md .agents/ .opencode/ 2>/dev/null`
  3. After it completes, briefly tell the user what Caliber updated. Then proceed with the commit.

**Valid `caliber refresh` options:** `--quiet` (suppress output) and `--dry-run` (preview without writing). Do not pass any other flags — options like `--auto-approve`, `--debug`, or `--force` do not exist and will cause errors.

**`caliber config`** takes no flags — it runs an interactive provider setup. Do not pass `--provider`, `--api-key`, or `--endpoint`.

If `caliber` is not found, tell the user: "This project uses Caliber for agent config sync. Run /setup-caliber to get set up."
<!-- /caliber:managed:pre-commit -->

<!-- caliber:managed:learnings -->
## Session Learnings

Read `CALIBER_LEARNINGS.md` for patterns and anti-patterns learned from previous sessions.
These are auto-extracted from real tool usage — treat them as project-specific rules.
<!-- /caliber:managed:learnings -->

<!-- caliber:managed:model-config -->
## Model Configuration

Recommended default: `claude-sonnet-4-6` with high effort (stronger reasoning; higher cost and latency than smaller models).
Smaller/faster models trade quality for speed and cost — pick what fits the task.
Pin your choice (`/model` in Claude Code, or `CALIBER_MODEL` when using Caliber with an API provider) so upstream default changes do not silently change behavior.

<!-- /caliber:managed:model-config -->

<!-- caliber:managed:sync -->
## Context Sync

This project uses [Caliber](https://github.com/caliber-ai-org/ai-setup) to keep AI agent configs in sync across Claude Code, Cursor, Copilot, and Codex.
Configs update automatically before each commit via `caliber refresh`.
If the pre-commit hook is not set up, run `/setup-caliber` to configure everything automatically.
<!-- /caliber:managed:sync -->
