<?php
/**
 *
 * @copyright (c) 2018 Insite Apps - http://www.insiteapps.co.za
 * @package       insiteapps
 * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
 * All rights reserved. No warranty, explicit or implicit, provided.
 *
 * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if any.
 * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and may be
 * covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or copyright
 * laws. Dissemination of this information or reproduction of this material is strictly forbidden unless prior written
 * permission is obtained from Insite Apps. Proprietary and confidential. There is no freedom to use, share or change
 * this file.
 *
 *
 */

namespace InsiteApps\AppBase\Utli;

use InsiteApps\Forms\CountryDropdownField;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\i18n\i18n;
use SilverStripe\Security\Security;

class UtilityManager
{
    use Injectable;

    public function __construct()
    {
        parent::__construct();

    }

    public static function includeSpectrum()
    {

        ///Requirements::javascript(SPECTRUM_DIR . '/js/spectrum.js');
        //Requirements::javascript(SPECTRUM_DIR . '/js/spectrum.int.js');
        //Requirements::css(SPECTRUM_DIR . '/css/spectrum.css');
        //Requirements::css(SPECTRUM_DIR . '/css/spectrum.custom.css');

    }


    public static function parseURL($url, $retdata = true)
    {
        $url = substr($url, 0, 4) == 'http' ? $url : 'http://' . $url; //assume http if not supplied
        if ( $urldata = parse_url(str_replace('&amp;', '&', $url)) ) {
            $path_parts = pathinfo($urldata[ 'host' ]);
            $tmp = explode('.', $urldata[ 'host' ]);
            $n = count($tmp);
            if ( $n >= 2 ) {
                if ( $n == 4 || ( $n == 3 && strlen($tmp[ ( $n - 2 ) ]) <= 3 ) ) {
                    $urldata[ 'domain' ] = $tmp[ ( $n - 3 ) ] . "." . $tmp[ ( $n - 2 ) ] . "." . $tmp[ ( $n - 1 ) ];
                    $urldata[ 'tld' ] = $tmp[ ( $n - 2 ) ] . "." . $tmp[ ( $n - 1 ) ]; //top-level domain
                    $urldata[ 'root' ] = $tmp[ ( $n - 3 ) ]; //second-level domain
                    $urldata[ 'subdomain' ] = $n == 4 ? $tmp[ 0 ] : ( $n == 3 && strlen($tmp[ ( $n - 2 ) ]) <= 3 ) ? $tmp[ 0 ] : '';
                } else {
                    $urldata[ 'domain' ] = $tmp[ ( $n - 2 ) ] . "." . $tmp[ ( $n - 1 ) ];
                    $urldata[ 'tld' ] = $tmp[ ( $n - 1 ) ];
                    $urldata[ 'root' ] = $tmp[ ( $n - 2 ) ];
                    $urldata[ 'subdomain' ] = $n == 3 ? $tmp[ 0 ] : '';
                }
            }
            //$urldata['dirname'] = $path_parts['dirname'];
            $urldata[ 'basename' ] = $path_parts[ 'basename' ];
            $urldata[ 'filename' ] = $path_parts[ 'filename' ];
            $urldata[ 'extension' ] = $path_parts[ 'extension' ];
            $urldata[ 'base' ] = $urldata[ 'scheme' ] . "://" . $urldata[ 'host' ];
            $urldata[ 'abs' ] = ( isset($urldata[ 'path' ]) && strlen($urldata[ 'path' ]) ) ? $urldata[ 'path' ] : '/';
            $urldata[ 'abs' ] .= ( isset($urldata[ 'query' ]) && strlen($urldata[ 'query' ]) ) ? '?' . $urldata[ 'query' ] : '';
            //Set data
            if ( $retdata ) {
                return $urldata;
            } else {
                $this->urldata = $urldata;

                return true;
            }
        } else {
            //invalid URL
            return false;
        }
    }

