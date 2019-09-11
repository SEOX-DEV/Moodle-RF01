<?php
include_once($CFG->libdir.'/formslib.php');

class seox_leanblocks_form extends moodleform {

	private $filtros_areas_content;
	private $root_area; 
	private $root_subarea; 
	private $root_assunto; 
 
    function definition() {

        $mform =& $this->_form;

        $this->$filtros_areas_content = array();
        $this->$root_area =  
		$this->$root_subarea =  
		$this->$root_assunto =  

        $this->render_select_dificuldades($mform);
        $this->render_select_filtros_areas($mform);

        $mform->addElement('hidden', 'questionid');
        $this->add_action_buttons();
        $mform->disabledIf('submitbutton', 'md_areas', 'eq', 0);
        $mform->disabledIf('submitbutton', 'md_subareas', 'eq', 0);
        $mform->disabledIf('submitbutton', 'md_assuntos', 'eq', 0);

    }

    protected function render_select_dificuldades($mform) {
    	$dificuldades = array(
		    'Fácil',
		    'Médio',
		    'Difícil',
		);
		$mform->addElement('select', 'md_dificuldades', get_string('seox_leanblocks:dificuldades_titles', 'block_seox_leanblocks'), $dificuldades);
		$mform->addRule('md_dificuldades', null, 'required', null, 'client', false, true);
		// This will select the skills A and B.
		// $mform->getElement('md_skills')->setSelected(array(0, 2));
    }

    protected function render_select_filtros_areas($mform) {

    	$areas = array(0 => '');
    	$subareas = array(0 => '');
    	$assuntos = array(0 => '');

    	$this->get_filtros_areas_content_db($areas, $subareas, $assuntos);

		$mform->addElement('select', 'md_areas', get_string('seox_leanblocks:area_titles', 'block_seox_leanblocks'), $areas);
		$mform->setDefault('md_areas', array('text' => 'sda'));
		$mform->addRule('md_areas', null, 'required', null, 'client', false, true);

		$mform->addElement('select', 'md_subareas', get_string('seox_leanblocks:subarea_titles', 'block_seox_leanblocks'), $subareas);
		$mform->addRule('md_subareas', null, 'required', null, 'client', false, true);
    	
		$mform->addElement('select', 'md_assuntos', get_string('seox_leanblocks:assunto_titles', 'block_seox_leanblocks'), $assuntos);
		$mform->addRule('md_assuntos', null, 'required', null, 'client', false, true);
    }

    protected function get_filtros_areas_content_db(&$root_area, &$root_subarea, &$root_assunto) {
    	global $DB;
    	if ($areas = $DB->get_records('seox_area', array())) {
    		foreach ($areas as $key => $area) {
    			$this->$filtros_areas_content['areas'][$area->id] = array(
    				'name' => $area->name,
    				'subareas' => array(),
    			);
    			$root_area[$area->id] = $area->name;
    		}
		}

		$subarea_temp = array();
		if ($areas and $subareas = $DB->get_records('seox_subarea', array())) {
			foreach ($subareas as $key => $subarea) {
    			$this->$filtros_areas_content['areas'][$subarea->id_area]['subareas'][$subarea->id] = array(
    				'name' => $subarea->name,
    				'assuntos' => array(),
    			);
    			$subarea_temp[$subarea->id] = $subarea->id_area;
    			$root_subarea[$subarea->id] = $subarea->name;
			}
		}

		if ($subareas and $assuntos = $DB->get_records('seox_assunto', array())) {
    		foreach ($assuntos as $key => $assunto) {
    			$this->$filtros_areas_content['areas'][$subarea_temp[$assunto->id_subarea]]['subareas'][$assunto->id_subarea]['assuntos'][$assunto->id] = array('name' => $assunto->name);
    			$root_assunto[$assunto->id] = $assunto->name;
			}
		}
    }

    public function get_filtros_areas_content() {
    	return $this->$filtros_areas_content;
    }

}