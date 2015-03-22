
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

var Link = function (params)
{
    var self = this;
    Extendable.call(self, params);

    self.insertPermalink = function(){
        self.editor.insert($('.rkcms-pages-selector:visible option:selected').val());
    };

    self.toolbar.push("permalinks");
};

Link.prototype = Object.create(Extendable.prototype);
Link.prototype.constructor = Link;

ko.components.register('rkcms-link', {
    viewModel: Link,
    template: { element: 'rkcms-link-editor' }
});