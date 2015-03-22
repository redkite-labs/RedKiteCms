/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

(function($){
    "use strict";
    
    var AceEditor = function(element, options)
    {
        var self = this;
        self._element = element;
        self._options = $.extend({}, AceEditor.DEFAULTS, options);
        self._editor = null;
        self._forceEditorInit = false;
        self._timer1 = null;
        self._timer2 = null;
        self._model = null;
        self._visible = true;

        this._placeEditor = function()
        {
            window.setTimeout(function()
            {
                var width, height;
                var target = blockEditorModel.activeModel.target();
                var $target = $(target);

                if (blockEditorModel.mode() == 'inline') {
                    width = self._options.width;
                    height = self._options.height;
                    _resize(width, height);

                    $(".rkcms-blocks-editor:visible").position({
                        of: $target,
                        my: "left-4 top+4",
                        at: "left bottom",
                        collision: "none"
                    })
                    .width(width + 20);

                    return;
                }

                $(".rkcms-blocks-editor:visible").position({
                    of: $target,
                    my: "left top",
                    at: "left top",
                    collision: "none"
                })
                .width($target.width())
                .height($target.height());

                width = $target.width() - 20;
                height = $target.height() - $('.rkcms-blocks-editor-toolbar:visible').height() - 20;
                if (height < 300) {
                    height = self._options.height;
                }
                _resize(width, height);
            }, 1);
        };

        function _resize(width, height)
        {
            var $editor = $(self._element);
            $editor
                .width(width)
                .height(height)
            ;
            self._editor.resize();
        }
    };
    
    AceEditor.DEFAULTS = {
        theme: 'twilight',
        mode: 'yaml',
        width: 450,
        height: 150
    };

    AceEditor.prototype.open = function()
    {
        if(!this._forceEditorInit && this._model != null && this._model == $(document).data('rkcms-active-model')) {
            return;
        }

        $(this._element)
            .width(this._options.width)
            .height(this._options.height)
        ;
        $('.rkcms-ace-editor-error').width(this._options.width);


        if(this._editor != null) {
            this._editor.destroy();
        }

        this._model = blockEditorModel.activeModel;
        if (this._model == null) {
            return;
        }

        var self = this;
        self._editor = ace.edit(self._element);
        self._editor.setTheme("ace/theme/" + self._options.theme);
        self._editor.setFontSize(14);
        self._editor.getSession().setMode("ace/mode/" + self._options.mode);
        self._editor.getSession().setUseWrapMode(true);
        self._editor.on("change", function(event, editor) {
            window.clearTimeout(self._timer1);
            var content = editor.getValue();
            if (self._options.mode != 'yaml')
            {
                self._timer1 = window.setTimeout(_update, 500, content, content);

                return;
            }
            self._timer1 = window.setTimeout(_parseYaml, 500, content);
        });
        self._model.editor = self._editor;
        this._placeEditor(this._editor);

        function _update(content, source)
        {
            var model = self._model;
            window.clearTimeout(self._timer2);
            if (model.source != content) {
                self._forceEditorInit = true;
                if (model.update(content, source) == false){
                    return;
                }
                model.resize();
                self._placeEditor();
                self._timer2 = window.setTimeout(_save, 1500);
            }
        }

        function _parseYaml(content)
        {
            var RkCmsYamlType = new jsyaml.Type('!rkcms', {
                kind: 'sequence',
                construct: function (data) {
                    return data.map(function (string) { return 'rkcms ' + string; });
                }
            });
            var RKCMS_SCHEMA = jsyaml.Schema.create([ RkCmsYamlType ]);

            var msg = "";
            try {
                var obj = jsyaml.load(content, { schema: RKCMS_SCHEMA });
                inspect(obj, false, 10);

                _update(obj, content);
            } catch (err) {
                window.clearTimeout(self._timer2);
                msg = '<p>The yml code you entered is malformed:<br />' + err.message + '</p>';
            }

            blockEditorModel.error(msg);
        }

        function _save()
        {
            blockEditorModel.edit();
        }
    };

    AceEditor.prototype.close = function()
    {
        this._editor.destroy();
    };

    AceEditor.prototype.place = function()
    {
        this._placeEditor();
    };

    AceEditor.prototype.toggle = function()
    {
        $(".rkcms-blocks-editor:visible").toggle();
    };




    
    // YMLEDITOR EDITOR PLUGIN DEFINITION
    // ==================================
    var old = $.fn.aceEditor;

    $.fn.aceEditor = function (command, options) {
        return this.each(function () {
            var $this = $(this);

            var data = $this.data('rkcms.ace_editor');
            var parsedOptions = $.extend({}, AceEditor.DEFAULTS, typeof options == 'object' && options);

            // Rebuilds ace editor only when an editor should be opened and the model has been changed,
            // because just destroying the editor before opening it causes a wrong behavior
            if (data && blockEditorModel.isModelChanged() && command == 'open') {
                data.close();

                // Forces the plugin to rebuild the editor
                data = false;
            }

            if (!data) {
                $this.data('rkcms.ace_editor', (data = new AceEditor(this, parsedOptions)));
            }

            if (typeof command == 'string') {
                data[command]();

                if (command == 'destroy') {
                    $this.removeData('rkcms.ace_editor');
                }
            }
        });
    };

    $.fn.aceEditor.Constructor = AceEditor;

    // YMLEDITOR EDITOR NO CONFLICT
    // ============================
    $.fn.aceEditor.noConflict = function () {
        $.fn.aceEditor = old;

        return this;
    };
    
})(jQuery);