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

var IconStacked = function (params)
{
    var self = this;
    ExtendableCollection.call(self, params);

    initIconStackedEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { height: '350px' });
    };
};

IconStacked.prototype = Object.create(ExtendableCollection.prototype);
IconStacked.prototype.constructor = IconStacked;
IconStacked.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    initIconStackedEditor();
};
IconStacked.prototype.doClosePanel = function(self)
{
    Block.prototype.doClosePanel(self);

    if ($(document).data('rkcms-active-model') != null) {
        initIconStackedEditor();
    }
};

ko.components.register('rkcms-icon-stacked', {
    viewModel: IconStacked,
    template: { element: 'rkcms-icon-stacked-editor' }
});

