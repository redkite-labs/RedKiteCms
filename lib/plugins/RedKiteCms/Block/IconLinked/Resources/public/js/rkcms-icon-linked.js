
var IconLinked = function (params)
{
    var self = this;
    ExtendableCollection.call(self, params);

    self.toolbar.push("permalinks");

    self.setActive = function() {
        return self.block.tags.href == pathInfo ? 'active' : '';
    }.bind(self);
};

IconLinked.prototype = Object.create(ExtendableCollection.prototype);
IconLinked.prototype.constructor = IconLinked;

ko.components.register('rkcms-icon-linked', {
    viewModel: IconLinked,
    template: { element: 'rkcms-icon-linked-editor' }
});

