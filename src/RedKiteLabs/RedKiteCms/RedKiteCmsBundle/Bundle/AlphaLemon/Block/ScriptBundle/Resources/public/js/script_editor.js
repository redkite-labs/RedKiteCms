$(document).ready(function() {
    $(document).on("popoverShow", function(event, idBlock, blockType){
        if (blockType != 'Script') {
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
        $(".al_Stylesheet_file_manager").ShowExternalFilesManager("stylesheet");
        $(".al_Javascript_file_manager").ShowExternalFilesManager("javascript");
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