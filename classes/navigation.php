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
 * Class navigation.
 *
 * Plugin entrypoint for navigation.
 *
 * @package    qbank_search
 * @copyright  2021 Catalyst IT Australia Pty Ltd
 * @author     Guillermo Gomez Arias <guillermogomez@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class navigation extends \core_question\local\bank\navigation_node_base {

    public function get_navigation_title(): string {
        return get_string('search');
    }

    public function get_navigation_node(): ?navigation_node_base {
        return new navigation();
    }

    public function get_navigation_key(): string {
        return 'search';
    }

    public function get_navigation_url(): \moodle_url {
        return new \moodle_url('/question/bank/search/search.php');
    }
}
