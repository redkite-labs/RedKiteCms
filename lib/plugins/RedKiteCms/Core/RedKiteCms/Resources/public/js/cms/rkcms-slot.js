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


var SlotModel = function (blocks, slotName, next)
{
    var self = this;
    HighlightableModel.call(self);

    self.slotName = slotName;
    self.blocks = ko.observableArray(blocks);
    self.next = ko.observable(next);
    self.transactionIcon = ko.observable(false);
    self.addBlockPanel = ko.observable(false);
    self.empty = ko.computed(function() {
        return self.blocks() == 0
    });

    /*
    self.save = function () {
        try{
            var savedData = ko.toJSON(self);
        }catch(ex)
        {
            console.log(ex);
        }
        localStorage.setItem('rkcms-' + self.slotName, savedData);
    };

    //localStorage.clear();
    if (localStorage && localStorage.getItem('rkcms-' + slotName)) {console.log('p');
        var retrievedData = JSON.parse(localStorage.getItem('savedData'));
        ko.mapping.fromJS(retrievedData, self);
    } else {

        console.log('a');
        self.save();
    }*/
};

SlotModel.prototype = Object.create(HighlightableModel.prototype);
SlotModel.prototype.constructor = SlotModel;
SlotModel.prototype.highlight = function()
{
    if ( ! this.active)
    {
        return true;
    }

    HighlightableModel.prototype.highlight.call(this);
};

SlotModel.prototype.showPanel = function()
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