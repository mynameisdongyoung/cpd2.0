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
 * Defines the CPD Activity form
 *
 * @package   admin-report-cpd                                               
 * @copyright 2010 Kineo open Source                                         
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class edit_activity_form extends moodleform 
{
	function definition() 
	{
		$mform    =& $this->_form;
		$mform->addElement('header', 'addactivity', 'Add an activity');
		$mform->addElement('hidden', 'cpdyearid', $this->_customdata['cpdyearid']);
		$mform->addElement('hidden', 'process', '1');
		if ($this->_customdata['cpdid'])
		{
			$mform->addElement('hidden', 'id', $this->_customdata['cpdid']); // This updates a CPD Report
		}
		
		$mform->addElement('textarea', 'objective', 'Training/Event Title', array('rows'=>'2', 'cols'=>'40'));
		$mform->addElement('textarea', 'development_need', 'Location', array('rows'=>'2', 'cols'=>'40'));
		$mform->addElement('textarea', 'description', 'Short Description', array('rows'=>'4', 'cols'=>'40'));
		$mform->addElement('textarea', 'activity', 'Instructor(s)', array('rows'=>'2', 'cols'=>'40'));
		
		if ($this->_customdata['activity_types'])
		{
			$mform->addElement('select', 'activitytypeid', 'Activity Type', $this->_customdata['activity_types']);
		}
		
		$mform->addElement('text', 'ceus', 'CEUs', array('rows'=>'2', 'cols'=>'10'));
		
		// Get CPD start and end years
		$startyear = date('Y') - 5;
		$endyear = date('Y') + 5;
		if ($this->_customdata['cpdyear'])
		{
			$startyear = date('Y', $this->_customdata['cpdyear']->startdate);
			$endyear = date('Y', $this->_customdata['cpdyear']->enddate);
		}
		
		for ($i=1; $i<=31; $i++) {
			$days[$i] = $i;
		}
		for ($i=1; $i<=12; $i++) {
			$months[$i] = userdate(gmmktime(12,0,0,$i,15,2000), "%B");
		}
		for ($i=$startyear; $i<=$endyear; $i++) {
			$years[$i] = $i;
		}
		
		$startdate[] = $mform->createElement('select', 'startdate[d]', '', $days);
		$startdate[] = $mform->createElement('select', 'startdate[m]', '', $months);
		$startdate[] = $mform->createElement('select', 'startdate[Y]', '', $years);
		$mform->addGroup($startdate, 'startdate', 'Start Date', array(' '), false);
		
		$duedate[] = $mform->createElement('select', 'duedate[d]', '', $days);
		$duedate[] = $mform->createElement('select', 'duedate[m]', '', $months);
		$duedate[] = $mform->createElement('select', 'duedate[Y]', '', $years);
		$mform->addGroup($duedate, 'duedate', 'Completion Date', array(' '), false);
		
		if ($this->_customdata['statuses']) {
			$mform->addElement('select', 'statusid', 'Status', $this->_customdata['statuses']);
		}
		
		$hours = null;
		$minutes = null;
		for ($i=1; $i<=20; $i++) {
			$hours[$i] = $i;
		}
		for ($i=15; $i<=45; $i+=15) {
			$minutes[$i] = $i;
		}		
		$timetaken[] = $mform->createElement('select', 'timetaken[hours]', '', array('' => '--') + $hours);
		$timetaken[] = $mform->createElement('select', 'timetaken[minutes]', '', array('' => '--') + $minutes);
		$mform->addGroup($timetaken, 'timetaken', 'Total Time Spent', ':', false);
		
		$buttonarray[] = $mform->createElement('submit', 'submitbutton', 'Update');
		$buttonarray[] = $mform->createElement('reset', '', 'Clear');
		$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->closeHeaderBefore('buttonar');
		
		//$mform->addRule('objective', 'Please enter an objective.', 'required', null, 'client');
	}
	
}
?>
