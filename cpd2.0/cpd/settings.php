<?php
// This file is part of CPD Report for Moodle
//
// CPD Report for Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// CPD Report for Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with CPD Report for Moodle.  If not, see <http://www.gnu.org/licenses/>.
 
 
/**
 * Defines the settings of this CPD Report
 *
 * @package   admin-report-cpd                                               
 * @copyright 2010 Kineo open Source                                         
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$ADMIN->add('root', new admin_externalpage('cpdrecord', 'CPD Report', "$CFG->wwwroot/$CFG->admin/report/cpd/index.php", 'report/cpd:userview'));
$ADMIN->add('modsettings', new admin_externalpage('cpdmetadata', 'CPD Report Admin', "$CFG->wwwroot/$CFG->admin/report/cpd/metadata.php", 'report/cpd:superadminview'));
$ADMIN->add('reports', new admin_externalpage('cpdadminview', 'CPD Development Report', "$CFG->wwwroot/$CFG->admin/report/cpd/adminview.php", 'report/cpd:adminview'));

?>
