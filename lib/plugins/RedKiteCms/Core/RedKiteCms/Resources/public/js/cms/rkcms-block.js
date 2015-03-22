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

var ListItemBlock = function (parent, block)
{
    var self = this;

    self.parent = parent;
    self.block = block;
    self.isDirty = false;
};

ListItemBlock.prototype.restore = function(view, event)
{
    $(event.target)
        .unbind("rkcms.event.list_element_selected")
        .unbind("rkcms.event.list_element_deselected")
        .on("rkcms.event.list_element_selected", function(){
            view.isDirty = true;
            view.parent.cloneBlock = view.parent;
            view.parent.activeListItemBlock = view;
            view.parent.restore(view.block);
            view.parent.resize();
        })
        .on("rkcms.event.list_element_deselected", function(){
            blockEditorModel.undoRestoration();
        })
        .selectOnce('toggle', {
            cssClass: 'rkcms-selected-block'
        })
    ;
};

var ArchiveBlock = function (parent, block)
{
    var self = this;
    ListItemBlock.call(self, parent, block);

    self.historyName = '';
    if (null != block.history_name) {
        self.historyName = block.history_name;
    }
};

ArchiveBlock.prototype = Object.create(ListItemBlock.prototype);
ArchiveBlock.prototype.constructor = ArchiveBlock;

var Block = function (params)
{
    var self = this;
    HighlightableModel.call(self);

    self.parent = params.parent;
    self.block = params.block;
    self.target = ko.observable(null);
    self.type = self.block.type;
    self.name = self.block.name;
    self.slotName = self.block.slot_name;
    self.cssClass = ko.observable(self.block.cssClass);
    self.cloneBlock = null;
    self.activeListItemBlock = null;
    self.history = ko.observableArray();
    self.toolbar = ko.observableArray();
    self.isRemoved = ko.computed(function(){
        return params.block.isRemoved != undefined && params.block.isRemoved;
    });

    // Initialization
    // ==============
    self.initHistory(self.block.history);
};

Block.prototype = Object.create(HighlightableModel.prototype);
Block.prototype.constructor = Block;
Block.prototype.startBlockEditing = function()
{
    if (!this.parent.active) {
        return true;
    }

    blockEditorModel
        .setModel(this)
        .openEditor()
    ;

    $(document).data('rkcms-active-model', this);
};

Block.prototype.highlight = function()
{
    if ( ! this.parent.active)
    {
        return true;
    }

    HighlightableModel.prototype.highlight.call(this);
};


Block.prototype.initHistory = function(values, model)
{
    if (null == values) {
        return;
    }

    if (null == model) {
        model = this;
    }

    model.history([]);
    ko.utils.arrayForEach(values, function(block){
        if (typeof block == 'string') {
            block = ko.utils.parseJson(block);
            if (typeof block != 'object') {
                return false;
            }
        }

        model.history.push(new ArchiveBlock(model, block));
    });
};

Block.prototype.blockToJson = function()
{
    alertDialog(redkitecmsDomain.frontend_missing_block_to_json_method_implementation, null, 'danger');
};

Block.prototype.restore = function(archivedBlock)
{
    alertDialog(redkitecmsDomain.frontend_missing_restore_method_implementation, null, 'danger');
};

Block.prototype.resize  = function()
{
    var self = this;
    if (self.target() != null && $('.rkcms-block-menu').is(':visible')) {
        window.setTimeout(function(){
            $(self.target()).highlight('resize');
        }, 10);
    }

    return true;
};

Block.prototype.restoreBlock = function()
{
    self = this;
    if (self.activeListItemBlock == null) {
        return;
    }

    $(document).trigger("rkcms.event.restoring_block", [ self ]);

    var url = frontcontroller + '/backend/block/restore';
    var data = {
        'name': self.name,
        'type': self.type,
        'slot': self.slotName,
        'archiveFile': self.activeListItemBlock.historyName,
        'page':  page,
        'language': language,
        'country': country,
        'data': self.blockToJson()
    };
    executeAjax(url, data,
        function(response)
        {
            self.history().length = 0;
            self.initHistory(response, self);
            blockEditorModel.history(self.history());
            $(document).trigger("rkcms.event.block_restored", [ self ]);
        }
    );
};