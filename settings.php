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
