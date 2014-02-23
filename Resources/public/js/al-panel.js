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
    
    var cpControlPanelType = 'mini';
    var cpPanelState = 'minimized';
    var cpPanelVerticalAlign = 'top';
    
    var methods = {
        init: function() 
        {
            var cpControlPanelTypeSaved = localStorage.getItem("cpControlPanelType");
            var cpPanelStateSaved = localStorage.getItem("cpPanelState");
            var cpPanelVerticalAlignSaved = localStorage.getItem("cpPanelVerticalAlign");
            
            if (cpControlPanelTypeSaved != null) {
                cpControlPanelType = cpControlPanelTypeSaved;
            }
            
            if (cpPanelStateSaved != null) {
                cpPanelState = cpPanelStateSaved;
            }
            
            if (cpPanelVerticalAlignSaved != null && cpControlPanelType == 'mini') {
                cpPanelVerticalAlign = cpPanelVerticalAlignSaved;
            }
            
            restorePanel();
            
            $('#rk-up').on('click', function() {
                switch (cpPanelVerticalAlign) {
                    case "middle":
                        alignTop();
                        cpPanelVerticalAlign = "top";
                        break;
                    case "bottom":
                        alignMiddle();
                        cpPanelVerticalAlign = "middle";
                        break;
                }
                
                localStorage.setItem("cpPanelVerticalAlign", cpPanelVerticalAlign);
            });
            
            $('#rk-down').on('click', function() {
                switch (cpPanelVerticalAlign) {
                    case "top":
                        alignMiddle();
                        cpPanelVerticalAlign = "middle";
                        break;
                    case "middle":
                        alignBottom();
                        cpPanelVerticalAlign = "bottom";
                        break;
                }
                
                localStorage.setItem("cpPanelVerticalAlign", cpPanelVerticalAlign);
            });
            
            $('.rk-navigation').on('click', function() {
                $('#rk-cp-nav-button').toggle();
                
                var left = 34;
                if (cpPanelState == "maximized" && cpControlPanelType == "full") {
                    left += 130;
                }
                $('#rk-cp-nav-button').position({
                    my: "left+" + left + "px top-11px",
                    at: "left top",
                    of: this
                });
            });

            $('.rk-navbar-toggle').on('click', function(){
                if (cpControlPanelType == 'mini') {
                    showControlPanelFull();
                    cpControlPanelType = 'full';
                    localStorage.setItem("cpControlPanelType", cpControlPanelType);
                } else {
                    showControlPanelMini();
                    cpControlPanelType = 'mini';
                    localStorage.setItem("cpControlPanelType", cpControlPanelType);
                }
            });
            
            $('#rk-maximize').on('click', function() {
                cpPanelState = "maximized";
                showControlPanelFull();
                localStorage.setItem("cpPanelState", cpPanelState);
            });

            $('#rk-minimize').on('click', function() {
                cpPanelState = "minimized";
                showControlPanelFull();
                localStorage.setItem("cpPanelState", cpPanelState);
            });
        }
    };

    function restorePanel()
    {
        if (cpControlPanelType == 'mini') {
            showControlPanelMini();
        } else {
            showControlPanelFull();
        }
        
        restoreVerticalAlignment();
    }
    
    function restoreVerticalAlignment()
    {
        switch (cpPanelVerticalAlign) {
            case "top":
                alignTop();
                break;
            case "middle":
                alignMiddle();
                break;
            case "bottom":
                alignBottom();
                break;
        }
    }
    
    function alignTop()
    {
        $('.rk-control-panel').css('top', '0');
        $('#rk-up').css('display', 'none');
        $('#rk-down').css('display', 'block');
    }
    
    function alignMiddle()
    {
        $('.rk-control-panel').css('top', '40%');
        $('.rk-control-panel').css('bottom', '');
        $('#rk-up').css('display', 'block');
        $('#rk-down').css('display', 'block');
    }
    
    function alignBottom()
    {
        $('.rk-control-panel').css('top', '');
        $('.rk-control-panel').css('bottom', '40px');
        $('#rk-up').css('display', 'block');
        $('#rk-down').css('display', 'none');
    }
    
    function showControlPanelMini()
    {
        $('.rk-control-panel')
            .removeClass('rk-control-panel-minimized')
            .removeClass('rk-control-panel-maximized')
        ;
        
        $('.rk-navigation-minimized').show();
        $('.rk-hide-minimized').hide();
        $('.rk-control-panel-mini').show();
        $('#rk-control-panel-full').hide();
        restoreVerticalAlignment();
    }
    
    function showControlPanelFull()
    {
        $('.rk-control-panel-mini').hide();
        $('#rk-control-panel-full').show();
        if (cpPanelState == 'minimized') {
            $('.rk-control-panel')
                .addClass('rk-control-panel-minimized')
                .removeClass('rk-control-panel-maximized')
            ;
            
            $('.rk-hide-minimized').hide();
            $('.rk-navigation-minimized').show();
            //$('#rk-navigation-full-container').show();
            showMaximizeCommands();
        } else {
            $('.rk-control-panel')
                .removeClass('rk-control-panel-minimized')
                .addClass('rk-control-panel-maximized')
            ;
            $('.rk-hide-minimized').show();
            $('.rk-navigation-minimized').hide();
            $('#rk-cp-nav-button').hide();
            //$('#rk-navigation-full-container').hide();
            showMinimizeCommands();
        }
        $('.rk-control-panel').css('bottom', '').css('top', '0px');
        $('#rk-up').css('display', 'none');
        $('#rk-down').css('display', 'none');
    }
    
    function showMinimizeCommands()
    {  
        $('#rk-minimize').show();                
        $('#rk-maximize').hide();   
    }
    
    function showMaximizeCommands()
    {  
        $('#rk-minimize').hide();                
        $('#rk-maximize').show();   
    }
    
    $.fn.controlPanel = function( method, options ) {        
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.blocksEditor' );
        }   
    };
})($);

;(function($){
    $.fn.OpenPanel = function(html, callback)
    {
        if(callback == null) callback = function(){};
        this.each(function()
        {
            var panel = this;
            if($('#al_panel_contents').length == 0)
            {
                var panelBody = document.createElement("DIV");
                panelBody.id = "al_panel_body";
                panel.appendChild(panelBody);
                
                var panelContents = document.createElement("DIV");
                panelContents.id = "al_panel_contents";
                panelBody.appendChild(panelContents);
                $(panelContents).html(html);
                
                $(panel).show("slide", { direction: "left" }, 500, callback);
            }
            else {
                $('#al_panel_contents').html(html);
                callback();
            }
            
            $('#al_panel_closer').unbind().click(function()
            {
                $(panel).hide("slide", { direction: "left" }, 500, function(){ $(this).empty(); });
            });
        });
    };
})($);
