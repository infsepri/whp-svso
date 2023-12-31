/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = lang_abbr;
	// config.uiColor = '#AADC6E';

	config.toolbarGroups = [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'links' },
        { name: 'insert' },
        { name: 'forms' },
        { name: 'tools' },
        { name: 'document',       groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },

        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },

        { name: 'styles' },
        { name: 'colors' },
        { name: 'others' }
    ];

    config.colorButton_enableAutomatic = true;
    config.colorButton_enableMore = true;
    config.extraPlugins = 'colordialog';

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Subscript,Superscript,Image,Source';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';
};
