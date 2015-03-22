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

var Menu = function (params)
{
    var self = this;
    ExtendableCollection.call(self, params);
    self.toolbar.push("permalinks", "link-button", "icon-button", "icon-linked-button", "icon-stacked-button");

    _initMenuEditor = function(){
        $(".rkcms-ace-editor:visible").aceEditor('open', { height: '350px' });
    };
};

Menu.prototype = Object.create(ExtendableCollection.prototype);
Menu.prototype.constructor = Menu;
Menu.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)) {
        return true;
    }

    _initMenuEditor();
};

ko.components.register('rkcms-menu', {
    viewModel: Menu,
    template: { element: 'rkcms-menu-editor' }
});