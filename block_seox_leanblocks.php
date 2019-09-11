<?php
class block_seox_leanblocks extends block_base {

    public function init() {
        $this->title = get_string('seox_leanblocks', 'block_seox_leanblocks');
    }

    public function get_content() {
	    if ($this->content !== null) {
	      return $this->content;
	    }
	    $this->content         =  new stdClass;
	    $this->content->text   = self::load_addquestion_link_html();
	    $this->content->footer = '';
	 
	    return $this->content;
	}

	public function applicable_formats() {
		return array();
	}

	public function _self_test() {
		return true;
	}

	private function load_addquestion_link_html() {
		global $OUTPUT, $CFG;
				
		ob_start();
		include('templates/add_question_button.php');
		return ob_get_clean();
	}
}