# Zip Zapper

Validates Zip / Postal type codes by country with some features 

[Postal Systems by Country](https://en.wikipedia.org/wiki/Category:Postal_system)
[DMOZ Post/Zip Code Info+DB](http://dmoztools.net/Reference/Directories/Address_and_Phone_Numbers/Postal_Codes/)
[List of Postal Codes](https://en.wikipedia.org/wiki/List_of_postal_codes)

## Usage

### Check If Country Is Supported

    use Detain\Zip-Zapper\Validator;
    
    $validator = new Validator();
    $validator->hasCountry('CH'); // returns true

### Check If Postal Code Is Properly Formatted

    use Detain\Zip-Zapper\Validator;
    
    $validator = new Validator();
    $validator->isValid('CH', 'usjU87jsdf'); // returns false
    $validator->isValid('CH', '3007'); // returns true

### Get The Possible Formats For a Specific Country

    use Detain\Zip-Zapper\Validator;
    
    $validator = new Validator();
    $validator->getFormats('GB'); // returns array('@@## #@@', '@#@ #@@', '@@# #@@', '@@#@ #@@', '@## #@@', '@# #@@')

## Formatting

+ `#` = `0-9`
+ `@` = `a-zA-Z`

