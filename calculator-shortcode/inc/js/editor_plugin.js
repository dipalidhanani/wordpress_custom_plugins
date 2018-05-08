function tinyplugin() {
    return "[my-calculator]";
}

(function() {

    tinymce.create('tinymce.plugins.tinyplugin', {

        init : function(ed, url){
            ed.addButton('tinyplugin', {
                title : 'Insert Calculator',
                onclick : function() {
                    ed.execCommand(
                        'mceInsertContent',
                        false,
                        tinyplugin()
                        );
                },
                text: "Insert Calculator"
            });
        }
    });

    tinymce.PluginManager.add('tinyplugin', tinymce.plugins.tinyplugin);
    
})();
