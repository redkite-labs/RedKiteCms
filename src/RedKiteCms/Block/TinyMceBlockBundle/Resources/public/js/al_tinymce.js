/**
 * This file is part of the RedKite CMS  Application and it is distributed
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

    var TinyMceEditor = function(element, options) {
        this.$element    = $(element);
    };

    function _renderEditorContainer()
    {
        if ($('#rk-tinymce-container').length > 0) {
            return;
        }

        var tinymceContainer = document.createElement("div");
        $(tinymceContainer)
            .attr('id', 'rk-tinymce-container')
            .css('position', 'absolute')
            .css('z-index', '60000')
        ;
        $(document).append(tinymceContainer);
    }

    function _initEditors()
    {
        _initTinyMCE('[data-texteditor-cfg="standard"]');
        _initTinyMCE('[data-texteditor-cfg="simple"]', true);
    }

    function _initTinyMCE(selector, simple)
    {
        var options = {
            selector: selector,
            inline: true,
            image_advtab: true,
            convert_urls: false,
            relative_urls: true,
            fixed_toolbar_container: "#rk-tinymce-container",
            extended_valid_elements: "i[*],span[class=fa-stack],span[class=glyphicon]",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code ",
                "insertdatetime media table contextmenu paste save"
            ],
            toolbar: "save | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            link_list : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_createPermalinksList/' + $('#al_languages_navigator').html(),
            save_onsavecallback: function(editor) {
                var blockId = "#" + editor.id;
                var requireNoParagraphs = ($(blockId).attr('data-texteditor-cfg'));

                $('body').EditBlock('Content', editor.getContent(), null, function(){
                    tinymce.remove(blockId);
                    if (requireNoParagraphs) {
                        $(blockId)
                            .attr('data-texteditor-cfg', 'simple')
                        ;

                        return false;
                    }
                });
            },
            file_browser_callback : function (id, value, type, win) {
                $('<div />').dialogelfinder({
                    url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect',
                    lang : $('#al_available_languages option:selected').val(),
                    width : 840,
                    destroyOnClose : true,
                    commandsOptions: {
                        getfile: {
                            oncomplete: 'destroy'
                        }
                    },
                    getFileCallback: function (url)
                    {
                        var fieldElm = win.document.getElementById(id);
                        fieldElm.value = url.url;
                    }
                });
            }
        };

        if (simple) {
            options = $.extend({}, options, {
                force_br_newlines : true,
                force_p_newlines : false,
                forced_root_block : ''
            });
        }

        tinymce.init(options);
    }

    TinyMceEditor.prototype.show = function () {
        if (this.$element.attr('data-type') != 'Text') {
            return;
        }

        this.$element.highligther('deactivate');
    };

    // TINYMCE EDITOR PLUGIN DEFINITION
    // ==========================
    var old = $.fn.tinymce;

    $.fn.tinymce = function () {
        return this.each(function () {
            var $this = $(this);
            var tinymceEditor    = $this.data('rk.tinymce_editor');
            if (!tinymceEditor) {
                $this.data('rk.tinymce_editor', (tinymceEditor = new TinyMceEditor(this)));
            }

            tinymceEditor.show();
        });
    };

    $.fn.tinymce.Constructor = TinyMceEditor;

    // TiNYMCE EDITOR NO CONFLICT
    // ===========================
    $.fn.tinymce.noConflict = function () {
        $.fn.tinymce = old;
        return this;
    };

    // TiNYMCE EDITOR  DATA-API
    // =========================
    $(document).on('startEditingBlocks', function(e, block){
        if (block.attr('data-type') != 'Text') {
            return;
        }

        block.tinymce();
    });

    $(document).on("cmsStarted", function(event, block)
    {
        _renderEditorContainer();
        _initEditors();
    });

    $(document).on("cmsStopped", function(event, block)
    {
        tinymce.remove();
    });

    $(document).on("blockEdited", function(event)
    {
        _initEditors();
    });

    $(document).on("blockDeleted", function(event)
    {
        _initEditors();
    });
})(jQuery);