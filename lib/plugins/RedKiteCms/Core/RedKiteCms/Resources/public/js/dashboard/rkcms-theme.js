/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

$(document).ready(function() {
    $('.rkcms-activate-theme').on('click', function () {
        alertDialog("This function is not available yet");
    });

    $('.rkcms-start-from-theme').on('click', function () {
        var $this = $(this);
        var themeName = $(this).attr('data-theme-name');
        confirmDialog("Warning: if you continue the current site will be erased. Would you like to continue", function(){
            var url = frontcontroller + '/backend/theme/start';
            var data = {
                'theme': themeName
            };

            executeAjax(url, data,
                function(response)
                {
                    location.href = frontcontroller + '/backend/en-gb-homepage';
                }
            );
        });

        return false;
    });
});