<?php
/**
 * @link     https://en.wikipedia.org/wiki/Category:Postal_system Postal Systems by Country
 * @link     http://dmoztools.net/Reference/Directories/Address_and_Phone_Numbers/Postal_Codes/ DMOZ Post/Zip Code Info+DB
 * @link     https://en.wikipedia.org/wiki/List_of_postal_codes List of Postal Codes
 * @param $what
 * @param $codes
 * @return array
 * @internal param mixed $
 */
function get_codes_from($what, $codes, $iso)
{
	$what = explode(',', $what);
	foreach ($what as $eachArea) {
		if (trim($eachArea) != '' && trim($eachArea) != '- no codes -') {
			$codes[] = "'".str_replace(['N', 'A', 'CC'], ['#', '@', $iso], trim($eachArea))."'";
		}
	}
	return $codes;
}

require __DIR__.'/../../../../include/functions.inc.php';
function_requirements('getcurlpage');
$page = getcurlpage('https://en.wikipedia.org/wiki/Special:Export/List_of_postal_codes');
$page = str_replace(["\n\n", '&lt;', '&gt;', '&amp;', '<br />'], ["\n", '<', '>', '&', ''], $page);
$lines = explode("\n", $page);
$found = [];
$out = [];
for ($x = 0, $xMax = sizeof($lines); $x < $xMax; $x++) {
	$line = $lines[$x];
	if ((trim($line) == '|-' || trim($line) == '|-.') && mb_substr($lines[$x + 1], 0, 1) != '!') {
		$x++;
		$country = preg_replace('/\| *\[\[Postal codes in [^\|]*\|(.*)\]\]/msU', '\1', $lines[$x]);
		$x++;
		$years = trim($lines[$x]);
		$x++;
		$iso = preg_replace('/\| *\[\[ISO 3166-[0-9]*:[A-Z]*\|(.*)\]\]/msU', '\1', $lines[$x]);
		$area = '';
		$street = '';
		$notes = array_key_exists($iso, $out) ? $out[$iso]['notes'] : '';
		if (trim($lines[$x + 1]) != '|-') { 
			$x++;
			$area = trim(mb_substr($lines[$x], 1));
			if (trim($lines[$x + 1]) != '|-') {
				$x++;
				$street = trim(mb_substr($lines[$x], 1));
				if (trim($lines[$x + 1]) != '|-') {
					$x++;
					if (trim(mb_substr($lines[$x], 1)) != '') {
						$notes .= ($years != '' ? $years.' ' : '') . trim(mb_substr($lines[$x], 1));
					}
				}
			}
		}
		$codes = array_key_exists($iso, $out) ? $out[$iso]['codes'] : [];
		$codes = get_codes_from($area, $codes, $iso);
		$codes = get_codes_from($street, $codes, $iso);
		$found[] = $iso;
		$out[$iso] = [
			'codes' => $codes,
			'country' => $country,
			'notes' => $notes,
		];
	}
}
$db = $GLOBALS['tf']->db;                                                    
$db->query('select * from country_t order by iso2;');
while ($db->next_record(MYSQL_ASSOC)) {
	if (!in_array($db->Record['iso2'], $found)) {
		$out[$db->Record['iso2']] = [
			'codes' => [],
			'country' => $db->Record['short_name'],
			'notes' => '',
		];
	}
}
$keys = array_keys($out);
sort($keys);
foreach ($keys as $iso) {
	$codes = $out[$iso]['codes'];
	$country = $out[$iso]['country'];
	$notes = $out[$iso]['notes'];
	echo "        '{$iso}' => [".implode(', ', $codes).'],'." // $country".(trim($notes) != '' ? ', Notes: '.$notes : '').PHP_EOL;
}
