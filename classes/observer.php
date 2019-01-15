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
 * The local_notifications custom notification observer.
 *
 * @package    local_notifications
 * @copyright  2017 GetSmarter {@link http://www.getsmarter.co.za}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_notifications_observer
{
    public static function moodle_event_observed($event_observed){
        global $DB;
        //error_log(print_r($event_observed), 1);
        $enabledeventsarray = explode(',', get_config("local_notifications_events")->enablenotificationsselect);

        if (in_array($event_observed->eventname, $enabledeventsarray)) {
	       	$newnotification = new stdClass();
	        $newnotification->userid = $event_observed->userid;
	        $newnotification->title = $event_observed->component;
	        $newnotification->shortmessage = $event_observed->eventname;
	        $newnotification->link = '';
	        $newnotification->status = 0;
	        $newnotification->timecreated = time();
        }
    }
}
