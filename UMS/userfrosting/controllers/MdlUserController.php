<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/23/2016
 * Time: 5:59 PM
 */
namespace UserFrosting;

class MdlUserController extends \UserFrosting\BaseController
{
    public function __construct($app)
    {
        $this->_app = $app;
    }

    public function pageMdlUsers()
    {
        //lấy list user trong csdl (là những user đang hoạt động có trường deleted = '0')
        $user_collection = MdlUser::queryBuilder()
            ->where("deleted", "0")
            ->get();
        $name = "Users Moodle";
        $icon = "fa fa-users";

        $this->_app->render("/users/mdluser_list.twig",[
            "box_title" => $name,
            "icon" => $icon,
            "users" => isset($user_collection) ? $user_collection : []
        ]);
    }

    //Create form Usermood. Hàm này tạo form cho create mdluser
    public function CreateMdlUser(){
        // Get a list of all groups
        $get = $this->_app->request->get();

        //Get auths
        $auths = array(
            'cas' => 'CAS server (SSO)',
            'db' => 'External database',
            'email' => 'Email-based self-registration',
            'fc' => 'FirstClass server',
            'imap' => 'IMAP server',
            'ldap' => 'LDAP server',
            'manual' => 'Manual accounts',
            'mnet' => 'MNet authentication',
            'nntp' => 'NNTP server',
            'nologin' => 'No login',
            'none' => 'No authentication',
            'pam' => 'PAM (Pluggable Authentication Modules)',
            'pop3' => 'POP3 server',
            'radius' => 'RADIUS server',
            'shibboleth' => 'Shibboleth',
            'webservice' => 'Web services authentication',
        );
        $mail_display = array(
            '0' => 'Hide my email address from everyone',
            '1' => 'Allow everyone to see my email address',
            '2' => 'Allow only other course members to see my email address',
        );

        $countries = array(
            'AD' => 'Andorra',
            'AE' => 'United Arab Emirates',
            'AF' => 'Afghanistan',
            'AG' => 'Antigua And Barbuda',
            'AI' => 'Anguilla',
            'AL' => 'Albania',
            'AM' => 'Armenia',
            'AO' => 'Angola',
            'AQ' => 'Antarctica',
            'AR' => 'Argentina',
            'AS' => 'American Samoa',
            'AT' => 'Austria',
            'AU' => 'Australia',
            'AW' => 'Aruba',
            'AX' => 'Åland Islands',
            'AZ' => 'Azerbaijan',
            'BA' => 'Bosnia And Herzegovina',
            'BB' => 'Barbados',
            'BD' => 'Bangladesh',
            'BE' => 'Belgium',
            'BF' => 'Burkina Faso',
            'BG' => 'Bulgaria',
            'BH' => 'Bahrain',
            'BI' => 'Burundi',
            'BJ' => 'Benin',
            'BL' => 'Saint Barthélemy',
            'BM' => 'Bermuda',
            'BN' => 'Brunei Darussalam',
            'BO' => 'Bolivia, Plurinational State Of',
            'BQ' => 'Bonaire, Sint Eustatius And Saba',
            'BR' => 'Brazil',
            'BS' => 'Bahamas',
            'BT' => 'Bhutan',
            'BV' => 'Bouvet Island',
            'BW' => 'Botswana',
            'BY' => 'Belarus',
            'BZ' => 'Belize',
            'CA' => 'Canada',
            'CC' => 'Cocos (Keeling) Islands',
            'CD' => 'Congo, The Democratic Republic Of The',
            'CF' => 'Central African Republic',
            'CG' => 'Congo',
            'CH' => 'Switzerland',
            'CI' => 'Côte D\'Ivoire',
            'CK' => 'Cook Islands',
            'CL' => 'Chile',
            'CM' => 'Cameroon',
            'CN' => 'China',
            'CO' => 'Colombia',
            'CR' => 'Costa Rica',
            'CU' => 'Cuba',
            'CV' => 'Cabo Verde',
            'CW' => 'Curaçao',
            'CX' => 'Christmas Island',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany',
            'DJ' => 'Djibouti',
            'DK' => 'Denmark',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'DZ' => 'Algeria',
            'EC' => 'Ecuador',
            'EE' => 'Estonia',
            'EG' => 'Egypt',
            'EH' => 'Western Sahara',
            'ER' => 'Eritrea',
            'ES' => 'Spain',
            'ET' => 'Ethiopia',
            'FI' => 'Finland',
            'FJ' => 'Fiji',
            'FK' => 'Falkland Islands (Malvinas)',
            'FM' => 'Micronesia, Federated States Of',
            'FO' => 'Faroe Islands',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GB' => 'United Kingdom',
            'GD' => 'Grenada',
            'GE' => 'Georgia',
            'GF' => 'French Guiana',
            'GG' => 'Guernsey',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GL' => 'Greenland',
            'GM' => 'Gambia',
            'GN' => 'Guinea',
            'GP' => 'Guadeloupe',
            'GQ' => 'Equatorial Guinea',
            'GR' => 'Greece',
            'GS' => 'South Georgia And The South Sandwich Islands',
            'GT' => 'Guatemala',
            'GU' => 'Guam',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HK' => 'Hong Kong',
            'HM' => 'Heard Island And Mcdonald Islands',
            'HN' => 'Honduras',
            'HR' => 'Croatia',
            'HT' => 'Haiti',
            'HU' => 'Hungary',
            'ID' => 'Indonesia',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IM' => 'Isle Of Man',
            'IN' => 'India',
            'IO' => 'British Indian Ocean Territory',
            'IQ' => 'Iraq',
            'IR' => 'Iran, Islamic Republic Of',
            'IS' => 'Iceland',
            'IT' => 'Italy',
            'JE' => 'Jersey',
            'JM' => 'Jamaica',
            'JO' => 'Jordan',
            'JP' => 'Japan',
            'KE' => 'Kenya',
            'KG' => 'Kyrgyzstan',
            'KH' => 'Cambodia',
            'KI' => 'Kiribati',
            'KM' => 'Comoros',
            'KN' => 'Saint Kitts And Nevis',
            'KP' => 'Korea, Democratic People\'s Republic Of',
            'KR' => 'Korea, Republic Of',
            'KW' => 'Kuwait',
            'KY' => 'Cayman Islands',
            'KZ' => 'Kazakhstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LB' => 'Lebanon',
            'LC' => 'Saint Lucia',
            'LI' => 'Liechtenstein',
            'LK' => 'Sri Lanka',
            'LR' => 'Liberia',
            'LS' => 'Lesotho',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'LV' => 'Latvia',
            'LY' => 'Libya',
            'MA' => 'Morocco',
            'MC' => 'Monaco',
            'MD' => 'Moldova, Republic Of',
            'ME' => 'Montenegro',
            'MF' => 'Saint Martin (French Part)',
            'MG' => 'Madagascar',
            'MH' => 'Marshall Islands',
            'MK' => 'Macedonia, The Former Yugoslav Republic Of',
            'ML' => 'Mali',
            'MM' => 'Myanmar',
            'MN' => 'Mongolia',
            'MO' => 'Macao',
            'MP' => 'Northern Mariana Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MS' => 'Montserrat',
            'MT' => 'Malta',
            'MU' => 'Mauritius',
            'MV' => 'Maldives',
            'MW' => 'Malawi',
            'MX' => 'Mexico',
            'MY' => 'Malaysia',
            'MZ' => 'Mozambique',
            'NA' => 'Namibia',
            'NC' => 'New Caledonia',
            'NE' => 'Niger',
            'NF' => 'Norfolk Island',
            'NG' => 'Nigeria',
            'NI' => 'Nicaragua',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'NP' => 'Nepal',
            'NR' => 'Nauru',
            'NU' => 'Niue',
            'NZ' => 'New Zealand',
            'OM' => 'Oman',
            'PA' => 'Panama',
            'PE' => 'Peru',
            'PF' => 'French Polynesia',
            'PG' => 'Papua New Guinea',
            'PH' => 'Philippines',
            'PK' => 'Pakistan',
            'PL' => 'Poland',
            'PM' => 'Saint Pierre And Miquelon',
            'PN' => 'Pitcairn',
            'PR' => 'Puerto Rico',
            'PS' => 'Palestine, State Of',
            'PT' => 'Portugal',
            'PW' => 'Palau',
            'PY' => 'Paraguay',
            'QA' => 'Qatar',
            'RE' => 'Réunion',
            'RO' => 'Romania',
            'RS' => 'Serbia',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'SA' => 'Saudi Arabia',
            'SB' => 'Solomon Islands',
            'SC' => 'Seychelles',
            'SD' => 'Sudan',
            'SE' => 'Sweden',
            'SG' => 'Singapore',
            'SH' => 'Saint Helena, Ascension And Tristan Da Cunha',
            'SI' => 'Slovenia',
            'SJ' => 'Svalbard And Jan Mayen',
            'SK' => 'Slovakia',
            'SL' => 'Sierra Leone',
            'SM' => 'San Marino',
            'SN' => 'Senegal',
            'SO' => 'Somalia',
            'SR' => 'Suriname',
            'SS' => 'South Sudan',
            'ST' => 'Sao Tome And Principe',
            'SV' => 'El Salvador',
            'SX' => 'Sint Maarten (Dutch Part)',
            'SY' => 'Syrian Arab Republic',
            'SZ' => 'Swaziland',
            'TC' => 'Turks And Caicos Islands',
            'TD' => 'Chad',
            'TF' => 'French Southern Territories',
            'TG' => 'Togo',
            'TH' => 'Thailand',
            'TJ' => 'Tajikistan',
            'TK' => 'Tokelau',
            'TL' => 'Timor-Leste',
            'TM' => 'Turkmenistan',
            'TN' => 'Tunisia',
            'TO' => 'Tonga',
            'TR' => 'Turkey',
            'TT' => 'Trinidad And Tobago',
            'TV' => 'Tuvalu',
            'TW' => 'Taiwan',
            'TZ' => 'Tanzania, United Republic Of',
            'UA' => 'Ukraine',
            'UG' => 'Uganda',
            'UM' => 'United States Minor Outlying Islands',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VA' => 'Holy See (Vatican City State)',
            'VC' => 'Saint Vincent And The Grenadines',
            'VE' => 'Venezuela, Bolivarian Republic Of',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'VN' => 'Viet Nam',
            'VU' => 'Vanuatu',
            'WF' => 'Wallis And Futuna',
            'WS' => 'Samoa',
            'YE' => 'Yemen',
            'YT' => 'Mayotte',
            'ZA' => 'South Africa',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );

        $timezone = array(
                'Africa/Abidjan' => 'Africa/Abidjan',
                'Africa/Accra' => 'Africa/Accra',
                'Africa/Addis_Ababa' => 'Africa/Addis_Ababa',
                'Africa/Algiers' => 'Africa/Algiers',
                'Africa/Asmera' => 'Africa/Asmera',
                'Africa/Bamako' => 'Africa/Bamako',
                'Africa/Bangui' => 'Africa/Bangui',
                'Africa/Banjul' => 'Africa/Banjul',
                'Africa/Bissau' => 'Africa/Bissau',
                'Africa/Blantyre' => 'Africa/Blantyre',
                'Africa/Brazzaville' => 'Africa/Brazzaville',
                'Africa/Bujumbura' => 'Africa/Bujumbura',
                'Africa/Cairo' => 'Africa/Cairo',
                'Africa/Casablanca' => 'Africa/Casablanca',
                'Africa/Ceuta' => 'Africa/Ceuta',
                'Africa/Conakry' => 'Africa/Conakry',
                'Africa/Dakar' => 'Africa/Dakar',
                'Africa/Dar_es_Salaam' => 'Africa/Dar_es_Salaam',
                'Africa/Djibouti' => 'Africa/Djibouti',
                'Africa/Douala' => 'Africa/Douala',
                'Africa/El_Aaiun' => 'Africa/El_Aaiun',
                'Africa/Freetown' => 'Africa/Freetown',
                'Africa/Gaborone' => 'Africa/Gaborone',
                'Africa/Harare' => 'Africa/Harare',
                'Africa/Johannesburg' => 'Africa/Johannesburg',
                'Africa/Kampala' => 'Africa/Kampala',
                'Africa/Khartoum' => 'Africa/Khartoum',
                'Africa/Kigali' => 'Africa/Kigali',
                'Africa/Kinshasa' => 'Africa/Kinshasa',
                'Africa/Lagos' => 'Africa/Lagos',
                'Africa/Libreville' => 'Africa/Libreville',
                'Africa/Lome' => 'Africa/Lome',
                'Africa/Luanda' => 'Africa/Luanda',
                'Africa/Lubumbashi' => 'Africa/Lubumbashi',
                'Africa/Lusaka' => 'Africa/Lusaka',
                'Africa/Malabo' => 'Africa/Malabo',
                'Africa/Maputo' => 'Africa/Maputo',
                'Africa/Maseru' => 'Africa/Maseru',
                'Africa/Mbabane' => 'Africa/Mbabane',
                'Africa/Mogadishu' => 'Africa/Mogadishu',
                'Africa/Monrovia' => 'Africa/Monrovia',
                'Africa/Nairobi' => 'Africa/Nairobi',
                'Africa/Ndjamena' => 'Africa/Ndjamena',
                'Africa/Niamey' => 'Africa/Niamey',
                'Africa/Nouakchott' => 'Africa/Nouakchott',
                'Africa/Ouagadougou' => 'Africa/Ouagadougou',
                'Africa/Porto-Novo' => 'Africa/Porto-Novo',
                'Africa/Sao_Tome' => 'Africa/Sao_Tome',
                'Africa/Timbuktu' => 'Africa/Timbuktu',
                'Africa/Tripoli' => 'Africa/Tripoli',
                'Africa/Tunis' => 'Africa/Tunis',
                'Africa/Windhoek' => 'Africa/Windhoek',
                'America/Adak' => 'America/Adak',
                'America/Anguilla' => 'America/Anguilla',
                'America/Anchorage' => 'America/Anchorage',
                'America/Antigua' => 'America/Antigua',
                'America/Araguaina' => 'America/Araguaina',
                'America/Argentina/Buenos_Aires' => 'America/Argentina/Buenos_Aires',
                'America/Argentina/Catamarca' => 'America/Argentina/Catamarca',
                'America/Argentina/ComodRivadavia' => 'America/Argentina/ComodRivadavia',
                'America/Argentina/Cordoba' => 'America/Argentina/Cordoba',
                'America/Argentina/Jujuy' => 'America/Argentina/Jujuy',
                'America/Argentina/La_Rioja' => 'America/Argentina/La_Rioja',
                'America/Argentina/Mendoza' => 'America/Argentina/Mendoza',
                'America/Argentina/Rio_Gallegos' => 'America/Argentina/Rio_Gallegos',
                'America/Argentina/San_Juan' => 'America/Argentina/San_Juan',
                'America/Argentina/Tucuman' => 'America/Argentina/Tucuman',
                'America/Argentina/Ushuaia' => 'America/Argentina/Ushuaia',
                'America/Aruba' => 'America/Aruba',
                'America/Asuncion' => 'America/Asuncion',
                'America/Bahia' => 'America/Bahia',
                'America/Barbados' => 'America/Barbados',
                'America/Belem' => 'America/Belem',
                'America/Belize' => 'America/Belize',
                'America/Boa_Vista' => 'America/Boa_Vista',
                'America/Bogota' => 'America/Bogota',
                'America/Boise' => 'America/Boise',
                'America/Cambridge_Bay' => 'America/Cambridge_Bay',
                'America/Campo_Grande' => 'America/Campo_Grande',
                'America/Cancun' => 'America/Cancun',
                'America/Caracas' => 'America/Caracas',
                'America/Cayenne' => 'America/Cayenne',
                'America/Cayman' => 'America/Cayman',
                'America/Costa_Rica' => 'America/Costa_Rica',
                'America/Cuiaba' => 'America/Cuiaba',
                'America/Curacao' => 'America/Curacao',
                'America/Danmarkshavn' => 'America/Danmarkshavn',
                'America/Dawson' => 'America/Dawson',
                'America/Dawson_Creek' => 'America/Dawson_Creek',
                'America/Denver' => 'America/Denver',
                'America/Detroit' => 'America/Detroit',
                'America/Dominica' => 'America/Dominica',
                'America/Edmonton' => 'America/Edmonton',
                'America/Eirunepe' => 'America/Eirunepe',
                'America/El_Salvador' => 'America/El_Salvador',
                'America/Fortaleza' => 'America/Fortaleza',
                'America/Glace_Bay' => 'America/Glace_Bay',
                'America/Godthab' => 'America/Godthab',
                'America/Goose_Bay' => 'America/Goose_Bay',
                'America/Grand_Turk' => 'America/Grand_Turk',
                'America/Grenada' => 'America/Grenada',
                'America/Guadeloupe' => 'America/Guadeloupe',
                'America/Guatemala' => 'America/Guatemala',
                'America/Guayaquil' => 'America/Guayaquil',
                'America/Guyana' => 'America/Guyana',
                'America/Halifax' => 'America/Halifax',
                'America/Havana' => 'America/Havana',
                'America/Hermosillo' => 'America/Hermosillo',
                'America/Chicago' => 'America/Chicago',
                'America/Chihuahua' => 'America/Chihuahua',
                'America/Indiana/Knox' => 'America/Indiana/Knox',
                'America/Indiana/Marengo' => 'America/Indiana/Marengo',
                'America/Indianapolis' => 'America/Indianapolis',
                'America/Indiana/Vevay' => 'America/Indiana/Vevay',
                'America/Inuvik' => 'America/Inuvik',
                'America/Iqaluit' => 'America/Iqaluit',
                'America/Jamaica' => 'America/Jamaica',
                'America/Juneau' => 'America/Juneau',
                'America/Kentucky/Monticello' => 'America/Kentucky/Monticello',
                'America/La_Paz' => 'America/La_Paz',
                'America/Lima' => 'America/Lima',
                'America/Los_Angeles' => 'America/Los_Angeles',
                'America/Louisville' => 'America/Louisville',
                'America/Maceio' => 'America/Maceio',
                'America/Managua' => 'America/Managua',
                'America/Manaus' => 'America/Manaus',
                'America/Martinique' => 'America/Martinique',
                'America/Mazatlan' => 'America/Mazatlan',
                'America/Menominee' => 'America/Menominee',
                'America/Merida' => 'America/Merida',
                'America/Mexico_City' => 'America/Mexico_City',
                'America/Miquelon' => 'America/Miquelon',
                'America/Monterrey' => 'America/Monterrey',
                'America/Montevideo' => 'America/Montevideo',
                'America/Montreal' => 'America/Montreal',
                'America/Montserrat' => 'America/Montserrat',
                'America/Nassau' => 'America/Nassau',
                'America/New_York' => 'America/New_York',
                'America/Nipigon' => 'America/Nipigon',
                'America/Nome' => 'America/Nome',
                'America/Noronha' => 'America/Noronha',
                'America/North_Dakota/Center' => 'America/North_Dakota/Center',
                'America/Panama' => 'America/Panama',
                'America/Pangnirtung' => 'America/Pangnirtung',
                'America/Paramaribo' => 'America/Paramaribo',
                'America/Phoenix' => 'America/Phoenix',
                'America/Port-au-Prince' => 'America/Port-au-Prince',
                'America/Port_of_Spain' => 'America/Port_of_Spain',
                'America/Porto_Velho' => 'America/Porto_Velho',
                'America/Puerto_Rico' => 'America/Puerto_Rico',
                'America/Rainy_River' => 'America/Rainy_River',
                'America/Rankin_Inlet' => 'America/Rankin_Inlet',
                'America/Recife' => 'America/Recife',
                'America/Regina' => 'America/Regina',
                'America/Rio_Branco' => 'America/Rio_Branco',
                'America/Santiago' => 'America/Santiago',
                'America/Santo_Domingo' => 'America/Santo_Domingo',
                'America/Sao_Paulo' => 'America/Sao_Paulo',
                'America/Scoresbysund' => 'America/Scoresbysund',
                'America/St_Johns' => 'America/St_Johns',
                'America/St_Kitts' => 'America/St_Kitts',
                'America/St_Lucia' => 'America/St_Lucia',
                'America/St_Thomas' => 'America/St_Thomas',
                'America/St_Vincent' => 'America/St_Vincent',
                'America/Swift_Current' => 'America/Swift_Current',
                'America/Tegucigalpa' => 'America/Tegucigalpa',
                'America/Thule' => 'America/Thule',
                'America/Thunder_Bay' => 'America/Thunder_Bay',
                'America/Tijuana' => 'America/Tijuana',
                'America/Toronto' => 'America/Toronto',
                'America/Tortola' => 'America/Tortola',
                'America/Vancouver' => 'America/Vancouver',
                'America/Whitehorse' => 'America/Whitehorse',
                'America/Winnipeg' => 'America/Winnipeg',
                'America/Yakutat' => 'America/Yakutat',
                'America/Yellowknife' => 'America/Yellowknife',
                'Antarctica/Casey' => 'Antarctica/Casey',
                'Antarctica/Davis' => 'Antarctica/Davis',
                'Antarctica/DumontDUrville' => 'Antarctica/DumontDUrville',
                'Antarctica/Mawson' => 'Antarctica/Mawson',
                'Antarctica/McMurdo' => 'Antarctica/McMurdo',
                'Antarctica/Palmer' => 'Antarctica/Palmer',
                'Antarctica/Rothera' => 'Antarctica/Rothera',
                'Antarctica/Syowa' => 'Antarctica/Syowa',
                'Antarctica/Vostok' => 'Antarctica/Vostok',
                'Asia/Aden' => 'Asia/Aden',
                'Asia/Almaty' => 'Asia/Almaty',
                'Asia/Amman' => 'Asia/Amman',
                'Asia/Anadyr' => 'Asia/Anadyr',
                'Asia/Aqtau' => 'Asia/Aqtau',
                'Asia/Aqtobe' => 'Asia/Aqtobe',
                'Asia/Ashgabat' => 'Asia/Ashgabat',
                'Asia/Baghdad' => 'Asia/Baghdad',
                'Asia/Bahrain' => 'Asia/Bahrain',
                'Asia/Baku' => 'Asia/Baku',
                'Asia/Bangkok' => 'Asia/Bangkok',
                'Asia/Beirut' => 'Asia/Beirut',
                'Asia/Bishkek' => 'Asia/Bishkek',
                'Asia/Brunei' => 'Asia/Brunei',
                'Asia/Calcutta' => 'Asia/Calcutta',
                'Asia/Colombo' => 'Asia/Colombo',
                'Asia/Damascus' => 'Asia/Damascus',
                'Asia/Dhaka' => 'Asia/Dhaka',
                'Asia/Dili' => 'Asia/Dili',
                'Asia/Dubai' => 'Asia/Dubai',
                'Asia/Dushanbe' => 'Asia/Dushanbe',
                'Asia/Gaza' => 'Asia/Gaza',
                'Asia/Harbin' => 'Asia/Harbin',
                'Asia/Hong_Kong' => 'Asia/Hong_Kong',
                'Asia/Hovd' => 'Asia/Hovd',
                'Asia/Choibalsan' => 'Asia/Choibalsan',
                'Asia/Chongqing' => 'Asia/Chongqing',
                'Asia/Irkutsk' => 'Asia/Irkutsk',
                'Asia/Jayapura' => 'Asia/Jayapura',
                'Asia/Jerusalem' => 'Asia/Jerusalem',
                'Asia/Kabul' => 'Asia/Kabul',
                'Asia/Kamchatka' => 'Asia/Kamchatka',
                'Asia/Karachi' => 'Asia/Karachi',
                'Asia/Kashgar' => 'Asia/Kashgar',
                'Asia/Katmandu' => 'Asia/Katmandu',
                'Asia/Krasnoyarsk' => 'Asia/Krasnoyarsk',
                'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur',
                'Asia/Kuching' => 'Asia/Kuching',
                'Asia/Kuwait' => 'Asia/Kuwait',
                'Asia/Macau' => 'Asia/Macau',
                'Asia/Magadan' => 'Asia/Magadan',
                'Asia/Makassar' => 'Asia/Makassar',
                'Asia/Manila' => 'Asia/Manila',
                'Asia/Muscat' => 'Asia/Muscat',
                'Asia/Nicosia' => 'Asia/Nicosia',
                'Asia/Novosibirsk' => 'Asia/Novosibirsk',
                'Asia/Omsk' => 'Asia/Omsk',
                'Asia/Oral' => 'Asia/Oral',
                'Asia/Phnom_Penh' => 'Asia/Phnom_Penh',
                'Asia/Pontianak' => 'Asia/Pontianak',
                'Asia/Pyongyang' => 'Asia/Pyongyang',
                'Asia/Qatar' => 'Asia/Qatar',
                'Asia/Qyzylorda' => 'Asia/Qyzylorda',
                'Asia/Rangoon' => 'Asia/Rangoon',
                'Asia/Riyadh' => 'Asia/Riyadh',
                'Asia/Saigon' => 'Asia/Saigon',
                'Asia/Sakhalin' => 'Asia/Sakhalin',
                'Asia/Samarkand' => 'Asia/Samarkand',
                'Asia/Seoul' => 'Asia/Seoul',
                'Asia/Shanghai' => 'Asia/Shanghai',
                'Asia/Singapore' => 'Asia/Singapore',
                'Asia/Taipei' => 'Asia/Taipei',
                'Asia/Tashkent' => 'Asia/Tashkent',
                'Asia/Tbilisi' => 'Asia/Tbilisi',
                'Asia/Tehran' => 'Asia/Tehran',
                'Asia/Thimphu' => 'Asia/Thimphu',
                'Asia/Tokyo' => 'Asia/Tokyo',
                'Asia/Ulaanbaatar' => 'Asia/Ulaanbaatar',
                'Asia/Urumqi' => 'Asia/Urumqi',
                'Asia/Vientiane' => 'Asia/Vientiane',
                'Asia/Vladivostok' => 'Asia/Vladivostok',
                'Asia/Yakutsk' => 'Asia/Yakutsk',
                'Asia/Yekaterinburg' => 'Asia/Yekaterinburg',
                'Asia/Yerevan' => 'Asia/Yerevan',
                'Atlantic/Azores' => 'Atlantic/Azores',
                'Atlantic/Bermuda' => 'Atlantic/Bermuda',
                'Atlantic/Canary' => 'Atlantic/Canary',
                'Atlantic/Cape_Verde' => 'Atlantic/Cape_Verde',
                'Atlantic/Faeroe' => 'Atlantic/Faeroe',
                'Atlantic/Madeira' => 'Atlantic/Madeira',
                'Atlantic/Reykjavik' => 'Atlantic/Reykjavik',
                'Atlantic/South_Georgia' => 'Atlantic/South_Georgia',
                'Atlantic/Stanley' => 'Atlantic/Stanley',
                'Atlantic/St_Helena' => 'Atlantic/St_Helena',
                'Australia/Adelaide' => 'Australia/Adelaide',
                'Australia/Brisbane' => 'Australia/Brisbane',
                'Australia/Broken_Hill' => 'Australia/Broken_Hill',
                'Australia/Darwin' => 'Australia/Darwin',
                'Australia/Hobart' => 'Australia/Hobart',
                'Australia/Lindeman' => 'Australia/Lindeman',
                'Australia/Lord_Howe' => 'Australia/Lord_Howe',
                'Australia/Melbourne' => 'Australia/Melbourne',
                'Australia/Perth' => 'Australia/Perth',
                'Australia/Sydney' => 'Australia/Sydney',
                'Europe/Amsterdam' => 'Europe/Amsterdam',
                'Europe/Andorra' => 'Europe/Andorra',
                'Europe/Athens' => 'Europe/Athens',
                'Europe/Belfast' => 'Europe/Belfast',
                'Europe/Belgrade' => 'Europe/Belgrade',
                'Europe/Berlin' => 'Europe/Berlin',
                'Europe/Brussels' => 'Europe/Brussels',
                'Europe/Budapest' => 'Europe/Budapest',
                'Europe/Bucharest' => 'Europe/Bucharest',
                'Europe/Copenhagen' => 'Europe/Copenhagen',
                'Europe/Dublin' => 'Europe/Dublin',
                'Europe/Gibraltar' => 'Europe/Gibraltar',
                'Europe/Helsinki' => 'Europe/Helsinki',
                'Europe/Chisinau' => 'Europe/Chisinau',
                'Europe/Istanbul' => 'Europe/Istanbul',
                'Europe/Kaliningrad' => 'Europe/Kaliningrad',
                'Europe/Kiev' => 'Europe/Kiev',
                'Europe/Lisbon' => 'Europe/Lisbon',
                'Europe/London' => 'Europe/London',
                'Europe/Luxembourg' => 'Europe/Luxembourg',
                'Europe/Madrid' => 'Europe/Madrid',
                'Europe/Malta' => 'Europe/Malta',
                'Europe/Minsk' => 'Europe/Minsk',
                'Europe/Monaco' => 'Europe/Monaco',
                'Europe/Moscow' => 'Europe/Moscow',
                'Europe/Oslo' => 'Europe/Oslo',
                'Europe/Paris' => 'Europe/Paris',
                'Europe/Prague' => 'Europe/Prague',
                'Europe/Riga' => 'Europe/Riga',
                'Europe/Rome' => 'Europe/Rome',
                'Europe/Samara' => 'Europe/Samara',
                'Europe/Simferopol' => 'Europe/Simferopol',
                'Europe/Sofia' => 'Europe/Sofia',
                'Europe/Stockholm' => 'Europe/Stockholm',
                'Europe/Tallinn' => 'Europe/Tallinn',
                'Europe/Tirane' => 'Europe/Tirane',
                'Europe/Uzhgorod' => 'Europe/Uzhgorod',
                'Europe/Vaduz' => 'Europe/Vaduz',
                'Europe/Vienna' => 'Europe/Vienna',
                'Europe/Vilnius' => 'Europe/Vilnius',
                'Europe/Warsaw' => 'Europe/Warsaw',
                'Europe/Zaporozhye' => 'Europe/Zaporozhye',
                'Europe/Zurich' => 'Europe/Zurich',
                'Indian/Antananarivo' => 'Indian/Antananarivo',
                'Indian/Comoro' => 'Indian/Comoro',
                'Indian/Chagos' => 'Indian/Chagos',
                'Indian/Christmas' => 'Indian/Christmas',
                'Indian/Kerguelen' => 'Indian/Kerguelen',
                'Indian/Mahe' => 'Indian/Mahe',
                'Indian/Maldives' => 'Indian/Maldives',
                'Indian/Mauritius' => 'Indian/Mauritius',
                'Indian/Mayotte' => 'Indian/Mayotte',
                'Indian/Reunion' => 'Indian/Reunion',
                'Pacific/Apia' => 'Pacific/Apia',
                'Pacific/Auckland' => 'Pacific/Auckland',
                'Pacific/Easter' => 'Pacific/Easter',
                'Pacific/Efate' => 'Pacific/Efate',
                'Pacific/Enderbury' => 'Pacific/Enderbury',
                'Pacific/Fakaofo' => 'Pacific/Fakaofo',
                'Pacific/Fiji' => 'Pacific/Fiji',
                'Pacific/Funafuti' => 'Pacific/Funafuti',
                'Pacific/Galapagos' => 'Pacific/Galapagos',
                'Pacific/Gambier' => 'Pacific/Gambier',
                'Pacific/Guadalcanal' => 'Pacific/Guadalcanal',
                'Pacific/Guam' => 'Pacific/Guam',
                'Pacific/Honolulu' => 'Pacific/Honolulu',
                'Pacific/Chatham' => 'Pacific/Chatham',
                'Pacific/Kiritimati' => 'Pacific/Kiritimati',
                'Pacific/Kosrae' => 'Pacific/Kosrae',
                'Pacific/Kwajalein' => 'Pacific/Kwajalein',
                'Pacific/Majuro' => 'Pacific/Majuro',
                'Pacific/Marquesas' => 'Pacific/Marquesas',
                'Pacific/Midway' => 'Pacific/Midway',
                'Pacific/Nauru' => 'Pacific/Nauru',
                'Pacific/Niue' => 'Pacific/Niue',
                'Pacific/Norfolk' => 'Pacific/Norfolk',
                'Pacific/Noumea' => 'Pacific/Noumea',
                'Pacific/Pago_Pago' => 'Pacific/Pago_Pago',
                'Pacific/Palau' => 'Pacific/Palau',
                'Pacific/Pitcairn' => 'Pacific/Pitcairn',
                'Pacific/Ponape' => 'Pacific/Ponape',
                'Pacific/Port_Moresby' => 'Pacific/Port_Moresby',
                'Pacific/Rarotonga' => 'Pacific/Rarotonga',
                'Pacific/Saipan' => 'Pacific/Saipan',
                'Pacific/Tahiti' => 'Pacific/Tahiti',
                'Pacific/Tarawa' => 'Pacific/Tarawa',
                'Pacific/Tongatapu' => 'Pacific/Tongatapu',
                'Pacific/Truk' => 'Pacific/Truk',
                'Pacific/Wake' => 'Pacific/Wake',
                'Pacific/Wallis' => 'Pacific/Wallis',
                'Pacific/Yap' => 'Pacific/Yap',
                '99' => 'Server timezone (Europe/Pais)',
            );

        // Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-create.json");
        $this->_app->jsValidator->setSchema($schema);

        if (isset($get['render']))
            $render = $get['render'];
        else
            $render = "modal";

        // Set default values lay tu xu ly cua moodle
        $data['id'] = "-1";
        // Set default cach tao new user
        $data['auth'] = "manual";
        // Set default thanh vien da duoc xac nhan (confirm)
        $data['confirmed'] = "1";
        // Set default deteted
        $data['deleted'] = "0";
        // Set default timezone
        $data['timezone'] = "99";
//        $a = array('đây', 'rồi', 'chứ', 'đâu', 'these', 'tags');

        $mdluser = new MdlUser($data);
        $this->_app->render('users/mdluser_form.twig', [
            "box_title" => "Create New User",
            "mdluser" => $mdluser,
            "auths" => $auths,
            "mail_dis" => $mail_display,
            "countries" => $countries,
//            "a" => $a,
            "timezone" => $timezone,
            "form_action" => $this->_app->site->uri['public'] . "/mdlusers",
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

    // Hàm này xử lý việc submit form create. lấy dl từ form lưu vào moodledb
    public function submitCreateUsermood(){
        //kiểm tra ảnh
//        if($_FILES["file"]["error"]>0)
//        {
//            echo "<p align='center'> Kiểm tra lại hình ảnh </p><br />";
//            exit;
//        }
//        else
//        {
//            move_uploaded_file($_FILES["file"]["tmp_name"],"../csdl/anh/" . $_FILES["file"]["name"]);
//            $luutru = "luu tru tai: " . "../csdl/anh/" . $_FILES["file"]["name"];
//            $hinh_anh = $_FILES["file"]["name"];
//        }
//        $hinh_anh = $_FILES["file"];
//        $_FILES["file"]["tmp_name"];
//        var_dump($this->_app->request->post());die;
        $post = $this->_app->request->post();

        // Load the request schema
        $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-create.json");
        // Get the alert message stream
        $ms = $this->_app->alerts;
        // Set up Fortress to process the request
        $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

        // Sanitize data
        $rf->sanitize();
        // Validate, and halt on validation errors.
        $error = !$rf->validate(true);
        // Get the filtered data
        $user = $rf->data();
        // Remove csrf_token from object data
        $rf->removeFields(['csrf_token']);
        //lấy các dữ liệu còn lại(những dl không cần validate) từ biến post
        foreach ($post as $key => $value) {
            if ($key != 'username') {
                if ($key != 'firstname') {
                    if ($key != 'surname') {
                        if ($key != 'email') {
                            $user[$key] = $value;
                        }
                    }
                }
            }
        }
        // Perform desired data transformations on required fields.
        // chuyển đổi dữ liệu mong muốn vào các trường khác trong db (không lấy dl từ forrm)
        $user['confirmed'] = 1;
        $user['deleted'] = 0;
        $user['descriptionformat'] = 1;
        $user['timecreated'] = time();
        $user['timemodified'] = $user['timecreated'];
        $user['mnethostid'] = 1;
        $user['picture'] = 0;
        //hash password theo moodle
        $fasthash = false;
        $options = ($fasthash) ? array('cost' => 4) : array();
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT, $options);

        $user['username'] = trim($user['username']);
        $user['firstname'] = trim($user['firstname']);
        $user['lastname'] = trim($user['lastname']);

        // Check if user_name or email already exists
        if (MdlUser::where('username', $user['username'])->first()) {
            $ms->addMessageTranslated("danger", "ACCOUNT_USERNAME_IN_USE", $user);
            $error = true;
        }
        if (MdlUser::where('email', $user['email'])->first()) {
            $ms->addMessageTranslated("danger", "ACCOUNT_EMAIL_IN_USE", $user);
            $error = true;
        }
        // Halt on any validation errors
        if ($error) {
            $this->_app->halt(400);
        }

        // Create the mdlUser
        $mdluser = new MdlUser($user);
        // Store new user to database
        $mdluser->store();
        // Success message
        $ms->addMessageTranslated("success", 'MDLUSER_CREATE_SUCCESS',["name" => $user['username']]);
        //lấy dữ liệu user_id vừa mới thêm vào bảng user để đưa vào instanceid của bảng mdl_context
        $userId = $mdluser->id;
        $context = array();
        $context['contextlevel'] = 30;
        $context['instanceid'] = $userId;
        $context['depth'] = 2;

        // Create the mdlContext
        $mdlcontext = new MdlContext($context);
        //Store new context to database
        $mdlcontext->store();
        //lấy context_id vừa thêm để đưa vào trường path của bảng context
        $contextId = $mdlcontext->id;
        $context['path'] = '/1/' . $contextId;
        //update path cho bảng context
        MdlContext::where('id','=',$contextId)->update(['path' => $context['path']]);

        //Update preferences: mdl_user_preferences: auth_forcepasswordchange
        $pref = array();
        $pref['userid'] = $userId;
        $pref['name']   = 'auth_forcepasswordchange';
        $pref['value']  = $user['preference_auth_forcepasswordchange'];
        $mdlpreference = new MdlUserPreferences($pref);
        $mdlpreference->store();
        //Create mdlcacheflags: auth_forcepasswordchange
        $cacheflag = array();
        $cacheflag['flagtype'] = 'userpreferenceschanged';
        $cacheflag['name'] = $userId;
        $cacheflag['timemodified'] = time();
        $cacheflag['value'] = 1;
        $cacheflag['expiry'] = $cacheflag['timemodified'] + 24*60*60; //24*60*60 chính là sessiontimeout
        $mdlcacheflag = new MdlCacheFlags($cacheflag);
        $mdlcacheflag->store();

        //Update tags
        $usertag = $user['taggles'];
        if (!empty($usertag)) {
            // 1. update bảng mdl_tag
            $tag = array();
            $tag['userid'] = 2;
            $tag['tagtype'] = 'default';
            $tag['description'] = NULL;
            $tag['descriptionformat'] = 0;
            $tag['flag'] = 0;
            $tag['timemodified'] = time();
            // 2. update bảng mdl_tag_instance
            $tag_instance = array();
            $tag_instance['component'] = 'core';
            $tag_instance['itemtype'] = 'user';
            $tag_instance['itemid'] = $userId;
            $tag_instance['contextid'] = $contextId;
            $tag_instance['tiuserid'] = 0;

            foreach ($usertag as $key => $value) {
                // kiểm tra xem đã tồn tại tag_name này chưa. trành việc lưu cùng sở thích nhiều lần

                $tagcurren = MdlTag::where('name',$value)->first();
                // Nếu đã tồn tại tag_name = value thì lấy tag_id và chỉ thêm bản ghi trong tag_instance
                if($tagcurren) {
                    $tag_instance['tagid'] = $tagcurren->id;
                    $tag_instance['ordering'] = $key;
                    $tag_instance['timecreated'] = time();
                    $tag_instance['timemodified'] = $tag_instance['timecreated'];
                    $mdltag_instance = new MdlTagInstance($tag_instance);
                    $mdltag_instance->store();
                }
                // Nếu chưa tồn tại tại tag_name = value thì thêm bản ghi mới trong mdl_tag và trong mdl_tag_instance
                else{
                    $tag['name'] = $value;
                    $tag['rawname'] = $value;
                    $mdltag = new MdlTag($tag);
                    $mdltag->store();
                    // với mỗi bản ghi trong tag vừa thêm ta cũng thêm một bản ghi mới trong tag_instance và với tag_id vừa thêm vào
                    $tag_instance['tagid'] = $mdltag->id;;
                    $tag_instance['ordering'] = $key;
                    $tag_instance['timecreated'] = time();
                    $tag_instance['timemodified'] = $tag_instance['timecreated'];
                    $mdltag_instance = new MdlTagInstance($tag_instance);
                    $mdltag_instance->store();
                }
            }
        }

        //Update mail bounces. mdl_user_preferences: email_bounce_count, email_send_count
        //set_bounce_count($usernew, true);
        $pref['userid'] = $userId;
        $pref['name']   = 'email_bounce_count';
        $pref['value']  = 1;
        $mdlpref_bounce = new MdlUserPreferences($pref);
        $mdlpref_bounce->store();
        //set_send_count($usernew, true);
        $pref['userid'] = $userId;
        $pref['name']   = 'email_send_count';
        $pref['value']  = 1;
        $mdlpref_send = new MdlUserPreferences($pref);
        $mdlpref_send->store();
    }

    // Hàm này lấy dữ liệu người dùng từ moodledb và đổ ra form edit mdluser
    public function formMdluserEdit($user_id){
//        // Get the mdluser to edit
        $mdluser = MdlUser::find($user_id)->toArray();

//        //Get auths
        $auths = array(
            'cas' => 'CAS server (SSO)',
            'db' => 'External database',
            'email' => 'Email-based self-registration',
            'fc' => 'FirstClass server',
            'imap' => 'IMAP server',
            'ldap' => 'LDAP server',
            'manual' => 'Manual accounts',
            'mnet' => 'MNet authentication',
            'nntp' => 'NNTP server',
            'nologin' => 'No login',
            'none' => 'No authentication',
            'pam' => 'PAM (Pluggable Authentication Modules)',
            'pop3' => 'POP3 server',
            'radius' => 'RADIUS server',
            'shibboleth' => 'Shibboleth',
            'webservice' => 'Web services authentication',
        );
        $mail_display = array(
            '0' => 'Hide my email address from everyone',
            '1' => 'Allow everyone to see my email address',
            '2' => 'Allow only other course members to see my email address',
        );
        $countries = array(
            'AD' => 'Andorra',
            'AE' => 'United Arab Emirates',
            'AF' => 'Afghanistan',
            'AG' => 'Antigua And Barbuda',
            'AI' => 'Anguilla',
            'AL' => 'Albania',
            'AM' => 'Armenia',
            'AO' => 'Angola',
            'AQ' => 'Antarctica',
            'AR' => 'Argentina',
            'AS' => 'American Samoa',
            'AT' => 'Austria',
            'AU' => 'Australia',
            'AW' => 'Aruba',
            'AX' => 'Åland Islands',
            'AZ' => 'Azerbaijan',
            'BA' => 'Bosnia And Herzegovina',
            'BB' => 'Barbados',
            'BD' => 'Bangladesh',
            'BE' => 'Belgium',
            'BF' => 'Burkina Faso',
            'BG' => 'Bulgaria',
            'BH' => 'Bahrain',
            'BI' => 'Burundi',
            'BJ' => 'Benin',
            'BL' => 'Saint Barthélemy',
            'BM' => 'Bermuda',
            'BN' => 'Brunei Darussalam',
            'BO' => 'Bolivia, Plurinational State Of',
            'BQ' => 'Bonaire, Sint Eustatius And Saba',
            'BR' => 'Brazil',
            'BS' => 'Bahamas',
            'BT' => 'Bhutan',
            'BV' => 'Bouvet Island',
            'BW' => 'Botswana',
            'BY' => 'Belarus',
            'BZ' => 'Belize',
            'CA' => 'Canada',
            'CC' => 'Cocos (Keeling) Islands',
            'CD' => 'Congo, The Democratic Republic Of The',
            'CF' => 'Central African Republic',
            'CG' => 'Congo',
            'CH' => 'Switzerland',
            'CI' => 'Côte D\'Ivoire',
            'CK' => 'Cook Islands',
            'CL' => 'Chile',
            'CM' => 'Cameroon',
            'CN' => 'China',
            'CO' => 'Colombia',
            'CR' => 'Costa Rica',
            'CU' => 'Cuba',
            'CV' => 'Cabo Verde',
            'CW' => 'Curaçao',
            'CX' => 'Christmas Island',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DE' => 'Germany',
            'DJ' => 'Djibouti',
            'DK' => 'Denmark',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'DZ' => 'Algeria',
            'EC' => 'Ecuador',
            'EE' => 'Estonia',
            'EG' => 'Egypt',
            'EH' => 'Western Sahara',
            'ER' => 'Eritrea',
            'ES' => 'Spain',
            'ET' => 'Ethiopia',
            'FI' => 'Finland',
            'FJ' => 'Fiji',
            'FK' => 'Falkland Islands (Malvinas)',
            'FM' => 'Micronesia, Federated States Of',
            'FO' => 'Faroe Islands',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GB' => 'United Kingdom',
            'GD' => 'Grenada',
            'GE' => 'Georgia',
            'GF' => 'French Guiana',
            'GG' => 'Guernsey',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GL' => 'Greenland',
            'GM' => 'Gambia',
            'GN' => 'Guinea',
            'GP' => 'Guadeloupe',
            'GQ' => 'Equatorial Guinea',
            'GR' => 'Greece',
            'GS' => 'South Georgia And The South Sandwich Islands',
            'GT' => 'Guatemala',
            'GU' => 'Guam',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HK' => 'Hong Kong',
            'HM' => 'Heard Island And Mcdonald Islands',
            'HN' => 'Honduras',
            'HR' => 'Croatia',
            'HT' => 'Haiti',
            'HU' => 'Hungary',
            'ID' => 'Indonesia',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IM' => 'Isle Of Man',
            'IN' => 'India',
            'IO' => 'British Indian Ocean Territory',
            'IQ' => 'Iraq',
            'IR' => 'Iran, Islamic Republic Of',
            'IS' => 'Iceland',
            'IT' => 'Italy',
            'JE' => 'Jersey',
            'JM' => 'Jamaica',
            'JO' => 'Jordan',
            'JP' => 'Japan',
            'KE' => 'Kenya',
            'KG' => 'Kyrgyzstan',
            'KH' => 'Cambodia',
            'KI' => 'Kiribati',
            'KM' => 'Comoros',
            'KN' => 'Saint Kitts And Nevis',
            'KP' => 'Korea, Democratic People\'s Republic Of',
            'KR' => 'Korea, Republic Of',
            'KW' => 'Kuwait',
            'KY' => 'Cayman Islands',
            'KZ' => 'Kazakhstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LB' => 'Lebanon',
            'LC' => 'Saint Lucia',
            'LI' => 'Liechtenstein',
            'LK' => 'Sri Lanka',
            'LR' => 'Liberia',
            'LS' => 'Lesotho',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'LV' => 'Latvia',
            'LY' => 'Libya',
            'MA' => 'Morocco',
            'MC' => 'Monaco',
            'MD' => 'Moldova, Republic Of',
            'ME' => 'Montenegro',
            'MF' => 'Saint Martin (French Part)',
            'MG' => 'Madagascar',
            'MH' => 'Marshall Islands',
            'MK' => 'Macedonia, The Former Yugoslav Republic Of',
            'ML' => 'Mali',
            'MM' => 'Myanmar',
            'MN' => 'Mongolia',
            'MO' => 'Macao',
            'MP' => 'Northern Mariana Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MS' => 'Montserrat',
            'MT' => 'Malta',
            'MU' => 'Mauritius',
            'MV' => 'Maldives',
            'MW' => 'Malawi',
            'MX' => 'Mexico',
            'MY' => 'Malaysia',
            'MZ' => 'Mozambique',
            'NA' => 'Namibia',
            'NC' => 'New Caledonia',
            'NE' => 'Niger',
            'NF' => 'Norfolk Island',
            'NG' => 'Nigeria',
            'NI' => 'Nicaragua',
            'NL' => 'Netherlands',
            'NO' => 'Norway',
            'NP' => 'Nepal',
            'NR' => 'Nauru',
            'NU' => 'Niue',
            'NZ' => 'New Zealand',
            'OM' => 'Oman',
            'PA' => 'Panama',
            'PE' => 'Peru',
            'PF' => 'French Polynesia',
            'PG' => 'Papua New Guinea',
            'PH' => 'Philippines',
            'PK' => 'Pakistan',
            'PL' => 'Poland',
            'PM' => 'Saint Pierre And Miquelon',
            'PN' => 'Pitcairn',
            'PR' => 'Puerto Rico',
            'PS' => 'Palestine, State Of',
            'PT' => 'Portugal',
            'PW' => 'Palau',
            'PY' => 'Paraguay',
            'QA' => 'Qatar',
            'RE' => 'Réunion',
            'RO' => 'Romania',
            'RS' => 'Serbia',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'SA' => 'Saudi Arabia',
            'SB' => 'Solomon Islands',
            'SC' => 'Seychelles',
            'SD' => 'Sudan',
            'SE' => 'Sweden',
            'SG' => 'Singapore',
            'SH' => 'Saint Helena, Ascension And Tristan Da Cunha',
            'SI' => 'Slovenia',
            'SJ' => 'Svalbard And Jan Mayen',
            'SK' => 'Slovakia',
            'SL' => 'Sierra Leone',
            'SM' => 'San Marino',
            'SN' => 'Senegal',
            'SO' => 'Somalia',
            'SR' => 'Suriname',
            'SS' => 'South Sudan',
            'ST' => 'Sao Tome And Principe',
            'SV' => 'El Salvador',
            'SX' => 'Sint Maarten (Dutch Part)',
            'SY' => 'Syrian Arab Republic',
            'SZ' => 'Swaziland',
            'TC' => 'Turks And Caicos Islands',
            'TD' => 'Chad',
            'TF' => 'French Southern Territories',
            'TG' => 'Togo',
            'TH' => 'Thailand',
            'TJ' => 'Tajikistan',
            'TK' => 'Tokelau',
            'TL' => 'Timor-Leste',
            'TM' => 'Turkmenistan',
            'TN' => 'Tunisia',
            'TO' => 'Tonga',
            'TR' => 'Turkey',
            'TT' => 'Trinidad And Tobago',
            'TV' => 'Tuvalu',
            'TW' => 'Taiwan',
            'TZ' => 'Tanzania, United Republic Of',
            'UA' => 'Ukraine',
            'UG' => 'Uganda',
            'UM' => 'United States Minor Outlying Islands',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VA' => 'Holy See (Vatican City State)',
            'VC' => 'Saint Vincent And The Grenadines',
            'VE' => 'Venezuela, Bolivarian Republic Of',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'VN' => 'Viet Nam',
            'VU' => 'Vanuatu',
            'WF' => 'Wallis And Futuna',
            'WS' => 'Samoa',
            'YE' => 'Yemen',
            'YT' => 'Mayotte',
            'ZA' => 'South Africa',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
        $timezone = array(
            'Africa/Abidjan' => 'Africa/Abidjan',
            'Africa/Accra' => 'Africa/Accra',
            'Africa/Addis_Ababa' => 'Africa/Addis_Ababa',
            'Africa/Algiers' => 'Africa/Algiers',
            'Africa/Asmera' => 'Africa/Asmera',
            'Africa/Bamako' => 'Africa/Bamako',
            'Africa/Bangui' => 'Africa/Bangui',
            'Africa/Banjul' => 'Africa/Banjul',
            'Africa/Bissau' => 'Africa/Bissau',
            'Africa/Blantyre' => 'Africa/Blantyre',
            'Africa/Brazzaville' => 'Africa/Brazzaville',
            'Africa/Bujumbura' => 'Africa/Bujumbura',
            'Africa/Cairo' => 'Africa/Cairo',
            'Africa/Casablanca' => 'Africa/Casablanca',
            'Africa/Ceuta' => 'Africa/Ceuta',
            'Africa/Conakry' => 'Africa/Conakry',
            'Africa/Dakar' => 'Africa/Dakar',
            'Africa/Dar_es_Salaam' => 'Africa/Dar_es_Salaam',
            'Africa/Djibouti' => 'Africa/Djibouti',
            'Africa/Douala' => 'Africa/Douala',
            'Africa/El_Aaiun' => 'Africa/El_Aaiun',
            'Africa/Freetown' => 'Africa/Freetown',
            'Africa/Gaborone' => 'Africa/Gaborone',
            'Africa/Harare' => 'Africa/Harare',
            'Africa/Johannesburg' => 'Africa/Johannesburg',
            'Africa/Kampala' => 'Africa/Kampala',
            'Africa/Khartoum' => 'Africa/Khartoum',
            'Africa/Kigali' => 'Africa/Kigali',
            'Africa/Kinshasa' => 'Africa/Kinshasa',
            'Africa/Lagos' => 'Africa/Lagos',
            'Africa/Libreville' => 'Africa/Libreville',
            'Africa/Lome' => 'Africa/Lome',
            'Africa/Luanda' => 'Africa/Luanda',
            'Africa/Lubumbashi' => 'Africa/Lubumbashi',
            'Africa/Lusaka' => 'Africa/Lusaka',
            'Africa/Malabo' => 'Africa/Malabo',
            'Africa/Maputo' => 'Africa/Maputo',
            'Africa/Maseru' => 'Africa/Maseru',
            'Africa/Mbabane' => 'Africa/Mbabane',
            'Africa/Mogadishu' => 'Africa/Mogadishu',
            'Africa/Monrovia' => 'Africa/Monrovia',
            'Africa/Nairobi' => 'Africa/Nairobi',
            'Africa/Ndjamena' => 'Africa/Ndjamena',
            'Africa/Niamey' => 'Africa/Niamey',
            'Africa/Nouakchott' => 'Africa/Nouakchott',
            'Africa/Ouagadougou' => 'Africa/Ouagadougou',
            'Africa/Porto-Novo' => 'Africa/Porto-Novo',
            'Africa/Sao_Tome' => 'Africa/Sao_Tome',
            'Africa/Timbuktu' => 'Africa/Timbuktu',
            'Africa/Tripoli' => 'Africa/Tripoli',
            'Africa/Tunis' => 'Africa/Tunis',
            'Africa/Windhoek' => 'Africa/Windhoek',
            'America/Adak' => 'America/Adak',
            'America/Anguilla' => 'America/Anguilla',
            'America/Anchorage' => 'America/Anchorage',
            'America/Antigua' => 'America/Antigua',
            'America/Araguaina' => 'America/Araguaina',
            'America/Argentina/Buenos_Aires' => 'America/Argentina/Buenos_Aires',
            'America/Argentina/Catamarca' => 'America/Argentina/Catamarca',
            'America/Argentina/ComodRivadavia' => 'America/Argentina/ComodRivadavia',
            'America/Argentina/Cordoba' => 'America/Argentina/Cordoba',
            'America/Argentina/Jujuy' => 'America/Argentina/Jujuy',
            'America/Argentina/La_Rioja' => 'America/Argentina/La_Rioja',
            'America/Argentina/Mendoza' => 'America/Argentina/Mendoza',
            'America/Argentina/Rio_Gallegos' => 'America/Argentina/Rio_Gallegos',
            'America/Argentina/San_Juan' => 'America/Argentina/San_Juan',
            'America/Argentina/Tucuman' => 'America/Argentina/Tucuman',
            'America/Argentina/Ushuaia' => 'America/Argentina/Ushuaia',
            'America/Aruba' => 'America/Aruba',
            'America/Asuncion' => 'America/Asuncion',
            'America/Bahia' => 'America/Bahia',
            'America/Barbados' => 'America/Barbados',
            'America/Belem' => 'America/Belem',
            'America/Belize' => 'America/Belize',
            'America/Boa_Vista' => 'America/Boa_Vista',
            'America/Bogota' => 'America/Bogota',
            'America/Boise' => 'America/Boise',
            'America/Cambridge_Bay' => 'America/Cambridge_Bay',
            'America/Campo_Grande' => 'America/Campo_Grande',
            'America/Cancun' => 'America/Cancun',
            'America/Caracas' => 'America/Caracas',
            'America/Cayenne' => 'America/Cayenne',
            'America/Cayman' => 'America/Cayman',
            'America/Costa_Rica' => 'America/Costa_Rica',
            'America/Cuiaba' => 'America/Cuiaba',
            'America/Curacao' => 'America/Curacao',
            'America/Danmarkshavn' => 'America/Danmarkshavn',
            'America/Dawson' => 'America/Dawson',
            'America/Dawson_Creek' => 'America/Dawson_Creek',
            'America/Denver' => 'America/Denver',
            'America/Detroit' => 'America/Detroit',
            'America/Dominica' => 'America/Dominica',
            'America/Edmonton' => 'America/Edmonton',
            'America/Eirunepe' => 'America/Eirunepe',
            'America/El_Salvador' => 'America/El_Salvador',
            'America/Fortaleza' => 'America/Fortaleza',
            'America/Glace_Bay' => 'America/Glace_Bay',
            'America/Godthab' => 'America/Godthab',
            'America/Goose_Bay' => 'America/Goose_Bay',
            'America/Grand_Turk' => 'America/Grand_Turk',
            'America/Grenada' => 'America/Grenada',
            'America/Guadeloupe' => 'America/Guadeloupe',
            'America/Guatemala' => 'America/Guatemala',
            'America/Guayaquil' => 'America/Guayaquil',
            'America/Guyana' => 'America/Guyana',
            'America/Halifax' => 'America/Halifax',
            'America/Havana' => 'America/Havana',
            'America/Hermosillo' => 'America/Hermosillo',
            'America/Chicago' => 'America/Chicago',
            'America/Chihuahua' => 'America/Chihuahua',
            'America/Indiana/Knox' => 'America/Indiana/Knox',
            'America/Indiana/Marengo' => 'America/Indiana/Marengo',
            'America/Indianapolis' => 'America/Indianapolis',
            'America/Indiana/Vevay' => 'America/Indiana/Vevay',
            'America/Inuvik' => 'America/Inuvik',
            'America/Iqaluit' => 'America/Iqaluit',
            'America/Jamaica' => 'America/Jamaica',
            'America/Juneau' => 'America/Juneau',
            'America/Kentucky/Monticello' => 'America/Kentucky/Monticello',
            'America/La_Paz' => 'America/La_Paz',
            'America/Lima' => 'America/Lima',
            'America/Los_Angeles' => 'America/Los_Angeles',
            'America/Louisville' => 'America/Louisville',
            'America/Maceio' => 'America/Maceio',
            'America/Managua' => 'America/Managua',
            'America/Manaus' => 'America/Manaus',
            'America/Martinique' => 'America/Martinique',
            'America/Mazatlan' => 'America/Mazatlan',
            'America/Menominee' => 'America/Menominee',
            'America/Merida' => 'America/Merida',
            'America/Mexico_City' => 'America/Mexico_City',
            'America/Miquelon' => 'America/Miquelon',
            'America/Monterrey' => 'America/Monterrey',
            'America/Montevideo' => 'America/Montevideo',
            'America/Montreal' => 'America/Montreal',
            'America/Montserrat' => 'America/Montserrat',
            'America/Nassau' => 'America/Nassau',
            'America/New_York' => 'America/New_York',
            'America/Nipigon' => 'America/Nipigon',
            'America/Nome' => 'America/Nome',
            'America/Noronha' => 'America/Noronha',
            'America/North_Dakota/Center' => 'America/North_Dakota/Center',
            'America/Panama' => 'America/Panama',
            'America/Pangnirtung' => 'America/Pangnirtung',
            'America/Paramaribo' => 'America/Paramaribo',
            'America/Phoenix' => 'America/Phoenix',
            'America/Port-au-Prince' => 'America/Port-au-Prince',
            'America/Port_of_Spain' => 'America/Port_of_Spain',
            'America/Porto_Velho' => 'America/Porto_Velho',
            'America/Puerto_Rico' => 'America/Puerto_Rico',
            'America/Rainy_River' => 'America/Rainy_River',
            'America/Rankin_Inlet' => 'America/Rankin_Inlet',
            'America/Recife' => 'America/Recife',
            'America/Regina' => 'America/Regina',
            'America/Rio_Branco' => 'America/Rio_Branco',
            'America/Santiago' => 'America/Santiago',
            'America/Santo_Domingo' => 'America/Santo_Domingo',
            'America/Sao_Paulo' => 'America/Sao_Paulo',
            'America/Scoresbysund' => 'America/Scoresbysund',
            'America/St_Johns' => 'America/St_Johns',
            'America/St_Kitts' => 'America/St_Kitts',
            'America/St_Lucia' => 'America/St_Lucia',
            'America/St_Thomas' => 'America/St_Thomas',
            'America/St_Vincent' => 'America/St_Vincent',
            'America/Swift_Current' => 'America/Swift_Current',
            'America/Tegucigalpa' => 'America/Tegucigalpa',
            'America/Thule' => 'America/Thule',
            'America/Thunder_Bay' => 'America/Thunder_Bay',
            'America/Tijuana' => 'America/Tijuana',
            'America/Toronto' => 'America/Toronto',
            'America/Tortola' => 'America/Tortola',
            'America/Vancouver' => 'America/Vancouver',
            'America/Whitehorse' => 'America/Whitehorse',
            'America/Winnipeg' => 'America/Winnipeg',
            'America/Yakutat' => 'America/Yakutat',
            'America/Yellowknife' => 'America/Yellowknife',
            'Antarctica/Casey' => 'Antarctica/Casey',
            'Antarctica/Davis' => 'Antarctica/Davis',
            'Antarctica/DumontDUrville' => 'Antarctica/DumontDUrville',
            'Antarctica/Mawson' => 'Antarctica/Mawson',
            'Antarctica/McMurdo' => 'Antarctica/McMurdo',
            'Antarctica/Palmer' => 'Antarctica/Palmer',
            'Antarctica/Rothera' => 'Antarctica/Rothera',
            'Antarctica/Syowa' => 'Antarctica/Syowa',
            'Antarctica/Vostok' => 'Antarctica/Vostok',
            'Asia/Aden' => 'Asia/Aden',
            'Asia/Almaty' => 'Asia/Almaty',
            'Asia/Amman' => 'Asia/Amman',
            'Asia/Anadyr' => 'Asia/Anadyr',
            'Asia/Aqtau' => 'Asia/Aqtau',
            'Asia/Aqtobe' => 'Asia/Aqtobe',
            'Asia/Ashgabat' => 'Asia/Ashgabat',
            'Asia/Baghdad' => 'Asia/Baghdad',
            'Asia/Bahrain' => 'Asia/Bahrain',
            'Asia/Baku' => 'Asia/Baku',
            'Asia/Bangkok' => 'Asia/Bangkok',
            'Asia/Beirut' => 'Asia/Beirut',
            'Asia/Bishkek' => 'Asia/Bishkek',
            'Asia/Brunei' => 'Asia/Brunei',
            'Asia/Calcutta' => 'Asia/Calcutta',
            'Asia/Colombo' => 'Asia/Colombo',
            'Asia/Damascus' => 'Asia/Damascus',
            'Asia/Dhaka' => 'Asia/Dhaka',
            'Asia/Dili' => 'Asia/Dili',
            'Asia/Dubai' => 'Asia/Dubai',
            'Asia/Dushanbe' => 'Asia/Dushanbe',
            'Asia/Gaza' => 'Asia/Gaza',
            'Asia/Harbin' => 'Asia/Harbin',
            'Asia/Hong_Kong' => 'Asia/Hong_Kong',
            'Asia/Hovd' => 'Asia/Hovd',
            'Asia/Choibalsan' => 'Asia/Choibalsan',
            'Asia/Chongqing' => 'Asia/Chongqing',
            'Asia/Irkutsk' => 'Asia/Irkutsk',
            'Asia/Jayapura' => 'Asia/Jayapura',
            'Asia/Jerusalem' => 'Asia/Jerusalem',
            'Asia/Kabul' => 'Asia/Kabul',
            'Asia/Kamchatka' => 'Asia/Kamchatka',
            'Asia/Karachi' => 'Asia/Karachi',
            'Asia/Kashgar' => 'Asia/Kashgar',
            'Asia/Katmandu' => 'Asia/Katmandu',
            'Asia/Krasnoyarsk' => 'Asia/Krasnoyarsk',
            'Asia/Kuala_Lumpur' => 'Asia/Kuala_Lumpur',
            'Asia/Kuching' => 'Asia/Kuching',
            'Asia/Kuwait' => 'Asia/Kuwait',
            'Asia/Macau' => 'Asia/Macau',
            'Asia/Magadan' => 'Asia/Magadan',
            'Asia/Makassar' => 'Asia/Makassar',
            'Asia/Manila' => 'Asia/Manila',
            'Asia/Muscat' => 'Asia/Muscat',
            'Asia/Nicosia' => 'Asia/Nicosia',
            'Asia/Novosibirsk' => 'Asia/Novosibirsk',
            'Asia/Omsk' => 'Asia/Omsk',
            'Asia/Oral' => 'Asia/Oral',
            'Asia/Phnom_Penh' => 'Asia/Phnom_Penh',
            'Asia/Pontianak' => 'Asia/Pontianak',
            'Asia/Pyongyang' => 'Asia/Pyongyang',
            'Asia/Qatar' => 'Asia/Qatar',
            'Asia/Qyzylorda' => 'Asia/Qyzylorda',
            'Asia/Rangoon' => 'Asia/Rangoon',
            'Asia/Riyadh' => 'Asia/Riyadh',
            'Asia/Saigon' => 'Asia/Saigon',
            'Asia/Sakhalin' => 'Asia/Sakhalin',
            'Asia/Samarkand' => 'Asia/Samarkand',
            'Asia/Seoul' => 'Asia/Seoul',
            'Asia/Shanghai' => 'Asia/Shanghai',
            'Asia/Singapore' => 'Asia/Singapore',
            'Asia/Taipei' => 'Asia/Taipei',
            'Asia/Tashkent' => 'Asia/Tashkent',
            'Asia/Tbilisi' => 'Asia/Tbilisi',
            'Asia/Tehran' => 'Asia/Tehran',
            'Asia/Thimphu' => 'Asia/Thimphu',
            'Asia/Tokyo' => 'Asia/Tokyo',
            'Asia/Ulaanbaatar' => 'Asia/Ulaanbaatar',
            'Asia/Urumqi' => 'Asia/Urumqi',
            'Asia/Vientiane' => 'Asia/Vientiane',
            'Asia/Vladivostok' => 'Asia/Vladivostok',
            'Asia/Yakutsk' => 'Asia/Yakutsk',
            'Asia/Yekaterinburg' => 'Asia/Yekaterinburg',
            'Asia/Yerevan' => 'Asia/Yerevan',
            'Atlantic/Azores' => 'Atlantic/Azores',
            'Atlantic/Bermuda' => 'Atlantic/Bermuda',
            'Atlantic/Canary' => 'Atlantic/Canary',
            'Atlantic/Cape_Verde' => 'Atlantic/Cape_Verde',
            'Atlantic/Faeroe' => 'Atlantic/Faeroe',
            'Atlantic/Madeira' => 'Atlantic/Madeira',
            'Atlantic/Reykjavik' => 'Atlantic/Reykjavik',
            'Atlantic/South_Georgia' => 'Atlantic/South_Georgia',
            'Atlantic/Stanley' => 'Atlantic/Stanley',
            'Atlantic/St_Helena' => 'Atlantic/St_Helena',
            'Australia/Adelaide' => 'Australia/Adelaide',
            'Australia/Brisbane' => 'Australia/Brisbane',
            'Australia/Broken_Hill' => 'Australia/Broken_Hill',
            'Australia/Darwin' => 'Australia/Darwin',
            'Australia/Hobart' => 'Australia/Hobart',
            'Australia/Lindeman' => 'Australia/Lindeman',
            'Australia/Lord_Howe' => 'Australia/Lord_Howe',
            'Australia/Melbourne' => 'Australia/Melbourne',
            'Australia/Perth' => 'Australia/Perth',
            'Australia/Sydney' => 'Australia/Sydney',
            'Europe/Amsterdam' => 'Europe/Amsterdam',
            'Europe/Andorra' => 'Europe/Andorra',
            'Europe/Athens' => 'Europe/Athens',
            'Europe/Belfast' => 'Europe/Belfast',
            'Europe/Belgrade' => 'Europe/Belgrade',
            'Europe/Berlin' => 'Europe/Berlin',
            'Europe/Brussels' => 'Europe/Brussels',
            'Europe/Budapest' => 'Europe/Budapest',
            'Europe/Bucharest' => 'Europe/Bucharest',
            'Europe/Copenhagen' => 'Europe/Copenhagen',
            'Europe/Dublin' => 'Europe/Dublin',
            'Europe/Gibraltar' => 'Europe/Gibraltar',
            'Europe/Helsinki' => 'Europe/Helsinki',
            'Europe/Chisinau' => 'Europe/Chisinau',
            'Europe/Istanbul' => 'Europe/Istanbul',
            'Europe/Kaliningrad' => 'Europe/Kaliningrad',
            'Europe/Kiev' => 'Europe/Kiev',
            'Europe/Lisbon' => 'Europe/Lisbon',
            'Europe/London' => 'Europe/London',
            'Europe/Luxembourg' => 'Europe/Luxembourg',
            'Europe/Madrid' => 'Europe/Madrid',
            'Europe/Malta' => 'Europe/Malta',
            'Europe/Minsk' => 'Europe/Minsk',
            'Europe/Monaco' => 'Europe/Monaco',
            'Europe/Moscow' => 'Europe/Moscow',
            'Europe/Oslo' => 'Europe/Oslo',
            'Europe/Paris' => 'Europe/Paris',
            'Europe/Prague' => 'Europe/Prague',
            'Europe/Riga' => 'Europe/Riga',
            'Europe/Rome' => 'Europe/Rome',
            'Europe/Samara' => 'Europe/Samara',
            'Europe/Simferopol' => 'Europe/Simferopol',
            'Europe/Sofia' => 'Europe/Sofia',
            'Europe/Stockholm' => 'Europe/Stockholm',
            'Europe/Tallinn' => 'Europe/Tallinn',
            'Europe/Tirane' => 'Europe/Tirane',
            'Europe/Uzhgorod' => 'Europe/Uzhgorod',
            'Europe/Vaduz' => 'Europe/Vaduz',
            'Europe/Vienna' => 'Europe/Vienna',
            'Europe/Vilnius' => 'Europe/Vilnius',
            'Europe/Warsaw' => 'Europe/Warsaw',
            'Europe/Zaporozhye' => 'Europe/Zaporozhye',
            'Europe/Zurich' => 'Europe/Zurich',
            'Indian/Antananarivo' => 'Indian/Antananarivo',
            'Indian/Comoro' => 'Indian/Comoro',
            'Indian/Chagos' => 'Indian/Chagos',
            'Indian/Christmas' => 'Indian/Christmas',
            'Indian/Kerguelen' => 'Indian/Kerguelen',
            'Indian/Mahe' => 'Indian/Mahe',
            'Indian/Maldives' => 'Indian/Maldives',
            'Indian/Mauritius' => 'Indian/Mauritius',
            'Indian/Mayotte' => 'Indian/Mayotte',
            'Indian/Reunion' => 'Indian/Reunion',
            'Pacific/Apia' => 'Pacific/Apia',
            'Pacific/Auckland' => 'Pacific/Auckland',
            'Pacific/Easter' => 'Pacific/Easter',
            'Pacific/Efate' => 'Pacific/Efate',
            'Pacific/Enderbury' => 'Pacific/Enderbury',
            'Pacific/Fakaofo' => 'Pacific/Fakaofo',
            'Pacific/Fiji' => 'Pacific/Fiji',
            'Pacific/Funafuti' => 'Pacific/Funafuti',
            'Pacific/Galapagos' => 'Pacific/Galapagos',
            'Pacific/Gambier' => 'Pacific/Gambier',
            'Pacific/Guadalcanal' => 'Pacific/Guadalcanal',
            'Pacific/Guam' => 'Pacific/Guam',
            'Pacific/Honolulu' => 'Pacific/Honolulu',
            'Pacific/Chatham' => 'Pacific/Chatham',
            'Pacific/Kiritimati' => 'Pacific/Kiritimati',
            'Pacific/Kosrae' => 'Pacific/Kosrae',
            'Pacific/Kwajalein' => 'Pacific/Kwajalein',
            'Pacific/Majuro' => 'Pacific/Majuro',
            'Pacific/Marquesas' => 'Pacific/Marquesas',
            'Pacific/Midway' => 'Pacific/Midway',
            'Pacific/Nauru' => 'Pacific/Nauru',
            'Pacific/Niue' => 'Pacific/Niue',
            'Pacific/Norfolk' => 'Pacific/Norfolk',
            'Pacific/Noumea' => 'Pacific/Noumea',
            'Pacific/Pago_Pago' => 'Pacific/Pago_Pago',
            'Pacific/Palau' => 'Pacific/Palau',
            'Pacific/Pitcairn' => 'Pacific/Pitcairn',
            'Pacific/Ponape' => 'Pacific/Ponape',
            'Pacific/Port_Moresby' => 'Pacific/Port_Moresby',
            'Pacific/Rarotonga' => 'Pacific/Rarotonga',
            'Pacific/Saipan' => 'Pacific/Saipan',
            'Pacific/Tahiti' => 'Pacific/Tahiti',
            'Pacific/Tarawa' => 'Pacific/Tarawa',
            'Pacific/Tongatapu' => 'Pacific/Tongatapu',
            'Pacific/Truk' => 'Pacific/Truk',
            'Pacific/Wake' => 'Pacific/Wake',
            'Pacific/Wallis' => 'Pacific/Wallis',
            'Pacific/Yap' => 'Pacific/Yap',
            '99' => 'Server timezone (Europe/Pais)',
        );

        // Lấy dữ liệu của trường preference_auth_forcepasswordchance (0 hoặc 1)
        //Update preferences: mdl_user_preferences: auth_forcepasswordchange
        $pref = MdlUserPreferences::where('name','auth_forcepasswordchange')->where('userid',$user_id)->first();

        //lấy dl của sở thích từ việc kết nối 2 bảng mdl_tag và mdl_tag_instance
        $tags = MdlTag::queryBuilder()
            ->join("mdl_tag_instance", "mdl_tag.id", "=", "mdl_tag_instance.tagid")
            ->where("mdl_tag_instance.itemid",$user_id)
            ->select("name")
            ->get();

//         Load validator rules
        $schema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-update.json");
        $this->_app->jsValidator->setSchema($schema);

        $this->_app->render('users/mdluser_form.twig', [
            "box_title" => "Update user",
            "mdluser" => $mdluser,
            "auths" => $auths,
            "tags" => $tags,
            "mail_dis" => $mail_display,
            "countries" => $countries,
            "timezone" => $timezone,
            "pref" => $pref,
            "form_action" => $this->_app->site->uri['public'] . "/mdlusers/u/$user_id",
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

    // Hàm này xử lý việc submit form edit. lấy dl từ form cập nhật vào moodledb
    public function submitEditUsermood($user_id){
        $post = $this->_app->request->post();

            // Load the request schema
            $requestSchema = new \Fortress\RequestSchema($this->_app->config('schema.path') . "/forms/mdluser-update.json");
            // Get the alert message stream
            $ms = $this->_app->alerts;

            // Get the target user and context_user
            $mdluser = MdlUser::find($user_id);

        //
        if((!isset($post['username'])) || (!isset($post['email']))){
            if(isset($post['suspended'])){
                $mdluser->suspended = $post['suspended'];
                $mdluser->store();
            }
            elseif(isset($post['confirmed'])){
                $mdluser->confirmed = $post['confirmed'];
                $mdluser->store();
            }
            else {
                $this->_app->halt(400);
            }
        }
        else {
            $context = MdlContext::where('instanceid', $user_id)->where('contextlevel', '=', 30)->first();
            // Remove csrf_token
            unset($post['csrf_token']);
            //        // Nếu password không được nhập -> unset password. Tránh việc báo lỗi khi validate dữ liệu pass
            if (($post['password']) == '') {
                unset($post['password']);
            }
            // Set up Fortress to process the request
            $rf = new \Fortress\HTTPRequestFortress($ms, $requestSchema, $post);

            // Check that the username is not in use
            if (isset($post['username']) && $post['username'] != $mdluser->username && MdlUser::where('username', $post['username'])->first()) {
                $ms->addMessageTranslated("danger", 'ACCOUNT_MDLUSERNAME_IN_USE', $post);
                $error = true;
            }
            // Check that the email address is not in use
            if (isset($post['email']) && $post['email'] != $mdluser->email && MdlUser::where('email', $post['email'])->first()) {
                $ms->addMessageTranslated("danger", "ACCOUNT_EMAIL_IN_USE", $post);
                $error = true;
            }

            // Sanitize
            $rf->sanitize();
            $error = !$rf->validate(true);
            // Validate, and halt on validation errors.
            if (!$rf->validate()) {
                $this->_app->halt(400);
            }
            if ($error) {
                $this->_app->halt(400);
            }

            // Get the filtered data
            $data = $rf->data();

            foreach ($post as $key => $value) {
                if ($key != 'username') {
                    if ($key != 'firstname') {
                        if ($key != 'surname') {
                            if ($key != 'email') {
                                $data[$key] = $value;
                            }
                        }
                    }
                }
            }
            $data['timemodified'] = time();
            //hash password theo moodle
            if (isset($data['password'])) {
                $fasthash = false;
                $options = ($fasthash) ? array('cost' => 4) : array();
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT, $options);
            }

            //Update preferences: mdl_user_preferences: auth_forcepasswordchange
            $pref = MdlUserPreferences::where('name', 'auth_forcepasswordchange')->where('userid', $user_id)->first();
            if ($data['preference_auth_forcepasswordchange'] != $pref->value) {
                MdlUserPreferences::where('name', 'auth_forcepasswordchange')->where('userid', $user_id)->update(['value' => $data['preference_auth_forcepasswordchange']]);
            }

            //Update tags
            $usertag = $data['taggles'];
            if (!empty($usertag)) {
                // 1. update bảng mdl_tag
                $tag = array();
                $tag['userid'] = 2;
                $tag['tagtype'] = 'default';
                $tag['description'] = NULL;
                $tag['descriptionformat'] = 0;
                $tag['flag'] = 0;
                $tag['timemodified'] = time();
                // 2. update bảng mdl_tag_instance
                $tag_instance = array();
                $tag_instance['component'] = 'core';
                $tag_instance['itemtype'] = 'user';
                $tag_instance['itemid'] = $user_id;
                $tag_instance['contextid'] = $context->id;
                $tag_instance['tiuserid'] = 0;

                foreach ($usertag as $key => $value) {
                    // kiểm tra xem đã tồn tại tag_name này chưa. tránh việc lưu cùng sở thích nhiều lần
                    $tagcurren = MdlTag::where('name', $value)->first();
                    if ($tagcurren) {
                        // Nếu đã tồn tại tag_name = value thì lấy tagid của nó.
                        // Kiểm tra xem với tagid, user_id thì key trong db của mdl_tag_instance có bằng key post từ form không
                        $taginstance_current = MdlTagInstance::where('tagid', $tagcurren->id)->where('itemid', $user_id)->first();
                        //Kiểm tra xem có tồn tại taginstance_current hay không
                        if (!$taginstance_current) {
                            // Nếu không có (sở thích đã có trong tag nhưng người dùng này mới khai báo thêm sở thích này)
                            //Xóa bản ghi cũ trong tag_instance với $user_id và $key
                            MdlTagInstance::where('ordering', $key)->where('itemid', $user_id)->delete();
                            // Thêm bản ghi mới trong tag_instance với tagid và $key
                            $tagid = $tagcurren->id;
                            $tag_instance['tagid'] = $tagid;
                            $tag_instance['ordering'] = $key;
                            $tag_instance['timecreated'] = time();
                            $tag_instance['timemodified'] = $tag_instance['timecreated'];
                            $mdltag_instance = new MdlTagInstance($tag_instance);
                            $mdltag_instance->store();
                        } else {
                            // Nếu có lấy ordering của bản ghi cũ này trong tag_instance để so sánh với $key hiện tại
                            $order = $taginstance_current->ordering;
                            if ($order != $key) {
                                // Nếu khác (có một sở thích cũ phía trước đã bị xóa nên ordering thay đổi)
                                $tag_instance['timemodified'] = time();
                                MdlTagInstance::where('id', '=', $taginstance_current->id)->update(['ordering' => $key], ['timemodified' => $tag_instance['timemodified']]);
                                // Xóa bản ghi cũ với ordering cũ, bản ghi cũ vừa được update phía trên
                                //                            MdlTagInstance::where('ordering',$order)->where('itemid',$user_id)->delete();
                                // xóa bản ghi với tagid cũ đi
                                MdlTagInstance::where('tagid', '<>', $taginstance_current->tagid)->where('ordering', $key)->where('itemid', $user_id)->delete();
                            } else {
                                //Nếu bằng -> update lại timemodified và duyệt sang tag tiếp theo
                                $tag_instance['timemodified'] = time();
                                MdlTagInstance::where('id', '=', $taginstance_current->id)->update(['timemodified' => $tag_instance['timemodified']]);
                            }
                        }
                    } else {
                        // Nếu chưa tồn tại tại tag_name = value thì thêm bản ghi mới trong mdl_tag và trong mdl_tag_instance
                        $tag['name'] = $value;
                        $tag['rawname'] = $value;
                        $mdltag = new MdlTag($tag);
                        $mdltag->store();
                        // với mỗi bản ghi trong tag vừa thêm ta cũng thêm một bản ghi mới trong tag_instance và với tag_id vừa thêm vào
                        $tag_instance['tagid'] = $mdltag->id;;
                        $tag_instance['ordering'] = $key;
                        $tag_instance['timecreated'] = time();
                        $tag_instance['timemodified'] = $tag_instance['timecreated'];
                        $mdltag_instance = new MdlTagInstance($tag_instance);
                        $mdltag_instance->store();
                    }
                }
                // Xóa những bản ghi trong tag_instance có ordering > $key (key_max)
                MdlTagInstance::where('ordering', '>', $key)->where('itemid', $user_id)->delete();
            }

            // unset cacs trường trong data dùng để xử lý mà không đưa vào mdl_user db
            unset($data['taggles']);
            unset($data['preference_auth_forcepasswordchange']);
            unset($data['createpassword']);

            foreach ($data as $name => $value) {
                if ($value != $mdluser->$name) {
                    $mdluser->$name = $value;
                }
            }
            $mdluser->store();
            $ms->addMessageTranslated("success", 'MDLUSER_UPDATE_SUCCESS', ["name" => $data['username']]);
        }
    }
}