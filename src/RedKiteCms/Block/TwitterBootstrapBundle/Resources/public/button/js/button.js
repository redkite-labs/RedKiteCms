/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'BootstrapButtonBlock') {
            return;
        }
        
        switch (bootstrapVersion) {
            case '2.x':
                $('#al_json_block_button_href')
                    .before('<div class="al_pages_selector pull-left"></div>')
                    .wrap('<div class="pull-right"></div>')
                    .parent()
                    .parent()
                    .append('<div class="clear-fix"></div>')
                ;
                break;
            case '3.x':
                $('#al_json_block_button_href')
                    .wrap('<div class="row"></div>')
                    .before('<div class="al_pages_selector col-lg-4"></div>')
                    .wrap('<div class="col-lg-8"></div>')
                ;
                break;
        }
        
        $('#al_page_name')
            .unbind()
            .on('change', function(){
                $('#al_json_block_button_href').val($('#al_page_name option:selected').text());
                
                return false;
            })
            .appendTo('.al_pages_selector')
            .show()
        ;
        
        // Adjust popover width because of permalinks select
        $('.al-popover').width('400px');
        
        $('#al_json_block_button_href').keydown(function(event){
            var $this = $(this);
            if ($this.val().match(/route:/g)) {
                if (event.which == 32) {
                    alert(translate('A space character is not accepted, when adding an internal route'));
                    
                    return false;
                }
            }
            
        });
    });
    
    $(document).on("stopEditingBlocks", function(event, element){
        $('#al_page_name')
            .appendTo('body')
            .val(0)
            .hide()
        ;
    });
});
