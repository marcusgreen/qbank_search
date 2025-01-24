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
class question_search_form extends moodleform{
    public function definition() {
        $mform = $this->_form;
        xdebug_break();

        $defaultcategory = $this->_customdata['defaultcategory'];
        $contexts = $this->_customdata['contexts'];


         $mform->addElement('questioncategory', 'category', get_string('searchcategory', 'qbank_search'),
                 ['contexts' => $contexts, 'top' => true]);
         $mform->setDefault('category', $defaultcategory);
         $mform->addHelpButton('category', 'exportcategory', 'question');


         $mform->addElement('text','search','search');
         $mform->setType('search',PARAM_TEXT);


    }

}
