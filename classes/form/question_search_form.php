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

namespace qbank_search\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use moodleform;

/**
 * Class question_search_form
 *
 * @package    qbank_search
 * @copyright  2025 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class question_search_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        $defaultcategory = $this->_customdata['defaultcategory'];
        $courseid = $this->_customdata['courseid'];
        $contexts = $this->_customdata['contexts'];
        $mform->addElement('text', 'courseid');
        $mform->setDefault('courseid', $courseid);
        $mform->setType('courseid', PARAM_INT);

        $mform->addElement(
            'questioncategory',
            'category',
            get_string('searchcategory', 'qbank_search'),
            [
                'contexts' => $contexts->having_one_edit_tab_cap('export'),
                'top' => true,
            ]
        );
        $mform->setDefault('category', $defaultcategory);
        $mform->addHelpButton('category', 'exportcategory', 'question');

        $mform->addElement('text', 'searchterm', 'search');
        $mform->setType('search', PARAM_TEXT);
        $this->add_action_buttons(true, get_string('search'));
        $mform->addElement('static', 'matchedquestiontext');
    }
    public function set_data($data){
        $mform = $this->_form;
        $data = (object) $data;
        $mform->getElement('courseid')->setValue($data->courseid);
        if ($data->searchterm) {
            $templateoutput = $this->get_matching_questions($data->matchids, $data->searchterm, $data->courseid);
            $mform->getElement('matchedquestiontext')->setValue($templateoutput);
        }
    }
    public function get_matching_questions(string $matchids, string $searchterm, int $courseid) {
        global $DB, $CFG, $OUTPUT;
        if ($matchids == '') {
            return '';
        }
        $ids = explode("'", $matchids);
        $sql = 'SELECT id, name, questiontext FROM {question} WHERE id IN (' . implode(',', $ids) . ')';
        $matchingquestions = $DB->get_records_sql($sql);

        foreach ($matchingquestions as $question) {
            $pattern = '/(' . preg_quote($searchterm, '/') . ')/i';
            $replacement = '<span class="bg-warning font-weight-bold">$1</span>';
            $question->questiontext = preg_replace($pattern, $replacement, $question->questiontext);
            $question->name = preg_replace($pattern, $replacement, $question->name);
            $editurl = "<a href = $CFG->wwwroot/question/bank/editquestion/question.php?returnurl=/question/edit.php?courseid=$courseid";
            $editurl .= "&deleteall=1&courseid=$courseid";
            $editurl .= "&id=" . $question->id . ">";
            $question->editurl = $editurl;
        }
        $data = ['questions' => array_values($matchingquestions)];

        $templateoutput =  $OUTPUT->render_from_template('qbank_search/questions', $data);
        return $templateoutput;
    }
}
