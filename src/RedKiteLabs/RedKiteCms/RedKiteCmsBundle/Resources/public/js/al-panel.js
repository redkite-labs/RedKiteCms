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
    $.fn.OpenPanel = function(html, callback)
    {
        //;
        if(callback == null) callback = function(){};
        this.each(function()
        {
            if($('#al_panel_contents').length == 0)
            {
                var panel = this;
                var panelBody = document.createElement("DIV");
                            panelBody.id = "al_panel_body";
                            panel.appendChild(panelBody);

                var panelContents = document.createElement("DIV");
                            panelContents.id = "al_panel_contents";
                            panelBody.appendChild(panelContents);
                $(panelContents).html(html);

                var panelCloser = document.createElement("DIV");
                            panelCloser.id = "al_panel_closer";
                            $(panelCloser).attr('class', "white-text-shadow");
                            panel.appendChild(panelCloser);

                $(panelCloser).html("Click me to close the panel").click(function()
                {
                    $(panel).animate({
                    height: "hide"
                    }, 1000, 'easeInOutCirc', function(){$(this).empty();});
                });
            }
            else {
                panel = $('#al_panel_contents');
                panel.hide().html(html);
            }

            $(panel).animate({
                height: 'show'
                }, 750, 'easeInExpo', callback);
        });
    };
})($);
