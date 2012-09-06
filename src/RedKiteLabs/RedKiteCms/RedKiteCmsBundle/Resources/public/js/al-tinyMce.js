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
    $.fn.AddTinyMCE = function()
    {
        try
        {
            tinyMCE.execCommand('mceAddControl', false, $(this).attr('name'));
        }
        catch(e)
        {
          alert('TinyMCE was not loaded. Verify that it is correctly installed and its initialization script is correctly setted');
        }
    };

    $.fn.RemoveTinyMCE = function()
    {
        if($(this).attr('name') != null)
        {
            try
            {
                tinyMCE.execCommand('mceRemoveControl', false, $(this).attr('name'));
            }
            catch(e)
            {
            }
        }
    };
})($);
