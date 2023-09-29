if ( CKEDITOR.env.ie && CKEDITOR.env.version < 9 )
    CKEDITOR.tools.enableHtml5Elements( document );

// The trick to keep the editor in the sample quite small
// unless user specified own height.
CKEDITOR.config.height = 150;
CKEDITOR.config.width = 'auto';
CKEDITOR.config.font_names = 'Arial;Helvetica;Verdana;Comic Sans MS;Tahoma';
CKEDITOR.config.font_defaultLabel = 'Helvetica';

var initSample = ( function() {

    return function() {
        $('.ckeditorelem').each( function () {

            CKEDITOR.replace( this.id, {
                allowedContent : true
            } );

        });
        /*var editorElement = CKEDITOR.document.getById( 'editor' );
        CKEDITOR.replace( 'editor' );*/
    };
} )();
function CKstart() {
    $('.ckeditorelem').each( function () {
		var height = 150;
		if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
        CKEDITOR.replace( this.id, {
            allowedContent : true,
            height: height
        } );

    });

    $('.ckeditorelemsimple').each( function () {
		var height = 150;
		if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
        CKEDITOR.replace( this.id, {
            toolbarGroups: [
                {"name":"basicstyles","groups":["basicstyles"]},
                {"name":"styles","groups":["styles"]},
                {"name": 'colors' },
                { name: 'links' }
            ],
            allowedContent : true,
            height: height
        } );

    });

    $('.ckeditorsimple').each( function () {
		var height = 50;
		if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
        CKEDITOR.replace( this.id, {
            toolbarGroups: [
                {"name":"basicstyles","groups":["basicstyles"]},
                {"name": 'colors' }
            ],
            allowedContent : true,
            height: height
        } );



    });
}

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}

function clearfield(elem){
	try{
		for ( instance in CKEDITOR.instances ){
			for ( el in elem ){
				if($(el).attr('name')==instance.name){
					CKEDITOR.instances[instance].setData('');
				}
			}
		}
	}catch(err){

	}
}
function CKchangevalue(elem, values){

	try{
		CKEDITOR.instances[elem].setData(values);
	}catch(err){

	}

}
