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

function SlotEditorModel()
{
    var self = this;
    BaseEditorModel.call(self);

    self.transactionIcon = ko.observable(false);
}

SlotEditorModel.prototype = Object.create(BaseEditorModel.prototype);
SlotEditorModel.prototype.constructor = SlotEditorModel;
SlotEditorModel.prototype.openEditor = function()
{
    blockEditorModel.closeEditor();
    BaseEditorModel.prototype.openEditor.call(this);

    this.availableBlocksPanel(true);
    this.dock();
    $('.rkcms-available-blocks').fullHeight();
};

SlotEditorModel.prototype.addBlock = function ()
{
    var self = this;
    self.transactionIcon(true);
    var blocks = self.activeModel.blocks;
    var type = $('.rkcms-available-blocks .rkcms-selected-block').attr('data-type');
    if (null == type) {
        type = "Text";
    }
    var position = 1;

    var slotName = self.activeModel.slotName;
    var url = frontcontroller + '/backend/block/add';
    var data = {
        'type': type,
        'slot': slotName,
        'position': position,
        'page':  page,
        'language': language,
        'country': country,
        'direction': "top"
    };

    executeAjax(url, data,
        function(response)
        {
            response.slot_name = slotName;
            response.is_new = true;
            blocks.splice(position, 0, response);
            self.closeEditor();

            $(document).trigger("rkcms.event.block_added", [ self, response ]);
        },
        null,
        function()
        {
            self.transactionIcon(false);
            self.closeEditor();
        }
    );
};