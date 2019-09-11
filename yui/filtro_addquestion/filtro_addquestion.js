var SELECTOR = {
	AREA: '#id_md_areas',
	SUBAREA: '#id_md_subareas',
	ASSUNTO: '#id_md_assuntos',
	DOM_SUBAREA: 'id_md_subareas',
	DOM_ASSUNTO: 'id_md_assuntos',
};

M.block_seox_leanblocks = M.block_seox_leanblocks || {};
var NS = M.block_seox_leanblocks.filtro_addquestion = {};


NS.init = function(params) {
	this.params = params;
    Y.delegate('change', this.populateSubarea, Y.config.doc, SELECTOR.AREA);
    Y.delegate('change', this.populateAssunto, Y.config.doc, SELECTOR.SUBAREA);
    this.removeMissSelections();
};

NS.removeMissSelections = function($) {
	document.getElementById(SELECTOR.DOM_SUBAREA).innerHTML = '<option value="0"></option>';
	document.getElementById(SELECTOR.DOM_ASSUNTO).innerHTML = '<option value="0"></option>';
}

NS.includeOptionsFiltroArea = function(target, target_jq) {
	Object.keys(target).map(function(index) {
		var o = new Option(target[index]['name'], index);
		target_jq.append(o);
	});
}

NS.populateSubarea = function(e) {
	var id_area = $(e.currentTarget._node).val();
	var subarea_jq = $(SELECTOR.SUBAREA);
	var assunto_jq = $(SELECTOR.ASSUNTO);
	subarea_jq.find('option').not('[value="0"]').remove();
	assunto_jq.find('option').not('[value="0"]').remove();

	if (id_area == 0) { return; }

	var subareas = NS.params[id_area]['subareas'];
	NS.includeOptionsFiltroArea(subareas, subarea_jq);
};

NS.populateAssunto = function(e) {
	var id_area = $(SELECTOR.AREA).val();
	var id_subarea = $(e.currentTarget._node).val();
	var assunto_jq = $(SELECTOR.ASSUNTO);
	assunto_jq.find('option').not('[value="0"]').remove();

	if (id_area == 0) { return; }
	if (id_subarea == 0) { return; }

	var assuntos = NS.params[id_area]['subareas'][id_subarea]['assuntos'];
	NS.includeOptionsFiltroArea(assuntos, assunto_jq);
};