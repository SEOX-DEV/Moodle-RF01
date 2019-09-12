<?php

require_once('../../config.php');

global $DB, $OUTPUT, $PAGE;

// require_login($course);

$PAGE->set_url('/blocks/seox_leanblocks/view.php', array('id' => $questionid));
$PAGE->set_pagelayout('base');
$PAGE->set_heading(get_string('seox_leanblocks:finalize_question_title', 'block_seox_leanblocks'));

$thisblock_form = new block_seox_leanblocks\form\seox_leanblocks_form();

if($thisblock_form->is_cancelled()) {
	// TODO: deleta questao e redireciona para lugar correto
    // $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    // redirect($courseurl);
} elseif ($fromform = $thisblock_form->get_data()) {
	$isAllClean = true;
	if ($fromform->md_areas == 0) {
	    print_error('seox_leanblocks:invalidarea', 'block_seox_leanblocks', $questionid);
	    $isAllClean = false;
	}
	if ($fromform->md_subareas == 0) {
	    print_error('seox_leanblocks:invalidsubarea', 'block_seox_leanblocks', $questionid);
	    $isAllClean = false;
	}
	if ($fromform->md_assuntos == 0) {
	    print_error('seox_leanblocks:invalidassunto', 'block_seox_leanblocks', $questionid);
	    $isAllClean = false;
	}

	$data = array(
		'id_question' => $fromform->questionid,
		'id_area' => $fromform->md_areas,
		'id_subarea' => $fromform->md_subareas,
		'id_assunto' => $fromform->md_assuntos,
		'dificuldade' => $fromform->md_dificuldades,
		// TODO: add user, createdAt e updatedAt
	);

	if ($isAllClean) {
		if (!$resultid = $DB->insert_record('block_seox_leanblocks', $data)) {
		    print_error('seox_leanblocks:addquestion_dberror_insert', 'block_seox_leanblocks');
		} else {
    		redirect(new moodle_url('/local/navseox', array('qid' => $resultid)));
    		exit;
		}
	}

} else {
	// ---- Pre exibition
	$questionid = required_param('lastchanged', PARAM_INT);
	if (!$question = $DB->get_record('question', array('id' => $questionid))) {
	    print_error('seox_leanblocks:invalidquestion', 'block_seox_leanblocks', $questionid);
	}
	$toform['questionid'] = $questionid;
	$thisblock_form->set_data($toform);
	// ----
	// Exibição padrão
    $site = get_site();
    echo $OUTPUT->header();

    echo html_writer::tag('h3', get_string('seox_leanblocks:finalize_question_details_1', 'block_seox_leanblocks'), null);
	echo '<br>';

	$thisblock_form->display();

	$PAGE->requires->yui_module(
		'moodle-block_seox_leanblocks-filtro_addquestion',
		'M.block_seox_leanblocks.filtro_addquestion.init',
		$thisblock_form->get_filtros_areas_content()
	);

	echo $OUTPUT->footer();
}