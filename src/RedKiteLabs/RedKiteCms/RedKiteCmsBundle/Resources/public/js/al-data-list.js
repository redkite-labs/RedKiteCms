/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

(function($){
    $.fn.StartListEditing =function()
    {
        var $this = $(this);
        $this.find('li').each(function(){
            var $this = $(this);
            if ($this.hasClass('al-empty')) {
                return;
            }
            
            var removeButton = document.createElement("div");
            $(removeButton)
                .addClass("al-delete-item-list")
                .attr('data-item', $this.attr('data-item'))
                .attr('data-key', $this.attr('data-key'))
                .append('<a class="btn btn-mini btn-danger"><i class="icon-trash icon-white" /></a>')
                .appendTo($this) 
            ;
                         
            $(removeButton).PlaceTopRight($this).PlaceTopRight($this);
        });
        
        $this.append('<li class="al-add-item-list"><a class="btn btn-mini btn-primary"><i class="icon-plus icon-white" /></a></li>');
        
        $('.al-add-item-list').click(function(){
            $('body').EditBlock("Content", '{"operation": "add", "value": { "width": "span3" }}', null, function(activeBlock)
            {
                activeBlock.StopEditBlock().find('.al_editable').StartToEdit();   
                Holder.run();
            });            
        });
        
        $('.al-delete-item-list').click(function(){
            if (confirm('Are you sure to remove the active block')) {
                $('body').EditBlock("Content", '{"operation": "remove", "item": "' + $(this).attr('data-item') + '", "key": "' + $(this).attr('data-key') + '"}', null, function(activeBlock)
                {
                    activeBlock.StopEditBlock().find('.al_editable').StartToEdit();
                    Holder.run();
                });
            }
        });
        
        return this;
    };
    
    $.fn.StopListEditing =function()
    {
        $('.al-add-item-list').unbind().remove();        
        $('.al-delete-item-list').unbind().remove();
        
        return this;
    };
})($);
