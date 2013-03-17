$(document).ready(function() {
    $(document).on("popoverShow", function(event, idBlock, blockType){
        if (blockType != 'File') {
            return;
        }
        
        $('#al_json_block_file').click(function()
        {
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showFileMediaLibrary',
                data: {'page' :  $('#al_pages_navigator').html(),
                       'language' : $('#al_languages_navigator').html()
                },
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(html)
                {
                    showMediaLibrary(html);                 
                    $('<div/>').dialogelfinder({
                        url : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderFileConnect',
                        lang : 'en',
                        width : 840,
                        destroyOnClose : true,
                        commandsOptions : {
                            getfile : {
                                onlyURL  : false
                            }
                        },
                        getFileCallback : function(file, fm) {
                            $('#al_json_block_file').val(file.path);
                            
                            alert('File has been added');
                        }
                    }).dialogelfinder('instance');
                },
                error: function(err)
                {
                    $('body').showDialog(err.responseText);
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        });
    });
}); 