<?php
/**
 *
 * @link https://en.wikipedia.org/wiki/Category:Postal_system Postal Systems by Country
 * @link http://dmoztools.net/Reference/Directories/Address_and_Phone_Numbers/Postal_Codes/ DMOZ Post/Zip Code Info+DB
 * @link https://en.wikipedia.org/wiki/List_of_postal_codes List of Postal Codes
 *
 * @var mixed
 */

$zip_names = [
	'CA' => ['name' => 'Postal Code'],
	'english speaking' => ['name' => 'Postcode'],
	'NL' => ['name' => 'Postcode'],
	'IE' => ['name' => 'Eircode'],
	'IT' => ['name' => 'CAP', 'acronym_text' => 'Codice di Avviamento Postale (Postal Expedition Code)'],
	'BR' => ['name' => 'CEP', 'acronym_text' => 'Código de endereçamento postal (Postal Addressing Code)'],
	'CH' => ['name' => 'NPA', 'acronym_text' => 'numéro postal d\'acheminement in French-speaking Switzerland and numéro postal d\'acheminement in Italian-speaking Switzerland'],
	'IN' => ['name' => 'PIN code', 'acronym_text' => 'postal index number.'],
	'DE' => ['name' => 'PLZ', 'acronym_text' => 'Postleitzahl (Postal Routing Number)'],
	'US' => ['name' => 'ZIP code', 'acronym_text' => 'Zone Improvement Plan'],
];

require(__DIR__ . '/../include/functions.inc.php');
function_requirements('getcurlpage');
//$page = getcurlpage('https://en.wikipedia.org/wiki/Special:Export/List_of_postal_codes');
$page = getcurlpage('https://en.wikipedia.org/wiki/List_of_postal_codes');
function_requirements('xml2array');
$data = xml2array($page, 1, 'attribute');
print_r($data);
exit;

/*
$file = file_get_contents('zip.txt');
$lines = explode("\n", $file);
$zips = [];
foreach ($lines as $line) {
	list($country, $area_zip, $street_zip, $notes) = explode("\t", $line);
	if (!isset($zips($country))) {
		$zips[$country] = [];
	$parts = explode(',', $area_zip);


	} else {
		echo "Country $country has area zip of $area_zip and Street zip $street zip    With Notes: $notes\n";
	}
}
*/
