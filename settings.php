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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_notifications
 * @category    admin
 * @copyright   2018 GetSmarter <you@example.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$ADMIN->add('root', new admin_category('local_notifications', get_string('pluginname', 'local_notifications')));
// General settings page
$general = new admin_settingpage('local_notifications_general',  'General Settings', 'moodle/site:config');

$name = 'local_notifications/enable';
$title = 'Enable/Disable notifications';
$description = 'Allows admins to turn local notifications plugin on or off.';
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$general->add($setting);

$name = 'local_notifications/enablecron';
$title = 'Enable cron';
$description = 'This setting, when enabled, will allow a cron to check for read/unread notifications that are older than X amount of time.';
$default = 1;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$general->add($setting);

$name = 'local_notifications/croncleanupperiod';
$title = 'Notification cleanup period';
$description = 'period a notification becomes ready for removal.';
$default = '86400';
$choices = array('86400' => '1 day', '604800' => '1 week', '259200' => '1 month', '15780000' => '6 months', '31536000' => '1 year');
$setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
$general->add($setting);

$name = 'local_notifications/deleterundread';
$title = 'Delete unread notifications';
$description = 'allow the cleanup cron to delete unread notifications.';
$default = 0;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default);
$general->add($setting);

$ADMIN->add('local_notifications', $general);

$events = new admin_settingpage('local_notifications_events',  'Event Monitoring', 'moodle/site:config');

$name = 'local_notifications_events/enablenotifications';
$title = 'Enabling Notifications';
$description = 'The following default events are enabled and listed to generate OLC notifications';
$setting = new admin_setting_heading($name, $title, $description);
$events->add($setting);

$completelist = report_eventlist_list_generator::get_all_events_list();

$defaultevents = array(
		array('Quiz attempt submitted' => '\mod_quiz\event\attempt_submitted'),
		array('Assignment submissions' => '\mod_assign\event\assessable_submitted'),
		array('Module releases' => '\local_getsmarter\event\module_1_student_module_release'),
		array('Grades release' => '\local_getsmarter\event\assignment_grade_released'),
		array('No module logon' => '\local_getsmarter\event\nudge_no_module_logon'),
		array('Late submissions' => '\local_getsmarter\event\late_submission'),
		array('Assignment due next day' => '\local_getsmarter\event\assignment_due_next_day'),
	);

foreach ($defaultevents as $defaulteventarray) {
	foreach ($defaulteventarray as $eventname => $eventmoodlename) {
		$name = str_replace('\\', '_', $eventmoodlename);
		$name = 'local_notifications_events_'.$name;
		$title = $eventname;
		$default = 1;
		$setting = new admin_setting_configcheckbox($name, $title, '', $default);
		$events->add($setting);
	}
}

$eventsmonitored = $DB->get_records_sql("SELECT name FROM {config} WHERE name like 'local_notifications_events%'");

foreach ($eventsmonitored as $enabledeventsarray) {
	foreach ($enabledeventsarray as $enabledevents) {

		$name = str_replace('\\', '_', $enabledevents);
		$title = explode('\\', $completelist[$enabledevents]['raweventname'])[0];
		$default = 0;
		$setting = new admin_setting_configcheckbox($name, $title, '', $default);
		$events->add($setting);
	}
}

$name = 'local_notifications_events/enablenotification';
$title = 'Add new events for notifications';
$description = 'The following default events are enabled and listed to generate OLC notifications';
$setting = new admin_setting_heading($name, $title, $description);
$events->add($setting);

$ADMIN->add('local_notifications', $events);

$tabledata = array();

foreach ($completelist as $value) {
    $tabledata['local_notifications_events'.$value['eventname']] = 'local_notifications_events'.$value['eventname'];
}

$name = 'local_notifications_events/enablenotificationsselect';
$title = 'Event';
$setting = new admin_setting_configmultiselect($name, $title, $description, '', $tabledata);

$events->add($setting);
$ADMIN->add('local_notifications_add', $events);
