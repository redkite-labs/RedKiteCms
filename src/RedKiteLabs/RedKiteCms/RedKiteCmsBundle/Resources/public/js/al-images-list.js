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
    $.fn.AddImage =function(file)
    {
        this.each(function()
        {
            $(this).EditBlock('HtmlContent', file);

            return false;
        });

        return this;
    };
    
    $.fn.RemoveImage =function(file)
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $(this).EditBlock('HtmlContent', $('.al_image_selected img').attr('rel'), {remove: true});

                return false;
            });
        });

        return this;
    };

})($);

