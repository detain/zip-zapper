---
name: write-validator-test
description: Creates PHPUnit test cases for Validator methods in tests/Detain/Tests/ZipZapper/ValidatorTest.php following existing patterns. Use when user says 'add test', 'write test for country', 'test isValid', 'test getFormats', 'test hasCountry', or 'test getZipName'. Covers isValid(), hasCountry(), getFormats(), getZipName(), and ValidationException throwing. Do NOT use for integration tests, parser tests, or bin/parse.php testing.
---
# write-validator-test

## Critical

- All tests go in `tests/Detain/Tests/ZipZapper/ValidatorTest.php` — never create a separate test file.
- Namespace must be `Detain\Tests\ZipZapper`; use statements must be `Detain\ZipZapper\Validator` and `PHPUnit\Framework\TestCase`.
- Every test method instantiates its own `new Validator()` — no shared `setUp()` instance.
- `@expectedException` docblock annotation is the project pattern for exception tests — do NOT use `$this->expectException()` (PHPUnit 5/6 style used here).
- Indentation: **tabs only** (per `.scrutinizer.yml`).
- Run `vendor/bin/phpunit tests/ -v` after every change to confirm no regressions.

## Instructions

1. **Identify what to test.** Determine which method and country code the user wants covered. Check `src/Validator.php` `$formats` array to confirm the country code exists and note its format strings. Check `$zipNames` if testing `getZipName()`.
   - Verify the country code is present in `$formats` before writing the test.

2. **Name the test method.** Follow the pattern `test{CountryAdjective}Code()` for `isValid()` country tests (e.g., `testCanadianCode`, `testJapaneseCode`). For other methods use descriptive names: `testZipName`, `testGetFormats`, `testHasCountry`, `testInvalidCountryCode`, `testGetFormatsWithInvalidCountryCode`.
   - Verify no method with the same name already exists in the file before adding.

3. **Write a valid-code assertion.** For `isValid()` tests, assert at least one real postal code that matches the country's format:
   ```php
   public function testCanadianCode()
   {
       $validator = new Validator();
       $this->assertTrue($validator->isValid('CA', 'K1A 0B1'));
   }
   ```

4. **Add a false assertion when the format is strict.** If the format requires a space (e.g., `'@#@ #@#'`, `'### ##'`) include an `assertFalse` for the no-space version and a `assertTrue` with `$ignoreSpaces = true`:
   ```php
   $this->assertFalse($validator->isValid('CA', 'K1A0B1'));
   $this->assertTrue($validator->isValid('CA', 'K1A0B1', true));
   ```
   - Only add this when the format string contains a literal space character.

5. **Test `getFormats()`.** Assert the exact array from `$formats`:
   ```php
   public function testGetFormats()
   {
       $validator = new Validator();
       $this->assertEquals(['@#@ #@#'], $validator->getFormats('CA'));
   }
   ```

6. **Test `getZipName()`.** Check both a country in `$zipNames` and the fallback default:
   ```php
   public function testZipName()
   {
       $validator = new Validator();
       $this->assertEquals('ZIP code', $validator->getZipName('US'));
       $this->assertEquals('Postal Code', $validator->getZipName('invalid_country_code'));
   }
   ```

7. **Test `hasCountry()`.** Assert true for a known code and false for an invalid one:
   ```php
   public function testHasCountry()
   {
       $validator = new Validator();
       $this->assertTrue($validator->hasCountry('US'));
       $this->assertFalse($validator->hasCountry('invalid_country_code'));
   }
   ```

8. **Test `ValidationException` throwing.** Use `@expectedException` docblock — one method for `isValid()`, one for `getFormats()`:
   ```php
   /**
    * @expectedException Detain\ZipZapper\ValidationException
    */
   public function testInvalidCountryCode()
   {
       $validator = new Validator();
       $validator->isValid('XXXXXX', 'YYYYYY');
   }

   /**
    * @expectedException Detain\ZipZapper\ValidationException
    */
   public function testGetFormatsWithInvalidCountryCode()
   {
       $validator = new Validator();
       $validator->getFormats('invalid_country_code');
   }
   ```

9. **Insert new methods** before the closing `}` of the class. Append; do not reorder existing tests.

10. **Run tests** to confirm green:
    ```bash
    vendor/bin/phpunit tests/ -v
    ```

## Examples

**User says:** "Add a test for Canadian postal codes"

**Actions taken:**
1. Check `src/Validator.php` — `'CA' => ['@#@ #@#']` found.
2. Method name: `testCanadianCode` (not already in file).
3. Format has a space → add `assertFalse` (no-space) and `assertTrue` with `$ignoreSpaces = true`.

**Result added to `ValidatorTest.php`:**
```php
public function testCanadianCode()
{
	$validator = new Validator();
	$this->assertTrue($validator->isValid('CA', 'K1A 0B1'));
	$this->assertFalse($validator->isValid('CA', 'K1A0B1'));
	$this->assertTrue($validator->isValid('CA', 'K1A0B1', true));
}
```

**User says:** "Test getZipName for German"

**Actions taken:**
1. Check `$zipNames` — `'DE' => ['name' => 'PLZ', ...]` found.

**Result:**
```php
public function testZipName()
{
	$validator = new Validator();
	$this->assertEquals('PLZ', $validator->getZipName('DE'));
	$this->assertEquals('Postal Code', $validator->getZipName('invalid_country_code'));
}
```
*(Update existing `testZipName` if already present; don't duplicate.)*

## Common Issues

- **`Call to undefined method ... getZipName()`** — you are testing a method that does not exist in the installed version. Run `grep -n 'getZipName' src/Validator.php` to confirm the method name spelling.
- **Test passes but wrong format tested** — you used a postal code that matches a different country's format. Derive test values from the exact format string in `$formats`: `#` → any digit, `@` → any letter.
- **`@expectedException` has no effect / test always passes** — confirm PHPUnit version with `vendor/bin/phpunit --version`. This project targets PHPUnit 4/5 annotation style. Do NOT switch to `$this->expectException()`.
- **Tab vs space IndentationError from phpcs** — the `.scrutinizer.yml` enforces tabs. If your editor inserts spaces, convert: `unexpand --first-only -t 4 file.php`.
- **`Class 'Detain\ZipZapper\ValidationException' not found`** — run `composer dump-autoload` to regenerate the classmap after any new file additions.
- **Duplicate method name** — PHPUnit will silently run only the last definition. Always `grep testMethodName tests/Detain/Tests/ZipZapper/ValidatorTest.php` before adding.