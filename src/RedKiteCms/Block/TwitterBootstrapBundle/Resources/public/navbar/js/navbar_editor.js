/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */
 
$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){ 
        if (element.attr('data-type') == 'BootstrapNavbarBlock') {
            $('.al-edit-item').unbind().click(function(){
                element.popover('hide');
                
                var filter = (bootstrapVersion == "2.x") ? 'navbar-block-2' : 'navbar-block-3';
                element.inlinelist('start', {'target': '.al-navbar-list', 'filterAdders': filter});
            });
        } 
        
        if (element.attr('data-type') == 'BootstrapNavbarMenuBlock') {
            $('.al-edit-item').unbind().click(function(){
                element.popover('hide');
                element.inlinelist('start', {'target': '> li', 'filterAdders': 'menu-navbar'});
            });
        } 
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        if (element.attr('data-type') == 'BootstrapNavbarBlock') {
            $('.al-navbar-list').inlinelist('stop');
        }

        if (element.attr('data-type') == 'BootstrapNavbarMenuBlock') {
            $('.al-navbar-menu-list').inlinelist('stop');
        }

        $('.al_block_adder').unbind().blocksMenu('initAdders');
    });

    $(document).on("blockEdited", function(event, element){        
        if (element.attr('data-type') == 'BootstrapNavbarBlock' || element.attr('data-type') == 'BootstrapNavbarMenuBlock') {
            $(element).blocksEditor('start');
        }
    });
});