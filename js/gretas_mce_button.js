console.log('tinyMCE !!!');
(function() {

    /**
     * Question simple courte
     */
    tinymce.PluginManager.add('gretas_mce_button0', function(editor, url) {
        editor.addButton('gretas_mce_button0', {
            text: '[question_simple_courte]',
            icon: false,
            onclick: function() {
                editor.windowManager.open({
                    title: 'Insérer une question simple',
                    body: [{
                            type: 'textbox',
                            name: 'title',
                            label: 'Texte de la question'
                        },
                        {
                            type: 'textbox',
                            name: 'name',
                            label: 'Nom du champ'
                        },
                    ],
                    onsubmit: function(e) {
                        editor.insertContent('[gform_question_simple_courte name="' + e.data.name + '"]' + e.data.title + '[/gform_question_simple_courte]');
                    }
                });
            }
        });
    });

    /**
     * Question simple longue
     */
    tinymce.PluginManager.add('gretas_mce_button1', function(editor, url) {
        editor.addButton('gretas_mce_button1', {
            text: '[question_simple_longue]',
            icon: false,
            onclick: function() {
                editor.windowManager.open({
                    title: 'Insérer une question simple',
                    body: [{
                            type: 'textbox',
                            name: 'title',
                            label: 'Texte de la question'
                        },
                        {
                            type: 'textbox',
                            name: 'name',
                            label: 'Nom du champ'
                        },
                        {
                            type: 'textbox',
                            name: 'rows',
                            label: 'Nombre de lignes pour la réponse'
                        },
                    ],
                    onsubmit: function(e) {
                        editor.insertContent('[gform_question_simple_longue name="' + e.data.name + '" rows="' + e.data.rows + '"]' + e.data.title + '[/gform_question_simple_longue]');
                    }
                });
            }
        });
    });

    /**
     * Choix multiples
     */
    tinymce.PluginManager.add('gretas_mce_button2', function(editor, url) {
        editor.addButton('gretas_mce_button2', {
            text: '[choix_multiples_radio]',
            icon: false,
            onclick: function() {
                var i = 0;
                var ppwin = editor.windowManager.open({
                    title: 'Insérer une question à choix multiples',
                    resizable: true,
                    height: 120,
                    width: 400,
                    body: [{
                            type: 'textbox',
                            name: 'title',
                            label: 'Texte de la question'
                        },
                        {
                            type: 'textbox',
                            name: 'name',
                            label: 'Nom du champ'
                        },
                        {
                            type: 'textbox',
                            name: 'options',
                            label: 'Options (une option par ligne)',
                            multiline: true,
                            resizable: true,
                            height: 60

                        },
                    ],
                    onsubmit: function(e) {
                        console.log(e)
                        console.log(e.data)
                        editor.insertContent('[gform_choix_multiples_radio name="' + e.data.name + '" options="' + e.data.options.split("\n").join('|') + '"]' + e.data.title + '[/gform_choix_multiples_radio]');
                    }
                });
            }
        });
    });

    /**
     * Evaluation 1-5
     */
    tinymce.PluginManager.add('gretas_mce_button3', function(editor, url) {
        editor.addButton('gretas_mce_button3', {
            text: '[evaluation_1-5]',
            icon: false,
            onclick: function() {
                editor.windowManager.open({
                    title: 'Evaluation de 1 à 5',
                    body: [{
                            type: 'textbox',
                            name: 'title',
                            label: 'Texte de la question'
                        },
                        {
                            type: 'textbox',
                            name: 'name',
                            label: 'Nom du champ'
                        },
                    ],
                    onsubmit: function(e) {
                        editor.insertContent('[gform_evaluation_1_5 name="' + e.data.name + '"]' + e.data.title + '[/gform_evaluation_1_5]');
                    }
                });
            }
        });
    });

    /**
     * Choix multiples
     */
    tinymce.PluginManager.add('gretas_mce_button4', function(editor, url) {
        editor.addButton('gretas_mce_button4', {
            text: '[choix_multiples_checkboxes]',
            icon: false,
            onclick: function() {
                var i = 0;
                var ppwin = editor.windowManager.open({
                    title: 'Insérer une question à choix multiples (plusieurs choix possibles)',
                    resizable: true,
                    height: 120,
                    width: 400,
                    body: [{
                            type: 'textbox',
                            name: 'title',
                            label: 'Texte de la question'
                        },
                        {
                            type: 'textbox',
                            name: 'name',
                            label: 'Nom du champ'
                        },
                        {
                            type: 'textbox',
                            name: 'options',
                            label: 'Options (une option par ligne)',
                            multiline: true,
                            resizable: true,
                            height: 60

                        },
                    ],
                    onsubmit: function(e) {
                        console.log(e)
                        console.log(e.data)
                        editor.insertContent('[gform_choix_multiples_checkboxes name="' + e.data.name + '" options="' + e.data.options.split("\n").join('|') + '"]' + e.data.title + '[/gform_choix_multiples_checkboxes]');
                    }
                });
            }
        });
    });

})();