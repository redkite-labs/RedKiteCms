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

    $.fn.AddRole = function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                showRole();
            });
        });
    };

    $.fn.EditRole = function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                showRole($(this).attr('rel'));
            });
        });
    };

    $.fn.DeleteRole = function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                deleteRole($(this).attr('rel'));
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
                    type: 'POST',
                    url: frontController + 'backend/users/' + $('#al_available_languages option:selected').val() + '/' + route,
                    data: {
                        'page' :  $('#al_pages_navigator').html(),
                        'language' : $('#al_languages_navigator').html()
                    },
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(html)
                    {
                        if ( ! $('#al_panel').is(":visible")) {
                            html = '<div id="al_user_panel">' + html  + '</div>';
                            $('#al_panel').OpenPanel(html, function(){
                                ObserveSecurity();
                            });
                        } else {
                            $('#al_user_panel').html(html);
                            ObserveSecurity();
                        }
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

                return false;
            });
        });
    }

})($);

function showUser(id)
{
    if(id == null) id = 0;
    show('al_showUser', id);

    return false;
}

function showRole(id)
{
    if(id == null) id = 0;
    show('al_showRole', id);

    return false;
}

function deleteUser(id)
{
    if(confirm("Are you sure you want to remove the user?"))
    {
        remove('al_deleteUser', id);
    }

    return false;
}

function deleteRole(id)
{
    if(confirm("Are you sure you want to remove the role?"))
    {
        remove('al_deleteRole', id);
    }

    return false;
}

function show(route, id)
{
    $.ajax({
      type: 'GET',
      url: frontController + 'backend/users/' + $('#al_available_languages option:selected').val() + '/' + route,
      data: {'id' : id },
      beforeSend: function()
      {
        $('body').AddAjaxLoader();
      },
      success: function(html)
      {
        $('#al_user_panel').html(html);
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

function remove(route, id)
{
    $.ajax({
      type: 'GET',
      url: frontController + 'backend/users/' + $('#al_available_languages option:selected').val() + '/' + route,
      data: {'id' : id },
      beforeSend: function()
      {
        $('body').AddAjaxLoader();
      },
      success: function(html)
      {
        $('#al_user_panel').html(html);
        ObserveSecurity();
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

function ObserveSecurity()
{
    $('.al_list_roles').unbind().ListRoles();
    $('.al_list_users').unbind().ListUsers();

    $('.al_add_user').unbind().AddUser();
    $('.al_edit_user').unbind().EditUser();
    $('.al_delete_user').unbind().DeleteUser();

    $('.al_add_role').unbind().AddRole();
    $('.al_edit_role').unbind().EditRole();
    $('.al_delete_role').unbind().DeleteRole();
}
