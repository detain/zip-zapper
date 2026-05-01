<?php
/**
 * Parser: regenerate the $formats array in src/Validator.php from Wikipedia.
 *
 * Fetches https://en.wikipedia.org/wiki/Special:Export/List_of_postal_codes,
 * parses the wikitext table, merges with the country_t DB table (for countries
 * not listed on Wikipedia), and prints sorted PHP array lines to stdout.
 *
 * NOTE: This script requires the parent MyAdmin environment.  It must be run
 * from a MyAdmin installation where the relative path
 * __DIR__ . '/../../../../include/functions.inc.php' resolves correctly
 * (i.e. the package is installed at vendor/detain/zip-zapper/).
 *
 * @link https://en.wikipedia.org/wiki/Category:Postal_system     Postal Systems by Country
 * @link https://en.wikipedia.org/wiki/List_of_postal_codes        List of Postal Codes
 */

/**
 * Convert a raw Wikipedia postal-code cell into an array of format strings.
 *
 * Wikipedia uses 'N' for a digit and 'A' for a letter; this function
 * translates those to the '#' / '@' symbols used by Validator.
 * 'CC' is replaced with the ISO 3166-1 alpha-2 country code.
 *
 * KNOWN LIMITATION: Wikipedia entries describing a single literal code
 * (e.g. 'AI-2640' for Anguilla, 'BIOT 1ZZ' for British Indian Ocean Territory)
 * can contain literal letters that this routine cannot distinguish from
 * format-symbol 'A's, and they will be mangled (e.g. 'AI-2640' → '@I-2640').
 * Generated output should be reviewed and corrected by hand for these cases
 * before being pasted into Validator::$formats.
 *
 * @param  string   $what  Comma-separated list of format patterns from the wikitext cell.
 * @param  string[] $codes Existing codes array to append to.
 * @param  string   $iso   ISO 3166-1 alpha-2 country code for 'CC' substitution.
 * @return string[]        Updated codes array.
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
$db = \MyAdmin\App::db();
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
