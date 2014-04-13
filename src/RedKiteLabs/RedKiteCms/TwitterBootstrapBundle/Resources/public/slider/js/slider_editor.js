/*
 * This file is part of the RedKite CMS Application and it is distributed
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
        if (element.attr('data-type') != 'BootstrapSliderBlock') {
            return;
        }
        
        Holder.run();  
        $('body').imagesList('init'); 
        $('#al_add_item').unbind().imagesList('addItem');     
        $('#al_save_item').unbind().imagesList('save'); 
    });
});
