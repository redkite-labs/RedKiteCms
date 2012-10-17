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
    $.fn.ChangeTemplate =function()
    {
        this.each(function()
        {
            $(this).change(function()
            {
                $(this).SaveTemplateMapping();
                var template = $('#al_templates_selector option:selected').text();
                location.href = frontController + 'backend/' + $('#al_available_languages').val() + '/al_previewTheme/' + $('#al_referal_language').text() + '/' + $('#al_referal_page').text() + '/' +  $('#al_current_theme').text() + '/' + template;
            });
        });

        return this;
    };

    $.fn.ShowTemplateSlots =function()
    {
        this.each(function()
        {
            $(this).change(function()
            {
                var storageKey = getCurrentStorageTemplateName();
                if (localStorage[storageKey] != null) {
                    if (!confirm("Changing the template when a current mappin is active, will destroy that map: are you sure to continue?")) {                        
                        return;
                    }
                    
                    $('.al_locker').each(function() {
                        $(this).DoUnlockSlot();
                    });
                    
                    localStorage.removeItem(storageKey);
                }

                $('#al_slots').find(':visible').each(function() {
                    $(this).hide();
                });

                var template = $('#al_active_template_selector option:selected').text();
                $('#al_map_' + template).show();
            });
        });

        return this;
    };

    $.fn.ActivateSlot =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                if ($(this).hasClass('al_slot_mapped')){
                    return this;
                }

                var slotActive = $('.al_slot_active');
                slotActive.removeClass('al_slot_active');

                // Restores the original slot content
                if ( ! slotActive.hasClass('al_slot_mapped') && slotActive.data('content') != null ) {
                    slotActive.html(slotActive.data('content'));
                }

                // Adds the slot active class only when the slot is not the active one,
                // bacause in that case, it must be removed
                if ($(this).attr('id') != slotActive.attr('id')) {
                    $(this).addClass('al_slot_active').data('content', $(this).html());
                }
                
                return false;
            });
        });

        return this;
    };

    $.fn.InjectContent =function()
    {
        this.each(function()
        {
            $(this).mouseenter(function()
            {
                var element = $('.al_template_slot.al_slot_active');
                if ( ! $(this).hasClass('al_slot_assigned') && ! element.hasClass('al_slot_mapped')) {
                    if (element.length == 1) {
                        var blockKey = "#" + $(this).attr('rel');
                        element.html($(blockKey).html());
                    }
                }
            });

            $(this).click(function()
            {
                var element = $('.al_template_slot.al_slot_active');
                if (element.length == 1) {
                    element
                        .removeClass('al_slot_active')
                        .addClass('al_slot_mapped');

                    $(this)
                        .addClass('al_slot_assigned')
                        .data('mapped_slot', element.attr('id'));

                    var key = $(this).attr('rel');
                    $("#al_locker_" + key)
                        .attr('rel', key)
                        .show();
                        
                    $('body').SaveTemplateMapping();
                }
                
                return false;
            });
        });

        return this;
    };

    $.fn.UnlockSlot =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                $(this).DoUnlockSlot();                
                $('body').SaveTemplateMapping();
                
                return false;
            });
        });

        return this;
    };
    
    $.fn.DoUnlockSlot =function()
    {
        var slotPlaceholder = $("#al_slot_" + $(this).attr('rel'));
        var slot = $("#" + slotPlaceholder.data('mapped_slot'));

        $(slotPlaceholder)
            .removeClass('al_slot_assigned')
            .data('mapped_slot', null);

        slot
            .removeClass('al_slot_mapped')
            .html(slot.data('content'));

        $(this).hide();

        return this;
    };

    $.fn.SaveTemplateMapping =function()
    {
        var newTemplate = getCurrentTemplateName();
        var oldTemplate = $('#al_active_template_selector option:selected').val();

        var c = 0;
        var slotData = [];
        $('#al_map_' + oldTemplate).find('.al_slot').each(function()
        {
            if ($(this).hasClass('al_slot_assigned')) {
                var slotName = $(this).data('mapped_slot');
                var mappedSlot = $("#" + slotName);
                var slot = {
                    'slot_placeholder': $(this).attr('id'),
                    'slot': slotName,
                    'default_content': mappedSlot.data('content')
                };

                slotData[c] = slot;
                c++;
            }

/*                 if (Modernizr.localstorage) {
// window.localStorage is available!
} else {
// no native support for HTML5 storage :(
// maybe try dojox.storage or a third-party solution
}*/
        });

        var data = {
            'new_template' : newTemplate,
            'old_template' : oldTemplate,            
            'slots' : slotData
        };
        var storageKey = getCurrentStorageTemplateName();
        localStorage[storageKey] = JSON.stringify(data);
        console.log(localStorage[storageKey]);
    };

    $.fn.RestoreTemplateMapping =function()
    {
        var storageKey = getCurrentStorageTemplateName();
        var data = localStorage[storageKey];
        if (data != null) {
            data = JSON.parse(data);

            $('#al_active_template_selector').val(data.old_template);
            $('#al_map_' + data.old_template).show();
            $(data.slots).each(function(key, value) {
                $('#' + value.slot_placeholder)
                    .addClass('al_slot_assigned')
                    .data('mapped_slot', value.slot)
                ;

                var slotKey = $('#' + value.slot_placeholder).attr('rel');
                $('#' + value.slot)
                    .addClass('al_slot_mapped')
                    .data('content', value.default_content)
                    .html($('#' + slotKey).html())
                ;
                $('#al_locker_' + slotKey)
                    .attr('rel', slotKey)
                    .show();
            });
        }

        return this;
    };

    $.fn.SaveActiveTheme =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                var c = 0;
                var templates = [];                
                $('#al_templates_selector option').each(function()
                {
                    var templateName = $(this).val();
                    if (templateName != "") {
                        var storageKey = "alphalemon." + $('#al_current_theme').text() + "." + templateName;
                        var savedData = localStorage[storageKey];
                        if (savedData != null) {                            
                            savedData = JSON.parse(savedData);
                            templates[c] = savedData;
                            c++;
                        }
                    }
                });
                
                var obj = {
                    'theme': $('#al_current_theme').text(),
                    'templates': templates
                };
                
                $.ajax({
                    type: 'POST',
                    url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_saveActiveTheme',
                    data: {
                        'data': $.param(obj)
                    },
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(html)
                    {
                        //$('#al_slots').html(html);
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
            });
            /*
            $(this).click(function()
            {
                $.ajax({
                    type: 'POST',
                    url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_saveActiveTheme',
                    data: {
                        'language' : $('#al_referal_language').text()
                    },
                    beforeSend: function()
                    {
                        $('body').AddAjaxLoader();
                    },
                    success: function(html)
                    {
                        //$('#al_slots').html(html);
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
            });*/
        });
    };
})($);

function getCurrentStorageTemplateName()
{
    return "alphalemon." + $('#al_current_theme').text() + "." + getCurrentTemplateName();
}

function getCurrentTemplateName()
{
    return $('#al_current_template').text();
}

function ObserveThemesPreviewCommands()
{
    try {
        $('#al_templates_selector').ChangeTemplate();
        $('.al_template_slot').ActivateSlot();
        $('.al_slot').InjectContent();
        $('#al_active_template_selector').ShowTemplateSlots();
        $('.al_locker').UnlockSlot();      
        $('#al_save').SaveActiveTheme();
        
        $('body').RestoreTemplateMapping();

    } catch (e) {alert(e)}
}


