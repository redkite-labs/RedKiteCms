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
    $.fn.activateTheme =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                var data = $(this).metadata();
                location.href = frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_activateCmsTheme/' + data.themeName + '/' + $('#al_languages_navigator').html() + '/' + $('#al_pages_navigator').html();
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
                  url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_showThemeFixer',
                  data: {
                      'themeName' : data.themeName
                  },
                  beforeSend: function()
                  {
                    $('body').AddAjaxLoader();
                  },
                  success: function(html)
                  {
                    $('body').showDialog(html);
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
    };
    
    $.fn.startFromTheme =function()
    {
        this.each(function()
        {
            $(this).click(function()
            {
                if ( ! confirm('WARNING: this command will destroy all the saved data and start a new site base on the choosen theme from the scratch: are you sure to continue?')) {
                    return;
                }
                
                var data = $(this).metadata();

                $.ajax({
                  type: 'POST',
                  url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/startFromTheme',
                  data: {
                      'themeName' : data.themeName
                  },
                  beforeSend: function()
                  {
                    $('body').AddAjaxLoader();
                  },
                  success: function(html)
                  {
                    $('body').showAlert(html);
                    location.href = frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_activateCmsTheme/' + data.themeName + '/' + $('#al_languages_navigator').html() + '/' + $('#al_pages_navigator').html();
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
    };
    
})($);

ObserveThemeCommands =function()
{
    try {
        $('.al_theme_activator').unbind().activateTheme();
        $('.al_themes_fixer').unbind().showThemeFixer();
        $('.al_start_from_theme').unbind().startFromTheme();
    } catch (e) {}
};
