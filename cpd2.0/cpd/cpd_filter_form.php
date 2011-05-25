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
 * Defines the CPD filter form
 *
 * @package   admin-report-cpd                                               
 * @copyright 2010 Kineo open Source                                         
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class cpd_filter_form extends moodleform 
{
	function definition() 
	{
		$mform    =& $this->_form;
		if (isset($this->_customdata['cpd_years']))
		{
			$this->_customdata['cpd_years'] = array('' => '--') + $this->_customdata['cpd_years'];
			$mform->addElement('select', 'cpdyearid', 'CPD Year', $this->_customdata['cpd_years']);
		}
		$mform->addElement('header', 'filterby', 'Filter by');
		
		$mform->addElement('checkbox', 'filterbydate', 'Filter by date range');
		$mform->addElement('date_selector', 'from', 'Date from', array('startyear'=>(date('Y')-5), 'stopyear'=>(date('Y')+5), 'optional' => false) );
		$mform->addElement('date_selector', 'to', 'Date to', array('startyear'=>(date('Y')-5), 'stopyear'=>(date('Y')+5), 'optional' => false) );
		$mform->disabledIf('from', 'filterbydate');
		$mform->disabledIf('to', 'filterbydate');
		
		if (isset($this->_customdata['activity_types']))
		{
			$this->_customdata['activity_types'] = array('' => '--') + $this->_customdata['activity_types'];
			$mform->addElement('select', 'activitytypeid', 'Activity Type', $this->_customdata['activity_types']);
		}
		
		if (isset($this->_customdata['userid']))
		{
			$mform->addElement('hidden', 'userid', $this->_customdata['userid']);
		}
		else if (isset($this->_customdata['users']))
		{
			$users[''] = '--';
			foreach ($this->_customdata['users'] as $user)
			{
				$users[$user->id] = $user->firstname . ' ' . $user->lastname;
			}
			$mform->addElement('select', 'userid', 'User', $users);
		}
		
		$mform->addElement('submit', 'submitbutton', 'View');
		
	}
	
}
?>
