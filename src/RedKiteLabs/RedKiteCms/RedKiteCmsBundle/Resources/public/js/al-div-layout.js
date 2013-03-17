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
    $.fn.BuildGrid = function()
    {
        $('.al_table_layout').hide();
        try
        {
            //for(i=0; i<12; i++) {}
            var cell = document.createElement("DIV");
            $(cell)
                .addClass("span12 al_table_layout")
                .css("border", "1px solid #C20000")
                .css("height", "30px")
            ;

            $(this).append(cell);
            
        }
        catch(e)
        {
            alert(e);
        }
    };
})($);

$(document).ready(function(){
    //$('.al_slot').BuildGrid();
});
