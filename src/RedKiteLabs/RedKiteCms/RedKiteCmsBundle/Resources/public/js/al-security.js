/*
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

;(function($){
    
    var methods = {
        init: function()
        {
            $('.al_edit_user').unbind().security('load_user_attributes');
            $('#al_save_user').unbind().security('user_save');
            $('.al_delete_user').unbind().security('user_delete');
            $('.al_list_users').unbind().security('users_list');
            $('.al_edit_role').unbind().security('load_role_attributes');
            $('#al_save_role').unbind().security('role_save');
            $('.al_list_roles').unbind().security('roles_list');
            $('.al_delete_role').unbind().security('role_delete');
        },
        users_list: function()
        {
            list($(this), 'al_usersList');         //, ''    
        },
        roles_list: function()
        {
            list($(this), 'al_rolesList');            
        },
        load_user_attributes: function()
        {
            loadAttributes($(this), 'al_loadUser');
        },
        load_role_attributes: function()
        {
            loadAttributes($(this), 'al_loadRole');
        },        
        user_save: function()
        {
            $(this).click(function()
            { 
                var userId = $('#al_user_id').val();
                if (userId == "") {
                    userId = 0;
                }

                var data = {
                    'userId' : userId,
                    'roleId' : $('#al_user_AlRole').val(),
                    'username' : $('#al_user_username').val(),
                    'password' : $('#al_user_password').val(),
                    'email' : $('#al_user_email').val()
                };
                    
                save('al_saveUser', data);
            });
        },        
        role_save: function()
        {
            $(this).click(function()
            {
                var roleId = $('#al_role_id').val();
                if (roleId == "") {
                    roleId = 0;
                }

                var data = {
                    'roleId' : roleId,
                    'role' : $('#al_role_role').val()
                };
                   
                save('al_saveRole', data);
            });
        },        
        user_delete: function()
        {
            remove($(this), 'al_deleteUser', 'user');
        },        
        role_delete: function()
        {
            remove($(this), 'al_deleteRole', 'role');
        }
    }

    function list(element, route)
    {
        element.click(function()
        {
            try{
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
                            html = '<div id="al_panel_contents">' + html  + '</div>';
                            $('#al_panel').OpenPanel(html, function(){
                                $('body').security('init');
                            });
                        } else {
                            $('#al_panel_contents').html(html);
                            $('body').security('init');
                        }
                    },
                    error: function(err)
                    {
                        $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                    },
                    complete: function()
                    {
                        $('body').RemoveAjaxLoader();
                    }
                });
            }
            catch(e){
                $('body').showAlert('An unespected error occoured in al-security file method list. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
            }

            return false;
        });
    }
    
    function loadAttributes(element, route)
    {
        element.click(function()
        {
            var element = $(this);
            
            if( ! element.hasClass('al_element_selected'))
            {
                try{
                    $('.al_security_form').ResetFormElements();
                    $.ajax({
                        type: 'POST',
                        url: frontController + 'backend/users/' + $('#al_available_languages option:selected').val() + '/' + route,
                        data: {'language' : $('#al_languages_navigator').html(),
                               'page' : $('#al_pages_navigator').html(),
                               'entityId' : element.attr('data-entity-id')},
                        beforeSend: function()
                        {
                            $('body').AddAjaxLoader();
                        },
                        success: function(response)
                        {
                            $(response).each(function(key, el)
                            {
                                $(el.name).val(el.value);
                            });
                            
                            $('.al_security_list .al_element_selected').removeClass('al_element_selected');
                            element.addClass('al_element_selected');
                        },
                        error: function(err)
                        {
                            $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                        },
                        complete: function()
                        {
                            $('body').RemoveAjaxLoader();
                        }
                    });  
                }
                catch(e){
                    $('body').showAlert('An unespected error occoured in a-security file while loading ' + settings.entity + '\'s attributes. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a hdata-page-id="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
                }  
            }
            else
            {
                element.removeClass('al_element_selected');
                $('.al_security_form').ResetFormElements();
            }
        });
    }
    
    function save(route, data)
    {
        try{
            $.ajax({
                type: 'POST',
                url: frontController + 'backend/users/' + $('#al_available_languages option:selected').val() + '/' + route,
                data: data,
                beforeSend: function()
                {
                    $('body').AddAjaxLoader();
                },
                success: function(response)
                {
                    renderResponse(response);
                    $('.al_security_form').ResetFormElements();
                },
                error: function(err)
                {
                    $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                },
                complete: function()
                {
                    $('body').RemoveAjaxLoader();
                }
            });
        }
        catch(e){
            $('body').showAlert('An unespected error occoured in al-security file method save. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
        }
    }

    function remove(element, route, entity)
    {
        element.click(function()
        {
            if(confirm(translate("Are you sure you want to remove the " + entity)))
            {
                try{
                    $.ajax({
                        type: 'POST',
                        url: frontController + 'backend/users/' + $('#al_available_languages option:selected').val() + '/' + route,
                        data: {'id' : element.attr('data-entity-id') },
                        beforeSend: function()
                        {
                            $('body').AddAjaxLoader();
                        },
                        success: function(response)
                        {
                            renderResponse(response);

                            $('.al_security_form').ResetFormElements();
                        },
                        error: function(err)
                        {
                            $('body').showAlert(err.responseText, 0, 'alert-error alert-danger');
                        },
                        complete: function()
                        {
                            $('body').RemoveAjaxLoader();
                        }
                    });
                }
                catch(e){
                    $('body').showAlert('An unespected error occoured in al-security file method remove. Here is the error from the server:<br/><br/>' + e + '<br/><br/>Please open an issue at <a href="https://github.com/redkite-labs/RedKiteCmsBundle/issues">Github</a> reporting this entire message.', 0, 'alert-error alert-danger');
                }
            }
        });
    }
    
    function renderResponse(response)
    {
        $(response).each(function(key, item)
        {
            switch(item.key)
            {
                case "message": 
                    $('body').showAlert(item.value);
                    
                    break;
                case "refresh_list":
                    $('#al_sidebar_left').html(item.value);

                    break;
            }
        });

        $('body').security('init');
    }
    
    $.fn.security = function( method ) {         
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.inlinelist' );
        }   
    };
})($);