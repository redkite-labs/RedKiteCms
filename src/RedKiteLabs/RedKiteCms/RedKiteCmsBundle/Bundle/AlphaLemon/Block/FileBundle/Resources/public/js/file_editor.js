$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'File') {
            return;
        }
        
        $('#al_json_block_file').click(function()
        {              
            $('<div/>').dialogelfinder({
                url : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderFileConnect',
                lang : 'en',
                width : 840,
                destroyOnClose : true,
                commandsOptions : {
                    getfile: {
                        oncomplete: 'destroy'
                    }
                },
                getFileCallback : function(file, fm) {
                    $('#al_json_block_file').val(file.path);
                }
            }).dialogelfinder('instance');
        });
    });
}); 
