<?php
/*
 * This file is part of the postal-code-validator package
 *
 * @author Joe Huss <detain@interserver.net>
 */

namespace Detain\ZipZapper;

/**
 * Validator.
 *
 * @author Joe Huss <detain@interserver.net>
 */
class Validator
{
    protected $zipNames = [
        'BR' => ['name' => 'CEP', 'acronym_text' => 'Código de endereçamento postal (Postal Addressing Code)'],
        'CA' => ['name' => 'Postal Code', 'acronym_text' => ''],
        'CH' => ['name' => 'NPA', 'acronym_text' => "numéro postal d'acheminement in French-speaking Switzerland and numéro postal d'acheminement in Italian-speaking Switzerland"],
        'DE' => ['name' => 'PLZ', 'acronym_text' => 'Postleitzahl (Postal Routing Number)'],
        'IE' => ['name' => 'Eircode', 'acronym_text' => ''],
        'IN' => ['name' => 'PIN code', 'acronym_text' => 'postal index number.'],
        'IT' => ['name' => 'CAP', 'acronym_text' => 'Codice di Avviamento Postale (Postal Expedition Code)'],
        'NL' => ['name' => 'Postcode', 'acronym_text' => ''],
        'US' => ['name' => 'ZIP code', 'acronym_text' => 'Zone Improvement Plan']
    ];

