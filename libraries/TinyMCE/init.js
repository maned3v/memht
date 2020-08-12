/* Advanced */
tinymce.init({
    selector: ".advanced",
    plugins: [
		"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"table directionality template paste textpattern"
	  ],
	toolbar1 : "newdocument | bold italic underline strikethrough | formatselect fontselect fontsizeselect | tablecontrols | hr removeformat",
	toolbar2 : "searchreplace | bullist numlist | undo redo | link unlink anchor image cleanup code | insertdate inserttime | forecolor backcolor | blockquote pagebreak",
	menubar: true,
	skin: 'oxide',
	content_css: 'libraries/MemHT/style/editor.css',
	toolbar_items_size: 'small',
	entity_encoding: "raw",
	force_br_newlines: true,
	force_p_newlines: false,
	element_format: "xhtml",
	forced_root_block: '',
	pagebreak_separator: "[[READMORE]]",
	extended_valid_elements : "iframe[src|width|height|name|align|allowfullscreen|frameborder]"
});
/* Public */
tinymce.init({
    selector: ".public",
    plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table directionality template paste fullpage textpattern"
    ],
    toolbar1: "newdocument | bold italic underline strikethrough | formatselect fontselect fontsizeselect ",
    toolbar2: "searchreplace | bullist numlist | undo redo | link unlink anchor image code | insertdatetime | forecolor backcolor",
    menubar: true,
    skin: 'oxide',
    content_css: 'libraries/MemHT/style/editor.css',
    toolbar_items_size: 'small',
	entity_encoding: "raw",
	force_br_newlines: true,
	force_p_newlines: false,
	element_format: "xhtml",
	invalid_elements : "script,iframe",
	forced_root_block: '',
	pagebreak_separator: "[[READMORE]]"
});

/* Simple */
tinymce.init({
    selector: ".simple",
    plugins: [
            "image link contextmenu autosave code textcolor",
    ],    
    toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | undo redo | link unlink | forecolor backcolor | code",
    menubar: true,
    skin: 'memht',
    content_css: 'libraries/MemHT/style/editor.css',
    invalid_elements : "script,iframe",
    toolbar_items_size: 'small',
	entity_encoding: "raw",
	force_br_newlines: true,
	force_p_newlines: false,
	element_format: "xhtml",
	forced_root_block: '',
	pagebreak_separator: "[[READMORE]]"
});

/* BBCode */
tinymce.init({
    selector: ".bbcode",
    plugins: [
            "bbcode image link contextmenu autosave code textcolor memotions ",
    ],    
    toolbar1: "newdocument | bold italic underline | undo redo | link unlink | image forecolor removeformat | memotions qquote ccode | youtube code",
    menubar: false,
    skin: 'oxide',
    invalid_elements : "script,iframe",
    toolbar_items_size: 'small',
	entity_encoding: "raw",
	force_br_newlines: true,
	convert_fonts_to_spans : false,
	add_unload_trigger: false, 
	paste_remove_styles: true,
	force_p_newlines : false,
	forced_root_block : '',
	remove_redundant_brs : false,
	verify_html : false,
    setup : function(ed) {
    	ed.addButton('qquote', {
            title : 'Quote',
            icon : 'blockquote',
            onclick : function() {
                ed.focus();
                ed.selection.setContent('[quote]...[/quote]');
            }
        });
        ed.addButton('ccode', {
            title : 'Code',
            icon : 'pastetext',
            onclick : function() {
                ed.focus();
                ed.selection.setContent('[code]...[/code]');
            }
        });
        ed.addButton('youtube', {
            title : 'YouTube',
            icon : 'media',
            onclick : function() {
                ed.focus();
                ed.selection.setContent('[youtube]YOUTUBE_ID[/youtube]');
            }
        });
    }    
});