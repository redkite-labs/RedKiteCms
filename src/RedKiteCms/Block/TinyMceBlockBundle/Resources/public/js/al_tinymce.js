/**
 * This file is part of the RedKiteCms CMS Application and it is distributed
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
    $(document).on("cmsStarted", function(event, block)
    {
        var tinymceContainer = document.createElement("div");
        $(tinymceContainer)
            .attr('id', 'rk-tinymce-container')
            .css('position', 'absolute')
            .css('z-index', '60000')
        ;
        $('body').append(tinymceContainer);
        
        initTinyMCE();        
    });
    
    $(document).on("cmsStopped", function(event, block)
    {
        tinymce.remove();
        $('#rk-tinymce-container').remove();
    });
    
    $(document).on("startEditingBlocks", function(event, block){
        if (block.attr('data-type') != 'Text') {
            return;
        }
        
        block.highligther('deactivate');
        $('#rk-tinymce-container')
            .css('width', '715px')
            .css('height', '69px')
            .position({
                my: "left bottom",
                at: "left top",
                of: block,
                collision: "flipfit flipfit"
            })
            .show()
        ;
    });
    
    $(document).on("stopEditingBlocks", function(event, block){
        if (block.attr('data-type') != 'Text') {
            return;
        }
        
        $('#rk-tinymce-container')
            .css('width', '0')
            .css('height', '0')
        ;
    });
    
    $(document).on("blockEdited", function(event)
    {
        reinitTinyMce();
    });
    
    $(document).on("blockDeleted", function(event)
    {
        reinitTinyMce();
    });
    
    function reinitTinyMce()
    {
        tinymce.remove();
        initTinyMCE();
    }
});

function initTinyMCE()
{
    tinymce.init({
        selector: ".al-editable-inline",
        inline: true,
        image_advtab: true,
        fixed_toolbar_container: "#rk-tinymce-container",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code ",
            "insertdatetime media table contextmenu paste save"
        ],
        toolbar: "save | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        link_list : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_createPermalinksList/' + $('#al_languages_navigator').html(), 
        save_onsavecallback: function(editor) {
            $('body').EditBlock('Content', editor.getContent(), null, function(){
                tinymce.remove();
                initTinyMCE();   
            });
        },
        file_browser_callback : function (id, value, type, win) {        
            $('<div />').dialogelfinder({
               url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect',
               lang : $('#al_available_languages option:selected').val(),
               width : 840,
               destroyOnClose : true,
               commandsOptions: {
                  getfile: {
                     oncomplete: 'destroy'
                  }
               },
               getFileCallback: function (url)
               {
                  var fieldElm = win.document.getElementById(id);
                  fieldElm.value = url.url;
               }
            });
        }
    });
}
