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

var Extendable = function (params)
{
    var self = this;
    Block.call(self, params);
    
    self.value = ko.observable(self.block.value);
    self.tags = ko.observable(self.block.tags);
    self.source = self.block.source;
    self.error = ko.observable();
    self.editor = null;
};

Extendable.prototype = Object.create(Block.prototype);
Extendable.prototype.constructor = Extendable;
Extendable.prototype.startBlockEditing = function()
{
    if (Block.prototype.startBlockEditing.call(this)){
        return true;
    }

    $(".rkcms-ace-editor:visible").aceEditor('open');
};

Extendable.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.value = self.value();
    block.tags = self.tags();
    block.source = self.source;

    return ko.toJSON(block);
};

Extendable.prototype.update = function(newValue, source)
{
    var self = this;
    self.value(newValue.value);
    self.tags(newValue.tags);
    self.source = source;
};

Extendable.prototype.restore = function(archivedBlock)
{
    var self = this;
    self.value(archivedBlock.value);
    self.tags(archivedBlock.tags);
    self.source = archivedBlock.source;
};

var ExtendableCollection = function (params)
{
    var self = this;
    Extendable.call(self, params);

    var children = params.block.children;
    if(children != undefined && children.constructor !== Array) {
        children = Object.keys(children).map(function (key) {return children[key]});
    }
    self.children = ko.observableArray(children);
};

ExtendableCollection.prototype = Object.create(Extendable.prototype);
ExtendableCollection.prototype.constructor = ExtendableCollection;

ExtendableCollection.prototype.update = function(newValue, source)
{
    var self = this;
    var save = true;
    var children = [];
    for (var key in newValue.children) {
        if (newValue.children.hasOwnProperty(key)) {
            var child = newValue.children[key];
            if ( ! child.hasOwnProperty('type')) {
                msg = '<p>The property "type: [block type]" is mandatory for each child in a collection block: please add that property to fix the issue. The block was not saved.</p>';
                alertDialog(msg, null, 'warning');
                save = false;

                break;
            }

            children.push(child);
        }
    }

    if (!save) {
        return false;
    }

    self.value(newValue.value);
    self.tags(newValue.tags);
    self.children(children);
    self.source = source;

    return true;
};

ExtendableCollection.prototype.blockToJson = function()
{
    var self = this;
    var block = self.block;
    block.value = self.value();
    block.tags = self.tags();
    block.children = self.children();
    block.source = self.source;

    return ko.toJSON(block);
};

ExtendableCollection.prototype.restore = function(archivedBlock)
{
    var self = this;
    self.value(archivedBlock.value);
    self.tags(archivedBlock.tags);
    self.source = archivedBlock.source;
    self.children(archivedBlock.children);
};
