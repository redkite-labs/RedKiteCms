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

var queue = {};

window.onbeforeunload = function (e) {
    saveQueue();
};

saveQueue = function(){
    if (Object.keys(queue).length === 0) {
        return;
    }

    var url = frontcontroller + '/backend/queue/save';
    executeAjax(url,
        {"queue": queue},
        null,
        null,
        null,
        false
    );
};