    public static function camelCase($str)
    {
        $str = self::convertToLowercase($str);

        return preg_replace_callback('/_([a-z])/', function ($match) {
            return strtoupper($match[ 1 ]);
        }, $str);
    }


    public static function SentenceCase($string)
    {
        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $newString = '';
        foreach ( $sentences as $key => $sentence ) {
            $newString .= ( $key & 1 ) === 0 ?
                ucfirst(strtolower(trim($sentence))) :
                $sentence . ' ';
        }

        return trim($newString);
    }

    /**
     * @param        $class
     * @param string $key
     * @param string $field
     *
     * @return bool
     */
    public static function get_object_map($class, $key = "ID", $field = "Title")
    {
        $oObjs = $class::get();

        if ( count($oObjs) ) {

            $objects_map = $oObjs->map($key, $field);

            $aObjects = $objects_map->toArray();

            return $aObjects;
        }

        return [];
    }

    /**
     * @param $class
     *
     * @return array
     */
    public static function getObjectMap($class)
    {
        $oObjs = $class::get();

        return $oObjs ? $oObjs->map()->toArray() : [];
    }

    public static function getTemplateList()
    {
        $aTemplateList = [
            "Simple" => "List",

        ];

        return $aTemplateList;
    }

    public static function writeCssFile($path, $css)
    {

        file_put_contents($path, $css);
        singleton(SiteTree::class)->flushCache();

    }

    /**
     * @param        $file
     * @param string $ext
     *
     * @return string
     */
    private static function getCssFilePath($file, $ext = 'css')
    {
        $path_parts = pathinfo($file);
        $path = BASE_PATH . '/' . $path_parts[ 'dirname' ] . '/' . $path_parts[ 'filename' ] . '.' . $ext;

        return $path;
    }

    public static function HowDidYouHearAboutUsList()
    {
        $list = "Word of mouth,Google,Online Ads,Social Media";
        $aList = explode(',', $list);

        return array_combine(array_values($aList), array_values($aList));

    }

    public static function CountyList()
    {

        return static::getCountryList();
    }

    public static function getCountyNameFromCode($code)
    {
        $code = strtoupper($code);

        $countryList = static::$aCountryList;

        if ( !$countryList[ $code ] ) {
            return $countryList;
        } else {
            return $countryList[ $code ];
        }
    }

    /**
     * @param $code
     *
     * @return array|mixed
     */
    function code_to_country($code)
    {

        return static::getCountyNameFromCode($code);
    }

    private static $aCountryList = [
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe',
    ];

    public static function getCountryList()
    {
        $aCountryList = static::$aCountryList;

        return array_combine(array_values($aCountryList), array_values($aCountryList));

    }

    /**
     * @param int $x
     * @param int $max
     *
     * @return array
     */
    public static function getNumericValues($x = 0, $max = 4)
    {
        $arrValues = [];
        for ( $i = $x; $i <= $max; $i++ ) {
            $arrValues[ $i ] = $i;
        }

        return $arrValues;
    }

    public static function FileExists($oFile)
    {
        $path = $oFile->getFullPath();

        return is_file($path) && file_exists($path);
    }


    /**
     * @param string $name
     * @param string $title
     *
     * @return array
     */
    public static function aMonths($name = 'n', $title = 'F')
    {
        $months = [];
        for ( $m = 1; $m <= 12; $m++ ) {
            $timestamp = mktime(0, 0, 0, $m, 1);
            $months[ date($name, $timestamp) ] = date($title, $timestamp);
        }

        return $months;
    }


    public static function aSalutationList()
    {
        $list = "Mr,Mrs,Ms,Miss,Dr";
        $aSalutationList = explode(',', $list);

        return array_combine(array_values($aSalutationList), array_values($aSalutationList));

    }


    /**
     * Create a global unique id
     *
     * @return string Guid
     */
    public static function createGuid()
    {
        if ( function_exists('com_create_guid') === true ) {
            return trim(com_create_guid(), '{}');
        }

        return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
    }
}
