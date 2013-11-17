/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 * 
 * @license    MIT LICENSE
 * 
 */
 
 $(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        var blockType = element.attr('data-type');
        if (blockType != 'BootstrapDropdownButtonBlock' && blockType != 'BootstrapSplitDropdownButtonBlock' && blockType != 'BootstrapNavbarDropdownBlock') {
            return;
        }
        
        $('.dropdown-menu-add-item').on('click', function(){ 
            var rowsCount = $('.dropdown-menu-row').length;
            var permalinks = $('#al_page_name')
                .clone()
                .attr('id', 'permalinks_' + rowsCount)                
                .attr('rel', rowsCount)
                .addClass('dropdown-menu-permalink')
                .show()
                .prop('outerHTML')
            ;
            
            
            var buttonRemoveMarkup = '<a href="#" class="dropdown-menu-delete-item btn btn-danger btn-xs" rel="row-' + rowsCount + '"><span class="glyphicon glyphicon-trash"></span></a>';
            if (bootstrapVersion == "2.x") {
                buttonRemoveMarkup = '<a class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i></a>';
            }
            
            var row = 
                '<tr class="row-' + rowsCount + ' dropdown-menu-row">' + 
                '<td class="col-lg-2"><select name="dropdown_items_form[' + rowsCount + '][metadata][type]" class="dropdown-menu-type form-control input-small" rel="' + rowsCount + '"><option value="link">Link</option><option value="header">Header</option><option value="divider">Divider</option></select></td>' + 
                '<td><input id="link_' + rowsCount + '" type="text" name="dropdown_items_form[' + rowsCount + '][data]" value="" class="form-control input-medium" /></td>' + 
                '<td class="col-lg-2">' + permalinks + '</td>' + 
                '<td><input id="href_' + rowsCount + '" type="text" name="dropdown_items_form[' + rowsCount + '][metadata][href]" value="" class="form-control input-medium" /></td>' + 
                '<td>' + buttonRemoveMarkup + '</td>' +
                '</tr>';
            
            $(".dropdown-menu-table").append(row);
            initRemoveButtons();
            initPermalinksSelect();
            initTypeSelect();
            $('.dropdown-menu-items-container').animate({scrollTop: $('.dropdown-menu-items-container')[0].scrollHeight});
            
            return false;
        });
        
        initRemoveButtons();
        initTypeSelect();
        initPermalinksSelect()
        $('.dropdown-menu-type').each(function(){
            toggleElementsByType($(this));
        });
        
        $(".al-editor-items").on('click', function(){
           if ( ! $('#al-dropdown-menu-items').is(":visible") && $('#al-dropdown-menu-items').html().trim() == "" ) {
                $.ajax({
                      type: 'POST',
                      url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_show_jstree',
                      data: {
                          'page' :  $('#al_pages_navigator').html(),
                          'language' : $('#al_languages_navigator').html(),  
                          'pageId' :  $('#al_pages_navigator').attr('rel'),
                          'languageId' : $('#al_languages_navigator').attr('rel'),                  
                          'idBlock' : element.attr('data-block-id')
                      },
                      beforeSend: function()
                      {
                          $('body').AddAjaxLoader();
                      },
                      success: function(html)
                      {
                          $('#al-dropdown-menu-items').html(html);
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
            }

            $("#al-dropdown-menu-items").toggle();

            return false;
        });
                 
        $('.al_editor_save').unbind().on('click', function()
        {
            var value = $('#al_item_form').serialize() + '&' + $('.dropdown-items-form').serialize();
            $('#al_item_form').EditBlock('Content', value);
            
            return false;
        });
    });
});

function initRemoveButtons()
{
    $('.dropdown-menu-delete-item').on('click', function(){
        if(confirm("Are you sure you want to remove the selected item")) {
            $("." + $(this).attr('data-row-id')).remove();
        }

        return false;
    });
}

function initPermalinksSelect()
{
    $('.dropdown-menu-permalink').unbind().on('change', function(){ 
        var $this =  $(this); // 
        $("#href_" + $this.attr('rel')).val($(this).find('option:selected').text());

        return false;
    });
}


function initTypeSelect()
{
    $('.dropdown-menu-type').unbind().on('change', function(){ 
        toggleElementsByType($(this));

        return false;
    });
}

function toggleElementsByType(element)
{
    var elementId = element.attr('rel');
    switch(element.val())
    {
        case 'link':
            $('#link_' + elementId).removeAttr('disabled');
            $('#permalinks_' + elementId).removeAttr('disabled');
            $('#href_' + elementId).removeAttr('disabled');
            break;
        case 'header':
            $('#link_' + elementId).removeAttr('disabled');
            $('#permalinks_' + elementId).attr('disabled', 'true');                  
            $('#href_' + elementId).attr('disabled', 'true');
            break;
        case 'divider':
            $('#link_' + elementId).attr('disabled', 'true');                    
            $('#permalinks_' + elementId).attr('disabled', 'true');                  
            $('#href_' + elementId).attr('disabled', 'true');
            break;
    }
}