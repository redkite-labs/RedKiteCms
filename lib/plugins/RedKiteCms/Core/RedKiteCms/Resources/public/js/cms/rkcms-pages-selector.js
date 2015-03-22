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

var PagesSelector = function (params)
{
    var self = this;
    Block.call(self, params);
    self.selected = ko.observable();
    var firstNotifySkipped = false;

    self.selected.subscribe(function(newval){
        if (!firstNotifySkipped) {
            firstNotifySkipped = true;
            
            return;
        }

        self.url(newval);
        self.edit();
    });
};

PagesSelector.prototype = Object.create(Block.prototype);
PagesSelector.prototype.constructor = PagesSelector;