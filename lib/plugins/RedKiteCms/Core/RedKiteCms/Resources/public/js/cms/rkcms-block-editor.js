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

var BlockEditorModel = function ()
{
    var self = this;
    BaseEditorModel.call(self);

    self.historyPanel = ko.observable(false);
    self.transactionAddTop = ko.observable(false);
    self.transactionAddBottom = ko.observable(false);
    self.transactionRemove = ko.observable(false);
    self.dockStatus = ko.observable();
    self.history = ko.observableArray();
    self.source = ko.observable();
    self.error = ko.observable();
    self.toolbar = ko.observableArray();
    self.isHistoryEmpty = ko.computed(function(){
        return self.history().length == 0;
    });

    _add = function(direction)
    {
        var model = self.activeModel;
        $(document).trigger("rkcms.event.adding_block", [ model ]);

        var block = model.block;
        var blocks = model.parent.blocks;

        var type = $('.rkcms-available-blocks .rkcms-selected-block').attr('data-type');
        var objBlock = JSON.parse($('.rkcms-available-blocks .rkcms-selected-block').attr('data-block'));
        var position = blocks.indexOf(block);
        if (direction == "bottom") {
            position += 1;
        }

        var next = parseInt(model.parent.next()) + 1;
        model.parent.next(next);
        objBlock.name = 'block' + next;
        objBlock.slot_name = block.slot_name;

        queue['rkcms-block-add-'  + model.slotName + '-' +  objBlock.name] = {
            'entity' : 'block',
            'action' : 'add',
            'data' :  {
                'type': type,
                'slot': block.slot_name,
                'name': objBlock.name,
                'position': position,
                'page':  page,
                'language': language,
                'country': country,
                'direction': direction
            }
        };

        blocks.splice(position, 0, objBlock);
        $(document).trigger("rkcms.event.block_added", [ model ]);
    }.bind(self);

    _remove = function(event)
    {
        var model = this.activeModel;
        queue['rkcms-block-'  + model.slotName + '-' +  model.name] = {
            'entity' : 'block',
            'action' : 'remove',
            'data' :  {
                'name': model.name,
                'slot': model.slotName,
                'page':  page,
                'language': language,
                'country': country
            }
        };

        model.parent.blocks.remove(model.block);
        self.closeEditor();
    }.bind(self);

    _closeAvailableBlocksPanel = function()
    {
        self.availableBlocksPanel(false);
    }.bind(self);


    _closeHistoryPanel = function()
    {
        self.undoRestoration();
        self.historyPanel(false);
    }.bind(self);

    _closePanels = function()
    {
        _closeAvailableBlocksPanel();
        _closeHistoryPanel();
    }.bind(self);

    _storageName = function()
    {
        return language + "-" + country  + "-" + page + "-" + self.activeModel.slotName + "-" + self.activeModel.name;
    };

    _addToHistory = function(block)
    {
        var activeModel = blockEditorModel.activeModel;
        var date = new Date();
        block.history_name = date.getFullYear() + '-' + (date.getMonth() + 1).padLeft(2) + '-' + date.getDate().padLeft(2) + '-' + date.getHours().padLeft(2) + '.' + date.getMinutes().padLeft(2) + '.' + date.getSeconds().padLeft(2);
        activeModel.history.splice(0, 0, new ArchiveBlock(activeModel, block));

        self.history(activeModel.history());
    };
};

BlockEditorModel.prototype = Object.create(BaseEditorModel.prototype);
BlockEditorModel.prototype.constructor = BlockEditorModel;

BlockEditorModel.prototype.setModel = function(model)
{
    BaseEditorModel.prototype.setModel.call(this, model);

    var history = [];
    var source = "";
    if(this.activeModel != null) {
        this.toolbar(this.activeModel.toolbar());
        history = this.activeModel.history();
        source = this.activeModel.source;
    }
    this.history(history);
    this.source(source);

    return this;
};

BlockEditorModel.prototype.openEditor = function()
{
    slotEditorModel.closeEditor();
    _closePanels();
    BaseEditorModel.prototype.openEditor.call(this);

    var savedMode = localStorage.getItem(_storageName());
    if(savedMode != null) {
        this.mode(savedMode);
    }
    if (this.mode() == 'inplace'){
        $(this.activeModel.target()).highlight('close');
    }
};

BlockEditorModel.prototype.closeEditor = function(keepHighlighted)
{
    BaseEditorModel.prototype.closeEditor.call(this, keepHighlighted);
    _closeHistoryPanel();
};

BlockEditorModel.prototype.openAddPanel = function()
{
    this._keepHighlighted = true;
    this.closeEditor();
    this._keepHighlighted = false;

    this.availableBlocksPanel(true);
    this.dock();
    $('.rkcms-available-blocks').fullHeight();
};

BlockEditorModel.prototype.openHistoryPanel = function()
{
    this._keepHighlighted = true;
    this.closeEditor();
    this._keepHighlighted = false;

    this.historyPanel(true);
    this.dock();
    $('.rkcms-block-history').fullHeight();
};

BlockEditorModel.prototype.closePanel = function()
{
    _closePanels();

    if (this.activeModel != null) {
        this.openEditor();
    }
};

BlockEditorModel.prototype.addTop = function()
{
    _add('top');
};

BlockEditorModel.prototype.addBottom = function(view, event)
{
    _add('bottom');
};

Number.prototype.padLeft = function (n,str){
    return Array(n-String(this).length+1).join(str||'0')+this;
};

