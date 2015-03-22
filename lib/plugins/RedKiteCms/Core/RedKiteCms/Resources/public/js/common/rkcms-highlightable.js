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

var HighlightableModel = function ()
{
    var self = this;
    self.active = false;
    self.target = ko.observable(null);
};

HighlightableModel.prototype.highlight = function()
{
    $(this.target()).highlight('highlight');
};

HighlightableModel.prototype.hide = function ()
{
    $(this.target()).highlight('hide');
};

HighlightableModel.prototype.activate = function(value)
{
    this.active = value;
};