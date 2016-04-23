<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/23/2016
 * Time: 5:59 PM
 */
namespace UserFrosting;

class MdlUserController extends \UserFrosting\BaseController{
    public function __construct($app){
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

        $this->_app->render("/users/mdl_user.twig",[
            "box_title" => $name,
            "icon" => $icon,
            "users" => isset($user_collection) ? $user_collection : []
        ]);
    }

    //Create form Usermood
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
            'africa/abidjan' => 'Africa/Abidjan',
            'africa/accra' => 'Africa/Accra',
            'africa/addis_ababa' => 'Africa/Addis_Ababa',
            'africa/algiers' => 'Africa/Algiers',
            'africa/asmera' => 'Africa/Asmera',
            'africa/bamako' => 'Africa/Bamako',
            'africa/bangui' => 'Africa/Bangui',
            'africa/banjul' => 'Africa/Banjul',
            'africa/bissau' => 'Africa/Bissau',
            'africa/blantyre' => 'Africa/Blantyre',
            'africa/brazzaville' => 'Africa/Brazzaville',
            'africa/bujumbura' => 'Africa/Bujumbura',
            'africa/cairo' => 'Africa/Cairo',
            'africa/casablanca' => 'Africa/Casablanca',
            'africa/ceuta' => 'Africa/Ceuta',
            'africa/conakry' => 'Africa/Conakry',
            'africa/dakar' => 'Africa/Dakar',
            'africa/dar_es_salaam' => 'Africa/Dar_es_Salaam',
            'africa/djibouti' => 'Africa/Djibouti',
            'africa/douala' => 'Africa/Douala',
            'africa/el_aaiun' => 'Africa/El_Aaiun',
            'africa/freetown' => 'Africa/Freetown',
            'africa/gaborone' => 'Africa/Gaborone',
            'africa/harare' => 'Africa/Harare',
            'africa/johannesburg' => 'Africa/Johannesburg',
            'africa/kampala' => 'Africa/Kampala',
            'africa/khartoum' => 'Africa/Khartoum',
            'africa/kigali' => 'Africa/Kigali',
            'africa/kinshasa' => 'Africa/Kinshasa',
            'africa/lagos' => 'Africa/Lagos',
            'africa/libreville' => 'Africa/Libreville',
            'africa/lome' => 'Africa/Lome',
            'africa/luanda' => 'Africa/Luanda',
            'africa/lubumbashi' => 'Africa/Lubumbashi',
            'africa/lusaka' => 'Africa/Lusaka',
            'africa/malabo' => 'Africa/Malabo',
            'africa/maputo' => 'Africa/Maputo',
            'africa/maseru' => 'Africa/Maseru',
            'africa/mbabane' => 'Africa/Mbabane',
            'africa/mogadishu' => 'Africa/Mogadishu',
            'africa/monrovia' => 'Africa/Monrovia',
            'africa/nairobi' => 'Africa/Nairobi',
            'africa/ndjamena' => 'Africa/Ndjamena',
            'africa/niamey' => 'Africa/Niamey',
            'africa/nouakchott' => 'Africa/Nouakchott',
            'africa/ouagadougou' => 'Africa/Ouagadougou',
            'africa/porto-novo' => 'Africa/Porto-Novo',
            'africa/sao_tome' => 'Africa/Sao_Tome',
            'africa/timbuktu' => 'Africa/Timbuktu',
            'africa/tripoli' => 'Africa/Tripoli',
            'africa/tunis' => 'Africa/Tunis',
            'africa/windhoek' => 'Africa/Windhoek',
            'america/adak' => 'America/Adak',
            'america/anguilla' => 'America/Anguilla',
            'america/anchorage' => 'America/Anchorage',
            'america/antigua' => 'America/Antigua',
            'america/araguaina' => 'America/Araguaina',
            'america/argentina/buenos_aires' => 'America/Argentina/Buenos_Aires',
            'america/argentina/catamarca' => 'America/Argentina/Catamarca',
            'america/argentina/comodrivadavia' => 'America/Argentina/ComodRivadavia',
            'america/argentina/cordoba' => 'America/Argentina/Cordoba',
            'america/argentina/jujuy' => 'America/Argentina/Jujuy',
            'america/argentina/la_rioja' => 'America/Argentina/La_Rioja',
            'america/argentina/mendoza' => 'America/Argentina/Mendoza',
            'america/argentina/rio_gallegos' => 'America/Argentina/Rio_Gallegos',
            'america/argentina/san_juan' => 'America/Argentina/San_Juan',
            'america/argentina/tucuman' => 'America/Argentina/Tucuman',
            'america/argentina/ushuaia' => 'America/Argentina/Ushuaia',
            'america/aruba' => 'America/Aruba',
            'america/asuncion' => 'America/Asuncion',
            'america/bahia' => 'America/Bahia',
            'america/barbados' => 'America/Barbados',
            'america/belem' => 'America/Belem',
            'america/belize' => 'America/Belize',
            'america/boa_vista' => 'America/Boa_Vista',
            'america/bogota' => 'America/Bogota',
            'america/boise' => 'America/Boise',
            'america/cambridge_bay' => 'America/Cambridge_Bay',
            'america/campo_grande' => 'America/Campo_Grande',
            'america/cancun' => 'America/Cancun',
            'america/caracas' => 'America/Caracas',
            'america/cayenne' => 'America/Cayenne',
            'america/cayman' => 'America/Cayman',
            'america/costa_rica' => 'America/Costa_Rica',
            'america/cuiaba' => 'America/Cuiaba',
            'america/curacao' => 'America/Curacao',
            'america/danmarkshavn' => 'America/Danmarkshavn',
            'america/dawson' => 'America/Dawson',
            'america/dawson_creek' => 'America/Dawson_Creek',
            'america/denver' => 'America/Denver',
            'america/detroit' => 'America/Detroit',
            'america/dominica' => 'America/Dominica',
            'america/edmonton' => 'America/Edmonton',
            'america/eirunepe' => 'America/Eirunepe',
            'america/el_salvador' => 'America/El_Salvador',
            'america/fortaleza' => 'America/Fortaleza',
            'america/glace_bay' => 'America/Glace_Bay',
            'america/godthab' => 'America/Godthab',
            'america/goose_bay' => 'America/Goose_Bay',
            'america/grand_turk' => 'America/Grand_Turk',
            'america/grenada' => 'America/Grenada',
            'america/guadeloupe' => 'America/Guadeloupe',
            'america/guatemala' => 'America/Guatemala',
            'america/guayaquil' => 'America/Guayaquil',
            'america/guyana' => 'America/Guyana',
            'america/halifax' => 'America/Halifax',
            'america/havana' => 'America/Havana',
            'america/hermosillo' => 'America/Hermosillo',
            'america/chicago' => 'America/Chicago',
            'america/chihuahua' => 'America/Chihuahua',
            'america/indiana/knox' => 'America/Indiana/Knox',
            'america/indiana/marengo' => 'America/Indiana/Marengo',
            'america/indianapolis' => 'America/Indianapolis',
            'america/indiana/vevay' => 'America/Indiana/Vevay',
            'america/inuvik' => 'America/Inuvik',
            'america/iqaluit' => 'America/Iqaluit',
            'america/jamaica' => 'America/Jamaica',
            'america/juneau' => 'America/Juneau',
            'america/kentucky/monticello' => 'America/Kentucky/Monticello',
            'america/la_paz' => 'America/La_Paz',
            'america/lima' => 'America/Lima',
            'america/los_angeles' => 'America/Los_Angeles',
            'america/louisville' => 'America/Louisville',
            'america/maceio' => 'America/Maceio',
            'america/managua' => 'America/Managua',
            'america/manaus' => 'America/Manaus',
            'america/martinique' => 'America/Martinique',
            'america/mazatlan' => 'America/Mazatlan',
            'america/menominee' => 'America/Menominee',
            'america/merida' => 'America/Merida',
            'america/mexico_city' => 'America/Mexico_City',
            'america/miquelon' => 'America/Miquelon',
            'america/monterrey' => 'America/Monterrey',
            'america/montevideo' => 'America/Montevideo',
            'america/montreal' => 'America/Montreal',
            'america/montserrat' => 'America/Montserrat',
            'america/nassau' => 'America/Nassau',
            'america/new_york' => 'America/New_York',
            'america/nipigon' => 'America/Nipigon',
            'america/nome' => 'America/Nome',
            'america/noronha' => 'America/Noronha',
            'america/north_dakota/center' => 'America/North_Dakota/Center',
            'america/panama' => 'America/Panama',
            'america/pangnirtung' => 'America/Pangnirtung',
            'america/paramaribo' => 'America/Paramaribo',
            'america/phoenix' => 'America/Phoenix',
            'america/port-au-prince' => 'America/Port-au-Prince',
            'america/port_of_spain' => 'America/Port_of_Spain',
            'america/porto_velho' => 'America/Porto_Velho',
            'america/puerto_rico' => 'America/Puerto_Rico',
            'america/rainy_river' => 'America/Rainy_River',
            'america/rankin_inlet' => 'America/Rankin_Inlet',
            'america/recife' => 'America/Recife',
            'america/regina' => 'America/Regina',
            'america/rio_branco' => 'America/Rio_Branco',
            'america/santiago' => 'America/Santiago',
            'america/santo_domingo' => 'America/Santo_Domingo',
            'america/sao_paulo' => 'America/Sao_Paulo',
            'america/scoresbysund' => 'America/Scoresbysund',
            'america/st_johns' => 'America/St_Johns',
            'america/st_kitts' => 'America/St_Kitts',
            'america/st_lucia' => 'America/St_Lucia',
            'america/st_thomas' => 'America/St_Thomas',
            'america/st_vincent' => 'America/St_Vincent',
            'america/swift_current' => 'America/Swift_Current',
            'america/tegucigalpa' => 'America/Tegucigalpa',
            'america/thule' => 'America/Thule',
            'america/thunder_bay' => 'America/Thunder_Bay',
            'america/tijuana' => 'America/Tijuana',
            'america/toronto' => 'America/Toronto',
            'america/tortola' => 'America/Tortola',
            'america/vancouver' => 'America/Vancouver',
            'america/whitehorse' => 'America/Whitehorse',
            'america/winnipeg' => 'America/Winnipeg',
            'america/yakutat' => 'America/Yakutat',
            'america/yellowknife' => 'America/Yellowknife',
            'antarctica/casey' => 'Antarctica/Casey',
            'antarctica/davis' => 'Antarctica/Davis',
            'antarctica/dumontdurville' => 'Antarctica/DumontDUrville',
            'antarctica/mawson' => 'Antarctica/Mawson',
            'antarctica/mcmurdo' => 'Antarctica/McMurdo',
            'antarctica/palmer' => 'Antarctica/Palmer',
            'antarctica/rothera' => 'Antarctica/Rothera',
            'antarctica/syowa' => 'Antarctica/Syowa',
            'antarctica/vostok' => 'Antarctica/Vostok',
            'asia/aden' => 'Asia/Aden',
            'asia/almaty' => 'Asia/Almaty',
            'asia/amman' => 'Asia/Amman',
            'asia/anadyr' => 'Asia/Anadyr',
            'asia/aqtau' => 'Asia/Aqtau',
            'asia/aqtobe' => 'Asia/Aqtobe',
            'asia/ashgabat' => 'Asia/Ashgabat',
            'asia/baghdad' => 'Asia/Baghdad',
            'asia/bahrain' => 'Asia/Bahrain',
            'asia/baku' => 'Asia/Baku',
            'asia/bangkok' => 'Asia/Bangkok',
            'asia/beirut' => 'Asia/Beirut',
            'asia/bishkek' => 'Asia/Bishkek',
            'asia/brunei' => 'Asia/Brunei',
            'asia/calcutta' => 'Asia/Calcutta',
            'asia/colombo' => 'Asia/Colombo',
            'asia/damascus' => 'Asia/Damascus',
            'asia/dhaka' => 'Asia/Dhaka',
            'asia/dili' => 'Asia/Dili',
            'asia/dubai' => 'Asia/Dubai',
            'asia/dushanbe' => 'Asia/Dushanbe',
            'asia/gaza' => 'Asia/Gaza',
            'asia/harbin' => 'Asia/Harbin',
            'asia/hong_kong' => 'Asia/Hong_Kong',
            'asia/hovd' => 'Asia/Hovd',
            'asia/choibalsan' => 'Asia/Choibalsan',
            'asia/chongqing' => 'Asia/Chongqing',
            'asia/irkutsk' => 'Asia/Irkutsk',
            'asia/jayapura' => 'Asia/Jayapura',
            'asia/jerusalem' => 'Asia/Jerusalem',
            'asia/kabul' => 'Asia/Kabul',
            'asia/kamchatka' => 'Asia/Kamchatka',
            'asia/karachi' => 'Asia/Karachi',
            'asia/kashgar' => 'Asia/Kashgar',
            'asia/katmandu' => 'Asia/Katmandu',
            'asia/krasnoyarsk' => 'Asia/Krasnoyarsk',
            'asia/kuala_lumpur' => 'Asia/Kuala_Lumpur',
            'asia/kuching' => 'Asia/Kuching',
            'asia/kuwait' => 'Asia/Kuwait',
            'asia/macau' => 'Asia/Macau',
            'asia/magadan' => 'Asia/Magadan',
            'asia/makassar' => 'Asia/Makassar',
            'asia/manila' => 'Asia/Manila',
            'asia/muscat' => 'Asia/Muscat',
            'asia/nicosia' => 'Asia/Nicosia',
            'asia/novosibirsk' => 'Asia/Novosibirsk',
            'asia/omsk' => 'Asia/Omsk',
            'asia/oral' => 'Asia/Oral',
            'asia/phnom_penh' => 'Asia/Phnom_Penh',
            'asia/pontianak' => 'Asia/Pontianak',
            'asia/pyongyang' => 'Asia/Pyongyang',
            'asia/qatar' => 'Asia/Qatar',
            'asia/qyzylorda' => 'Asia/Qyzylorda',
            'asia/rangoon' => 'Asia/Rangoon',
            'asia/riyadh' => 'Asia/Riyadh',
            'asia/saigon' => 'Asia/Saigon',
            'asia/sakhalin' => 'Asia/Sakhalin',
            'asia/samarkand' => 'Asia/Samarkand',
            'asia/seoul' => 'Asia/Seoul',
            'asia/shanghai' => 'Asia/Shanghai',
            'asia/singapore' => 'Asia/Singapore',
            'asia/taipei' => 'Asia/Taipei',
            'asia/tashkent' => 'Asia/Tashkent',
            'asia/tbilisi' => 'Asia/Tbilisi',
            'asia/tehran' => 'Asia/Tehran',
            'asia/thimphu' => 'Asia/Thimphu',
            'asia/tokyo' => 'Asia/Tokyo',
            'asia/ulaanbaatar' => 'Asia/Ulaanbaatar',
            'asia/urumqi' => 'Asia/Urumqi',
            'asia/vientiane' => 'Asia/Vientiane',
            'asia/vladivostok' => 'Asia/Vladivostok',
            'asia/yakutsk' => 'Asia/Yakutsk',
            'asia/yekaterinburg' => 'Asia/Yekaterinburg',
            'asia/yerevan' => 'Asia/Yerevan',
            'atlantic/azores' => 'Atlantic/Azores',
            'atlantic/bermuda' => 'Atlantic/Bermuda',
            'atlantic/canary' => 'Atlantic/Canary',
            'atlantic/cape_verde' => 'Atlantic/Cape_Verde',
            'atlantic/faeroe' => 'Atlantic/Faeroe',
            'atlantic/madeira' => 'Atlantic/Madeira',
            'atlantic/reykjavik' => 'Atlantic/Reykjavik',
            'atlantic/south_georgia' => 'Atlantic/South_Georgia',
            'atlantic/stanley' => 'Atlantic/Stanley',
            'atlantic/st_helena' => 'Atlantic/St_Helena',
            'australia/adelaide' => 'Australia/Adelaide',
            'australia/brisbane' => 'Australia/Brisbane',
            'australia/broken_hill' => 'Australia/Broken_Hill',
            'australia/darwin' => 'Australia/Darwin',
            'australia/hobart' => 'Australia/Hobart',
            'australia/lindeman' => 'Australia/Lindeman',
            'australia/lord_howe' => 'Australia/Lord_Howe',
            'australia/melbourne' => 'Australia/Melbourne',
            'australia/perth' => 'Australia/Perth',
            'australia/sydney' => 'Australia/Sydney',
            'europe/amsterdam' => 'Europe/Amsterdam',
            'europe/andorra' => 'Europe/Andorra',
            'europe/athens' => 'Europe/Athens',
            'europe/belfast' => 'Europe/Belfast',
            'europe/belgrade' => 'Europe/Belgrade',
            'europe/berlin' => 'Europe/Berlin',
            'europe/brussels' => 'Europe/Brussels',
            'europe/budapest' => 'Europe/Budapest',
            'europe/bucharest' => 'Europe/Bucharest',
            'europe/copenhagen' => 'Europe/Copenhagen',
            'europe/dublin' => 'Europe/Dublin',
            'europe/gibraltar' => 'Europe/Gibraltar',
            'europe/helsinki' => 'Europe/Helsinki',
            'europe/chisinau' => 'Europe/Chisinau',
            'europe/istanbul' => 'Europe/Istanbul',
            'europe/kaliningrad' => 'Europe/Kaliningrad',
            'europe/kiev' => 'Europe/Kiev',
            'europe/lisbon' => 'Europe/Lisbon',
            'europe/london' => 'Europe/London',
            'europe/luxembourg' => 'Europe/Luxembourg',
            'europe/madrid' => 'Europe/Madrid',
            'europe/malta' => 'Europe/Malta',
            'europe/minsk' => 'Europe/Minsk',
            'europe/monaco' => 'Europe/Monaco',
            'europe/moscow' => 'Europe/Moscow',
            'europe/oslo' => 'Europe/Oslo',
            'europe/paris' => 'Europe/Paris',
            'europe/prague' => 'Europe/Prague',
            'europe/riga' => 'Europe/Riga',
            'europe/rome' => 'Europe/Rome',
            'europe/samara' => 'Europe/Samara',
            'europe/simferopol' => 'Europe/Simferopol',
            'europe/sofia' => 'Europe/Sofia',
            'europe/stockholm' => 'Europe/Stockholm',
            'europe/tallinn' => 'Europe/Tallinn',
            'europe/tirane' => 'Europe/Tirane',
            'europe/uzhgorod' => 'Europe/Uzhgorod',
            'europe/vaduz' => 'Europe/Vaduz',
            'europe/vienna' => 'Europe/Vienna',
            'europe/vilnius' => 'Europe/Vilnius',
            'europe/warsaw' => 'Europe/Warsaw',
            'europe/zaporozhye' => 'Europe/Zaporozhye',
            'europe/zurich' => 'Europe/Zurich',
            'indian/antananarivo' => 'Indian/Antananarivo',
            'indian/comoro' => 'Indian/Comoro',
            'indian/chagos' => 'Indian/Chagos',
            'indian/christmas' => 'Indian/Christmas',
            'indian/kerguelen' => 'Indian/Kerguelen',
            'indian/mahe' => 'Indian/Mahe',
            'indian/maldives' => 'Indian/Maldives',
            'indian/mauritius' => 'Indian/Mauritius',
            'indian/mayotte' => 'Indian/Mayotte',
            'indian/reunion' => 'Indian/Reunion',
            'pacific/apia' => 'Pacific/Apia',
            'pacific/auckland' => 'Pacific/Auckland',
            'pacific/easter' => 'Pacific/Easter',
            'pacific/efate' => 'Pacific/Efate',
            'pacific/enderbury' => 'Pacific/Enderbury',
            'pacific/fakaofo' => 'Pacific/Fakaofo',
            'pacific/fiji' => 'Pacific/Fiji',
            'pacific/funafuti' => 'Pacific/Funafuti',
            'pacific/galapagos' => 'Pacific/Galapagos',
            'pacific/gambier' => 'Pacific/Gambier',
            'pacific/guadalcanal' => 'Pacific/Guadalcanal',
            'pacific/guam' => 'Pacific/Guam',
            'pacific/honolulu' => 'Pacific/Honolulu',
            'pacific/chatham' => 'Pacific/Chatham',
            'pacific/kiritimati' => 'Pacific/Kiritimati',
            'pacific/kosrae' => 'Pacific/Kosrae',
            'pacific/kwajalein' => 'Pacific/Kwajalein',
            'pacific/majuro' => 'Pacific/Majuro',
            'pacific/marquesas' => 'Pacific/Marquesas',
            'pacific/midway' => 'Pacific/Midway',
            'pacific/nauru' => 'Pacific/Nauru',
            'pacific/niue' => 'Pacific/Niue',
            'pacific/norfolk' => 'Pacific/Norfolk',
            'pacific/noumea' => 'Pacific/Noumea',
            'pacific/pago_pago' => 'Pacific/Pago_Pago',
            'pacific/palau' => 'Pacific/Palau',
            'pacific/pitcairn' => 'Pacific/Pitcairn',
            'pacific/ponape' => 'Pacific/Ponape',
            'pacific/port_moresby' => 'Pacific/Port_Moresby',
            'pacific/rarotonga' => 'Pacific/Rarotonga',
            'pacific/saipan' => 'Pacific/Saipan',
            'pacific/tahiti' => 'Pacific/Tahiti',
            'pacific/tarawa' => 'Pacific/Tarawa',
            'pacific/tongatapu' => 'Pacific/Tongatapu',
            'pacific/truk' => 'Pacific/Truk',
            'pacific/wake' => 'Pacific/Wake',
            'pacific/wallis' => 'Pacific/Wallis',
            'pacific/yap' => 'Pacific/Yap',

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

