$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'Script') {
            return;
        }
        
        $('#myTab a').click(function(){ 
            $(this).tab('show'); 
            
            return false;
        });
        
        $("#al_html_saver").click(function()
        {
            $("#al_html_editor").EditBlock("Content");
            
            return false;
        });
        
        $("#al_internal_javascript_saver").click(function()
        {
            $("#al_internal_javascript").EditBlock("InternalJavascript");
            
            return false;
        });
        
        $("#al_internal_stylesheet_saver").click(function()
        {
            $("#al_internal_stylesheet").EditBlock("InternalStylesheet");
            
            return false;
        });
        
        $(".al_Stylesheet_item_remover").RemoveExternalFile("ExternalStylesheet");
        $(".al_Javascript_item_remover").RemoveExternalFile("ExternalJavascript"); 
        $(".al_Stylesheet_file_manager").click(function(){
            openMediaLibrary('al-stylesheet-external-files', 'ExternalStylesheet', 'al_elFinderStylesheetsConnect')
        });
        $(".al_Javascript_file_manager").click(function(){
            openMediaLibrary('al-javascript-external-files', 'ExternalJavascript', 'al_elFinderJavascriptsConnect')
        });
        $(".al-Stylesheet-removable-item").click(function()
        {
            var isCurrentItemSelected = $(this).hasClass("al_selected_item");
            $(".al-Stylesheet .al_selected_item").removeClass("al_selected_item");
            if(!isCurrentItemSelected) $(this).addClass("al_selected_item");
            
            return false;
        });
        $(".al-Javascript-removable-item").click(function()
        {
            var isCurrentItemSelected = $(this).hasClass("al_selected_item");
            $(".al-Javascript .al_selected_item").removeClass("al_selected_item");
            if(!isCurrentItemSelected) $(this).addClass("al_selected_item");
            
            return false;
        });
    });
}); 

function openMediaLibrary(id, key, connector)
{
    $('<div/>').dialogelfinder({
            url : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/' + connector,
            lang : 'en',
            width : 840,
            destroyOnClose : true,
            commandsOptions : {
                getfile : {
                    onlyURL  : false,
                }
            },
            getFileCallback : function(file, fm) {
                $('#' + id).AddExternalFile(key, file.path);
            }
    }).dialogelfinder('instance');
}
