/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

(function($){
    $.fn.AddUser = function()
    {
        this.each(function() 
        {
            $(this).click(function()
            {
                showUser();
            });
        });
    };
    
    $.fn.EditUser = function()
    {
        this.each(function() 
        {
            $(this).click(function()
            {
                showUser($(this).attr('rel'));
            });
        });
    };
    
    $.fn.DeleteUser = function()
    {
        this.each(function() 
        {
            $(this).click(function()
            {
                deleteUser($(this).attr('rel'));
            });
        });
    };
    
    $.fn.ListUsers = function()
    {
        this.List('al_userList');
    }
    
    $.fn.ListRoles = function()
    {
        this.List('al_rolesList');
    }
    
    $.fn.List = function(route)
    {
        this.each(function() 
        {
            $(this).click(function()
            {
                $.ajax({
                    type: 'GET',
                    url: frontController + $('#al_available_languages').val() + '/' + route,
                    data: {},
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(html)
                    {
                        $('#al_dialog').html(html);
                        $('#al_dialog').dialog('open');
                        ObserveUsers();
                    },
                    error: function(err)
                    {
                        $('#al_dialog').html(err.responseText);
                        $('#al_dialog').dialog('open');
                    },
                    complete: function()
                    {
                        $('body').RemoveAjaxLoader();
                    }
                });
                
                return false;
            });
        });
    }
        
})($);

function showUser(id)
{
    if(id == null) id = 0;
    $.ajax({
      type: 'GET',
      url: frontController + $('#al_available_languages').val() + '/al_showUser',
      data: {'id' : id },
      beforeSend: function()
      {
        $('body').AddAjaxLoader();
      },
      success: function(html)
      {
        $('#al_dialog').html(html);
      },
      error: function(err)
      {
        $('#al_error').html(err.responseText);
      },
      complete: function()
      {
        $('body').RemoveAjaxLoader();
      }
    });

    return false;
}

function deleteUser(id)
{
    if(confirm("Are you sure to remove the user?"))
    {
        $.ajax({
          type: 'GET',
          url: frontController + $('#al_available_languages').val() + '/al_deleteUser',
          data: {'id' : id },
          beforeSend: function()
          {
            $('body').AddAjaxLoader();
          },
          success: function(html)
          {
            $('#al_dialog').html(html);
            ObserveUsers();
          },
          error: function(err)
          {
            $('#al_error').html(err.responseText);
          },
          complete: function()
          {
            $('body').RemoveAjaxLoader();
          }
        });
    }

    return false;
}

function ObserveUsers()
{
    $('.al_add_user').unbind().AddUser();
    $('.al_edit_user').unbind().EditUser();
    $('.al_delete_user').unbind().DeleteUser();
}