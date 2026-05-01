<?php

namespace Detain\Tests\ZipZapper;

use Detain\ZipZapper\Validator;
use Detain\ZipZapper\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for the Validator class.
 *
 * Covers isValid(), getFormats(), hasCountry(), getZipName() and the
 * ValidationException thrown for unrecognised country codes.
 *
 * @package Detain\Tests\ZipZapper
 */
class ValidatorTest extends TestCase
{
	/**
	 * An unrecognised country code must throw ValidationException from isValid().
	 */
	public function testInvalidCountryCode(): void
	{
		$this->expectException(ValidationException::class);
		$validator = new Validator();
		$validator->isValid('XXXXXX', 'YYYYYY');
	}

	/**
	 * Standard UK postcodes (outward+inward format) must validate.
	 */
	public function testUkCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('GB', 'TN1 2GE'));
		$this->assertTrue($validator->isValid('GB', 'BD16 3QA'));
	}

	/**
	 * Swiss four-digit NPA codes must validate.
	 */
	public function testSwissCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('CH', '3007'));
	}

	/**
	 * German five-digit PLZ codes must validate.
	 */
	public function testGermanCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('DE', '50672'));
	}

	/**
	 * Portuguese NNNN-NNN codes must validate.
	 */
	public function testPortugeseCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('PT', '2765-073'));
	}

	/**
	 * Japanese NNN-NNNN codes must validate.
	 */
	public function testJapaneseCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('JP', '155-0031'));
	}

	/**
	 * US five-digit ZIP codes must validate.
	 */
	public function testUsCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('US', '81301'));
	}

	/**
	 * Estonian five-digit codes must validate.
	 */
	public function testEstonianCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('EE', '10123'));
	}

	/**
	 * Russian six-digit codes must validate.
	 */
	public function testRussianCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('RU', '624800'));
	}

	/**
	 * Belgian four-digit codes must validate.
	 */
	public function testBelgianCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('BE', '1620'));
	}

	/**
	 * Italian five-digit CAP codes must validate.
	 */
	public function testItalianCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('IT', '00146'));
	}

	/**
	 * Finnish five-digit codes must validate.
	 */
	public function testFinnishCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('FI', '00160'));
	}

	/**
	 * Swedish NNN NN codes (with internal space) must validate.
	 */
	public function testSwedishCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('SE', '113 37'));
	}

	/**
	 * Czech NNN NN codes are valid with space; invalid without; valid without space
	 * when $ignoreSpaces is true.
	 */
	public function testCzechCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('CZ', '602 00'));
		$this->assertFalse($validator->isValid('CZ', '60200'));
		$this->assertTrue($validator->isValid('CZ', '60200', true));
	}

	/**
	 * getZipName() returns the country-specific name for known countries,
	 * and falls back to 'Postal Code' for unknown ones.
	 */
	public function testZipName(): void
	{
		$validator = new Validator();
		$this->assertEquals('ZIP code', $validator->getZipName('US'));
		$this->assertEquals('Postal Code', $validator->getZipName('invalid_country_code'));
	}

	/**
	 * getFormats() returns the correct format array for a valid country.
	 */
	public function testGetFormats(): void
	{
		$validator = new Validator();
		$this->assertEquals(['#####', '#####-####'], $validator->getFormats('US'));
	}

	/**
	 * getFormats() must throw ValidationException for an unrecognised country code.
	 */
	public function testGetFormatsWithInvalidCountryCode(): void
	{
		$this->expectException(ValidationException::class);
		$validator = new Validator();
		$validator->getFormats('invalid_country_code');
	}

	/**
	 * hasCountry() returns true for supported codes and false for unknown ones.
	 */
	public function testHasCountry(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->hasCountry('US'));
		$this->assertFalse($validator->hasCountry('invalid_country_code'));
	}

	/**
	 * Countries with an empty formats array (no postal system) must return true
	 * from isValid() regardless of the supplied postal code.
	 */
	public function testCountryWithNoPostalSystem(): void
	{
		$validator = new Validator();
		// AE (United Arab Emirates) has no postal system — any value is valid
		$this->assertTrue($validator->isValid('AE', 'anything'));
		$this->assertTrue($validator->isValid('AE', ''));
	}

	/**
	 * US ZIP+4 codes (NNNNN-NNNN) must validate.
	 */
	public function testUsZipPlusFour(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('US', '81301-1234'));
	}

	/**
	 * Canadian FSA-LDU postal codes must validate.
	 */
	public function testCanadianCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('CA', 'K1A 0B1'));
	}

	/**
	 * Irish Eircode (routing key + unique identifier) must validate.
	 */
	public function testIrishEircode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('IE', 'D02 XY45'));
	}

	/**
	 * Anguilla's single literal code 'AI-2640' must validate exactly,
	 * and a code with a different leading letter must not.
	 *
	 * Regression: an earlier version of $formats stored the code as '@I-2640'
	 * (the parser had mangled the literal 'A' into the @ format symbol),
	 * which incorrectly accepted any letter in that position.
	 */
	public function testAnguillaLiteralCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('AI', 'AI-2640'));
		$this->assertFalse($validator->isValid('AI', 'BI-2640'));
	}

	/**
	 * British Indian Ocean Territory must validate the literal 'BIOT 1ZZ' code.
	 *
	 * Regression: an earlier version stored the code as 'BB#D 1ZZ',
	 * which both used a literal 'D' (so 'BIOT 1ZZ' would not match) and
	 * incorrectly allowed any digit in the third position.
	 */
	public function testBritishIndianOceanTerritoryLiteralCode(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('IO', 'BIOT 1ZZ'));
	}

	/**
	 * Argentina must accept both the legacy NNNN format and the modern
	 * ANNNNAAA Codigo Postal Argentino format.
	 *
	 * Regression: $formats previously had '####' duplicated in the AR entry.
	 */
	public function testArgentinaCodes(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('AR', '1406'));
		$this->assertTrue($validator->isValid('AR', 'C1406DOM'));
		$this->assertEquals(['####', '@####@@@'], $validator->getFormats('AR'));
	}

	/**
	 * Brazil must accept the bare 5-digit CEP and the 8-digit CEP with hyphen.
	 *
	 * Regression: $formats previously had '#####-###' duplicated in the BR entry.
	 */
	public function testBrazilCepCodes(): void
	{
		$validator = new Validator();
		$this->assertTrue($validator->isValid('BR', '01310'));
		$this->assertTrue($validator->isValid('BR', '01310-100'));
		$this->assertEquals(['#####', '#####-###'], $validator->getFormats('BR'));
	}

	/**
	 * getZipAcronym() returns the published expansion when present and
	 * an empty string for countries with no acronym or unknown country codes.
	 */
	public function testGetZipAcronym(): void
	{
		$validator = new Validator();
		$this->assertEquals('Zone Improvement Plan', $validator->getZipAcronym('US'));
		$this->assertEquals('Postleitzahl (Postal Routing Number)', $validator->getZipAcronym('DE'));
		$this->assertEquals('', $validator->getZipAcronym('CA'));
		$this->assertEquals('', $validator->getZipAcronym('XX'));
	}

	/**
	 * getCountries() returns every key in the $formats registry, including
	 * countries with no postal system, and contains the expected set of
	 * common ISO 3166-1 alpha-2 codes.
	 */
	public function testGetCountries(): void
	{
		$validator = new Validator();
		$countries = $validator->getCountries();
		$this->assertIsArray($countries);
		$this->assertContains('US', $countries);
		$this->assertContains('GB', $countries);
		$this->assertContains('AE', $countries); // empty formats — still listed
		$this->assertGreaterThan(200, count($countries));
	}

	/**
	 * Format symbols must distinguish digits ('#') from letters ('@'),
	 * and must reject codes that mismatch the type at any position.
	 */
	public function testFormatSymbolEnforcement(): void
	{
		$validator = new Validator();
		// US is digits-only — letters must fail
		$this->assertFalse($validator->isValid('US', 'ABCDE'));
		// CA is @#@ #@# — all-digits must fail
		$this->assertFalse($validator->isValid('CA', '123 456'));
	}
}
