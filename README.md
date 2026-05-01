# Zip Zapper

Validate postal/ZIP codes for any country worldwide.

[![Latest Stable Version](https://poser.pugx.org/detain/zip-zapper/version)](https://packagist.org/packages/detain/zip-zapper)
[![Total Downloads](https://poser.pugx.org/detain/zip-zapper/downloads)](https://packagist.org/packages/detain/zip-zapper)
[![License](https://poser.pugx.org/detain/zip-zapper/license)](https://packagist.org/packages/detain/zip-zapper)
[![Build Status](https://travis-ci.org/detain/zip-zapper.svg?branch=master)](https://travis-ci.org/detain/zip-zapper)
[![Code Climate](https://codeclimate.com/github/detain/zip-zapper/badges/gpa.svg)](https://codeclimate.com/github/detain/zip-zapper)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/detain/zip-zapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/detain/zip-zapper/?branch=master)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/659523f63e16487ea71f6b763908d09e)](https://www.codacy.com/app/detain/zip-zapper)

PHP library for validating postal/ZIP codes by country.  Covers **200+ countries** using
format data sourced from [Wikipedia's List of Postal Codes](https://en.wikipedia.org/wiki/List_of_postal_codes).

Based on [sirprize/postal-code-validator](https://github.com/sirprize/postal-code-validator) with major
expansions: 100+ new country validations, space-ignore mode, localised code names (CEP, PLZ, PIN, Eircode …),
and an automated Wikipedia parser to keep formats current.

## Installation

```bash
composer require detain/zip-zapper
```

## Quick Start

```php
use Detain\ZipZapper\Validator;
use Detain\ZipZapper\ValidationException;

$validator = new Validator();

// Check if a country is supported
$validator->hasCountry('US'); // true
$validator->hasCountry('XX'); // false

// Validate a postal code
$validator->isValid('US', '81301');         // true
$validator->isValid('US', '8130');          // false  (too short)
$validator->isValid('GB', 'SW1A 1AA');      // true
$validator->isValid('CA', 'K1A 0B1');       // true
$validator->isValid('DE', '50672');         // true

// Validate ignoring spaces (useful for user-input normalisation)
$validator->isValid('CZ', '60200');         // false  (format requires space: '602 00')
$validator->isValid('CZ', '60200', true);   // true   (space treated as optional)

// Get the local name for the postal code concept
$validator->getZipName('US'); // 'ZIP code'
$validator->getZipName('DE'); // 'PLZ'
$validator->getZipName('IE'); // 'Eircode'
$validator->getZipName('IN'); // 'PIN code'
$validator->getZipName('BR'); // 'CEP'
$validator->getZipName('ZZ'); // 'Postal Code'  (default fallback)

// Get the descriptive expansion of the local acronym
$validator->getZipAcronym('US'); // 'Zone Improvement Plan'
$validator->getZipAcronym('DE'); // 'Postleitzahl (Postal Routing Number)'
$validator->getZipAcronym('CA'); // ''  (no acronym registered)
$validator->getZipAcronym('XX'); // ''  (unknown country)

// Get all valid format strings for a country
$validator->getFormats('US'); // ['#####', '#####-####']
$validator->getFormats('GB'); // ['@#', '@##', '@@#', '@@##', '@#@', '@@#@', '@@@', '@# #@@', ...]

// List every supported ISO 3166-1 alpha-2 country code
$validator->getCountries(); // ['AD', 'AE', 'AF', 'AG', 'AI', 'AL', ... 'ZW']
```

## Handling Exceptions

`isValid()` and `getFormats()` throw `ValidationException` for unrecognised country codes.
Use `hasCountry()` first, or catch the exception:

```php
use Detain\ZipZapper\Validator;
use Detain\ZipZapper\ValidationException;

$validator = new Validator();

// Safe pattern using hasCountry()
if ($validator->hasCountry($countryCode)) {
    $valid = $validator->isValid($countryCode, $postalCode);
}

// Or catch the exception
try {
    $valid = $validator->isValid($countryCode, $postalCode);
} catch (ValidationException $e) {
    echo 'Unknown country code: ' . $e->getMessage();
}
```

## Format String Convention

Format strings describe the structure of a postal code:

| Symbol | Matches               | Example              |
|--------|-----------------------|----------------------|
| `#`    | One digit `[0-9]`     | `#####` → `81301`    |
| `@`    | One letter `[a-zA-Z]` | `@#@ #@#` → `K1A 0B1`|
| ` `    | Literal space         | `### ##` → `113 37`  |

A space is required unless `$ignoreSpaces = true` is passed to `isValid()`.

### Selected country formats

| Country   | Code | Format(s)                                      |
|-----------|------|------------------------------------------------|
| USA       | `US` | `#####`, `#####-####`                          |
| Canada    | `CA` | `@#@ #@#`                                      |
| UK        | `GB` | `@# #@@`, `@## #@@`, `@@# #@@`, `@@## #@@` … |
| Germany   | `DE` | `##`, `####`, `#####`                          |
| Japan     | `JP` | `###-####`                                     |
| Ireland   | `IE` | `@## @#@#`, `@## @@##`, `@## @#@@` …           |
| Sweden    | `SE` | `### ##`                                       |
| Australia | `AU` | `####`                                         |
| Brazil    | `BR` | `#####`, `#####-###`                           |

## Countries Without a Postal Code System

About 50 countries have no postal code system (e.g. AE, AG, AO, AW, BF).
Their `$formats` entry is `[]`, and `isValid()` returns `true` for any input:

```php
$validator->isValid('AE', 'anything'); // true — UAE has no postal system
$validator->isValid('AE', '');         // true
$validator->getFormats('AE');          // []
```

## API Reference

### `isValid(string $countryCode, string $postalCode, bool $ignoreSpaces = false): bool`

Validate `$postalCode` for `$countryCode`.
Returns `true` if it matches any registered format, or if the country has no postal system.
Throws `ValidationException` for unrecognised country codes.

### `getFormats(string $countryCode): string[]`

Return the format strings for a country (`[]` for countries with no postal system).
Throws `ValidationException` for unrecognised country codes.

### `hasCountry(string $countryCode): bool`

Return `true` if `$countryCode` is in the registry (does not throw).

### `getZipName(string $countryCode): string`

Return the local postal code name (`'ZIP code'`, `'PLZ'`, `'CEP'`, `'NPA'`, `'Eircode'`,
`'PIN code'`, `'CAP'`, `'Postcode'`, `'Postal Code'`).  Never throws — defaults to `'Postal Code'`.

### `getZipAcronym(string $countryCode): string`

Return the descriptive expansion for a country's postal code acronym
(e.g. `'Zone Improvement Plan'` for `US`, `'Postleitzahl (Postal Routing Number)'`
for `DE`).  Returns `''` for countries with no published acronym and for
unknown country codes.  Never throws.

### `getCountries(): string[]`

Return every ISO 3166-1 alpha-2 country code known to the validator,
including countries with no postal code system (empty `$formats` arrays).

## Country-Specific Names and Acronyms

The following countries have a localised name registered.  All other
countries fall back to `'Postal Code'` from `getZipName()` and `''`
from `getZipAcronym()`.

| ISO | Name        | Acronym                                                   |
|-----|-------------|-----------------------------------------------------------|
| BR  | CEP         | Código de endereçamento postal (Postal Addressing Code)   |
| CA  | Postal Code | —                                                         |
| CH  | NPA         | Numéro postal d'acheminement                              |
| DE  | PLZ         | Postleitzahl (Postal Routing Number)                      |
| IE  | Eircode     | —                                                         |
| IN  | PIN code    | Postal Index Number                                       |
| IT  | CAP         | Codice di Avviamento Postale (Postal Expedition Code)     |
| NL  | Postcode    | —                                                         |
| US  | ZIP code    | Zone Improvement Plan                                     |

## Adding a New Country

1. Add an entry to `$formats` in `src/Validator.php`, keyed by the ISO 3166-1 alpha-2 code.
2. Use `#` for digits and `@` for letters; use `[]` for no postal system.
3. Optionally add an entry to `$zipNames` if the country uses a distinct local name.
4. Add test cases to `tests/Detain/Tests/ZipZapper/ValidatorTest.php`.

```php
// In $formats:
'XX' => ['#####', '#####-####'],  // Country Name

// In $zipNames (optional):
'XX' => ['name' => 'PIN', 'acronym_text' => 'Postal Index Number'],
```

## Updating Format Data from Wikipedia

`bin/parse.php` fetches the Wikipedia postal codes list and prints updated PHP array lines.

> **Note:** requires the parent MyAdmin environment — cannot run standalone.

```bash
# From the MyAdmin root:
php vendor/detain/zip-zapper/bin/parse.php > /tmp/new_formats.php
# Review output, then paste into the $formats array in src/Validator.php
```

> **Known limitation:** Wikipedia entries that describe a single literal
> code (e.g. `AI-2640` for Anguilla, `BIOT 1ZZ` for British Indian Ocean
> Territory) contain literal letters that the parser cannot distinguish
> from format-symbol `A`s, so they will be mangled (e.g. `AI-2640` →
> `@I-2640`).  Always review the generated output by hand before
> committing.

## Development

```bash
composer install                       # install dependencies
composer test                          # run the test suite (PHPUnit)
vendor/bin/phpunit tests/ --testdox    # readable test output
```

### Requirements

- PHP **7.2+** (no runtime dependencies)
- PHPUnit **9.6+** (dev only)

## Resources

- [List of Postal Codes](https://en.wikipedia.org/wiki/List_of_postal_codes) — Wikipedia source
- [Postal Systems by Country](https://en.wikipedia.org/wiki/Category:Postal_system)

## License

MIT — see [LICENSE](LICENSE).
