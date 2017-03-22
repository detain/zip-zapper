# Zip Zapper

Validates Zip / Postal type codes by country with some features 

[![Build Status](https://travis-ci.org/detain/zip-zapper.svg?branch=master)](https://travis-ci.org/detain/zip-zapper)

[Postal Systems by Country](https://en.wikipedia.org/wiki/Category:Postal_system)<br>
[DMOZ Post/Zip Code Info+DB](http://dmoztools.net/Reference/Directories/Address_and_Phone_Numbers/Postal_Codes/)<br>
[List of Postal Codes](https://en.wikipedia.org/wiki/List_of_postal_codes)<br>

Based on a similar project [sirprize/postal-code-validator](https://github.com/sirprize/postal-code-validator) but expanded on it adding over 100 new validations and updating ther others using mostly the Wikipedia postal codes list and some other features I needed in zip validation.

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

