/**
 * plugin.js
 *
 */

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
