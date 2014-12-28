/* Advanced */
tinymce.init({
    selector: ".advanced",
    plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality memotions template textcolor paste textcolor colorpicker textpattern"
    ],
    //toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect ",
    //toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
    //toolbar3: "table | hr removeformat | subscript superscript | charmap memotions | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
    toolbar1 : "newdocument | bold italic underline strikethrough | justifyleft justifycenter justifyright justifyfull | styleselect formatselect fontselect fontsizeselect | tablecontrols | hr removeformat | sub sup | charmap iespell media advhr | print | cite abbr acronym del ins attribs",
    toolbar2 : "cut copy paste pastetext | searchreplace | bullist numlist | outdent indent | undo redo | link unlink anchor image cleanup code | insertdate inserttime | forecolor backcolor | blockquote pagebreak",
    menubar: true,
    skin: 'memht',
    content_css: 'libraries/MemHT/style/editor.css',
    toolbar_items_size: 'small',
	entity_encoding: "raw",
	force_br_newlines: true,
	force_p_newlines: false,
	element_format: "xhtml",
	forced_root_block: '',
	pagebreak_separator: "[[READMORE]]",
	style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ],
    extended_valid_elements : "iframe[src|width|height|name|align|allowfullscreen|frameborder]"
    /*,
    file_browser_callback : function (field_name, url, type, win) {
        //alert("Field_Name: " + field_name + "nURL: " + url + "nType: " + type + "nWin: " + win);
        tinyMCE.activeEditor.windowManager.open({
            title: "Media",
            url : "libraries/TinyMCE/dialog.php?type="+type,
            width : 800,
            height : 600,
            close_previous : "no"
         }, {
            window : win,
            input : field_name,
            resizable : "yes",
            inline : "yes"
         });
        return false;
    }*/
});
/* Public */
tinymce.init({
    selector: ".public",
    plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality template textcolor paste fullpage textcolor colorpicker textpattern"
    ],
    toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect ",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent | undo redo | link unlink anchor image code | insertdatetime | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap media | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
    menubar: true,
    skin: 'memht',
    content_css: 'libraries/MemHT/style/editor.css',
    toolbar_items_size: 'small',
	entity_encoding: "raw",
	force_br_newlines: true,
	force_p_newlines: false,
	element_format: "xhtml",
	invalid_elements : "script,iframe",
	forced_root_block: '',
	pagebreak_separator: "[[READMORE]]",
	style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
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
    skin: 'memht',
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