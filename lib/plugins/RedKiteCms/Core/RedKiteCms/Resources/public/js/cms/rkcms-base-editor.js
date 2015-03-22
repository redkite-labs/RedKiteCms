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

function BaseEditorModel()
{
    var self = this;
    DockableModel.call(self);

    self.activeModel = null;
    self.showEditor = ko.observable(false);
    self.showToolbar = ko.observable(false);
    self.showSlotsPanel = ko.observable(false);
    self.mode = ko.observable('inline');
    self.isEditing = ko.observable(true);

    self.availableBlocksPanel = ko.observable(false);
    self.isModelChanged = ko.observable(false);
    var _keepHighlighted = false;
}

BaseEditorModel.prototype = Object.create(DockableModel.prototype);
BaseEditorModel.prototype.constructor = BaseEditorModel;

BaseEditorModel.prototype.keepHighlighted = function()
{
    _keepHighlighted = true;

    return this;
};

BaseEditorModel.prototype.openEditor = function()
{
    if (this.showEditor() || !this.isEditing()) {
        this.closeEditor();

        if (!this.isModelChanged()){
            return true;
        }
    }

    this.showEditor(true);
    this.showToolbar(true);
    this.showSlotsPanel(true);
    $(this.activeModel.target()).highlight('activate');
};

BaseEditorModel.prototype.closeEditor = function()
{
    if (this.activeModel != null && ! this._keepHighlighted) {
        $(this.activeModel.target()).highlight('close');
    }

    this.availableBlocksPanel(false);
    this.showEditor(false);
    this.showToolbar(false);
    this.showSlotsPanel(false);
    this.mode('inline');
    this.isEditing(true);
};

BaseEditorModel.prototype.setModel = function(model)
{

    this.isModelChanged(false);
    if (this.activeModel != null && this.activeModel != model) {
        this.isModelChanged(true);
    }

    this.activeModel = model;

    return this;
};

BaseEditorModel.prototype.selectBlock = function (view, event)
{
    $(event.target).selectOnce('toggle',{
        cssClass: 'rkcms-selected-block'
    });
};