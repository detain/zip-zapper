# Zip Zapper

Validates Zip / Postal type codes by country with some features 

[![Latest Stable Version](https://poser.pugx.org/detain/zip-zapper/version)](https://packagist.org/packages/detain/zip-zapper)
[![Total Downloads](https://poser.pugx.org/detain/zip-zapper/downloads)](https://packagist.org/packages/detain/zip-zapper)
[![Latest Unstable Version](https://poser.pugx.org/detain/zip-zapper/v/unstable)](//packagist.org/packages/detain/zip-zapper)
[![License](https://poser.pugx.org/detain/zip-zapper/license)](https://packagist.org/packages/detain/zip-zapper)
[![Monthly Downloads](https://poser.pugx.org/detain/zip-zapper/d/monthly)](https://packagist.org/packages/detain/zip-zapper)
[![Daily Downloads](https://poser.pugx.org/detain/zip-zapper/d/daily)](https://packagist.org/packages/detain/zip-zapper)
[![Reference Status](https://www.versioneye.com/php/detain:zip-zapper/reference_badge.svg?style=flat)](https://www.versioneye.com/php/detain:zip-zapper/references) 
[![Build Status](https://travis-ci.org/detain/zip-zapper.svg?branch=master)](https://travis-ci.org/detain/zip-zapper)
[![Code Climate](https://codeclimate.com/github/detain/zip-zapper/badges/gpa.svg)](https://codeclimate.com/github/detain/zip-zapper) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/detain/zip-zapper/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/detain/zip-zapper/?branch=master) 
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/659523f63e16487ea71f6b763908d09e)](https://www.codacy.com/app/detain/zip-zapper)



[Postal Systems by Country](https://en.wikipedia.org/wiki/Category:Postal_system)<br>
[DMOZ Post/Zip Code Info+DB](http://dmoztools.net/Reference/Directories/Address_and_Phone_Numbers/Postal_Codes/)<br>
[List of Postal Codes](https://en.wikipedia.org/wiki/List_of_postal_codes)<br>

Based on a similar project [sirprize/postal-code-validator](https://github.com/sirprize/postal-code-validator) but expanded on it adding over 100 new validations and updating ther others using mostly the Wikipedia postal codes list and some other features I needed in zip validation.

## Installation

    composer require detain/zip-zapper

## Usage

### Check If Country Is Supported

    use Detain\ZipZapper\Validator;
    
    $validator = new Validator();
    $validator->hasCountry('CH'); // returns true

### Check If Postal Code Is Properly Formatted

    use Detain\ZipZapper\Validator;
    
    $validator = new Validator();
    $validator->isValid('CH', 'usjU87jsdf'); // returns false
    $validator->isValid('CH', '3007'); // returns true

### Get The Possible Formats For a Specific Country

    use Detain\ZipZapper\Validator;
    
    $validator = new Validator();
    $validator->getFormats('GB'); // returns array('@@## #@@', '@#@ #@@', '@@# #@@', '@@#@ #@@', '@## #@@', '@# #@@')

## Formatting

+ `#` = `0-9`
+ `@` = `a-zA-Z`

