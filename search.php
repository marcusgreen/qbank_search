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
 * TODO describe file search
 *
 * @package    qbank_search
 * @copyright  2025 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//require('../../../config.php');
require_once(__DIR__ . '/../../../config.php');

require_once($CFG->dirroot . '/question/editlib.php');
require_login();
use qbank_search\form\question_search_form;

require_login();

$url = new moodle_url('/question/bank/search/search.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$defaultcategory = 0;

list($thispageurl, $contexts, $cmid, $cm, $module, $pagevars) =
    question_edit_setup('categories', '/question/bank/search/search.php');
list($catid, $catcontext) = explode(',', $pagevars['cat']);
$category = $DB->get_record('question_categories', ["id" => $catid, 'contextid' => $catcontext], '*', MUST_EXIST);
$courseid = optional_param('courseid', 0, PARAM_INT);

$form = new question_search_form($thispageurl,
     ['contexts' => $contexts->having_one_edit_tab_cap('export'), 'defaultcategory' => $pagevars['cat']]);



$PAGE->set_context(context_system::instance());

$PAGE->set_heading($SITE->fullname);

if ($fromform = $form->get_data()) {
    if (isset($fromform->submitbutton)) {
        xdebug_break();
        $msg = 'No matches found';
        \core\notification::add($msg, \core\notification::SUCCESS);
        [$categoryid,$context] = explode(',', $fromform->category);
         $sql = "SELECT q.id,q.name,q.questiontext FROM {question} q
                JOIN {question_versions} qv ON qv.questionid = q.id
                JOIN {question_bank_entries} qbe ON qbe.id = qv.questionbankentryid
                JOIN {question_categories} qc ON qc.id = qbe.questioncategoryid
                WHERE qc.id = :categoryid";
                $questions = $DB->get_records_sql($sql, ['categoryid' => $categoryid]);
       }
}
echo $OUTPUT->header();
xdebug_break();

$form->display();
xdebug_break();

echo $OUTPUT->footer();
