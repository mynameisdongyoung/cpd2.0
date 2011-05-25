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
 * This page is a report of all CPD Activities.
 *
 * The report can be filtered by CPD Year, Date and User.
 *
 * @package   admin-report-cpd                                               
 * @copyright 2010 Kineo open Source                                         
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/lib/tablelib.php');
require_once "$CFG->libdir/formslib.php";
require_once('cpd_filter_form.php');
require_once('lib.php');

global $CFG, $USER, $PAGE;

// Check permissions.
require_login();
$systemcontext = get_context_instance(CONTEXT_SYSTEM);
require_capability('report/cpd:adminview', $systemcontext);

// Log request
add_to_log(SITEID, "admin", "report capability", "report/cpd/adminview.php");

$print = optional_param('print', NULL);
$download = optional_param('download', NULL);

// Extra columns
$extra_columns['user_name'] = true;

// CPD Report headers
//DK
//Replace old variable name to new one.
//Add Short Description and CEUs
$columns = array(
		'name' => 'Name',
		'objective' => 'Training/Event Title',
		'development_need' => 'Location',
		'description' => 'Short Description',
		'activity' => 'Instructor(s)',
		'activity_type' => 'Activity type',	
		'due_date' => 'Completion Date',
		'start_date' => 'Start date',
		'end_date' => 'End date',
		'status' => 'Status',
		'timetaken' => 'Total Time Spent',
		'ceus' => 'CESu'
		);

if (!empty($download) || !empty($print))
{
	// Filter object
	$filter_data = new stdClass;
	$filter_data->cpdyearid = optional_param('cpdyearid');
	$filter_data->from = optional_param('from');
	$filter_data->to = optional_param('to');
	$filter_data->activitytypeid = optional_param('activitytypeid');
	$filter_data->userid = optional_param('userid');
	
	if (($cpd_records = get_cpd_records($filter_data, false, $extra_columns)) && !empty($download))
	{
		download_csv('cpd_record', $columns, $cpd_records);
		exit;
	}
}

$cpd_years = get_cpd_menu('years');
$activity_types = get_cpd_menu('activity_types');
$users = get_records('user'); // Get all users

$filter = new cpd_filter_form('adminview.php', compact('cpd_years', 'activity_types', 'users'), 'post', '', array('class' => 'cpdfilter'));

if (empty($cpd_records)) {
	if ($filter_data = $filter->get_data())
	{
		if (! ($errors = validate_filter($filter_data)) )
		{
			$cpd_records = get_cpd_records($filter_data, false, $extra_columns);
		}
	}
}

// Print the header.
if (has_capability('report/cpd:superadminview', $systemcontext) && empty($print))
{
	admin_externalpage_setup('cpdadminview');
	// Include styles
	$CFG->stylesheets[] = $CFG->wwwroot.'/admin/report/cpd/css/style.css';
	admin_externalpage_print_header();
}
else
{
	if (empty($print)) {
		$navlinks = array();
		$navlinks[] = array('name' => 'Reports', 'link' => null, 'type' => 'misc');
		$navlinks[] = array('name' => 'CPD Development Report', 'link' => null, 'type' => 'misc');
		$navigation = build_navigation($navlinks);
		// Include styles
		$CFG->stylesheets[] = $CFG->wwwroot.'/admin/report/cpd/css/style.css';
		print_header("CPD Development Report", "CPD Development Report", $navigation);
	} else {
		$CFG->stylesheets[] = $CFG->wwwroot.'/admin/report/cpd/css/print.css';
		// Add JS needed for printing
		require_js(array('yui_dom-event', $CFG->wwwroot.'/admin/report/cpd/js/print.js'));
		print_header();
	}
}

print_heading('CPD Development Report');

if (isset($errors))
{
	echo '<div class="box errorbox errorboxcontent">'. implode('<br />' , $errors) .'</div>';
}

//$filter->set_data();
$filter->display();

if (!empty($cpd_records))
{
	$table = new flexible_table('cpd');
	$table->define_columns(array_keys($columns));
	$table->define_headers(array_values($columns));
	//$table->define_baseurl($reporturlwithdisplayoptions->out());
	
	$table->sortable(false);
	$table->collapsible(false);
	//$table->pageable(true);
	//$table->pagesize(3, count($data));
	$table->column_style_all('white-space', 'normal');
	$table->set_attribute('cellspacing', '0');
	$table->set_attribute('id', 'attempts');
	$table->set_attribute('class', 'generaltable boxalignleft cpd');

	$table->data = $cpd_records;
	$table->setup();
	$table->print_html();
	
	echo '<table class="boxalignleft"><tr>';
	echo '<td>';
	print_single_button('adminview.php', array('download' => 1) + ((array)$filter_data), "Export as CSV", null, null, false);
	echo '</td><td>';
	print_print_button('adminview.php', $filter_data);
	echo '</td></tr></table>';
}

if (has_capability('report/cpd:superadminview', $systemcontext) && empty($print))
{
	admin_externalpage_print_footer();
}
else
{
	print_footer();
}
?>
