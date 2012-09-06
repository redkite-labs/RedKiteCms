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

InitDialog = function(id, options)
{
    try
    {
        var defaultOptions = {
        autoOpen: false,
        width: 800,
        buttons: {
            "Close": function() {
                $(this).dialog("close");
            }
        }};

        if(id == null) id = "al_dialog";
        if(options !== null) $.extend (defaultOptions, options);

        if($('body').find(id).length == 0)
        {
            $('<div id="' + id + '"></div>')
                    .css("display", "none")
                    .appendTo('body');
        }

        $('#' + id).dialog(defaultOptions);
    }
    catch(e)
    {
        alert(e);
    }
};
