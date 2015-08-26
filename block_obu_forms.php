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
 * Display OBU forms block
 *
 * @package    obu_forms
 * @category   block
 * @copyright  2015, Oxford Brookes University {@link http://www.brookes.ac.uk/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 

class block_obu_forms extends block_base {
    public function init() {
        $this->title = get_string('obu_forms', 'block_obu_forms');
    }
	
	/**
	 * Locations where block can be displayed
	 *
	 * @return array
	 */

    public function applicable_formats() {
        return array('all' => true);
    }
	  
	/**
	 * Allow block multiples
	 * 
	 * @return boolean
	 */
	  
	public function instance_allow_multiple() {
	  return false;
	}

	/**
	 * Allow the block to have a configuration page
	 *
	 * @return boolean
	 */

	public function has_config() {
		return true;
	}
	
	/**
	 * Generate content of the block
	 * 
	 * @global type $DB
	 * @global type $USER
	 * @return type
	 */    
		  
	 public function get_content() {
		global $CFG, $DB, $USER; 
		
		require_once($CFG->dirroot . '/blocks/obu_forms/lib.php');
		require_once($CFG->dirroot . '/blocks/obu_forms/dbquery.php');
		 
		if ($this->content !== null) {
		  return $this->content;
		}

		$this->content =  new stdClass;
		$this->content->text = '';    
								
		$form_list = '';
		$authorisations = get_authorisations($USER->id);
		foreach ($authorisations as $authorisation) {
			$date = date_create();
			date_timestamp_set($date, $authorisation->request_date);
			$request_date = date_format($date, "d-m-y H:i");

			// If the authorisation is overdue, show visual alert (bold text)
			$elapsed_days = (time() - $authorisation->request_date) / 86400;
			if ($elapsed_days >= get_config('block_obu_forms', 'alertdays')) {
				$form_list .= "<span style='font-weight:bold'>" . $request_date . "</span>)";
			}
			else {
				$form_list .= $request_date;  
			}
				
			$data = $DB->get_record('local_obu_forms_data', array('id' => $authorisation->data_id), '*', MUST_EXIST);
			$template = $DB->get_record('local_obu_forms_templates', array('id' => $data->template_id), '*', MUST_EXIST);
			$form = $DB->get_record('local_obu_forms', array('id' => $template->form_id), '*', MUST_EXIST);
			$form_list .= ' '. html_writer::link('/local/obu_forms/process.php?id=' . $authorisation->data_id, $form->formref, array("style"=>"color:red"));
			$form_list .= "<br>";              
		}
					
		if ($form_list) {
			$form_list = "<p><b>" . get_string('requireauthorisation', 'block_obu_forms') . "</b></p>" . $form_list;
			$this->content->text .= $form_list;   
		}
	   
		return $this->content;
	}
}
 






