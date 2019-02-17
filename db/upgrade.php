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
 * Plugin upgrade steps are defined here.
 *
 * @package     local_getsmarter
 * @category    upgrade
 * @copyright   2018 Getsmarter <rumano.balie@getsmarter.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute mod_netverify upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_local_notifications_upgrade($oldversion) {
    global $CFG;

    if ($oldversion < 2018120100) {

        global $DB;
        $dbman = $DB->get_manager();

        $table = new xmldb_table('local_notifications');

        if (!$dbman->table_exists($table)) {
            $dbman->install_one_table_from_xmldb_file($CFG->dirroot.'/local/notifications/db/install.xml', 'local_notifications');
        }

        upgrade_plugin_savepoint(true, 2018120100, 'local', 'notifications');
    }

    if ($oldversion < 2019021700) {

        global $DB;
        $dbman = $DB->get_manager();

        $table = new xmldb_table('local_notifications');

        if ($dbman->table_exists($table)) {
            $dbman->delete_tables_from_xmldb_file($CFG->dirroot.'/local/notifications/db/install.xml', 'local_notifications');
        }

        upgrade_plugin_savepoint(true, 2019021700, 'local', 'notifications');
    }

    return true;
}
