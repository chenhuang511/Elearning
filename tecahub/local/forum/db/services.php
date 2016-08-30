<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Core external functions and service definitions.
 *
 * The functions and services defined on this file are
 * processed and registered into the Moodle DB after any
 * install or upgrade operation. All plugins support this.
 *
 * For more information, take a look to the documentation available:
 *	   - Webservices API: {@link http://docs.moodle.org/dev/Web_services_API}
 *	   - External API: {@link http://docs.moodle.org/dev/External_functions_API}
 *	   - Upgrade API: {@link http://docs.moodle.org/dev/Upgrade_API}
 *
 * @package	   core_webservice
 * @category   webservice
 * @copyright  2009 Petr Skodak
 * @license	   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    'local_mod_get_forum_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_forum_discussions_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_discussions_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum discussions by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_forum_discussion_subs_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_discussion_subs_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum discussion subs by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_forum_posts_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_posts_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum posts by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_forum_digests_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_digests_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum digest by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_forum_track_prefs_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_track_prefs_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum track prefs by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_forum_subscriptions_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_forum_subscriptions_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get forum subscriptions by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_scale_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_scale_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get scale by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_discussions_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_discussions_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum discussions by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_discussions_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_discussions_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum discussions',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_posts_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_posts_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum posts by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_posts_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_posts_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum posts',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_read_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_read_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum read by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_list_forum_discussion_subs_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_list_forum_discussion_subs_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list forum discussion subs by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_delete_mdl_forum' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'delete_mdl_forum',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Delete forum object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_save_mdl_forum' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'save_mdl_forum',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'save new forum object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_update_mdl_forum' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'update_mdl_forum',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'update forum object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_update_mdl_forum_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'update_mdl_forum_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'update forum object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_field_forum_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_field_forum_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get field of forum by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_field_forum_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_field_forum_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get field of forum sql',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_count_forum_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_count_forum_by',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get count row of forum by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_check_record_forum_exists_by' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'check_record_forum_exists',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'check record exists',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_get_discussions_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_get_discussions_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'get list obejct',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_get_count_forum_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'get_count_forum_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get count row of forum',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_count_discussion_replies_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_count_discussion_replies_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get list replies',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_get_post_full_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_get_post_full_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Get full post',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_get_discussion_neighbours_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_get_discussion_neighbours_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'get discussion neighbours',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_get_all_discussion_posts_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_get_all_discussion_posts_sql',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'get all discussion post',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_user_has_posted' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_user_has_posted',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'check forum user has posted',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_user_has_posted_discussion' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_user_has_posted_discussion',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'check forum user has posted discussion',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_search_posts_sql' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_search_posts',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'search forum posts',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_delete_mdl_forum_select' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'delete_mdl_forum_select',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'Delete forum object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_add_instance' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_add_instance',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'save new forum  and new context',
        'type' => 'read',
        'ajax' => true
    ),
    'local_mod_forum_add_discussions' => array(
        'classname' => 'local_mod_forum_external',
        'methodname' => 'forum_add_discussions',
        'classpath' => 'local/forum/externallib.php',
        'description' => 'save new discussion, post and new context',
        'type' => 'read',
        'ajax' => true
    ),
);
