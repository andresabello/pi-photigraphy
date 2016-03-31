(function() {
    tinymce.create('tinymce.plugins.piPhotography', {
        init : function(ed, url) {
 
            ed.addButton('grid', {
                type: 'listbox',
                title : 'Add a Content Column',
                onselect: function(e) {}, 
                values: [
                 
                    {text: '1 Column', onclick : function() {
                        tinymce.execCommand('mceInsertContent', false, '[col size="1"][/col]');
                    }},
                     
                    {text: '2 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="2"][/col]');
                    }},

                    {text: '3 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="3"][/col]');
                    }},

                    {text: '4 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="4"][/col]');
                    }},

                    {text: '5 Columns', onclick : function() {
                        tinymce.execCommand('mceInsertContent', false, '[col size="5"][/col]');
                    }},
                     
                    {text: '6 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="6"][/col]');
                    }},

                    {text: '7 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="7"][/col]');
                    }},

                    {text: '8 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="8"][/col]');
                    }},
                    {text: '9 Columns', onclick : function() {
                        tinymce.execCommand('mceInsertContent', false, '[col size="9"][/col]');
                    }},
                     
                    {text: '10 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="10"][/col]');
                    }},

                    {text: '11 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="11"][/col]');
                    }},

                    {text: '12 Columns', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="12"][/col]');
                    }},

                    {text: 'Full Width Block', onclick : function() {
                         tinymce.execCommand('mceInsertContent', false, '[col size="full"][/col]');
                    }}

                ]
            });
 
            // ed.addCommand('grid', function() {
            //   var selected_text = ed.selection.getContent();
            //     var return_text = '';
            //     return_text = '<span class="grid">' + selected_text + '</span>';
            //     ed.execCommand('mceInsertContent', 0, return_text);
            // });
        },
        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Create Grid Button',
                author : 'Andres Abello',
                authorurl : 'http://andresabello.com',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version : "0.1"
            };
        }
    });
    // Register plugin
    tinymce.PluginManager.add( 'piPhotography', tinymce.plugins.piPhotography );
})();

