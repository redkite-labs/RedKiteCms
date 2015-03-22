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

function confirmDialog(message, confirmCallback, cancelCallback, icon){
    if (null == icon) {
        icon = 'question';
    }
    message = formatDialogMessage(icon, message);

    bootbox.dialog({
        message: message,
        title: "RedKite CMS",
        animate: false,
        buttons: {
            cancel: {
                label: "Cancel",
                callback: cancelCallback
            },
            confirm: {
                label: "OK",
                className: "btn-primary",
                callback: confirmCallback
            }
        }
    });
}

function alertDialog(message, confirmCallback, icon, customTextClass){
    message = formatDialogMessage(icon, message);

    if (customTextClass != null) {
        textClass = customTextClass;
    }

    bootbox.dialog({
        message: message,
        title: "RedKite CMS",
        animate: false,
        buttons: {
            confirm: {
                label: "OK",
                className: "btn-" + textClass,
                callback: confirmCallback
            }
        }
    });
}

function formatDialogMessage(icon, message){
    switch (icon) {
        case "info":
            icon = 'fa-exclamation-circle';
            textClass = 'primary';
            break;
        case "warning":
            icon = 'fa-exclamation-triangle';
            textClass = 'warning';
            break;
        case "danger":
            icon = 'fa-close';
            textClass = 'danger';

            break;
        case "question":
            icon = 'fa-question-circle';
            textClass = 'primary';
            break;
        default:
            icon = null;
            textClass = 'default';
            break;
    }

    if (null !== icon) {
        message = '<div><i class="fa ' + icon + ' fa-3x text-' + textClass + ' pull-left"></i><p>' + message + '</p></div>';
    }

    return message;
}