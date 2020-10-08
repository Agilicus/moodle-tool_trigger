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
 * Cohort assignment action step class.
 *
 * @package    tool_trigger
 * @copyright  Paul Damiani <pauldamiani@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_trigger\steps\actions;

defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot.'/cohort/lib.php');

class assign_course_action_step extends base_action_step
{

    use \tool_trigger\helper\datafield_manager;
    use \tool_trigger\helper\iomad_safe;

    /**
     * The fields supplied by this step.
     *
     * @var array
     */
    private static $stepfields = array();

    /**
     * Returns the step name.
     *
     * @return string human readable step name.
     */
    public static function get_step_name() {
        return get_string('assigncourseactionstepname', 'tool_trigger');
    }

    /**
     * Returns the step name.
     *
     * @return string human readable step name.
     */
    public static function get_step_desc() {
        return get_string('assigncohortactionstepdesc', 'tool_trigger');
    }

    protected function init() {
        $this->useridfield = $this->data['useridfield'];
        $this->courseidfield = $this->data['courseidfield'];
    }

    /**
     * @param $step
     * @param $trigger
     * @param $event
     * @param $stepresults - result of previousstep to include in processing this step.
     * @return array if execution was succesful and the response from the execution.
     */
    public function execute($step, $trigger, $event, $stepresults) {

        $datafields = $this->get_datafields($event, $stepresults);

        if ($this->iomad_check_course($this->courseidfield, $step->companyid) && $this->iomad_check_user($this->useridfield, $step->companyid)){
            \company_user::enrol($datafields[$this->useridfield], array($this->courseidfield), $step->companyid);
            $stepresults['course_assigned'] = $this->courseidfield;
            return array(true, $stepresults);
        }

        return array(false, $stepresults);
    }

    /**
     * {@inheritDoc}
     * @see \tool_trigger\steps\base\base_step::add_extra_form_fields()
     */
    public function form_definition_extra($form, $mform, $customdata) {
        $mform->addElement('text', 'useridfield', get_string('step_lookup_user_useridfield', 'tool_trigger'));
        $mform->setType('useridfield', PARAM_ALPHANUMEXT);
        $mform->addRule('useridfield', get_string('required'), 'required');
        $mform->setDefault('useridfield', 'userid');

        $mform->addElement('text', 'courseidfield', get_string('courseidfield', 'tool_trigger'));
        $mform->setType('courseidfield', PARAM_INT);
        $mform->addRule('courseidfield', get_string('required'), 'required');
        $mform->setDefault('courseidfield', '0');
    }

    /**
     * Get a list of fields this step provides.
     *
     * @return array $stepfields The fields this step provides.
     */
    public static function get_fields() {
        return self::$stepfields;
    }
}
