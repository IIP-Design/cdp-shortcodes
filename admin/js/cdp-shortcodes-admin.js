// TODO - Need to consider if we want to use the previous Corona version as a whole or modify it. 
// This file needs to be broken out into the individual parts. Temporarily in one file for testing purposes.

(function ($) { 
    
    tinymce.PluginManager.add('cdp_shortcodes', function ( editor ) { 
      
        function getCDPShortcodeName ( e ) { 
            var shortcodeName = {};
            if( e.control && e.control.state ) {
                shortcodeName = e.control.state.data;
            }

            return shortcodeName;
        }

        // menu is written in the footer via php
        function getListofCDPMenuItemsWrittenIntoFooter() {
            var shList = [];
            $.each( cdp_menu, function(i)  {
                shList.push({text: cdp_menu[i], value:i});
            });

            return shList;
        }

        function openCDPShortcodeDialogue( e ) {
            // TODO - Implement javascript files for each shortcode.
            var scn =  getCDPShortcodeName ( e );
            
            var form = Object.create( window['form_' + scn.value]);
            form.shortcodeName = scn;
            form.editor = editor;
            form.open();
        }

        editor.addButton( 'cdp_shortcode', {
            type: 'listbox',
            text: 'Add CDP Shortcode',
            values: getListofCDPMenuItemsWrittenIntoFooter(),
            onselect: function ( e ) {
                openCDPShortcodeDialogue( e );
            } 
        });

    });

})(jQuery);

// Base plugin prototype

var form_base = { 
    width: 500,
    height: 220,
    editor: null,
    cacheReqs: {},

    debug:  function ( msg ) {
        if ( console ) {
            if( _.isObject(msg ) ) {
                console.dir( msg )
            } else {
                console.log( msg );
            }
        }
    },

    addShortcodeToEditor: function ( data ) {
        var sc = this.shortcodeName.value.replace('_', '-'),
            content = '[' + sc,
            value;

        $.each( data, function( name ) {
            value =  data[name];
            if( _.isBoolean( value ) ) {  // need to use either 1 or 0 for booleans for ease with php
                value =  +value;
            }
           content += ' ' + name + '="' + value + '"';
        });

        content += ' ]';
        
        return content;
    },

    ajax: function ( func ) {
        return jQuery.ajax({
            type: 'post',
            context: this,
            url: ajaxVars.url,
            dataType : "json",
            data: {
                action: func,
                security: ajaxVars.tinymceNonce
            }
        })
    },

    open: function () {
        if( this.action ) {
            if( this.cacheReqs[name] ) {
                this.openFormWindow( this.cacheReqs[name] );
            } else {
                this.ajax( this.action )
                
                .done( function(data) {
                    // data =  JSON.parse(data )
                    this.cacheReqs[name] = data;
                    this.openFormWindow( data );
                })
                
                .fail( function(err) {
                    this.debug(err);
                });
            }
        } else {
           this.openFormWindow( {} );
        }
    },

    openFormWindow: function ( data ) {
        var self = this;   // using self reference until I can figure out why bind() is not working

        var args = {
            title: 'Add ' + this.shortcodeName.text,
            body: this.getFields( data ),
            width: this.width,
            height: this.height,
            onsubmit: function( e ) {
                tinyMCE.activeEditor.selection.setContent( self.addShortcodeToEditor(e.data) );
            }
        };

       this.editor.windowManager.open( args );
    }
}

var $ = tinymce.dom.DomQuery;  // tinymce jQuery implementation

var form_course = Object.create( window[ 'form_base' ] );

form_course.height =  200;
form_course.action = "fetch_data_course";

form_course.getFields = function ( data ) {
    var self = this;  // need to use bind not tself

    var fields = [ 
        { 
            type: 'form',
            padding: 0,
            items: [
                {
                    type: 'textbox', 
                    label: 'Course ID:',
                    name: 'id', 
                    maxWidth: 70,
                    onPostRender: function (e) {
                        self.numbersOnly( e.target );
                    } 
                }
            ]
        }
    ];

    return fields;
};

form_course.numbersOnly = function ( obj ) {
    var el =  obj.getEl();
    $(el).attr( 'type', 'number' ); 
    $(el).attr( 'min', '0' );
};
