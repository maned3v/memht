tinyMCEPopup.requireLangPack();

var MemotionsDialog = {
	init : function() {
		var f = document.forms[0];

		// Get the selected contents as text and place it in the input
		//f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		//f.somearg.value = tinyMCEPopup.getWindowArg('some_custom_arg');
	},	

	insert : function(code) {
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, code);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(MemotionsDialog.init, MemotionsDialog);
