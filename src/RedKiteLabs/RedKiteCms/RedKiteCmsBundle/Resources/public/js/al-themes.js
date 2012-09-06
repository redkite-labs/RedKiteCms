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
    $.fn.importTheme =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                var data = $(this).metadata();

                $.ajax({
                  type: 'POST',
                  url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_importTheme',
                  data: {'themeName' : data.themeName},
                  beforeSend: function()
                  {
                    $('body').AddAjaxLoader();
                  },
                  success: function(html)
                  {
                    $('#al_themes_sections').html(html);
                    ObserveThemeCommands();
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
            });
        });
    };

    $.fn.activateTheme =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                var data = $(this).metadata();
                location.href = frontController + 'backend/' + $('#al_available_languages').val() + '/al_activateTheme/' + data.themeName + '/' + $('#al_languages_navigator option:selected').val() + '/' + $('#al_pages_navigator option:selected').text();
            });
        });
    };

    $.fn.showThemeFixer =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                var data = $(this).metadata();

                $.ajax({
                  type: 'POST',
                  url: frontController + $('#al_available_languages').val() + '/al_showThemeFixer',
                  data: {'themeName' : data.themeName},
                  beforeSend: function()
                  {
                    $('body').AddAjaxLoader();
                  },
                  success: function(html)
                  {
                    $('#al_dialog').html(html);
                    $('#al_dialog').dialog('open');
                  },
                  error: function(err)
                  {
                    $('#al_dialog').html(err.responseText);
                    $('#al_dialog').dialog('open');
                  },
                  complete: function(html)
                  {
                    $('body').RemoveAjaxLoader();
                  }
                });
            });
        });
    };

    extractTheme =function()
    {
        var success = false;
        $.ajax({
              type: 'POST',
              url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_extractTheme',
              beforeSend: function()
              {
                $('body').AddAjaxLoader();
              },
              success: function(html)
              {
                $('#al_themes_sections').html(html);
                ObserveThemeCommands();
                //success = true;
              },
              error: function(err)
              {
                $('#al_dialog').html(err.responseText);
                $('#al_dialog').dialog('open');
              },
              complete: function()
              {
                $('body').RemoveAjaxLoader();
                //if(success) installAssets();
              }
            });
    };
    /*
    installAssets =function()
    {
        $.ajax({
              type: 'POST',
              url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_installAssets',
              beforeSend: function()
              {
                $('body').AddAjaxLoader();
              },
              success: function(html)
              {
                $('#al_dialog').html(html);
                $('#al_dialog').dialog('open');
              },
              error: function(err)
              {
                $('#al_dialog').html(err.responseText);
                $('#al_dialog').dialog('open');
              },
              complete: function(html)
              {
                $('body').RemoveAjaxLoader();
              }
            });
    };*/

    $.fn.removeTheme =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                var data = $(this).metadata();
                if(confirm("Are you sure to remove the " + data.themeName + " theme?"))
                {
                    $.ajax({
                      type: 'POST',
                      url: frontController + 'backend/' + $('#al_available_languages').val() + '/al_removeTheme',
                      data: {'themeName' : data.themeName},
                      beforeSend: function()
                      {
                        $('body').AddAjaxLoader();
                      },
                      success: function(html)
                      {
                        $('#al_themes_sections').html(html);
                        ObserveThemeCommands();
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
                }
            });
        });
    };
})($);

ObserveThemeCommands =function()
{
    $('.al_theme_activator').activateTheme();
    $('.al_themes_fixer').showThemeFixer();
    $('.al_theme_importer').importTheme();
    $('.al_theme_remover').removeTheme();


};
