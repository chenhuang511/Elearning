<?php

/**
 * External resource API
 *
 * @package    core_local_resource
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");


/**
 * Resource external functions
 *
 * @package    core_local_resource
 * @category   external
 * @copyright  2016 Nccsoft Vietnam
 * @license    http://nccsoft.vn
 * @since Moodle 3.0
 */
class local_mod_resource_external extends external_api
{
    public static function get_resource_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_resource_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_resource_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] == '') {
            $resource = $DB->get_record("resource", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $resource = $DB->get_record("resource", $arr, $params['sort']);
        } else {
            $resource = $DB->get_record("resource", $arr, '*', MUST_EXIST);
        }

        if (!$resource) {
            $resource = new stdClass();
        }

        $result['resource'] = $resource;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_resource_by_returns()
    {
        return new external_single_structure(
            array(
                'resource' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'the name'),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                        'introformat' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL),
                        'tobemigrated' => new external_value(PARAM_INT, 'tobe migrated'),
                        'legacyfiles' => new external_value(PARAM_INT, 'legacy files'),
                        'legacyfileslast' => new external_value(PARAM_INT, 'legacy files last'),
                        'display' => new external_format_value(PARAM_INT, 'display'),
                        'displayoptions' => new external_value(PARAM_RAW, 'display options'),
                        'filterfiles' => new external_value(PARAM_INT, 'filter files'),
                        'revision' => new external_value(PARAM_INT, 'revision'),
                        'timemodified' => new external_format_value(PARAM_INT, 'time modified')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_resource_old_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_resource_old_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_resource_old_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] == '') {
            $resource = $DB->get_record("resource_old", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $resource = $DB->get_record("resource_old", $arr, $params['sort']);
        } else {
            $resource = $DB->get_record("resource_old", $arr, '*', MUST_EXIST);
        }

        if (!$resource) {
            $resource = new stdClass();
        }

        $result['resource'] = $resource;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_resource_old_by_returns()
    {
        return new external_single_structure(
            array(
                'resource' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'the name'),
                        'type' => new external_value(PARAM_RAW, 'the type'),
                        'reference' => new external_value(PARAM_RAW, 'the reference'),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                        'introformat' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL),
                        'alltext' => new external_value(PARAM_RAW, 'all text'),
                        'popup' => new external_value(PARAM_RAW, 'popup'),
                        'options' => new external_value(PARAM_RAW, 'options'),
                        'timemodified' => new external_format_value(PARAM_INT, 'time modified'),
                        'oldid' => new external_format_value(PARAM_INT, 'the old id'),
                        'cmid' => new external_value(PARAM_INT, 'the course module id'),
                        'newmodule' => new external_value(PARAM_RAW, 'new module'),
                        'newid' => new external_value(PARAM_INT, 'the new id'),
                        'migrated' => new external_value(PARAM_INT, 'the migrate')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_field_resource_by_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'field' => new external_value(PARAM_RAW, 'field')
            )
        );
    }

    public static function get_field_resource_by($modname, $parameters, $field)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_field_resource_by_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
            'field' => $field
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $f = $DB->get_field($params['modname'], $params['field'], $arr);

        if (!$f) {
            $f = 0;
        }

        $result['field'] = $f;
        $result['warnings'] = $warnings;

        return $result;

    }

    public static function get_field_resource_by_returns()
    {
        return new external_single_structure(
            array(
                'field' => new external_value(PARAM_RAW, 'field'),
                'warnings' => new external_warnings()
            )
        );
    }
	
	
	public static function get_resource_files_by_cm_parameters()
    {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'course module id'),                
            )
        );
    }

    public static function get_resource_files_by_cm($cmid)
    {
        global $DB;

        $params = self::validate_parameters(self::get_resource_files_by_cm_parameters(), array(
            'cmid' => $cmid,
        ));
		
		$context = context_module::instance($cmid);
		
		$fs = get_file_storage();
		$files = $fs->get_area_files($context->id, 'mod_resource', 'content', 0, 'sortorder DESC, id ASC', false); // TODO: this is not very efficient!!
	
		$file = reset($files);
 		$url = moodle_url::make_pluginfile_url(
            		$file->get_contextid(),
		        $file->get_component(),
            		$file->get_filearea(),
            		$file->get_itemid(),
            		$file->get_filepath(),
            		$file->get_filename(),
            		true
        	);
		
		$result['filename'] = $file->get_filename();
		$result['url']	    = $url->out();
		$result['type']     = $file->get_filearea();
		$result['size']     = $file->get_filesize();
		
		return $result;
    }

    public static function get_resource_files_by_cm_returns()
    {
        return new external_single_structure(
            array(
                'filename' => new external_value(PARAM_TEXT, 'file instance'),
		'url' => new external_value(PARAM_TEXT, 'file instance'),
		'type' => new external_value(PARAM_TEXT, 'file instance'),
		'size' => new external_value(PARAM_INT, 'file instance'),
            )
        );
    }
}
