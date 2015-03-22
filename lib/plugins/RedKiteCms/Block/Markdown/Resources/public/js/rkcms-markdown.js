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

var Markdown = function (params)
{
    var self = this;
    Extendable.call(self, params);
    self.html = ko.observable(self.block.html);
    self.markdown = ko.observable(self.block.markdown);
    self.toolbar.push("image-button", "permalinks", "preview", "toggle-editor");

    _initMarkdownEditor = function()
    {
        $(self.target()).unbind("rkcms.event.in_place_preview").on("rkcms.event.in_place_preview", function(){
            Highlight();
        });

        $(".rkcms-ace-editor:visible").aceEditor('open', { mode: 'markdown', width: 650, height: 500 });
    };
};


Markdown.prototype = Object.create(Extendable.prototype);
Markdown.prototype.constructor = Markdown;
Markdown.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    _initMarkdownEditor();
};

Markdown.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.html = self.html();
    block.source = self.source;
    block.markdown = self.markdown;

    Highlight();
    self.resize();
    window.setTimeout(function(){
        $(".rkcms-ace-editor:visible").aceEditor('place');
    }, 100);

    return ko.toJSON(block);
};

Markdown.prototype.restore = function(archivedBlock)
{
    this.html(archivedBlock.html);
};

Markdown.prototype.update = function(newValue, source)
{
    var self = this;
    self.html(markdown.toHTML(source));
    self.source = source;
    self.markdown(source);
};

ko.components.register('rkcms-markdown', {
    viewModel: Markdown,
    template: { element: 'rkcms-markdown-editor' }
});

