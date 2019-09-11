<?php

defined('MOODLE_INTERNAL') || die();

// ---- Gera formuláro
// include_once('seox_leanblocks_form.php');
// $seox_leanblocks_form = new seox_leanblocks_form();
// $seox_leanblocks_form->display();

require_once('../../config.php');
require_once($CFG->dirroot . '/question/editlib.php');

$returnurl = '/blocks/seox_leanblocks/view.php';
$courseid = 2;
$categoryid = 8;
$params = array(
	'returnurl' => $returnurl,
	'courseid'	=> $courseid,
);
echo '<div class="createnewquestion">';
create_new_question_button($categoryid, $params, get_string('seox_leanblocks:question_botton_title', 'block_seox_leanblocks'));
echo '</div>';