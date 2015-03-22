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


var PageModel = function (blocks, slotName)
{
    var self = this;
    HighlightableModel.call(self);

    self.slotName = slotName;
    self.blocks = ko.observableArray(blocks);
    self.transactionIcon = ko.observable(false);
    self.addBlockPanel = ko.observable(false);
    self.empty = ko.computed(function() {
        return self.blocks() == 0
    });
};

PageModel.prototype = Object.create(HighlightableModel.prototype);
PageModel.prototype.constructor = PageModel;
PageModel.prototype.highlight = function()
{
    if ( ! this.active)
    {
        return true;
    }

    HighlightableModel.prototype.highlight.call(this);
};

PageModel.prototype.showPanel = function()
{
    if ( ! this.active) {
        return true;
    }

    $(document).data('kcms-active-model', this);
    slotEditorModel
        .setModel(this)
        .openEditor()
    ;
};