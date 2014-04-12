/**
 * This file is part of the RedKiteCms CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

 $(document).ready(function() {
    $(document).on("cmsStarted", function(event, block)
    {
        var tinymceContainer = document.createElement("div");
        $(tinymceContainer)
            .attr('id', 'rk-tinymce-container')
            .css('position', 'absolute')
            .css('z-index', '60000')
        ;
        $('body').append(tinymceContainer);
        
        initTinyMCE();        
    });
    
    $(document).on("cmsStopped", function(event, block)
    {
        tinymce.remove();
        $('#rk-tinymce-container').remove();
    });
    
    $(document).on("startEditingBlocks", function(event, block){
        if (block.attr('data-type') != 'Text') {
            return;
        }
        
        block.highligther('deactivate');
        $('#rk-tinymce-container')
            .css('width', '715px')
            .css('height', '69px')
            .position({
                my: "left bottom",
                at: "left top",
                of: block,
                collision: "flipfit flipfit"
            })
            .show()
        ;
    });
    
    $(document).on("stopEditingBlocks", function(event, block){
        if (block.attr('data-type') != 'Text') {
            return;
        }
        
        $('#rk-tinymce-container')
            .css('width', '0')
            .css('height', '0')
        ;
    });
    
    $(document).on("blockEdited", function(event)
    {
        reinitTinyMce();
    });
    
    $(document).on("blockDeleted", function(event)
    {
        reinitTinyMce();
    });
    
    function reinitTinyMce()
    {
        tinymce.remove();
        initTinyMCE();
    }
});

function pippo() {
    var ICONS;

    var icon = function(id) {
        return '<i class="fa fa-' + id + '"></i> ';
    }

    var createControl = function(name, controlManager) {alert('a');
        if (name != 'fontAwesomeIconSelect') return null;
        var listBox = controlManager.createListBox('fontAwesomeIconSelect', {
            title: 'Icons',
            onselect: function(v) {
                var editor = this.control_manager.editor;
                if (v) {
                    editor.selection.setContent(icon(v));
                }
                return false;
            }
        });

        for (var i = 0; i < ICONS.length; i++) {
            var _id = ICONS[i];
            listBox.add(icon(_id) + ' ' + _id, _id);
        }

        return listBox;
    };

    tinymce.create('tinymce.plugins.FontAwesomeIconsPlugin', {
        createControl: createControl
    });

    tinymce.PluginManager.add('font_awesome_icons', tinymce.plugins.FontAwesomeIconsPlugin);

    ICONS = ["adjust", "adn", "align-center", "align-justify", "align-left", "align-right", "ambulance", "anchor", "android", "angle-double-down", "angle-double-left", "angle-double-right", "angle-double-up", "angle-down", "angle-left", "angle-right", "angle-up", "apple", "archive", "arrow-circle-down", "arrow-circle-left", "arrow-circle-o-down", "arrow-circle-o-left", "arrow-circle-o-right", "arrow-circle-o-up", "arrow-circle-right", "arrow-circle-up", "arrow-down", "arrow-left", "arrow-right", "arrow-up", "arrows", "arrows-alt", "arrows-h", "arrows-v", "asterisk", "backward", "ban", "bar-chart-o", "barcode", "bars", "beer", "bell", "bell-o", "bitbucket", "bitbucket-square", "bitcoin", "bold", "bolt", "book", "bookmark", "bookmark-o", "briefcase", "btc", "bug", "building-o", "bullhorn", "bullseye", "calendar", "calendar-o", "camera", "camera-retro", "caret-down", "caret-left", "caret-right", "caret-square-o-down", "caret-square-o-left", "caret-square-o-right", "caret-square-o-up", "caret-up", "certificate", "chain", "chain-broken", "check", "check-circle", "check-circle-o", "check-square", "check-square-o", "chevron-circle-down", "chevron-circle-left", "chevron-circle-right", "chevron-circle-up", "chevron-down", "chevron-left", "chevron-right", "chevron-up", "circle", "circle-o", "clipboard", "clock-o", "cloud", "cloud-download", "cloud-upload", "cny", "code", "code-fork", "coffee", "cog", "cogs", "columns", "comment", "comment-o", "comments", "comments-o", "compass", "compress", "copy", "credit-card", "crop", "crosshairs", "css3", "cut", "cutlery", "dashboard", "dedent", "desktop", "dollar", "dot-circle-o", "download", "dribbble", "dropbox", "edit", "eject", "ellipsis-h", "ellipsis-v", "envelope", "envelope-o", "eraser", "eur", "euro", "exchange", "exclamation", "exclamation-circle", "exclamation-triangle", "expand", "external-link", "external-link-square", "eye", "eye-slash", "facebook", "facebook-square", "fast-backward", "fast-forward", "female", "fighter-jet", "file", "file-o", "file-text", "file-text-o", "files-o", "film", "filter", "fire", "fire-extinguisher", "flag", "flag-checkered", "flag-o", "flash", "flask", "flickr", "floppy-o", "folder", "folder-o", "folder-open", "folder-open-o", "font", "forward", "foursquare", "frown-o", "gamepad", "gavel", "gbp", "gear", "gears", "gift", "github", "github-alt", "github-square", "gittip", "glass", "globe", "google-plus", "google-plus-square", "group", "h-square", "hand-o-down", "hand-o-left", "hand-o-right", "hand-o-up", "hdd-o", "headphones", "heart", "heart-o", "home", "hospital-o", "html5", "inbox", "indent", "info", "info-circle", "inr", "instagram", "italic", "jpy", "key", "keyboard-o", "krw", "laptop", "leaf", "legal", "lemon-o", "level-down", "level-up", "lightbulb-o", "link", "linkedin", "linkedin-square", "linux", "list", "list-alt", "list-ol", "list-ul", "location-arrow", "lock", "long-arrow-down", "long-arrow-left", "long-arrow-right", "long-arrow-up", "magic", "magnet", "mail-forward", "mail-reply", "mail-reply-all", "male", "map-marker", "maxcdn", "medkit", "meh-o", "microphone", "microphone-slash", "minus", "minus-circle", "minus-square", "minus-square-o", "mobile", "mobile-phone", "money", "moon-o", "music", "outdent", "pagelines", "paperclip", "paste", "pause", "pencil", "pencil-square", "pencil-square-o", "phone", "phone-square", "picture-o", "pinterest", "pinterest-square", "plane", "play", "play-circle", "play-circle-o", "plus", "plus-circle", "plus-square", "plus-square-o", "power-off", "print", "puzzle-piece", "qrcode", "question", "question-circle", "quote-left", "quote-right", "random", "refresh", "renren", "repeat", "reply", "reply-all", "retweet", "rmb", "road", "rocket", "rotate-left", "rotate-right", "rouble", "rss", "rss-square", "rub", "ruble", "rupee", "save", "scissors", "search", "search-minus", "search-plus", "share", "share-square", "share-square-o", "shield", "shopping-cart", "sign-in", "sign-out", "signal", "sitemap", "skype", "smile-o", "sort", "sort-alpha-asc", "sort-alpha-desc", "sort-amount-asc", "sort-amount-desc", "sort-asc", "sort-desc", "sort-down", "sort-numeric-asc", "sort-numeric-desc", "sort-up", "spinner", "square", "square-o", "stack-exchange", "stack-overflow", "star", "star-half", "star-half-empty", "star-half-full", "star-half-o", "star-o", "step-backward", "step-forward", "stethoscope", "stop", "strikethrough", "subscript", "suitcase", "sun-o", "superscript", "table", "tablet", "tachometer", "tag", "tags", "tasks", "terminal", "text-height", "text-width", "th", "th-large", "th-list", "thumb-tack", "thumbs-down", "thumbs-o-down", "thumbs-o-up", "thumbs-up", "ticket", "times", "times-circle", "times-circle-o", "tint", "toggle-down", "toggle-left", "toggle-right", "toggle-up", "trash-o", "trello", "trophy", "truck", "try", "tumblr", "tumblr-square", "turkish-lira", "twitter", "twitter-square", "umbrella", "underline", "undo", "unlink", "unlock", "unlock-alt", "unsorted", "upload", "usd", "user", "user-md", "users", "video-camera", "vimeo-square", "vk", "volume-down", "volume-off", "volume-up", "warning", "weibo", "wheelchair", "windows", "won", "wrench", "xing", "xing-square", "yen", "youtube", "youtube-play", "youtube-square"];
};

function initTinyMCE()
{
/*
    var ICONS;

    var icon = function(id) {
        return '<i class="fa fa-' + id + '"></i> ';
    }

    var createControl = function(name, controlManager) {alert('a');
        if (name != 'fontAwesomeIconSelect') return null;
        var listBox = controlManager.createListBox('fontAwesomeIconSelect', {
            title: 'Icons',
            onselect: function(v) {
                var editor = this.control_manager.editor;
                if (v) {
                    editor.selection.setContent(icon(v));
                }
                return false;
            }
        });

        for (var i = 0; i < ICONS.length; i++) {
            var _id = ICONS[i];
            listBox.add(icon(_id) + ' ' + _id, _id);
        }

        return listBox;
    };

    tinymce.create('tinymce.plugins.FontAwesomeIconsPlugin', {
        createControl: createControl
    });

    tinymce.PluginManager.add('font_awesome_icons', tinymce.plugins.FontAwesomeIconsPlugin);

    ICONS = ["adjust", "adn", "align-center", "align-justify", "align-left", "align-right", "ambulance", "anchor", "android", "angle-double-down", "angle-double-left", "angle-double-right", "angle-double-up", "angle-down", "angle-left", "angle-right", "angle-up", "apple", "archive", "arrow-circle-down", "arrow-circle-left", "arrow-circle-o-down", "arrow-circle-o-left", "arrow-circle-o-right", "arrow-circle-o-up", "arrow-circle-right", "arrow-circle-up", "arrow-down", "arrow-left", "arrow-right", "arrow-up", "arrows", "arrows-alt", "arrows-h", "arrows-v", "asterisk", "backward", "ban", "bar-chart-o", "barcode", "bars", "beer", "bell", "bell-o", "bitbucket", "bitbucket-square", "bitcoin", "bold", "bolt", "book", "bookmark", "bookmark-o", "briefcase", "btc", "bug", "building-o", "bullhorn", "bullseye", "calendar", "calendar-o", "camera", "camera-retro", "caret-down", "caret-left", "caret-right", "caret-square-o-down", "caret-square-o-left", "caret-square-o-right", "caret-square-o-up", "caret-up", "certificate", "chain", "chain-broken", "check", "check-circle", "check-circle-o", "check-square", "check-square-o", "chevron-circle-down", "chevron-circle-left", "chevron-circle-right", "chevron-circle-up", "chevron-down", "chevron-left", "chevron-right", "chevron-up", "circle", "circle-o", "clipboard", "clock-o", "cloud", "cloud-download", "cloud-upload", "cny", "code", "code-fork", "coffee", "cog", "cogs", "columns", "comment", "comment-o", "comments", "comments-o", "compass", "compress", "copy", "credit-card", "crop", "crosshairs", "css3", "cut", "cutlery", "dashboard", "dedent", "desktop", "dollar", "dot-circle-o", "download", "dribbble", "dropbox", "edit", "eject", "ellipsis-h", "ellipsis-v", "envelope", "envelope-o", "eraser", "eur", "euro", "exchange", "exclamation", "exclamation-circle", "exclamation-triangle", "expand", "external-link", "external-link-square", "eye", "eye-slash", "facebook", "facebook-square", "fast-backward", "fast-forward", "female", "fighter-jet", "file", "file-o", "file-text", "file-text-o", "files-o", "film", "filter", "fire", "fire-extinguisher", "flag", "flag-checkered", "flag-o", "flash", "flask", "flickr", "floppy-o", "folder", "folder-o", "folder-open", "folder-open-o", "font", "forward", "foursquare", "frown-o", "gamepad", "gavel", "gbp", "gear", "gears", "gift", "github", "github-alt", "github-square", "gittip", "glass", "globe", "google-plus", "google-plus-square", "group", "h-square", "hand-o-down", "hand-o-left", "hand-o-right", "hand-o-up", "hdd-o", "headphones", "heart", "heart-o", "home", "hospital-o", "html5", "inbox", "indent", "info", "info-circle", "inr", "instagram", "italic", "jpy", "key", "keyboard-o", "krw", "laptop", "leaf", "legal", "lemon-o", "level-down", "level-up", "lightbulb-o", "link", "linkedin", "linkedin-square", "linux", "list", "list-alt", "list-ol", "list-ul", "location-arrow", "lock", "long-arrow-down", "long-arrow-left", "long-arrow-right", "long-arrow-up", "magic", "magnet", "mail-forward", "mail-reply", "mail-reply-all", "male", "map-marker", "maxcdn", "medkit", "meh-o", "microphone", "microphone-slash", "minus", "minus-circle", "minus-square", "minus-square-o", "mobile", "mobile-phone", "money", "moon-o", "music", "outdent", "pagelines", "paperclip", "paste", "pause", "pencil", "pencil-square", "pencil-square-o", "phone", "phone-square", "picture-o", "pinterest", "pinterest-square", "plane", "play", "play-circle", "play-circle-o", "plus", "plus-circle", "plus-square", "plus-square-o", "power-off", "print", "puzzle-piece", "qrcode", "question", "question-circle", "quote-left", "quote-right", "random", "refresh", "renren", "repeat", "reply", "reply-all", "retweet", "rmb", "road", "rocket", "rotate-left", "rotate-right", "rouble", "rss", "rss-square", "rub", "ruble", "rupee", "save", "scissors", "search", "search-minus", "search-plus", "share", "share-square", "share-square-o", "shield", "shopping-cart", "sign-in", "sign-out", "signal", "sitemap", "skype", "smile-o", "sort", "sort-alpha-asc", "sort-alpha-desc", "sort-amount-asc", "sort-amount-desc", "sort-asc", "sort-desc", "sort-down", "sort-numeric-asc", "sort-numeric-desc", "sort-up", "spinner", "square", "square-o", "stack-exchange", "stack-overflow", "star", "star-half", "star-half-empty", "star-half-full", "star-half-o", "star-o", "step-backward", "step-forward", "stethoscope", "stop", "strikethrough", "subscript", "suitcase", "sun-o", "superscript", "table", "tablet", "tachometer", "tag", "tags", "tasks", "terminal", "text-height", "text-width", "th", "th-large", "th-list", "thumb-tack", "thumbs-down", "thumbs-o-down", "thumbs-o-up", "thumbs-up", "ticket", "times", "times-circle", "times-circle-o", "tint", "toggle-down", "toggle-left", "toggle-right", "toggle-up", "trash-o", "trello", "trophy", "truck", "try", "tumblr", "tumblr-square", "turkish-lira", "twitter", "twitter-square", "umbrella", "underline", "undo", "unlink", "unlock", "unlock-alt", "unsorted", "upload", "usd", "user", "user-md", "users", "video-camera", "vimeo-square", "vk", "volume-down", "volume-off", "volume-up", "warning", "weibo", "wheelchair", "windows", "won", "wrench", "xing", "xing-square", "yen", "youtube", "youtube-play", "youtube-square"];
*/

    /*
    SELECT EXAMPLE

     tinymce.PluginManager.add('myexample', function(editor, url) {
     var self = this, button;

     function getValues() {
     return editor.settings.myKeyValueList;
     }
     // Add a button that opens a window
     editor.addButton('myexample', {
     type: 'listbox',
     text: 'My Example',
     values: getValues(),
     onselect: function() {
     //insert key
     editor.insertContent(this.value());

     //reset selected value
     this.value(null);
     },
     onPostRender: function() {
     //this is a hack to get button refrence.
     //there may be a better way to do this
     button = this;
     },
     });

     self.refresh = function() {
     //remove existing menu if it is already rendered
     if(button.menu){
     button.menu.remove();
     button.menu = null;
     }

     button.settings.values = button.settings.menu = getValues();
     };
     });


     Call following code block from ajax success method
     //Set new values to myKeyValueList
     tinyMCE.activeEditor.settings.myKeyValueList = [{text: 'newtext', value: 'newvalue'}];
     //Call plugin method to reload the dropdown
     tinyMCE.activeEditor.plugins.myexample.refresh();*/



    tinymce.PluginManager.add('example', function(editor, url) {
        function getValues() {
            return ["adjust", "adn", "align-center", "align-justify", "align-left", "align-right", "ambulance", "anchor", "android", "angle-double-down", "angle-double-left", "angle-double-right", "angle-double-up", "angle-down", "angle-left", "angle-right", "angle-up", "apple", "archive", "arrow-circle-down", "arrow-circle-left", "arrow-circle-o-down", "arrow-circle-o-left", "arrow-circle-o-right", "arrow-circle-o-up", "arrow-circle-right", "arrow-circle-up", "arrow-down", "arrow-left", "arrow-right", "arrow-up", "arrows", "arrows-alt", "arrows-h", "arrows-v", "asterisk", "backward", "ban", "bar-chart-o", "barcode", "bars", "beer", "bell", "bell-o", "bitbucket", "bitbucket-square", "bitcoin", "bold", "bolt", "book", "bookmark", "bookmark-o", "briefcase", "btc", "bug", "building-o", "bullhorn", "bullseye", "calendar", "calendar-o", "camera", "camera-retro", "caret-down", "caret-left", "caret-right", "caret-square-o-down", "caret-square-o-left", "caret-square-o-right", "caret-square-o-up", "caret-up", "certificate", "chain", "chain-broken", "check", "check-circle", "check-circle-o", "check-square", "check-square-o", "chevron-circle-down", "chevron-circle-left", "chevron-circle-right", "chevron-circle-up", "chevron-down", "chevron-left", "chevron-right", "chevron-up", "circle", "circle-o", "clipboard", "clock-o", "cloud", "cloud-download", "cloud-upload", "cny", "code", "code-fork", "coffee", "cog", "cogs", "columns", "comment", "comment-o", "comments", "comments-o", "compass", "compress", "copy", "credit-card", "crop", "crosshairs", "css3", "cut", "cutlery", "dashboard", "dedent", "desktop", "dollar", "dot-circle-o", "download", "dribbble", "dropbox", "edit", "eject", "ellipsis-h", "ellipsis-v", "envelope", "envelope-o", "eraser", "eur", "euro", "exchange", "exclamation", "exclamation-circle", "exclamation-triangle", "expand", "external-link", "external-link-square", "eye", "eye-slash", "facebook", "facebook-square", "fast-backward", "fast-forward", "female", "fighter-jet", "file", "file-o", "file-text", "file-text-o", "files-o", "film", "filter", "fire", "fire-extinguisher", "flag", "flag-checkered", "flag-o", "flash", "flask", "flickr", "floppy-o", "folder", "folder-o", "folder-open", "folder-open-o", "font", "forward", "foursquare", "frown-o", "gamepad", "gavel", "gbp", "gear", "gears", "gift", "github", "github-alt", "github-square", "gittip", "glass", "globe", "google-plus", "google-plus-square", "group", "h-square", "hand-o-down", "hand-o-left", "hand-o-right", "hand-o-up", "hdd-o", "headphones", "heart", "heart-o", "home", "hospital-o", "html5", "inbox", "indent", "info", "info-circle", "inr", "instagram", "italic", "jpy", "key", "keyboard-o", "krw", "laptop", "leaf", "legal", "lemon-o", "level-down", "level-up", "lightbulb-o", "link", "linkedin", "linkedin-square", "linux", "list", "list-alt", "list-ol", "list-ul", "location-arrow", "lock", "long-arrow-down", "long-arrow-left", "long-arrow-right", "long-arrow-up", "magic", "magnet", "mail-forward", "mail-reply", "mail-reply-all", "male", "map-marker", "maxcdn", "medkit", "meh-o", "microphone", "microphone-slash", "minus", "minus-circle", "minus-square", "minus-square-o", "mobile", "mobile-phone", "money", "moon-o", "music", "outdent", "pagelines", "paperclip", "paste", "pause", "pencil", "pencil-square", "pencil-square-o", "phone", "phone-square", "picture-o", "pinterest", "pinterest-square", "plane", "play", "play-circle", "play-circle-o", "plus", "plus-circle", "plus-square", "plus-square-o", "power-off", "print", "puzzle-piece", "qrcode", "question", "question-circle", "quote-left", "quote-right", "random", "refresh", "renren", "repeat", "reply", "reply-all", "retweet", "rmb", "road", "rocket", "rotate-left", "rotate-right", "rouble", "rss", "rss-square", "rub", "ruble", "rupee", "save", "scissors", "search", "search-minus", "search-plus", "share", "share-square", "share-square-o", "shield", "shopping-cart", "sign-in", "sign-out", "signal", "sitemap", "skype", "smile-o", "sort", "sort-alpha-asc", "sort-alpha-desc", "sort-amount-asc", "sort-amount-desc", "sort-asc", "sort-desc", "sort-down", "sort-numeric-asc", "sort-numeric-desc", "sort-up", "spinner", "square", "square-o", "stack-exchange", "stack-overflow", "star", "star-half", "star-half-empty", "star-half-full", "star-half-o", "star-o", "step-backward", "step-forward", "stethoscope", "stop", "strikethrough", "subscript", "suitcase", "sun-o", "superscript", "table", "tablet", "tachometer", "tag", "tags", "tasks", "terminal", "text-height", "text-width", "th", "th-large", "th-list", "thumb-tack", "thumbs-down", "thumbs-o-down", "thumbs-o-up", "thumbs-up", "ticket", "times", "times-circle", "times-circle-o", "tint", "toggle-down", "toggle-left", "toggle-right", "toggle-up", "trash-o", "trello", "trophy", "truck", "try", "tumblr", "tumblr-square", "turkish-lira", "twitter", "twitter-square", "umbrella", "underline", "undo", "unlink", "unlock", "unlock-alt", "unsorted", "upload", "usd", "user", "user-md", "users", "video-camera", "vimeo-square", "vk", "volume-down", "volume-off", "volume-up", "warning", "weibo", "wheelchair", "windows", "won", "wrench", "xing", "xing-square", "yen", "youtube", "youtube-play", "youtube-square"];
        }
        // Add a button that opens a window
        editor.addButton('example', {
            text: 'My button',
            type: 'listbox',
            icon: false,
            values: getValues(),
            onclick: function() {
                // Open window
                editor.windowManager.open({
                    title: 'Example plugin',
                    body: [
                        {type: 'textbox', name: 'title', label: 'Title'}
                    ],
                    onsubmit: function(e) {
                        // Insert content when the window form is submitted
                        editor.insertContent('Title: ' + e.data.title);
                    }
                });
            }
        });

        // Adds a menu item to the tools menu
        /*editor.addMenuItem('example', {
            text: 'Example plugin',
            context: 'tools',
            onclick: function() {
                // Open window with a specific url
                editor.windowManager.open({
                    title: 'TinyMCE site',
                    url: 'http://www.tinymce.com',
                    width: 800,
                    height: 600,
                    buttons: [{
                        text: 'Close',
                        onclick: 'close'
                    }]
                });
            }
        });*/
    });

    /*
    tinymce.PluginManager.add('example', function(editor, url) {
        var ICONS;

        var icon = function(id) {
            return '<i class="fa fa-' + id + '"></i> ';
        }

        var createControl = function(name, controlManager) {alert('a');
            if (name != 'fontAwesomeIconSelect') return null;
            var listBox = controlManager.createListBox('fontAwesomeIconSelect', {
                title: 'Icons',
                onselect: function(v) {
                    var editor = this.control_manager.editor;
                    if (v) {
                        editor.selection.setContent(icon(v));
                    }
                    return false;
                }
            });

            for (var i = 0; i < ICONS.length; i++) {
                var _id = ICONS[i];
                listBox.add(icon(_id) + ' ' + _id, _id);
            }

            return listBox;
        };

        tinymce.create('tinymce.plugins.FontAwesomeIconsPlugin', {
            createControl: createControl
        });

        tinymce.PluginManager.add('font_awesome_icons', tinymce.plugins.FontAwesomeIconsPlugin);

        ICONS = ["adjust", "adn", "align-center", "align-justify", "align-left", "align-right", "ambulance", "anchor", "android", "angle-double-down", "angle-double-left", "angle-double-right", "angle-double-up", "angle-down", "angle-left", "angle-right", "angle-up", "apple", "archive", "arrow-circle-down", "arrow-circle-left", "arrow-circle-o-down", "arrow-circle-o-left", "arrow-circle-o-right", "arrow-circle-o-up", "arrow-circle-right", "arrow-circle-up", "arrow-down", "arrow-left", "arrow-right", "arrow-up", "arrows", "arrows-alt", "arrows-h", "arrows-v", "asterisk", "backward", "ban", "bar-chart-o", "barcode", "bars", "beer", "bell", "bell-o", "bitbucket", "bitbucket-square", "bitcoin", "bold", "bolt", "book", "bookmark", "bookmark-o", "briefcase", "btc", "bug", "building-o", "bullhorn", "bullseye", "calendar", "calendar-o", "camera", "camera-retro", "caret-down", "caret-left", "caret-right", "caret-square-o-down", "caret-square-o-left", "caret-square-o-right", "caret-square-o-up", "caret-up", "certificate", "chain", "chain-broken", "check", "check-circle", "check-circle-o", "check-square", "check-square-o", "chevron-circle-down", "chevron-circle-left", "chevron-circle-right", "chevron-circle-up", "chevron-down", "chevron-left", "chevron-right", "chevron-up", "circle", "circle-o", "clipboard", "clock-o", "cloud", "cloud-download", "cloud-upload", "cny", "code", "code-fork", "coffee", "cog", "cogs", "columns", "comment", "comment-o", "comments", "comments-o", "compass", "compress", "copy", "credit-card", "crop", "crosshairs", "css3", "cut", "cutlery", "dashboard", "dedent", "desktop", "dollar", "dot-circle-o", "download", "dribbble", "dropbox", "edit", "eject", "ellipsis-h", "ellipsis-v", "envelope", "envelope-o", "eraser", "eur", "euro", "exchange", "exclamation", "exclamation-circle", "exclamation-triangle", "expand", "external-link", "external-link-square", "eye", "eye-slash", "facebook", "facebook-square", "fast-backward", "fast-forward", "female", "fighter-jet", "file", "file-o", "file-text", "file-text-o", "files-o", "film", "filter", "fire", "fire-extinguisher", "flag", "flag-checkered", "flag-o", "flash", "flask", "flickr", "floppy-o", "folder", "folder-o", "folder-open", "folder-open-o", "font", "forward", "foursquare", "frown-o", "gamepad", "gavel", "gbp", "gear", "gears", "gift", "github", "github-alt", "github-square", "gittip", "glass", "globe", "google-plus", "google-plus-square", "group", "h-square", "hand-o-down", "hand-o-left", "hand-o-right", "hand-o-up", "hdd-o", "headphones", "heart", "heart-o", "home", "hospital-o", "html5", "inbox", "indent", "info", "info-circle", "inr", "instagram", "italic", "jpy", "key", "keyboard-o", "krw", "laptop", "leaf", "legal", "lemon-o", "level-down", "level-up", "lightbulb-o", "link", "linkedin", "linkedin-square", "linux", "list", "list-alt", "list-ol", "list-ul", "location-arrow", "lock", "long-arrow-down", "long-arrow-left", "long-arrow-right", "long-arrow-up", "magic", "magnet", "mail-forward", "mail-reply", "mail-reply-all", "male", "map-marker", "maxcdn", "medkit", "meh-o", "microphone", "microphone-slash", "minus", "minus-circle", "minus-square", "minus-square-o", "mobile", "mobile-phone", "money", "moon-o", "music", "outdent", "pagelines", "paperclip", "paste", "pause", "pencil", "pencil-square", "pencil-square-o", "phone", "phone-square", "picture-o", "pinterest", "pinterest-square", "plane", "play", "play-circle", "play-circle-o", "plus", "plus-circle", "plus-square", "plus-square-o", "power-off", "print", "puzzle-piece", "qrcode", "question", "question-circle", "quote-left", "quote-right", "random", "refresh", "renren", "repeat", "reply", "reply-all", "retweet", "rmb", "road", "rocket", "rotate-left", "rotate-right", "rouble", "rss", "rss-square", "rub", "ruble", "rupee", "save", "scissors", "search", "search-minus", "search-plus", "share", "share-square", "share-square-o", "shield", "shopping-cart", "sign-in", "sign-out", "signal", "sitemap", "skype", "smile-o", "sort", "sort-alpha-asc", "sort-alpha-desc", "sort-amount-asc", "sort-amount-desc", "sort-asc", "sort-desc", "sort-down", "sort-numeric-asc", "sort-numeric-desc", "sort-up", "spinner", "square", "square-o", "stack-exchange", "stack-overflow", "star", "star-half", "star-half-empty", "star-half-full", "star-half-o", "star-o", "step-backward", "step-forward", "stethoscope", "stop", "strikethrough", "subscript", "suitcase", "sun-o", "superscript", "table", "tablet", "tachometer", "tag", "tags", "tasks", "terminal", "text-height", "text-width", "th", "th-large", "th-list", "thumb-tack", "thumbs-down", "thumbs-o-down", "thumbs-o-up", "thumbs-up", "ticket", "times", "times-circle", "times-circle-o", "tint", "toggle-down", "toggle-left", "toggle-right", "toggle-up", "trash-o", "trello", "trophy", "truck", "try", "tumblr", "tumblr-square", "turkish-lira", "twitter", "twitter-square", "umbrella", "underline", "undo", "unlink", "unlock", "unlock-alt", "unsorted", "upload", "usd", "user", "user-md", "users", "video-camera", "vimeo-square", "vk", "volume-down", "volume-off", "volume-up", "warning", "weibo", "wheelchair", "windows", "won", "wrench", "xing", "xing-square", "yen", "youtube", "youtube-play", "youtube-square"];
    });
     */
    tinymce.init({
        selector: ".al-editable-inline",
        inline: true,
        image_advtab: true,
        convert_urls: false,
        relative_urls: true,
        fixed_toolbar_container: "#rk-tinymce-container",
        extended_valid_elements: "i[*],span[class=fa-stack],span[class=glyphicon]",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code ",
            "insertdatetime media table contextmenu paste save example"
        ],
        toolbar: "example | save | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        link_list : frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_createPermalinksList/' + $('#al_languages_navigator').html(), 
        save_onsavecallback: function(editor) {
            $('body').EditBlock('Content', editor.getContent(), null, function(){
                tinymce.remove();
                initTinyMCE();   
            });
        },
        file_browser_callback : function (id, value, type, win) {        
            $('<div />').dialogelfinder({
               url: frontController + 'backend/' + $('#al_available_languages option:selected').val() + '/al_elFinderMediaConnect',
               lang : $('#al_available_languages option:selected').val(),
               width : 840,
               destroyOnClose : true,
               commandsOptions: {
                  getfile: {
                     oncomplete: 'destroy'
                  }
               },
               getFileCallback: function (url)
               {
                  var fieldElm = win.document.getElementById(id);
                  fieldElm.value = url.url;
               }
            });
        }
    });
}
