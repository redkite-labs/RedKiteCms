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

(function($){
    "use strict";

    var activeBlock = false;
    
    var Highlighter = function(element, options) {
        this.$element    = $(element);
        this.options     = $.extend({}, Highlighter.DEFAULTS, options);
    };
    
    Highlighter.DEFAULTS = {
        elements: {
            "top" : '.rkcms-block-menu-top',
            "bottom" : '.rkcms-block-menu-bottom',
            "left" : '.rkcms-block-menu-left',
            "right" : '.rkcms-block-menu-right'
        },
        activeElements: {
            "top" : '.rkcms-active-block-menu-top',
            "bottom" : '.rkcms-active-block-menu-bottom',
            "left" : '.rkcms-active-block-menu-left',
            "right" : '.rkcms-active-block-menu-right'
        }
    };

    Highlighter.prototype.highlight = function ()
    {
        _render(this.$element, this.options.elements);
    };
    
    Highlighter.prototype.activate = function ()
    {
        if (activeBlock != null && activeBlock == this.$element) {

            return;
        }

        $('.rkcms-block-menu-wrapper').hide();
        $('.rkcms-block-menu-active').show();
        _activate(this, this.$element);

        activeBlock = this.$element;
    };
    
    Highlighter.prototype.resize = function (target)
    {
        _activate(this, this.$element);
    };

    Highlighter.prototype.hide = function (force)
    {
        $('.rkcms-block-menu-wrapper').hide();
    };
    
    Highlighter.prototype.close = function (force)
    {
        $('.rkcms-block-menu').hide();
        activeBlock = null;
    };

    function _activate(self, target)
    {
        _render(target, self.options.activeElements);
    }
    
    function _render(target, elements)
    {
        var position = target.offset();
        var blockWidth = target.outerWidth() + 4;
        var blockHeight = target.outerHeight() + 6;

        $(elements['top'])
            .width(blockWidth)
            .css('top', position.top - 2 + 'px')
            .css('left', position.left - 2 + 'px')
            .show()
        ;

        $(elements['bottom'])
            .width(blockWidth)
            .css('top', position.top - 4 + blockHeight + 'px')
            .css('left', position.left - 2 + 'px')
            .show()
        ;

        $(elements['left'])
            .height(blockHeight)
            .css('top', position.top - 2  + 'px')
            .css('left', position.left - 4 + 'px')
            .show()
        ;

        $(elements['right'])
            .height(blockHeight)
            .css('top', position.top - 2 + 'px')
            .css('left', position.left - 2 + blockWidth + 'px')
            .show()
        ;
    }
    
    
    // HIGHLIGHTER EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.selectOnce;

    $.fn.highlight = function (command, options) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('rkcms.highlighter');
            var parsedOptions = $.extend({}, Highlighter.DEFAULTS, typeof options == 'object' && options);
            if (!data) {
                $this.data('rkcms.highlighter', (data = new Highlighter(this, parsedOptions)));
            }

            if (typeof command == 'string') {
                data[command]();

                if (command == 'destroy') {
                    $this.removeData('rkcms.highlighter');
                }
            }
        });
    };

    $.fn.highlight.Constructor = Highlighter;

    // HIGHLIGHTER EDITOR NO CONFLICT
    // ===========================
    $.fn.highlight.noConflict = function () {
        $.fn.highlight = old;

        return this;
    };
})(jQuery);

(function($){
    "use strict";
    
    var ListElementSelector = function(element, options){
        this.$element    = $(element);
        this.options     = $.extend({}, ListElementSelector.DEFAULTS, options);
    };
    
    ListElementSelector.DEFAULTS = {
        cssClass: null
    };

    ListElementSelector.prototype.toggle = function(){   
        if (null == this.options.cssClass) {
            return false;
        }
        
        var isActiveElementSelected = $(this.$element).hasClass(this.options.cssClass);
        this.clean();
        if (isActiveElementSelected) {
            $(this.$element).trigger("rkcms.event.list_element_deselected", []);
            
            return false;
        }
        
        $(this.$element).addClass(this.options.cssClass);
        $(this.$element).trigger("rkcms.event.list_element_selected", []);
        
        return true;
    };

    ListElementSelector.prototype.clean = function(){
        $('.' + this.options.cssClass).removeClass(this.options.cssClass);
    };


        // ELEMENTSELECTOR EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.selectOnce;

    $.fn.selectOnce = function (command, options){
        return this.each(function(){
            var $this = $(this);
            var selector = $this.data('rkcms.elements_selector');
            var parsedOptions = $.extend({}, ListElementSelector.DEFAULTS, typeof options == 'object' && options);
            if (!selector) {
                $this.data('rkcms.elements_selector', (selector = new ListElementSelector(this, parsedOptions)));
            }

            //selector.toggle();
            if (typeof command == 'string') {
                selector[command]();
            }
        });
    };

    $.fn.selectOnce.Constructor = ListElementSelector;

    // ELEMENTSELECTOR EDITOR NO CONFLICT
    // ===========================
    $.fn.selectOnce.noConflict = function (){
        $.fn.selectOnce = old;

        return this;
    };
})(jQuery);

