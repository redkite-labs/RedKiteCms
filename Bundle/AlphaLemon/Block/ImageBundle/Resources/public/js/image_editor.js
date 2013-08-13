$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'Image') {
            return;
        }
    
        $('#al_json_block_src').click(function(){
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
    });
}); 
