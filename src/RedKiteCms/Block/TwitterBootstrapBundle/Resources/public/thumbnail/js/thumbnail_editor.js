/*
 * This file is part of the BootstrapThumbnailBlockBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    MIT LICENSE
 * 
 */
 
 $(document).ready(function() {
    $(document).on("startEditingBlocks", function(event, element){
        var type = element.attr('data-type');
        if (type != 'BootstrapThumbnailsBlock' && type != 'BootstrapSimpleThumbnailsBlock') {
            return;
        }
        
        var block = '';
        switch(type) {
            case 'BootstrapSimpleThumbnailsBlock':
                block = 'BootstrapSimpleThumbnailBlock';
                break;
            case 'BootstrapThumbnailsBlock':
                block = 'BootstrapThumbnailBlock';
                break;
        }
        
        element.inlinelist('start', { target: ".col-sm-6", addValue: '{"operation": "add", "value": { "type": "' + block + '" }}'});
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        var type = element.attr('data-type');
        if (type != 'BootstrapThumbnailsBlock' && type != 'BootstrapSimpleThumbnailsBlock') {
            return;
        }
                
        element.inlinelist('stop');
    });
});
