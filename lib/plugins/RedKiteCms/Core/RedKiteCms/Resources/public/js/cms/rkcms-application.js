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

var slotEditorModel;
var blockEditorModel;

$(document).ready(function()
{
    slotEditorModel = new SlotEditorModel();
    ko.applyBindings(slotEditorModel, document.getElementById('rkcms-slots-editor-panel'));

    blockEditorModel = new BlockEditorModel();
    ko.applyBindings(blockEditorModel, document.getElementById('rkcms-blocks-editor-panel'));

    $('.rkcms-slot').each(function(){
        var element = $(this);
        var blocks = ko.utils.parseJson(decodeURIComponent(element.attr("data-slot")));
        var slotName = element.attr("data-slotname");
        var slot = new PageModel(blocks, slotName, blockEditorModel);
        ko.applyBindings(slot, this);
    });

    $('.rkcms-warning-btn').popover();
    $('#rkcms-control-panel-btn')
        .popover()
        .on('shown.bs.popover', function () {
            var status = $('body').data('rkcms.editor-button-status');
            var moveMode = $('body').data('rkcms.editor-move-mode');
            var model = new ControlPanelModel(status, moveMode);
            ko.applyBindings(model, document.getElementById('rkcms-control-panel-body'));
        })
        .on('hide.bs.popover', function () {
            $('.rkcms-edit').each(function(){
                ko.cleanNode(this);
            });
        })
    ;

    var seoModel = new SeoModel($('#rkcms-seo').attr("data-seo"));
    ko.applyBindings(seoModel, document.getElementById('rkcms-seo'));

    RunHolder();
    Highlight();
});

function RunHolder()
{
    window.setTimeout(function(){
        Holder.run();
    }, 1);
}

function Highlight()
{
    window.setTimeout(function(){
        $('pre code').each(function(i, block) {
            hljs.highlightBlock(block);
        });
    }, 1);
}

function mediaLibrary(url, callback)
{
    $('<div/>').dialogelfinder({
        url : url,
        lang : $('#al_available_languages option:selected').val(),
        width : 840,
        destroyOnClose : true,
        commandsOptions : {
            getfile: {
                oncomplete: 'destroy'
            }
        },
        getFileCallback : function(file, fm) {
            callback(file, fm);
        }
    }).dialogelfinder('instance');
}