        $mdl_user = new MdlUser($data);
        $this->_app->render('users/form_mdluser_create.twig', [
            "mdl_user" => $mdl_user,
            "auths" => $auths,
            "mail_dis" => $mail_display,
            "countries" => $countries,
            "timezone" => $timezone,
            "validators" => $this->_app->jsValidator->rules()
        ]);
    }

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
            if($key != 'username'){
                if($key != 'firstname'){
                    if($key != 'surname'){
                        if($key != 'email'){
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
        $user['password'] = password_hash($user['password'],PASSWORD_DEFAULT,$options);

        $user['username'] = trim($user['username']);
        $user['firstname'] = trim($user['firstname']);
        $user['lastname'] = trim($user['lastname']);

        // Check if user_name or email already exists
        if (MdlUser::where('username', $user['username'])->first()){
            $ms->addMessageTranslated("danger", "ACCOUNT_USERNAME_IN_USE", $user);
            $error = true;
        }
        if (MdlUser::where('email', $user['email'])->first()){
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
        $ms->addMessageTranslated("success", 'Create user "{{name}}"!! successfull',["name" => $user['username']]);

        //lấy dữ liệu user_id vừa mới thêm vào bảng user để đưa vào instanceid
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
        $context['path'] = '/1/'.$contextId;
        //update path cho bảng context
        MdlContext::where('id','=',$contextId)->update(['path' => $context['path']]);
    }
}