<?php

namespace Detain\Tests\ZipZapper;

use Detain\ZipZapper\Validator;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 *
 * @package Detain\Tests\ZipZapper
 */
class ValidatorTest extends TestCase
{

    /**
     * @expectedException Detain\ZipZapper\ValidationException
     */
    public function testInvalidCountryCode()
    {
        $validator = new Validator();
        $validator->isValid('XXXXXX', 'YYYYYY');
    }

    public function testUkCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('GB', 'TN1 2GE'));
        $this->assertTrue($validator->isValid('GB', 'BD16 3QA'));
    }

    public function testSwissCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('CH', '3007'));
    }

    public function testGermanCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('DE', '50672'));
    }

    public function testPortugeseCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('PT', '2765-073'));
    }

    public function testJapaneseCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('JP', '155-0031'));
    }

    public function testUsCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('US', '81301'));
    }

    public function testEstonianCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('EE', '10123'));
    }

    public function testRussianCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('RU', '624800'));
    }

    public function testBelgianCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('BE', '1620'));
    }

    public function testItalianCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('IT', '00146'));
    }

    public function testFinnishCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('FI', '00160'));
    }

    public function testSwedishCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('SE', '113 37'));
    }

    public function testCzechCode()
    {
        $validator = new Validator();
        $this->assertTrue($validator->isValid('CZ', '602 00'));
        $this->assertFalse($validator->isValid('CZ', '60200'));
        $this->assertTrue($validator->isValid('CZ', '60200', TRUE));
    }

    public function testZipName()
    {
        $validator = new Validator();
        $this->assertEquals('ZIP code', $validator->getZipName('US'));
        $this->assertEquals('Postal Code', $validator->getZipName('invalid_country_code'));
    }

    public function testGetFormats()
    {
        $validator = new Validator();
        $this->assertEquals(['#####', '#####-####'], $validator->getFormats('US'));
    }

    /**
     * @expectedException Detain\ZipZapper\ValidationException 
     */
    public function testGetFormatsWithInvalidCountryCode()
    {
        $validator = new Validator();
        $validator->getFormats('invalid_country_code');
    }

    public function testHasCountry()
    {
        $validator = new Validator();
        $this->assertTrue($validator->hasCountry('US'));
        $this->assertFalse($validator->hasCountry('invalid_country_code'));
    }

}
