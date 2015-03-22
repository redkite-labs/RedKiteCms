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

function executeAjax(url, data, successCallback, failureCallback, completeCallback)
{
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        beforeSend: function() {
            $('.rkcms-saving-progress').show();
        },
        success: function(response) {
            if (null != successCallback) {
                successCallback(response);
            }
        },
        error: function(error) {
            if (null != failureCallback) {
                failureCallback(error);

                return;
            }

            alertDialog(error.responseText, null, 'danger');
        },
        complete: function() {
            if (null != completeCallback) {
                completeCallback();
            }
            $('.rkcms-saving-progress').hide();
        }
    });
}