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

$(document).ready(function()
{
    $('.al_context_menu_item').each(function(){
        $(this).click(function(){
            $($('#al_context_menu').data('parent')).AddBlock($(this).attr('rel')); 
            
            return false;
        });
    });
    
    $('#al_context_menu_edit').click(function() {
        $($('#al_context_menu').data('parent')).OpenEditor(); 
            
        return false;
    });
    
    $('#al_context_menu_delete').click(function() {
        $($('#al_context_menu').data('parent')).DeleteBlock(); 
            
        return false;
    });
});