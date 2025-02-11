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

namespace qbank_search;

/**
 * Class helper
 *
 * @package    qbank_search
 * @copyright  2025 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    public static function search_questions($fromform) {
        global $DB;
        $qids = [];

        [$categoryid, $contextid] = explode(',', $fromform->category);
        if($fromform->includesubcategories == 1){
            $questioncontext = \context::instance_by_id($contextid, MUST_EXIST);
            $childcontexts = $questioncontext->get_child_contexts();
            $children = [];
            foreach($childcontexts as $context) {
                $children[] = \question_bank::get_finder()->get_questions_from_categories([$context->id], "");
            }
            foreach($children as $child) {
                $qids = array_merge($qids, $child);
            }
        }
        $questionids = \question_bank::get_finder()->get_questions_from_categories([$categoryid], "");
        $questionids = array_merge($questionids, $qids);
        list($usql, $params) = $DB->get_in_or_equal($questionids);

        $sql = "SELECT q.id, q.questiontext, c.contextid
                  FROM {question} q
                  JOIN {question_versions} qv ON qv.questionid = q.id
                  JOIN {question_bank_entries} qbe ON qbe.id = qv.questionbankentryid
                  JOIN {question_categories} c ON c.id = qbe.questioncategoryid
                 WHERE q.id
                 {$usql} and q.questiontext like ?";
        $params[] = "%$fromform->searchterm%";
        $matches = $DB->get_records_sql($sql, $params);
        return $matches;
    }
}
