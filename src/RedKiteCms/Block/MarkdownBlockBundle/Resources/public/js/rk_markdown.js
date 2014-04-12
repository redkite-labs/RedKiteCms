/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

(function($){
    "use strict";

    var MarkdownEditor = function(element, options) {
        this.$element    = $(element);
        this.savedContent = this.$element.html();
    };

    function _renderToolbar(mardownEditor, element)
    {
        var toolbar = document.createElement("DIV");
        var saveButton = document.createElement("BUTTON");
        var closeButton = document.createElement("BUTTON");

        $(toolbar)
            .addClass('markdown-toolbar')
        ;

        $(saveButton)
            .html('Save')
            .addClass('btn btn-primary')
            .appendTo(toolbar)
            .on('click', function(){
                mardownEditor.save();
            })
        ;

        $(closeButton)
            .html('Close')
            .addClass('btn')
            .appendTo(toolbar)
            .on('click', function(){
                mardownEditor.hide();
            })
        ;

        element.after(toolbar);
        mardownEditor.toolbar = toolbar;
    }

    MarkdownEditor.prototype.startEditor = function () {
        var _element = this.$element.attr('id');
        var defaultContentSource = this.$element.find('p');
        if (!defaultContentSource) {
            defaultContentSource = this.$element;
        }
        var editor    = this.$element.data('rk.markdown_editor.editor');
        if (!editor) {
            var opts = {
                container: _element,
                textarea: null,
                basePath: '/bundles/markdownblock/js/vendor/EpicEditor',
                clientSideStorage: true,
                localStorageName: _element,
                useNativeFullscreen: true,
                parser: marked,
                file: {
                    name: _element,
                    defaultContent: defaultContentSource.html(),
                    autoSave: 100
                },
                theme: {
                    base: '/themes/base/epiceditor.css',
                    preview: '/themes/preview/preview-dark.css',
                    editor: '/themes/editor/epic-dark.css'
                },
                button: {
                    preview: true,
                    fullscreen: true,
                    bar: "auto"
                },
                focusOnLoad: false,
                shortcut: {
                    modifier: 18,
                    fullscreen: 70,
                    preview: 80
                },
                string: {
                    togglePreview: 'Toggle Preview Mode',
                    toggleEdit: 'Toggle Edit Mode',
                    toggleFullscreen: 'Enter Fullscreen'
                },
                autogrow: false
            };

            this.$element.data('rk.markdown_editor.editor', (this.editor = new EpicEditor(opts)));
        }

        this.$element.highligther('deactivate');
        this.editor.load();
        this.initialContent = this.editor.exportFile();

        _renderToolbar(this, this.$element);
    };

    MarkdownEditor.prototype.save = function () {
        var markdown = this;
        this.editor.save();
        var content = this.editor.exportFile();

        $(document).EditBlock('Content', content, null, function(){
            markdown.toolbar.remove();
            markdown.$element.removeAttr('style');
        });
    };

    MarkdownEditor.prototype.hide = function () {
        var content = this.editor.exportFile();
        if (content != this.initialContent) {
            if (!confirm('Looks like there are unsaved changes: are you sure you really want to close the editor without saving those changes?'))
            {
                return;
            }
        }

        this.editor.save();
        this.editor.unload();
        this.toolbar.remove();
        this.$element
            .removeAttr('style')
            .html(this.savedContent)
        ;
    };

    // MARKDOWN EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.markdown;

    $.fn.markdown = function () {
        return this.each(function () {
            var $this = $(this);
            var markdownEditor    = $this.data('rk.markdown_editor');
            if (!markdownEditor) {
                $this.data('rk.markdown_editor', (markdownEditor = new MarkdownEditor(this)));
            }

            markdownEditor.startEditor();
        });
    };

    $.fn.markdown.Constructor = MarkdownEditor;

    // MARKDOWN EDITOR NO CONFLICT
    // ===========================
    $.fn.markdown.noConflict = function () {
        $.fn.markdown = old;
        return this;
    };

    // MARKDOWN EDITOR  DATA-API
    // =========================
    $(document).on('startEditingBlocks', function(e, block){
        if (block.attr('data-type') != 'Markdown') {
            return;
        }

        block.markdown();
    });

    $(document).on("blockDeleted", function(event, block)
    {
        if (!block || block.attr('data-type') != 'Markdown') {
            return;
        }

        var markdown = block.data('rk.markdown_editor');
        if (markdown) {
            markdown.toolbar.remove();
        }
    });
})(jQuery);