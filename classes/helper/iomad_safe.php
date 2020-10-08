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
 * A lookup step that takes a user's ID and adds standard data about the user.
 *
 * @package    tool_trigger
 * @author     Aaron Wells <aaronw@catalyst.net.nz>
 * @copyright  Catalyst IT, 2018
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_trigger\helper;

defined('MOODLE_INTERNAL') || die;

trait iomad_safe {
    function iomad_check_course($courseid, $companyid) {
        global $DB;
        if ($companyid == null) {
            return True;
        } else {
            return $DB->get_record( 'company_course', array('courseid' => $courseid) ) != null;
        }
    }

    function iomad_check_user($userid, $companyid) {
        global $DB;
        if (!$companyid == null) {
            return True;
        } else {
            $company = \company::get_company_byuserid($userid);
            if (!$company) {
                return False;
            }

            return get_topcompanyid() == $companyid;
        }
    }
}
