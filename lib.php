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


function local_notifications_render_navbar_output(\renderer_base $renderer) {
    global $USER, $CFG;

    // Early bail out conditions.
    if (!isloggedin() || isguestuser() || user_not_fully_set_up($USER) ||
        get_user_preferences('auth_forcepasswordchange') ||
        (!$USER->policyagreed && !is_siteadmin() &&
            ($manager = new \core_privacy\local\sitepolicy\manager()) && $manager->is_defined())) {
        return '';
    }

    $output = '';


    // Add the notifications popover.
        $unreadcount = count_unread_popup_notifications($USER->id);
        $context = [
            'userid' => $USER->id,
            'unreadcount' => $unreadcount,
            'urls' => [
                'seeall' => (new moodle_url('/message/output/popup/notifications.php'))->out(),
                'preferences' => (new moodle_url('/message/notificationpreferences.php', ['userid' => $USER->id]))->out(),
            ],
        ];
        $output .= $renderer->render_from_template('theme_legend/notification_popover_local', $context);

    return $output;
}

/**
 * Count the unread notifications for a user.
 *
 * @param int $useridto the user id who received the notification
 * @return int count of the unread notifications
 * @since 3.2
 */
function count_unread_popup_notifications($useridto = 0) {
    global $USER, $DB;

    if (empty($useridto)) {
        $useridto = $USER->id;
    }

    return $DB->count_records_sql(
        "SELECT count(id)
           FROM {notifications}
          WHERE id IN (SELECT notificationid FROM {message_popup_notifications})
            AND useridto = ?
            AND (timeread is NULL OR timeread = 0)",
        [$useridto]
    );
}

function get_file_list($directory) {
    global $CFG;
    $directoryroot = $CFG->dirroot;
    $finaleventfiles = array();
    if (is_dir($directory)) {
        if ($handle = opendir($directory)) {
            $eventfiles = scandir($directory);
            foreach ($eventfiles as $file) {
                if ($file != '.' && $file != '..') {
                    // Ignore the file if it is external to the system.
                    if (strrpos($directory, $directoryroot) !== false) {
                        $location = substr($directory, strlen($directoryroot));
                        $eventname = substr($file, 0, -4);
                        $finaleventfiles[$eventname] = $location  . '/' . $file;
                    }
                }
            }
        }
    }
    return $finaleventfiles;
}