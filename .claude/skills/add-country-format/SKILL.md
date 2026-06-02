---
name: add-country-format
description: Adds a new country postal code format to src/Validator.php $formats array and optional $zipNames entry. Use when user says 'add country', 'support new postal code', 'add format for XX', or requests a new ISO country code. Includes format string syntax (#=digit, @=letter) and corresponding PHPUnit test in tests/Detain/Tests/ZipZapper/ValidatorTest.php. Do NOT use for modifying existing country entries or updating the full dataset from Wikipedia (use bin/parse.php for that).
---
# Add Country Format

## Critical

- Country codes MUST be ISO 3166-1 alpha-2 (exactly 2 uppercase letters, e.g. `'XX'`)
- Format strings use ONLY `#` (digit 0–9), `@` (letter a-zA-Z), spaces, and literal characters
- Never duplicate an existing key — run `grep -n "'XX' =>" src/Validator.php` before adding
- Entries in `$formats` MUST be in strict alphabetical order by country code
- Countries with no postal system get an empty array `[]`, never omit the entry
- Indentation is **tabs**, not spaces (enforced by `.scrutinizer.yml`)

## Instructions

### Step 1 — Confirm the country code is not already present

Search `src/Validator.php` for the target code:
```bash
grep -n "'XX' =>" src/Validator.php
```
If a match is found, stop — this skill is for new entries only. Use a direct Edit for modifications.

### Step 2 — Determine the correct format strings

Convert the official postal format to format-string notation:

| Official | Format string |
|----------|---------------|
| 5 digits | `'#####'` |
| A1A 1A1 | `'@#@ #@#'` |
| SW1A 2AA | `'@@#@ #@@'` |
| 1234 AB | `'#### @@'` |
| Literal prefix + digits | `'AD###'` (Andorra) |
| No postal system | `[]` |

If the country has multiple valid formats (e.g. with/without hyphen), include all variants in the array:
```php
'XX' => ['#####', '#####-####'],
```

### Step 3 — Insert the entry into `$formats` in `src/Validator.php`

Find the alphabetically adjacent entries to identify the insertion point. The array starts around line 35. Each line follows this exact pattern:
```php
		'XX' => ['format1', 'format2'], // Country Name, Notes: | ...
```

Minimal form (no notes):
```php
		'XX' => ['#####'], // Country Name
```

Empty postal system:
```php
		'XX' => [], // Country Name
```

Verify the entry is between its alphabetical neighbours before proceeding.

### Step 4 — Optionally add to `$zipNames` in `src/Validator.php`

Only add a `$zipNames` entry if the country uses a named/branded postal code (e.g. CEP, PLZ, PIN, Eircode). The array starts around line 17:
```php
		'XX' => ['name' => 'Local Name', 'acronym_text' => 'Full expansion of the acronym'],
```

If the country just calls it "Postal Code", skip this step — `getZipName()` already defaults to `'Postal Code'`.

Verify alphabetical order in `$zipNames` before proceeding.

### Step 5 — Add a test method in `tests/Detain/Tests/ZipZapper/ValidatorTest.php`

Append a new `public function` before the closing `}` of the class. Test at least one valid code; test an invalid code if the format is strict (e.g. contains a space that matters):

```php
    public function testXxCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('XX', 'A1B 2C3'));
        $this->assertFalse($validator->isValid('XX', 'A1B2C3'));
        $this->assertTrue($validator->isValid('XX', 'A1B2C3', true)); // ignoreSpaces
    }
```

For countries with no postal system (`[]`), test that `hasCountry()` returns `true` and `isValid()` returns `false`:
```php
    public function testXxCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->hasCountry('XX'));
        $this->assertFalse($validator->isValid('XX', '12345'));
    }
```

### Step 6 — Run the test suite

```bash
vendor/bin/phpunit tests/ -v
```

All prior tests must still pass. The new test method must pass. Fix any failures before finishing.

## Examples

**User says:** "Add support for Ruritania (RU already exists, use RR), 5-digit numeric codes"

**Step 1** — `grep -n "'RR' =>" src/Validator.php` → no match, proceed.

**Step 2** — Format: `'#####'`

**Step 3** — Insert in `src/Validator.php` between `'RQ'` and `'RS'` entries:
```php
		'RR' => ['#####'], // Ruritania
```

**Step 4** — Standard name, skip `$zipNames`.

**Step 5** — Add to `ValidatorTest.php`:
```php
    public function testRuritanianCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('RR', '12345'));
        $this->assertFalse($validator->isValid('RR', '1234'));
        $this->assertFalse($validator->isValid('RR', '123456'));
    }
```

**Step 6** — `vendor/bin/phpunit tests/ -v` → all green.

---

**User says:** "Add Kosovo (XK), formats: NNNNN"

```php
// In $formats (alphabetically between 'XB' and 'XL' if they exist, else near end):
		'XK' => ['#####'], // Kosovo
```

Test:
```php
    public function testKosovoCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('XK', '10000'));
        $this->assertFalse($validator->isValid('XK', '1000'));
    }
```

## Common Issues

**`ValidationException` thrown for the new country code in tests:**
The country code was not saved correctly. Run `grep "'XX'" src/Validator.php` — if missing, the Edit didn't apply. Check for tab vs space indentation mismatch.

**Test `assertFalse` unexpectedly passes for a valid code:**
Format string contains a literal character that isn't `#` or `@` and isn't handled by `getFormatPattern()`. Check `src/Validator.php:getFormatPattern()` to confirm the literal is passed through correctly (e.g. `'AD###'` — the literal `AD` is matched as-is).

**`isValid()` returns `false` for a code with a space when `$ignoreSpaces = true`:**
The format string space is being stripped but the regex still expects it. Verify `getFormatPattern()` handles `$ignoreSpaces` — when `true`, spaces in both the format and the input are removed before matching.

**Alphabetical order assertion failure or hard-to-find insertion point:**
Search for the entry one letter before: `grep -n "'XJ' =>" src/Validator.php` to find the line number, then insert immediately after it.

**`vendor/bin/phpunit` command not found:**
Run `composer install` first to install PHPUnit into `vendor/`.