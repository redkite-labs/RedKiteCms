$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'Image') {
            return;
        }
        
        // Removes the parent link
        element
            .data('href', element.parent().attr('href'))
            .unwrap()
        ;
            
        $('.rk-upload-image').click(function(){
            $('<div/>').dialogelfinder({
                    url : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect',
                    lang : $('#al_available_languages option:selected').val(),
                    width : 840,
                    destroyOnClose : true,
                    commandsOptions : {
                        getfile: {
                            oncomplete: 'destroy'
                        }
                    },
                    getFileCallback : function(file, fm) {
                        $('#al_json_block_src').val(file.url);
                    }
            }).dialogelfinder('instance');
        });
        
        $('#al_page_name')
            .unbind()
            .on('change', function(){
                $('#al_json_block_href').val($('#al_page_name option:selected').text());
                
                return false;
            })
            .appendTo('.al_pages_selector')
            .show()
        ;
        
        openFilesMediaLibrary('.rk-upload-file');
        
        $('.al_editor_save').unbind().click(function(){
            element.data('href', null);
            
            $('body').EditBlock('Content', $('#al_item_form').serialize());

            return false;
        });
    });
    
    $(document).on("stopEditingBlocks", function(event, element){ 
        if (element.attr('data-type') != 'Image') {
            return;
        }
        
        var link = element.data('href');
        if (link == null) {
            return;
        }
        
        // restores the link
        element
            .wrap('<a></a>')
            .parent()
            .attr('href', link)
        ;
    });
});