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
;var Text = function (params)
{
    var self = this;
    Block.call(self, params);
    self.html = ko.observable(self.block.html);
    self.highlightable = true;
    self.isActive = false;

    _save = function(element, content)
    {
        element.html(content);
        blockEditorModel.edit();
    };
    
    _init = function (element, obj)
    {
        var selector = '[data-slot-name="' + element.slotName + '"][data-name="' + element.name + '"]';

        tinymce.init({
            selector: selector,
            inline: true,
            image_advtab: true,
            convert_urls: false,
            relative_urls: true,
            menubar: false,
            extended_valid_elements: "i[*],span[class=fa-stack],span[class=glyphicon]",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code ",
                "insertdatetime media table contextmenu save"
            ],
            toolbar: "save | styleselect | bold italic | bullist numlist | link image table | code",
            link_list : frontcontroller + '/backend/page/permalinks',
            init_instance_callback : function(editor)
            {
                // Forces to display the editor
                editor.focus();
            },
            setup: function(editor) {
                editor.on("focus", function(event)
                {
                    event.stopPropagation();
                    // Saves current editor
                    $(document).data('rkcms-active-mce-editor', editor);
                    self.isActive = true;
                });
                editor.on("blur", function(event)
                {
                    // Saves when any change has been made
                    if (!editor.isNotDirty) {
                        _save(element, editor.getContent());
                    }
                });                
                editor.on("dblclick", function(event)
                {
                    if (!editor.isNotDirty) {
                        _save(element, editor.getContent());
                    }
                    
                    editor.remove();
                    blockEditorModel.closeEditor();
                });
            },
            save_onsavecallback: function(editor)
            {
                _save(element, editor.getContent());
            },
            file_browser_callback : function (id, value, type, win)
            {
                var url = frontcontroller + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect';
                mediaLibrary(url, function (url)
                {
                    var fieldElm = win.document.getElementById(id);
                    fieldElm.value = url.url;
                });
            }
        });
    };
};

Text.prototype = Object.create(Block.prototype);
Text.prototype.constructor = Text;
Text.prototype.startBlockEditing = function()
{
    var self = this;

    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    self.highlightable = false;
    self.isActive = true;
    _init(self, self.target());
    blockEditorModel.showEditor(false);
    $(self.target()).highlight('close');
};

Text.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.html = self.html();

    return ko.toJSON(block);
};

Text.prototype.restore = function(archivedBlock)
{
    this.html(archivedBlock.html);
};

// Highlights the block only when the editor is not active
Text.prototype.highlight = function (view, event)
{
    var self = this;
    if ( ! self.parent.active)
    {
        return true;
    }

    if (self.highlightable) {
        $(self.target()).highlight('highlight');
    }
};

ko.components.register('rkcms-text', {
    viewModel: Text,
    template: { element: 'rkcms-text-editor' }
});