BlockEditorModel.prototype.edit = function()
{
    var model = this.activeModel;
    queue['rkcms-block-' + model.slotName + '-' + model.name] = {
        'entity' : 'block',
        'action' : 'edit',
        'data' :  {
            'name': model.name,
            'type': model.type,
            'slot': model.slotName,
            'page':  page,
            'language': language,
            'country': country,
            'data': model.blockToJson()
        }
    };

    var archiveBlockKey = 'rkcms-block-archive-' + model.slotName + '-' + model.name;
    if (queue[archiveBlockKey] == null) {
        var archiveBlock = model.archiveBlock;
        _addToHistory(archiveBlock);
        queue[archiveBlockKey] = {
            'entity' : 'block',
            'action' : 'archive',
            'data' :  {
                'name': model.name,
                'type': model.type,
                'slot': model.slotName,
                'page':  page,
                'language': language,
                'country': country,
                'data': ko.toJSON(archiveBlock)
            }
        };
    }
};

BlockEditorModel.prototype.remove = function(view, event)
{
    var self = this;
    var message = redkitecmsDomain.frontend_confirm_block_remove;
    confirmDialog(message, function(){
        _remove(event, self.transactionRemove);
    });
};

BlockEditorModel.prototype.undoRestoration = function ()
{
    if ( ! this.historyPanel()) {
        return;
    }

    var clonedBlock = this.activeModel.cloneBlock;
    if (clonedBlock == null) {
        return false;
    }

    clonedBlock.restore(clonedBlock.block);
    this.activeModel.resize();
    $('.rkcms-selected-block').removeClass('rkcms-selected-block');
    this.activeModel.activeListItemBlock.isDirty = false;
};

BlockEditorModel.prototype.confirmRestoration = function ()
{
    var self = this;
    var activeModel = blockEditorModel.activeModel;
    if (activeModel.activeListItemBlock == null) {
        return;
    }

    var historyIndex = activeModel.history.indexOf(activeModel.activeListItemBlock);
    if (historyIndex > -1) {
        var archivedBlock = self.history.splice(historyIndex, 1)[0];
        _addToHistory(activeModel.block);
        activeModel.block = archivedBlock.block;

        queue['rkcms-block-restore-' + activeModel.slotName + '-' + activeModel.name] = {
             'entity' : 'block',
             'action' : 'restore',
             'data' :  {
             'name': activeModel.name,
             'type': activeModel.type,
             'slot': activeModel.slotName,
             'archiveFile': activeModel.activeListItemBlock.historyName,
             'page':  page,
             'language': language,
             'country': country,
             'data': activeModel.blockToJson()
             }
         };
    }
};

BlockEditorModel.prototype.changeMode = function()
{
    var mode = this.mode() == 'inline' ? 'inplace' : 'inline';
    localStorage.setItem(_storageName(), mode);
    this.mode(mode);

    var $target = $(this.activeModel.target());
    if (mode == 'inline') {
        $target.highlight('activate');
    } else {
        $target.highlight('close');
    }
    $(".rkcms-ace-editor:visible").aceEditor('place');
};

BlockEditorModel.prototype.toggleEditor = function(a, event)
{
    this.isEditing(!this.isEditing());

    var target = this.activeModel.target();
    if (this.isEditing()) {
        this.showEditor(true);
        $(target).highlight('close').trigger("rkcms.event.in_place_editing", [ target ]);
    } else {
        this.showEditor(false);
        $('.rkcms-preview-toolbar').position({
            of: target,
            my: "right top",
            at: "right top",
            collision: "none"
        });
        $(target).highlight('activate').trigger("rkcms.event.in_place_preview", [ target ]);
    }
};

BlockEditorModel.prototype.insertPermalink = function()
{
    this.activeModel.editor.insert($('.rkcms-pages-selector:visible option:selected').val());
};

BlockEditorModel.prototype.openImagesSelector  = function()
{
    var self = this;

    var url = frontcontroller + '/backend/elfinder/media/connect';
    mediaLibrary(url, function(file, fm){
        self.activeModel.editor.insert(file.url);
    });
};

BlockEditorModel.prototype.insertLink = function()
{
    var link = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    value: \'Displayed value\'\n' +
        '    tags:\n' +
        '      href: \'#\'\n' +
        '    type: Link\n';
    this.activeModel.editor.insert(link);
};

BlockEditorModel.prototype.insertIcon = function()
{
    var icon = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    value: \'Displayed value\'\n' +
        '    tags:\n' +
        '      class: \'fa fa-cog\'\n' +
        '    type: Icon\n';

    this.activeModel.editor.insert(icon);
};

BlockEditorModel.prototype.insertIconLinked = function()
{
    var stackedIcon = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    children:\n' +
        '      item1:\n' +
        '        value: \'Linked icon\'\n' +
        '        tags:\n' +
        '          class: \'fa fa-caret-right\'\n' +
        '        type: Icon\n' +
        '    tags:\n' +
        '      href: \'#\'\n' +
        '    type: IconLinked\n';
    this.activeModel.editor.insert(stackedIcon);
};

BlockEditorModel.prototype.insertIconStacked = function()
{
    var stackedIcon = '  item' + (this.activeModel.children().length + 1) + ':\n' +
        '    children:\n' +
        '      item1:\n' +
        '        tags:\n' +
        '          class: \'fa fa-circle-o fa-stack-2x\'\n' +
        '        type: Icon\n' +
        '      item2:\n' +
        '        tags:\n' +
        '          class: \'fa fa-cog fa-stack-1x\'\n' +
        '        type: Icon\n' +
        '    tags:\n' +
        '      class: \'fa-stack fa-lg\'\n' +
        '    value: \'Stacked icon\'\n' +
        '    type: IconStacked\n';
    this.activeModel.editor.insert(stackedIcon);
};