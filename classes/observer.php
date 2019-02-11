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
    public static function moodle_notification_event_observed($event_observed){
        global $DB;

       	$newnotification = new stdClass();
        $newnotification->id = $event_observed->objectid;
        $newnotification->timeread = null;

        try {
            $DB->update_record('notifications', $newnotification);
        } catch (Exception $e) {
            error_log(print_r($e, true));
        }
    }
}