    /*
     * country code: ISO 3166 2-letter code
     * format:
     *     # - numberic 0-9
     *     @ - alpha a-zA-Z
     */
    protected $formats = [
        'AD' => ['AD###'], // Andorra, Notes: | 2004 Each [[Parishes of Andorra|parish]] now has its own post code.
        'AE' => [], // United Arab Emirates
        'AF' => ['####'], // Afghanistan, Notes: | 2011 The first two digits (ranging from 10–43) correspond to the province, while the last two digits correspond either to the city/delivery zone (range 01–50) or to the district/delivery zone (range 51–99). [https://web.archive.org/web/20130723140604/http://postalcode.afghanpost.gov.af/ Afghanistan Postal code lookup]. [https://youbianku.com/files/upu/AFG.pdf UPU: Afghanistan].
        'AG' => [], // Antigua and Barbuda
        'AI' => ['@I-2640'], // Anguilla, Notes: | 2007 Single code used for all addresses.
        'AL' => ['####'], // Albania, Notes: | 2006 Introduced in 2006, gradually implemented throughout 2007.
        'AM' => ['####'], // Armenia, Notes: | 2006-04-01 Previously used '''NNNNNN''' system inherited from former [[Soviet Union]].
        'AO' => [], // Angola
        'AQ' => ['BIQQ 1ZZ'], // British Antarctic Territory, Notes: | One code for all addresses (AAAA NAA). UK territory, but not UK postcode.
        'AR' => ['####', '####', '@####@@@'], // Argentina, Notes: | 1974, modified 1999 1974-1998 NNNN, and from 1999 ANNNNAAA. Codigo Postal Argentino (CPA), where the first A is the province code as in [[ISO 3166-2:AR]], the four numbers are the old postal codes, the three last letters indicate a side of the block. Previously '''NNNN''' which is the minimum requirement as of 2006, but ANNNNAAA is not mandatory.
        'AS' => ['#####', '#####-####'], // American Samoa, Notes: | 1963-07-01 U.S. ZIP codes (range '''96799''')
        'AT' => ['####'], // Austria, Notes: | 1966 The first digit denotes regions, which are partly identical to one of the nine provinces—called ''Bundesländer''; the last the nearest post office in the area.
        'AU' => ['####'], // Australia, Notes: | 1967 In general, the first digit identifies the state or territory.
        'AW' => [], // Aruba
        'AX' => ['#####', 'AX-#####'], // Åland Islands, Notes: | With Finland, first two numbers are 22. CC-NNNNN used from abroad.
        'AZ' => ['AZ ####'], // Azerbaijan, Notes: | Previously used '''NNNNNN''' system inherited from former [[Soviet Union]].
        'BA' => ['#####'], // Bosnia and Herzegovina
        'BB' => ['BB#####'], // Barbados, Notes: | Only one postal code currently assigned. '''11000''' applies to the General Post Office building in Cheapside, Bridgetown, to enable delivery to Barbados by global package delivery companies whose software requires a postal code.
        'BD' => ['####'], // | [[List of postal codes in Bangladesh|Bangladesh]]
        'BE' => ['####'], // Belgium, Notes: | In general, the first digit gives the province.
        'BF' => [], // Burkina Faso
        'BG' => ['####'], // Bulgaria
        'BH' => ['###', '####'], // Bahrain, Notes: | Valid post code numbers are '''101''' to '''1216''' with gaps in the range. Known as ''block number'' ({{lang-ar|رقم المجمع}}) formally. The first digit in NNN format and the first two digits in NNNN format refer to one of the 12 municipalities of the country. PO Box address doesn't need a block number or city name, just the PO Box number followed by the name of the country, [[Bahrain]].
        'BI' => [], // Burundi
        'BJ' => [], // Benin
        'BL' => ['97133'], // Saint Barthélemy, Notes: | Overseas Collectivity of France. French codes used, still within the same range used for [[Postal codes in Guadeloupe|Guadeloupe]]
        'BM' => ['@@ ##', '@@ @@'], // Bermuda, Notes: | AA NN for street addresses, AA AA for P.O. Box addresses. The second half of the postcode identifies the street delivery walk (e.g.: Hamilton HM 12) or the PO Box number range (e.g.: Hamilton HM BX). See [[Postal codes in Bermuda]].
        'BN' => ['@@####'], // Brunei
        'BO' => ['####'], // Bolivia
        'BQ' => [], // Bonaire, Sint Eustatius and Saba
        'BR' => ['#####', '#####-###', '#####-###'], // Brazil, Notes: | 1971 NNNNN only from 1971 to 1992. Código de Endereçamento Postal (CEP): -000 to -899 are used for streets, roads, avenues, boulevards; -900 to -959 are used for buildings with a high postal use; -960 to -969 are for promotional use; -970 to -989 are post offices and regular P.O. boxes; and -990 to -998 are used for community P.O. boxes. -999 is used for special services.
        'BS' => [], // Bahamas
        'BT' => ['#####'], // Bhutan, Notes: | 2010 Written Behind the village name. Digits: postal region (Dzongdey), district (Dzongkhag), sub district (Dungkhag), delivery area (two digits). [https://youbianku.com/files/upu/BTN.pdf UPU: Bhutan].
        'BV' => [], // Bouvet Island
        'BW' => [], // Botswana
        'BY' => ['######'], // Belarus, Notes: | Retained system inherited from former [[Soviet Union]].
        'BZ' => [], // Belize
        'CA' => ['@#@ #@#'], // Canada, Notes: | 1971–1975 The system was gradually introduced starting in April 1971 in Ottawa. The letters D, F, I, O, Q, and U are not used to avoid confusion with other letters or numbers.
        'CC' => ['####'], // Cocos (Keeling) Island, Notes: | Part of the Australian postal code system.
        'CD' => [], // Congo, Democratic Republic
        'CF' => [], // Central African Republic
        'CG' => [], // Congo (Brazzaville)
        'CH' => ['####'], // Switzerland, Notes: | 1964 With Liechtenstein, ordered from west to east. In [[Geneva]] and other big cities, like [[Basel]], [[Bern]], [[Zurich]], there may be one or two digits after the name of the city when the generic City code (1211) is used instead of the area-specific code (1201, 1202...), e.g.: 1211 Geneva 13. The digit identifies the post office. This addressing is generally used for P.O. box deliveries. Büsingen (Germany) and Campione (Italy) have also a Swiss postal code. [https://youbianku.com/files/upu/CHE.pdf UPU: Switzerland].
        'CI' => [], // Côte d'Ivoire (Ivory Coast)
        'CK' => [], // Cook Islands
        'CL' => ['#######', '###-####'], // Chile, Notes: | May only be required for bulk mail.
        'CM' => [], // Cameroon
        'CN' => ['######'], // China, Notes: | A postal code or ''youbian'' (邮编) in a subordinate division will have the same first two digits as its governing one (see [[Administrative divisions of the People's Republic of China#Levels|Political divisions of China]]. The postal services in [[Macau]] or [[Hong Kong]] [[Special Administrative Region of the People's Republic of China|Special Administrative Region]]s remain separate from [[Mainland China]], with no post code system currently used.
        'CO' => ['######'], // Colombia, Notes: | First NN = 32 [[departments of Colombia|departments]]<ref>{{cite web|url=http://english.4-72.com.co/?q=content/postal-codes |title=Postal Codes |publisher=Colombian Postal Network 4-72 |accessdate=2010-03-04 }}</ref> [http://www.codigopostal4-72.com.co/codigosPostales/ Códigos Postales | 4-72]
        'CR' => ['#####', '#####-####'], // Costa Rica, Notes: | 2007-03 Was NNNN until 2007. First codes the provinces, next two the canton, last two the district.
        'CU' => ['#####'], // Cuba, Notes: | May only be required for bulk mail. The letters CP are frequently used before the postal code. This is not a country code, but an abbreviation for "codigo postal" or postal code.
        'CV' => ['####'], // Cape Verde, Notes: | The first digit indicates the island.
        'CW' => [], // Curaçao
        'CX' => ['####'], // Christmas Island, Notes: | Part of the Australian postal code system.
        'CY' => ['####'], // Cyprus, Notes: | 1994-10-01 The postal code system covers the whole island, but is not used on mail to [[Northern Cyprus]]. Northern Cyprus uses a 5-digit code commencing 99, introduced in 2013. For mail sent there from abroad, the line "Mersin 10" is written on the line above that containing the postal code, and the country name used is "Turkey".
        'CZ' => ['### ##'], // Czech Republic, Notes: | 1973 With Slovak Republic, Poštovní směrovací číslo (PSČ) - postal routing number. The first digit is from the range 1–7.
        'DE' => ['##', '####', '#####'], // Germany, Notes: | 1941-07-25 - 1962 resp. 1965 Postleitzahl (PLZ)| 1962 resp. 1965 – 1993 Postleitzahl (PLZ) – Two separate systems in Western Germany (Federal Republic) and East Germany (German Democratic Republic). Between [[German reunification]] in 1989 and 1993 the old separate 4-digit postal codes of former [[West-Germany|West-]] and [[East-Germany]] were distinguished by preceding "W-" ('West') or "O-" ({{lang|de|'Ost'}} for East).| 1993 Postleitzahl (PLZ) – Totally new system introduced about 4 years after the [[German reunification]].
        'DJ' => [], // Djibouti
        'DK' => ['####'], // Denmark, Notes: | 1967-09-20 Numbering follows the dispatch of postal trains from Copenhagen.<ref>{{cite web|url=http://denstoredanske.dk/It,_teknik_og_naturvidenskab/Elektronik,_teletrafik_og_kommunikation/Postforsendelser/postnummer|title=postnummer - Gyldendal - Den Store Danske|website=denstoredanske.dk}}</ref> Also used by [[Greenland]], e.g.: [[Nuuk|DK-3900 Nuuk]].
        'DM' => [], // Dominica
        'DO' => ['#####'], // Dominican Republic
        'DZ' => ['#####'], // Algeria, Notes: | First two as in [[ISO 3166-2:DZ]]
        'EC' => ['######'], // Ecuador
        'EE' => ['#####'], // Estonia
        'EG' => ['#####'], // Egypt
        'EH' => [], // Western Sahara
        'ER' => [], // Eritrea
        'ES' => ['#####'], // Spain, Notes: | 1976 First two indicate the province, range 01-52
        'ET' => ['####'], // Ethiopia, Notes: | The code is only used on a trial basis for [[Addis Ababa]] addresses.
        'FI' => ['#####'], // Finland, Notes: | 1971 A lower first digit indicates a place in south (for example 00100 [[Helsinki]]), a higher indicates a place further to north (99800 in [[Ivalo]]). The last digit is usually 0, except for postal codes for PO Box number ranges, in which case it is 1. Country code for Finland: "FI". In the [[Åland Islands]], the postal code is prefixed with "AX", not "FI". Some postal codes for rural settlements may end with 5, and there are some unique postal codes for large companies and institutions, e.g. 00014 HELSINGIN YLIOPISTO (university), 00102 EDUSKUNTA (parliament), 00020 NORDEA (a major Scandinavian bank).
        'FJ' => [], // Fiji
        'FK' => ['FIQQ 1ZZ'], // Falkland Islands, Notes: | Single code (AAAA NAA). UK territory, but not UK postcode
        'FM' => ['#####', '#####-####'], // Micronesia, Notes: | 1963-07-01 U.S. ZIP codes. Range '''96941''' - '''96944'''.
        'FO' => ['###'], // Faroe Islands, Notes: | Self-governing territory within the Kingdom of Denmark, but not Danish postcode.
        'FR' => ['#####'], // France, Notes: | 1972 The first two digits give the ''[[département in France|département]]'' number, while in [[Paris]], [[Lyon]] and [[Marseille]], the last two digits of the postal code indicate the [[Municipal arrondissement in France|arrondissement]]. Both of the 2 Corsican départements use "20" as the first two digits. Also used by [[French overseas departments and territories]]. [[Monaco]] is also part of the French postal code system, but the country code MC- is used for Monegasque addresses.
        'GA' => [], // Gabon
        'GB' => ['@#', '@##', '@@#', '@@##', '@#@', '@@#@', '@@@', '@# #@@', '@## #@@', '@@# #@@', '@@## #@@', '@#@ #@@', '@@#@ #@@', '@@@ #@@'], // | [[Postcodes in the United Kingdom|United Kingdom]], Notes: | 1959–1974 Known as the [[UK postcodes|postcode]]. The first letter(s) indicate the [[List of postcode areas in the United Kingdom|postal area]], such as the town or part of London. Placed on a separate line below the city (or county, if used). The UK postcode is made up of two parts separated by a space. These are known as the '''outward postcode''' and the '''inward postcode'''. The outward postcode is always one of the following formats: AN, ANN, AAN, AANN, ANA, AANA, AAA. The inward postcode is always formatted as NAA. A valid inward postcode never contains the letters: C, I, K, M, O or V. The [[British Forces Post Office]] has a different system, but as of 2012 has also adopted UK-style postcodes that begin with "BF1" for electronic compatibility.
        'GD' => [], // Grenada
        'GE' => ['####'], // Georgia
        'GF' => ['973##'], // French Guiana, Notes: | Overseas Department of France. French codes used. Range '''97300''' - '''97390'''.
        'GG' => ['@@# #@@', '@@## #@@'], // | [[GY postcode area|Guernsey]], Notes: | 1993 UK-format postcode (first two letters are always GY)
        'GH' => [], // Ghana, Notes: | {{citation needed|date=January 2011}}
        'GI' => ['GX11 1@@'], // Gibraltar, Notes: | Single code used for all addresses.
        'GL' => ['####'], // Greenland, Notes: | Part of the Danish postal code system.
        'GM' => [], // Gambia
        'GN' => ['###'], // Guinea
        'GP' => ['971##'], // Guadeloupe, Notes: | Overseas Department of France. French codes used. Range '''97100''' - '''97190''', which still includes the distinctive postal codes for [[Postal codes in Saint Martin|Saint Martin]] and [[Postal codes in Saint Barthélemy|Saint Barthélemy]].
        'GQ' => [], // Equatorial Guinea
        'GR' => ['### ##'], // Greece
        'GS' => ['SIQQ 1ZZ'], // South Georgia and the South Sandwich Islands, Notes: | One code for all addresses.
        'GT' => ['#####'], // Guatemala, Notes: | The first two numbers identify the department, the third number the route and the last two the office.
        'GU' => ['#####', '#####-####'], // Guam, Notes: | 1963-07-01 U.S. ZIP codes. Range '''96910''' - '''96932'''.
        'GW' => ['####'], // Guinea Bissau
        'GY' => [], // Guyana
        'HK' => [], // Hong Kong, Notes: | [http://www.upu.int/fileadmin/documentsFiles/activities/addressingUnit/hkgEn.pdf] The dummy postal code of Hong Kong is 999077.
        'HM' => [], // Heard and McDonald Islands
        'HN' => ['@@####', '#####'], // Honduras, Notes: | NNNNN is still being used <ref>{{cite web|author=Graham Rhind |url=https://www.grcdi.nl/gsb/honduras.html |title=Global Sourcebook for International Data Management |publisher=Grcdi.nl |date= |accessdate=2019-04-03}}</ref><ref>{{cite web|url=https://en.youbianku.com/honduras |title=Honduras - Postal Code &#124; Post Code &#124; Postcode &#124; ZIP Code |publisher=En.youbianku.com |date=2019-03-16 |accessdate=2019-04-03}}</ref>
        'HR' => ['#####'], // Croatia
        'HT' => ['####'], // Haiti
        'HU' => ['####'], // Hungary, Notes: | 1973 The code defines an area, usually one code per settlement except the six largest towns. One code can identify more (usually) small settlements as well.
        'ID' => ['#####'], // Indonesia, Notes: | Kode Pos. Included [[East Timor]] (ranges 88xxx and 89xxx) until 1999, no longer used. For Indonesia postal code information visit [http://kodepos.posindonesia.co.id <nowiki>[2]</nowiki>]
        'IE' => ['@## @#@#', '@## @@##', '@## @#@@', '@#W @#@#', '@#W @@##', '@#W @#@@'], // Ireland, Notes: |2015-07-13 Ireland's postcode system (called Eircode) refers to individual properties - not to streets/areas. The first 3 characters are a routing key referring to an area's postal district, and the second 4 characters are a unique identifier for individual properties. See www.eircode.ie for more information. See also [[Republic of Ireland postal addresses]].''
        'IL' => ['#######'], // Israel, Notes: | 2013 In 2013, after the introduction of the 7 digit codes, 5 digit codes were still being used widely.
        'IM' => ['IM# #@@', 'IM## #@@'], // | [[IM postcode area|Isle of Man]], Notes: | 1993 UK-format postcode. The first two letters are always IM.
        'IN' => ['######', '## ###'], // India
        'IO' => ['BB#D 1ZZ'], // British Indian Ocean Territory, Notes: | One code for all addresses (AAAA NAA). UK territory, but not UK postcode.
        'IQ' => ['#####'], // Iraq
        'IR' => ['##########'], // Iran, Notes: | ([[Persian language|Persian]]: کد پستی)
        'IS' => ['###'], // Iceland
        'IT' => ['#####'], // Italy, Notes: | 1967 Codice di Avviamento Postale (CAP). Also used by [[San Marino]] and [[Vatican City]]. First two digits identify province with some exceptions, because there are more than 100 provinces.
        'JE' => ['JE# #@@', 'JE## #@@'], // | [[JE postcode area|Jersey]], Notes: | 1994 UK-format postcode. The first two letters are always JE.
        'JM' => ['##'], // Jamaica, Notes: |2007 Before suspension: CCAAANN. Jamaica currently has no national postal code system, except for Kingston and Lower St. Andrew, which are divided into postal districts numbered '''1'''-'''20'''<ref>[http://www.jamaicapost.gov.jm/corporate_news/pressrelease_07.htm Postal Corporation of Jamaica Press Releases 2007 - POST CODE PROJECT SUSPENDED INDEFINITELY (12 February 2007)] {{webarchive|url=https://web.archive.org/web/20100226125027/http://www.jamaicapost.gov.jm/corporate_news/pressrelease_07.htm |date=26 February 2010 }}</ref>
        'JO' => ['#####'], // Jordan, Notes: | Deliveries to PO Boxes only.
        'JP' => ['###-####'], // Japan, Notes: | 1968 See also [[Japanese addressing system]].
        'KE' => ['#####'], // Kenya, Notes: | Deliveries to PO Boxes only. The postal code refers to the post office at which the receiver's P. O. Box is located.
        'KG' => ['######'], // Kyrgyzstan
        'KH' => ['#####'], // Cambodia
        'KI' => [], // Kiribati
        'KM' => [], // Comoros
        'KN' => [], // Saint Kitts and Nevis
        'KP' => [], // Korea, North
        'KR' => ['#####'], // | [[List of postal codes in South Korea|Korea, South]], Notes: | 2015-08-01 Previously NNN-NNN (1988~2015), NNN-NN (1970~1988)
        'KW' => ['#####'], // Kuwait, Notes: | The first two digits represent the sector and the last three digits represents the post office.
        'KY' => ['KY#-####'], // Cayman Islands
        'KZ' => ['######'], // Kazakhstan, Notes: | <ref>[http://eshop.kazpost.kz/QueryForm.php Kazakhstan's postal codes] {{webarchive|url=https://archive.is/20070706064457/http://eshop.kazpost.kz/QueryForm.php |date=2007-07-06 }}</ref>
        'LA' => ['#####'], // Laos
        'LB' => ['#####', '#### ####'], // Lebanon, Notes: | The first four digits represent the region or postal zone,the last four digits represent the building see also [http://postal-codes.net/lebanon_postal_codes/ Lebanon Postal code website].
        'LC' => ['LC## ###'], // Saint Lucia, Notes: | 2015-12-03 The first two letters are always LC. There are two spaces between the second and third digits.
        'LI' => ['####'], // Liechtenstein, Notes: | 1964 With Switzerland, ordered from west to east. Range '''9485''' - '''9498'''.
        'LK' => ['#####'], // Sri Lanka, Notes: | Reference: http://mohanjith.net/ZIPLook/ Incorporates [[Colombo]] postal districts, e.g.: Colombo 1 is "00100". You can search for specific postal codes here [http://www.slpost.gov.lk].
        'LR' => ['####'], // Liberia, Notes: | Two digit postal zone after city name.
        'LS' => ['###'], // Lesotho
        'LT' => ['LT-#####'], // Lithuania, Notes: | References: http://www.post.lt/en/help/postal-code-search. Previously '''9999''' which was actually the old [[Soviet Union|Soviet]] '''999999''' format code with the first 2 digits dropped.
        'LU' => ['####'], // Luxembourg, Notes: | References: http://www.upu.int/post_code/en/countries/LUX.pdf
        'LV' => ['LV-####'], // Latvia
        'LY' => [], // Libya
        'MA' => ['#####'], // Morocco
        'MC' => ['980##'], // Monaco, Notes: | 1972 Uses the French Postal System, but with an "MC" Prefix for Monaco. Code range 98000-98099
        'MD' => ['MD####', 'MD-####'], // Moldova
        'ME' => ['#####'], // Montenegro
        'MF' => ['97150'], // Saint Martin, Notes: | Overseas Collectivity of France. French codes used, still within the same range used for [[Postal codes in Guadeloupe|Guadeloupe]]
        'MG' => ['###'], // Madagascar
        'MH' => ['#####', '#####-####'], // Marshall Islands, Notes: | 1963-07-01 U.S. ZIP codes. Range '''96960''' - '''96970'''.
        'MK' => ['####'], // Macedonia
        'ML' => [], // Mali
        'MM' => ['#####'], // Myanmar
        'MN' => ['#####'], // Mongolia, Notes: | First digit: region / zone, Second digit: province / district, Last three digits: locality / delivery block<ref>{{cite web|url=https://en.youbianku.com/Mongolia|title=Mongolia - Postal Code - Post Code - Postcode - ZIP Code|website=en.youbianku.com}}</ref>
        'MO' => [], // Macau, Notes: | [http://www.upu.int/fileadmin/userUpload/damFileSystem/universalPostalUnion/activities/addressing/postalAddressingSystemsInMemberCountries/sheetsEn/MAC.pdf] 999078 is the dummy code used in [[China]] to represent Macau, but it is not used.<!-- Please DON'T add any post code information. ALL postcode sources are WRONG, and Macau doesn't use postcodes. See the addressing guide from Universal Postal Union (link above) BEFORE editing. -->
        'MP' => ['#####', '#####-####'], // Northern Mariana Islands, Notes: | 1963-07-01 U.S. ZIP codes. Range '''96950''' - '''96952'''.
        'MQ' => ['972##'], // Martinique, Notes: | Overseas Department of France. French codes used. Range '''97200''' - '''97290'''.
        'MR' => [], // Mauritania
        'MS' => ['MSR ####'], // Montserrat, Notes: | Range 1110-1350
        'MT' => ['@@@ ####'], // Malta, Notes: | Kodiċi Postali
        'MU' => ['#####'], // Mauritius
        'MV' => ['#####'], // Maldives
        'MW' => [], // Malawi
        'MX' => ['#####'], // Mexico, Notes: | The first two digits identify the [[States of Mexico|state]] (or a part thereof), except for Nos. 00 to 16, which indicate ''delegaciones'' (boroughs) of the [[Mexican Federal District|Federal District]] ([[Mexico City]]).
        'MY' => ['#####'], // Malaysia
        'MZ' => ['####'], // Mozambique
        'NA' => [], // Namibia, Notes: | Formerly used South African postal code ranges from 9000-9299.<ref name="panorama">[https://books.google.co.uk/books?id=rMtBAAAAYAAJ&dq=Post+Codes+introduced+in+South+Africa+in+1973&focus=searchwithinvolume&q=9000 ''South African Panorama'' – Volume 22], South African Information Service, 1977, page 9</ref> Withdrawn from use after independence in 1990.<ref>[https://books.google.co.uk/books?id=1c63AAAAIAAJ&dq=Postal+codes+in+Namibia&focus=searchwithinvolume&q=%22postal+codes%22 ''The comprehensive handbook of the postmarks of German South West Africa, South West Africa, Namibia''], Ralph F. Putzel, R.F. Putzel, 1991, page 173</ref> A five-digit postal code system is under consideration.<ref>[http://www.upu.int/fileadmin/documentsFiles/activities/addressingUnit/namEn.pdf Namibia], [[Universal Postal Union]], May 2014</ref>
        'NC' => ['988##'], // New Caledonia, Notes: | Overseas Collectivity of France. French codes used. Range '''98800''' - '''98890'''.
        'NE' => ['####'], // Niger
        'NF' => ['####'], // Norfolk Island, Notes: | Part of the Australian postal code system.
        'NG' => ['######'], // Nigeria
        'NI' => ['#####'], // Nicaragua
        'NL' => ['#### @@'], // Netherlands, Notes: | 1977 The combination of the postal code and the house number gives a unique identifier of the address. The four numbers indicate an area, the two letters indicate a group of some 25 habitations, offices, factories, or post office boxes.
        'NO' => ['####'], // Norway, Notes: | 1968-03-18 From south to north
        'NP' => ['#####'], // | [[List of postal codes in Nepal|Nepal]]
        'NR' => [], // Nauru
        'NU' => [], // Niue
        'NZ' => ['####'], // New Zealand, Notes: | 2008-06 Postcodes were originally intended for bulk mailing and were not needed for addressing individual items. However, new post codes for general use were phased in from June 2006 and came into force by July 2008.
        'OM' => ['###'], // Oman, Notes: | Deliveries to P.O. Boxes only. [http://thepostalcodes.net/oman/ Oman Postal code for postoffice list/]
        'PA' => ['####'], // Panama
        'PE' => ['#####', 'PE ####'], // | [[List of postal codes in Peru|Peru]]
        'PF' => ['987##'], // French Polynesia, Notes: | Overseas Collectivity of France. French codes used. Range '''98700''' - '''98790'''.
        'PG' => ['###'], // Papua New Guinea
        'PH' => ['####'], // Philippines
        'PK' => ['#####'], // | [[List of postal codes of Pakistan|Pakistan]], Notes: | [https://web.archive.org/web/20190629161352/https://www.thepostalcodes.net/pakistan/ Pakistan postal codes list]
        'PL' => ['##-###'], // Poland, Notes: | 1973 (PNA) Pocztowy Numer Adresowy
        'PM' => ['97500'], // Saint Pierre and Miquelon, Notes: | Overseas Collectivity of France. French codes used.
        'PN' => ['PCR# 1ZZ'], // Pitcairn Islands, Notes: | Single code used(AAAA NAA). UK territory, but not UK postcode
        'PR' => ['#####', '#####-####'], // Puerto Rico, Notes: | 1963-07-01 U.S. ZIP codes. ZIP codes 006XX for NW PR, 007XX for SE PR, in which XX designates the town or post office and 009XX for the San Juan Metropolitan Area, in which XX designates the area or borough of San Juan. The last four digits identify an area within the post office. For example, 00716-2604: 00716-for the east section of the city of Ponce and 2604 for Aceitillo St. in the neighborhood of Los Caobos. US Post office is changing the PR address format to the American one: 1234 No Name Avenue, San Juan, PR 00901.
        'PS' => ['###'], // Palestine, Notes: | not yet implemented in practice. Codes '''100-899''' are in the Westbank, '''900-999''' in the Gaza Strip
        'PT' => ['####', '####-###'], // Portugal
        'PW' => ['#####', '#####-####'], // Palau, Notes: | 1963-07-01 U.S. ZIP codes. All locations '''96940'''.
        'PY' => ['####'], // | [[List of postal codes of Paraguay|Paraguay]]
        'QA' => [], // Qatar
        'RE' => ['974##'], // Réunion, Notes: | Overseas Department of France. French codes used. Range '''97400''' - '''97490'''.
        'RO' => ['######'], // Romania, Notes: | 2003-05-01 Previously '''99999''' in [[Bucharest]] and '''9999''' in rest of country.
        'RS' => ['#####'], // Serbia, Notes: | 2005-01-01 Poštanski adresni kod (PAK)
        'RU' => ['######'], // Russia, Notes: | 1971 Placed on a line of its own.
        'RW' => [], // Rwanda
        'SA' => ['#####-####', '#####'], // Saudi Arabia, Notes: | NNNNN for PO Boxes. NNNNN-NNNN for home delivery. A complete 13-digit code has 5-digit number representing region, sector, city, and zone; 4-digit X between 2000 and 5999; 4-digit Y between 6000 and 9999.<ref>{{cite web|url=http://www.esri.com/news/arcnews/winter1011articles/saudi-arabia.html |title=Saudi Arabia Implements New Postal Code System Based on GIS Analysis &#124; ArcNews Winter &#124; 2010/2011 Issue |publisher=Esri.com |date= |accessdate=2019-04-03}}</ref>  Digits of 5-digit code may represent postal region, sector, branch, section, and block respectively.<ref>{{cite web|url=http://youbianku.com/files/upu/SAU.pdf |title=Data |website=youbianku.com |format=PDF}}</ref>
        'SB' => [], // Solomon Islands
        'SC' => [], // Seychelles
        'SD' => ['#####'], // Sudan
        'SE' => ['### ##'], // Sweden, Notes: | 1968-05-12 The lowest number is '''100 00''' and the highest number is '''984 99'''.
        'SG' => ['##', '####', '######'], // Singapore, Notes: | 1995 Each building has its own unique postcode.
        'SH' => ['@@@@ 1ZZ'], // Saint Helena, Ascension and Tristan da Cunha, Notes: | Part of UK system (AAAA NAA). [[Saint Helena, Ascension and Tristan da Cunha|Saint Helena]] uses one code STHL 1ZZ, [[Ascension Island|Ascension]] uses one code ASCN 1ZZ, [[Tristan da Cunha]] uses one code TDCU 1ZZ.
        'SI' => ['####', 'SI-####'], // Slovenia
        'SJ' => ['####'], // | [[Svalbard and Jan Mayen]], Notes: | Norway postal codes
        'SK' => ['### ##'], // Slovakia, Notes: | 1973 with Czech Republic from west to east, Poštové smerovacie číslo (PSČ) - postal routing number. The first digit is from 8, 9, 0.
        'SL' => [], // Sierra Leone
        'SM' => ['4789#'], // San Marino, Notes: | With Italy, uses a five-digit numeric CAP of Emilia Romagna. Range 47890 and 47899
        'SN' => ['#####'], // Senegal, Notes: | The letters CP or C.P. are often written in front of the postcode. This is not a country code, but simply an abbreviation for "code postal".
        'SO' => ['@@ #####'], // Somalia, Notes: | Two letter postal codes for each of the nation's 18 [[Administrative divisions of Somalia|administrative regions]] (e.g. AW for [[Awdal]], BN for [[Banaadir]], BR for [[Bari, Somalia|Bari]] and SL for [[Sool, Somalia|Sool]]).<ref name="Wsptsg">{{cite news|title=Weekly Statement: Progress of the Somali Government|url=http://diplomat.so/2014/10/11/weekly-statement-progress-of-the-somali-government-3/|accessdate=12 October 2014|agency=Diplomat News Network|date=11 October 2014}}</ref>
        'SR' => [], // Suriname
        'SS' => [], // South Sudan
        'ST' => [], // Sao Tome and Principe
        'SV' => ['####'], // El Salvador
        'SX' => [], // Sint Maarten
        'SY' => [], // Syria, Notes: | A 4-digit system has been announced. Status unknown.
        'SZ' => ['@###'], // Swaziland, Notes: | The letter identifies one of the country's four districts, either H, M, S or L. The four district regions are Hhohho region (H), Manzini region (M), Shiselweni region (S), and Lumombo region (L). <ref>{{cite web|url=http://www.sptc.co.sz/swazipost/codes/index.php|title=SwaziPost - a division of Swaziland Post and Telecommunications Corporation (SPTC)|website=www.sptc.co.sz}}</ref>
        'TC' => ['TKC@ 1ZZ'], // Turks and Caicos Islands, Notes: | Single code used for all addresses.
        'TD' => [], // Chad
        'TF' => [], // French Southern and Antarctic Territories, Notes: | French codes in the '''98400''' range have been reserved.
        'TG' => [], // Togo
        'TH' => ['#####'], // Thailand, Notes: | 1982-02-25 The first two specify the province, numbers as in [[ISO 3166-2:TH]], the third and fourth digits specify a district (amphoe)
        'TJ' => ['######'], // Tajikistan, Notes: | Retained system from former [[Soviet Union]]. First two numbers: Republic and Province, Second two numbers: District, last two numbers: Post Office. [https://youbianku.com/files/upu/TJK.pdf UPU: Tajikistan]. [http://tajikpost.tj/ru/2017/05/31/perechen-pochtovyih-indeksov-respubliki-tadzhikistan-stolitsa-gorod-dushanbe-734000/ Overview over some numbers].
        'TK' => [], // Tokelau
        'TL' => [], // East Timor, Notes: | No postal code system in use since Indonesian withdrawal in 1999.
        'TM' => ['######'], // Turkmenistan, Notes: | Retained system from former [[Soviet Union]].
        'TN' => ['####'], // Tunisia
        'TO' => [], // Tonga
        'TR' => ['#####'], // Turkey, Notes: | First two digits are the city numbers.<ref>{{cite web|url=http://postakodu.ptt.gov.tr/|title=Posta Kodu Öğrenme Ekranı|website=postakodu.ptt.gov.tr}}</ref>
        'TT' => ['######'], // Trinidad and Tobago, Notes: | 2012 (planned) First two digits specify a postal district (one of 72), next two digits a carrier route, last two digits a building or zone along that route
        'TV' => [], // Tuvalu
        'TW' => ['###', '###-##'], // Taiwan, Notes: | 1970-03-20 The first three digits of the postal code are required; the last two digits are optional. Codes are known as ''youdi quhao'' (郵遞區號), and are also assigned to [[Senkaku Islands]] (''Diaoyutai''), though Japanese-administered, the [[Pratas Islands]] and the [[Spratly Islands]]. See [[List of postal codes in Taiwan]].
        'TZ' => ['#####'], // Tanzania
        'UA' => ['#####'], // Ukraine
        'UG' => [], // Uganda
        'UM' => ['96898'], // | [[United States Minor Outlying Islands]], Notes: | ? Mostly uninhabited. There is only one postal code in use, 96898 Wake Island.
        'US' => ['#####', '#####-####'], // | [[ZIP code|United States]], Notes: | 1963-07-01 Known as the [[ZIP Code]] with five digits '''99999*''' or the ZIP+4 Code with nine digits '''99999-9999*''' (while the minimum requirement is the first five digits, the U.S. Postal Service encourages everyone to use all nine). Also used by the former US Pacific Territories: [[Federated States of Micronesia]]; [[Palau]]; and the [[Marshall Islands]], as well as in current US territories [[American Samoa]], [[Guam]], [[Northern Mariana Islands]], [[Puerto Rico]], and the [[United States Virgin Islands]]. An individual delivery point may be represented as an 11-digit number, but these are usually represented by [[Intelligent Mail barcode]] or formerly [[POSTNET]] bar code.
        'UY' => ['#####'], // Uruguay
        'UZ' => ['######'], // Uzbekistan, Notes: | 2005 [https://web.archive.org/web/20101102030521/http://www.aci.uz/ru/online_services/postal_indexes/ Почтовые индексы]
        'VA' => ['00120'], // Vatican, Notes: | Single code used for all addresses. Part of the Italian postal code system.
        'VC' => ['VC####'], // Saint Vincent and the Grenadines
        'VE' => ['####', '####-@'], // Venezuela
        'VG' => ['VG####'], // British Virgin Islands, Notes: | Specifically, VG1110 through VG1160<ref>{{cite web|url=http://www.bvi.gov.vg/content/what-are-postcodes-addresses-british-virgin-islands|title=What are the postcodes for addresses in the British Virgin Islands? - Government of the Virgin Islands|website=www.bvi.gov.vg}}</ref>
        'VI' => ['#####', '#####-####'], // U.S. Virgin Islands, Notes: | 1963-07-01 U.S. ZIP codes. Range '''00801''' - '''00851'''.
        'VN' => ['######'], // Vietnam, Notes: | First two indicate a [[provinces of Vietnam|province]].
        'VU' => [], // Vanuatu
        'WF' => ['986##'], // Wallis and Futuna, Notes: | Overseas Collectivity of France. French codes used. Range '''98600''' - '''98690'''.
        'WS' => ['WS####'], // Samoa
        'XK' => ['#####'], // Kosovo, Notes: | A separate postal code for Kosovo was introduced by the [[UNMIK]] postal administration in 2004. Serbian postcodes are still widely used in the Serbian enclaves. No country code has been assigned.
        'YE' => [], // Yemen, Notes: | System for Sana'a Governorate using geocoding "عنواني" based on the [[Postal addresses in the Republic of Ireland#OpenPostcode|OpenPostcode]] algorithm is inaugurated in 2014.<ref>{{cite web |url=http://www.althawranews.net/pdf/main/2014-01-02/02.pdf |title=Archived copy |accessdate=2014-01-02 |url-status=dead |archiveurl=https://web.archive.org/web/20140102193800/http://www.althawranews.net/pdf/main/2014-01-02/02.pdf |archivedate=2014-01-02 }}</ref>
        'YT' => ['976##'], // Mayotte, Notes: | Overseas Department of France. French codes used. Range '''97600''' - '''97690'''.
        'ZA' => ['####'], // South Africa, Notes: | 1973 Postal codes are allocated to individual Post Office branches, some have two codes to differentiate between P.O. Boxes and street delivery addresses. Included [[Namibia]] (ranges 9000-9299) until 1992, no longer used.
        'ZM' => ['#####'], // Zambia
        'ZW' => [], // Zimbabwe, Notes: | System is being planned.
    ];

