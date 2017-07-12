// Base plugin prototype

var form_cdp_base = { 
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
        var sc = this.shortcodeName.value.replace(/_/g, '-'),
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


