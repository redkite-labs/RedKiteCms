/**
 * This file is part of the RedKiteCms CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */
 
 $(document).ready(function() {
    $(document).on("cmsStarted", function(event, block)
    {
        initTinyMCE();        
    });
    
    $(document).on("cmsStopped", function(event, block)
    {
        tinymce.remove();
    });
});

function initTinyMCE()
{
    tinymce.init({
        selector: ".al-editable-inline",
        inline: true,
        image_advtab: true,
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