    /**
     * @param      $countryCode
     * @param      $postalCode
     * @param bool $ignoreSpaces
     * @return bool
     * @throws \Detain\ZipZapper\ValidationException
     */
    public function isValid($countryCode, $postalCode, $ignoreSpaces = false)
    {
        //$postalCode = str_replace('-', '', $postalCode);
        if (!isset($this->formats[$countryCode])) {
            throw new ValidationException(sprintf('Invalid country code: "%s"', $countryCode));
        }

        foreach ($this->formats[$countryCode] as $format) {
            #echo $postalCode.' - '.$this->getFormatPattern($format).PHP_EOL;
            if (preg_match($this->getFormatPattern($format, $ignoreSpaces), $postalCode)) {
                return true;
            }
        }

        if (!count($this->formats[$countryCode])) {
            return true;
        }

        return false;
    }

    /**
     * @param $countryCode
     * @return mixed
     * @throws \Detain\ZipZapper\ValidationException
     */
    public function getFormats($countryCode)
    {
        if (!isset($this->formats[$countryCode])) {
            throw new ValidationException(sprintf('Invalid country code: "%s"', $countryCode));
        }

        return $this->formats[$countryCode];
    }

    /**
     * @param $countryCode
     * @return bool
     */
    public function hasCountry($countryCode)
    {
        return isset($this->formats[$countryCode]);
    }

    /**
     * @param      $format
     * @param bool $ignoreSpaces
     * @return string
     */
    protected function getFormatPattern($format, $ignoreSpaces = false)
    {
        //$format = str_replace('-', '', $format);
        $pattern = str_replace('#', '\d', $format);
        $pattern = str_replace('@', '[a-zA-Z]', $pattern);

        if ($ignoreSpaces) {
            $pattern = str_replace(' ', ' ?', $pattern);
        }

        return '/^'.$pattern.'$/';
    }

    /**
     * @param $countryCode
     * @return string
     */
    public function getZipName($countryCode)
    {
        if (isset($this->zipNames[$countryCode])) {
            $name = $this->zipNames[$countryCode]['name'];
        } else {
            $name = 'Postal Code';
        }
        return $name;
    }
}