(function($){
    "use strict";

    var BlocksMover = function(element, options) {
        this.$element    = $(element);
        this.options     = $.extend({}, BlocksMover.DEFAULTS, options);
    };

    BlocksMover.DEFAULTS = {
        connectWith: ".rkcms-sortable",
        placeholder: "rkcms-sortable-placeholder",
        cursor: 'pointer',
        cursorAt: { cursor: "move", top: 10, left: 10 },
        receive: function(event, ui)
        {
            _moveBlockToAnotherSlot(ui, this);
        },
        start: function(event, ui)
        {
            _startMoving(ui, this);
        },
        stop: function(event, ui)
        {
            _moveBlockToSameSlot(ui, this);
        }
    };

    BlocksMover.prototype.start = function ()
    {
        this.$element.sortable(BlocksMover.DEFAULTS);
    };

    function _startMoving(ui, sortable)
    {
        var data = ko.dataFor(ui.item[0]);
        $('body').data('rkcms-target-list', null);
        $('body').data('rkcms-sorting-element-index', _getIndex(sortable, data["name"]));
    }

    function _moveBlockToSameSlot(ui, sortable)
    {
        // Checks when dragging to another slot
        if (null !== $('body').data('rkcms-target-list')) {
            return;
        }

        // Checks when sorting on the same slot and the position is the same
        // which means that nothing has been done
        var data = ko.dataFor(ui.item[0]);
        var index = _getIndex(sortable, data["name"]);
        if($('body').data('rkcms-sorting-element-index') == index) {
            return;
        }

        // Adds the block
        var slot = _getSlot(data);
        slot.blocks.remove(data);
        slot.blocks().splice(index, 0, data);

        var url = frontcontroller + '/backend/block/move';
        var ajaxData = {
            'sourceSlot': data["slot_name"],
            'name': data["name"],
            'position': index,
            'page':  page,
            'language': language,
            'country': country
        };

        executeAjax(url, ajaxData);
    }

    function _moveBlockToAnotherSlot(ui, sortable)
    {
        $('body').data('rkcms-target-list', sortable);

        var slot = ko.dataFor(sortable);
        var targetSlotName = slot.slotName;

        // Adds the block to target slot
        var block = ko.dataFor(ui.item[0]);
        block.slotName = targetSlotName;
        block.slot_name = targetSlotName;
        var index = _getIndex(sortable, block["name"]);

        // When dragging an element to an empty slot we must refer to ko observable
        // otherwise, when adding to a not empty slot, we must refer to ko raw array
        // because, if we would refer to observable for this situation, ko refreshes
        // the slot and the dragged element is displayed twice
        if (slot.blocks().length == 0) {
            slot.blocks.splice(index, 0, block);
        }else {
            slot.blocks().splice(index, 0, block);

            // Here we must manually refresh the data-slot-name attribute
            $($(ui.item[0]).children()[0])
                .attr('data-slot-name', targetSlotName)
            ;
        }

        // Removes the block from source slot
        var sourceSlot = ko.dataFor(ui.sender[0]);
        sourceSlot.blocks.remove(block);
        
        var url = frontcontroller + '/backend/block/move';
        var ajaxData = {
            'sourceSlot': sourceSlot.slotName,
            'targetSlot': targetSlotName,
            'name': block.name,
            'position': index,
            'page':  page,
            'language': language,
            'country': country
        };

        executeAjax(url, ajaxData,
            function(response)
            {
                // Dragging a block to another slot chamges the block name, so we must update the block and
                // find the dom elememt which represents the block itself and change the data-name attribute
                // manually
                var element = $("[data-slot-name='" + targetSlotName + "'][data-name='" + block["name"] + "']");
                block.name = response.name;
                $(element).attr('data-name', block.name);
            }
        );
    }

    function _getIndex(element, name)
    {
        var index = 0;
        $(element).children().each(function(){
            var children = $(this).children();
            if($(children[0]).attr('data-name') == name) {
                return false;
            }
            index++;
        });

        return index;
    }
    
    function _getSlot(slotName)
    {
        var slot = null;
        $('.rkcms-slot').each(function(){
            slot = ko.dataFor(this);
            if (slot.slotName == slotName){
                return false;
            }
        });
        
        return slot;
    }

    // BLOCKSMOVER EDITOR PLUGIN DEFINITION
    // =================================
    var old = $.fn.blocksmover;

    $.fn.blocksmover = function (options) {
        if (null == options) {
            options = {};
        }
        
        return this.each(function () {
            var $this = $(this);
            var blocksMover = $this.data('rkcms.blocks_mover');
            if (!blocksMover) {
                $this.data('rkcms.blocks_mover', (blocksMover = new BlocksMover(this, options)));
            }

            blocksMover.start();
        });
    };

    $.fn.blocksmover.Constructor = BlocksMover;

    // BLOCKSMOVER EDITOR NO CONFLICT
    // ===========================
    $.fn.blocksmover.noConflict = function () {
        $.fn.blocksmover = old;

        return this;
    };
})(jQuery);

(function($){
    "use strict";

    $.fn.fullHeight = function (adjust) {
        if (adjust == undefined) {
            adjust = 0;
        }

        return this.each(function () {
            var $this = $(this);
            var visiblePortion = window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight||0;
            var height = visiblePortion - $('.rkcms-block-attributes-title:visible').outerHeight() - $('.rkcms-block-panel:visible').outerHeight() - $('.rkcms-link-extra-panel:visible').outerHeight() - 80; // - 80 - adjust;
            $this.css('height', height + 'px');
        });
    };
})(jQuery);