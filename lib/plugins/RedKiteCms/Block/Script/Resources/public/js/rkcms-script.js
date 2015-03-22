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

var Script = function (params)
{
    var self = this;
    Extendable.call(self, params);
    self.html = ko.observable(self.block.html);
    self.isScriptExecuted = ko.observable(params.block.is_new != undefined ? false : true);

    _initScriptEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { mode: 'html' });
    };

    $(document).on("rkcms.event.blocks_editor_started", function(){
        self.isScriptExecuted(false);
    });

    $(document).on("rkcms.event.blocks_editor_stopped", function(){
        self.isScriptExecuted(true);
    });
};

Script.prototype = Object.create(Extendable.prototype);
Script.prototype.constructor = Script;
Script.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    _initScriptEditor();
};

Script.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.html = self.html();
    block.source = self.source;

    return ko.toJSON(block);
};

Script.prototype.restore = function(archivedBlock)
{
    this.html(archivedBlock.html);
};

Script.prototype.update = function(newValue, source)
{
    var self = this;
    self.html(source);
    self.source = source;
};

ko.components.register('rkcms-script', {
    viewModel: Script,
    template: { element: 'rkcms-script-editor' }
});

