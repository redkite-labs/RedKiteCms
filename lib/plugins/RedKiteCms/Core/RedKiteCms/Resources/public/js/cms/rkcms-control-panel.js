/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

var ControlPanelModel = function (status, moveMode) {
    var self = this;    
    if (null == status) {
        status = 'off';
    }

    if (null == moveMode) {
        moveMode = 'off';
    }

    self.editorStatus = ko.observable(status);
    self.moveMode = ko.observable(moveMode);
    self.isTheme = ko.observable(isTheme);

    _toggleButtonPressed = function(element, pressed)
    {
        if(!pressed) {
            element.addClass('btn-default');
            element.addClass('active');

            return;
        }

        element.removeClass('btn-default');
        element.removeClass('active');
    }.bind(self);

    _closeControlPanel = function()
    {
        $('#rkcms-control-panel-btn').popover('hide');
    }.bind(self);

    _stopEditor = function(view, event)
    {
        blockEditorModel.closeEditor();
        slotEditorModel.closeEditor();

        $('.rkcms-slot').each(function(){
            var slot = ko.dataFor(this);
            slot.activate(false);
        });

        $('#rkcms-control-panel-btn').removeClass('btn-success');
        _updateStatus('off');

        $(document).trigger("rkcms.event.blocks_editor_stopped", [ self, event ]);
    }.bind(self);
    
    _stopMoving = function()
    {
        $(".rkcms-sortable").sortable("destroy");
        $('#rkcms-control-panel-btn').removeClass('btn-warning');
        _updateMoveMode('off');
    }.bind(self);
    
    _updateStatus = function(status)
    {
        self.editorStatus(status);
        
        // we must save the current status outside the model because the popover destroys
        // its content when it is closed
        $('body').data('rkcms.editor-button-status', status);
    }.bind(self);

    _updateMoveMode = function(status)
    {
        self.moveMode(status);

        // we must save the current status outside the model because the popover destroys
        // its content when it is closed
        $('body').data('rkcms.editor-move-mode', status);
    }.bind(self);

    _toggleButtonPressed($('#rkcms-page-published'), !pagePublished);
};

ControlPanelModel.prototype.startEdit = function (view, event)
{
    var self = this;
    if (self.moveMode() == 'on') {
        _stopMoving();
    }

    $('.rkcms-slot').each(function(){
        var slot = ko.dataFor(this);
        slot.activate(true);
    });

    _updateStatus('on');
    $('#rkcms-control-panel-btn').addClass('btn-success');
    _closeControlPanel();

    $(document).trigger("rkcms.event.blocks_editor_started", [ self, event ]);
};

ControlPanelModel.prototype.stopEdit = function (view, event)
{
    _stopEditor(view, event);
    _closeControlPanel();
};

ControlPanelModel.prototype.dashboard = function()
{
    location.href = frontcontroller + '/backend/dashboard';
};

ControlPanelModel.prototype.seoPanel = function (view, event)
{
    var seoModel = ko.dataFor(document.getElementById('rkcms-seo'));
    var element = $(event.target).parent();
    _toggleButtonPressed(element, seoModel.toggleBlocksEditor());

    _closeControlPanel();

    seoModel.dockRight();
    seoModel.toggle();
};

ControlPanelModel.prototype.startMove = function (view, event)
{
    _stopEditor(view, event);

    $('#rkcms-control-panel-btn').addClass('btn-warning');
    _closeControlPanel();
    _updateMoveMode('on');
    $('.rkcms-sortable').blocksmover();
};

ControlPanelModel.prototype.stopMove = function ()
{
    _stopMoving();
};

ControlPanelModel.prototype.savePage = function (view, event)
{
    var self = this;
    if (self.isTheme() == 'on'){
        alertDialog(redkitecmsDomain.frontend_save_page_disable_in_theme_mode, null, 'info');

        return;
    }

    var message = redkitecmsDomain.frontend_confirm_save_page;
    confirmDialog(message, function(){
        $(document).trigger("rkcms.event.saving_page", [ self, event ]);

        var url = frontcontroller + '/backend/page/save';
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            alertDialog(redkitecmsDomain.frontend_page_saved, null, 'info');
        });
    });
};

ControlPanelModel.prototype.saveAllPages = function ()
{
    var self = this;
    if (self.isTheme() == 'on'){
        alertDialog(redkitecmsDomain.frontend_save_site_disable_in_theme_mode, null, 'info');

        return;
    }

    var message = redkitecmsDomain.frontend_confirm_save_site;
    confirmDialog(message, function(){
        var url = frontcontroller + '/backend/page/collection/save-all';
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            alertDialog(redkitecmsDomain.frontend_site_saved, null, 'info');
        });
    });
};

ControlPanelModel.prototype.saveTheme = function ()
{
    var message = redkitecmsDomain.frontend_confirm_save_theme;
    confirmDialog(message, function(){
        var url = frontcontroller + '/backend/theme/save';
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            alertDialog(redkitecmsDomain.frontend_theme_saved, null, 'info');
        });
    });
};

ControlPanelModel.prototype.pagePublished = function (view, event)
{
    var message = redkitecmsDomain.frontend_confirm_publish_page;
    var url = frontcontroller + '/backend/page/publish';
    if (pagePublished) {
        message = redkitecmsDomain.frontend_confirm_hide_page;
        url =  frontcontroller + '/backend/page/hide';
    }

    var element = $(event.target).parent();
    confirmDialog(message, function(){
        var data = {
            'page':  page,
            'language': language,
            'country': country
        };
        executeAjax(url, data, function(){
            _toggleButtonPressed(element, pagePublished);
            pagePublished = !pagePublished;
        });
    },function(){
        _toggleButtonPressed(element, !pagePublished);
    });
};

ControlPanelModel.prototype.logout = function()
{
    var message = redkitecmsDomain.frontend_confirm_logout;
    confirmDialog(message, function(){
        location.href = frontcontroller + '/backend/logout';
    });
};