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

function DockableModel()
{
    var self = this;
    self.dockStatus = ko.observable();

    _dockStatusFromElement = function(target)
    {
        var element = $(target);
        var position = element.offset();
        if(position == undefined) {
            return null;
        }

        var dockStatus = null;
        if (position.left < $('#rkcms-blocks-editor-panel').width()){
            dockStatus = "right";
        }

        if (screen.width - position.left + element.width() < $('#rkcms-blocks-editor-panel').width()){
            dockStatus = "left";
        }

        return dockStatus;
    }.bind(self);
}

DockableModel.prototype.dock = function()
{
    var self = this;

    var dockStatus = _dockStatusFromElement(self.activeModel.target());

    if (null == dockStatus) {
        self.dockLeft();

        return;
    }

    if (dockStatus == 'right') {
        self.dockRight();

        return;
    }

    self.dockLeft();
};

DockableModel.prototype.dockLeft = function ()
{
    var dockStatus = 'left';
    this.dockStatus(dockStatus);
    $('.rkcms-blocks-panel').addClass('rkcms-dock-left').removeClass('rkcms-dock-right');
    $('body').data('rkcms.dock-status', dockStatus);
    this.dockStatus('left');
};

DockableModel.prototype.dockRight = function ()
{
    var dockStatus = 'right';
    this.dockStatus(dockStatus);
    $('.rkcms-blocks-panel').addClass('rkcms-dock-right').removeClass('rkcms-dock-left');
    $('body').data('rkcms.dock-status', dockStatus);
    this.dockStatus('right');
};