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
    $(document).on("startEditingBlocks", function(event, element){
        if (element.attr('data-type') != 'BootstrapNavbarBlock') {
            return;
        }
        
        $(element)
            .find('.al-navbar-list')
            .inlinelist('start')
        ;
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        if (element.attr('data-type') != 'BootstrapNavbarBlock') {
            return;
        }
                
        $(element)
            .find('.al-navbar-list')
            .inlinelist('stop')
        ;
        $('.al_block_adder').unbind().blocksMenu('initAdders')
    });
});
