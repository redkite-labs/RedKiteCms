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

var PageCollectionModel = function (pages)
{
    var self = this;
    self.pages = ko.observableArray();
    self.activePage = null;

    self.toggle = function(page)
    {
        if (self.activePage != null) {

            // Closes the active page details
            self.activePage.showDetails(false);
            if (self.activePage == page) {
                self.activePage = null;

                return;
            }
        }

        page.showDetails(true);
        self.activePage = page;
    };

    self.add = function(view, event)
    {
        var index = 1;
        ko.utils.arrayForEach(self.pages(), function(page){
            var n = page.currentName;
            if(/new-page/g.exec(n)) {
                index++;
            }
        });

        var seo = [];
        var pageName = 'new-page-' + index;
        ko.utils.arrayForEach(languages, function(language){
            var permalink = language.toLowerCase().replace(/_/g, '-') + '-' + pageName;
            seo.push(
                {
                    'permalink': permalink,
                    'title': pageName + '-title',
                    'description': pageName + '-description',
                    'keywords': pageName + '-keywords',
                    'language': language,
                    'sitemap_frequency': 'monthly',
                    'sitemap_priority': '0.5'
                }
            );
        });
        var page = {
            'name': pageName,
            'currentName': pageName,
            'template': template,
            'isHome': false,
            'seo': seo
        };
        initPage(page);
        self.pages.push(page);

        queue['rkcms-add-page-' + pageName] = {
            'entity' : 'page',
            'action' : 'add',
            'data' :  page
        };
        /*
        var url = frontcontroller + '/backend/page/collection/add';
        executeAjax(url, {},
            function(response) {
                initPage(response);
                self.pages.push(response);
            }
        );*/

    };

    self.editPage = function(page)
    {
        if (pageExists(page, self.pages()))
        {
            page.name(page.currentName);
            alertDialog(redkitecmsDomain.frontend_page_already_exists, null, 'warning');

            return;
        }

        var pageName = page.name().slugify();
        if (pageName != page.name()) {
            page.name(pageName);
        }
        var data = {
            'name': pageName,
            'template': page.template,
            'isHome': page.isHome(),
            'currentName': page.currentName
        };
        queue['rkcms-edit-page-' + page.currentName] = {
            'entity' : 'page',
            'action' : 'edit',
            'data' :  data
        };

        /*
         var pageClone = $.extend({}, page);
         delete pageClone.seo;
         delete pageClone.showDetails;

        var url = frontcontroller + '/backend/page/collection/edit';
        var data = {
            'data': pageClone
        };

        executeAjax(url, data,
            function(response) {
                if(page.name() != response.name){
                    page.name(response.name);
                }

                page.currentName = page.name();
            },
            function(error) {
                page.name(page.currentName);
                alertDialog(error.responseText, null, 'danger');
            }
        );*/
    };

    self.remove = function(page)
    {
        if (page.isHome()) {
            alertDialog(redkitecmsDomain.frontend_homepage_cannot_be_removed, null, 'danger');

            return;
        }
        var message = redkitecmsDomain.frontend_confirm_page_remove;
        confirmDialog(message, function(){
            var pageIndex = self.pages.indexOf(page);
            self.pages.splice(pageIndex, 1);
            var pageName = page.name();
            var data = {
                'name': pageName
            };
            queue['rkcms-remove-page-' + page.currentName] = {
                'entity' : 'page',
                'action' : 'remove',
                'data' :  data
            };

            //self.pages.remove(page);

            /*
            var url = frontcontroller + '/backend/page/collection/remove';
            var data = {
                'page-data': page
            };

            executeAjax(url, data,
                function(response) {
                    page.currentName = page.name;
                },
                function(response) {
                    self.pages.splice(pageIndex, 0, page);

                    alertDialog(response.responseText, null, 'error');
                }
            );*/
        });
    };

    self.editSeo = function(seo, event)
    {
        var permalink = seo.permalink().slugify();
        if (permalink != seo.permalink()) {
            seo.permalink(permalink);
        }

        var seoData = $.extend({}, seo);
        seoData.permalink = permalink;
        var pageName = self.activePage.name();
        var data = {
            'pageName': pageName,
            'seoData': seoData
        };

        queue['rkcms-edit-permalink-' + pageName] = {
            'entity' : 'seo',
            'action' : 'edit',
            'data' :  data
        };

        /*
         var url = frontcontroller + '/backend/page/edit';
         var seoData = $.extend({}, seo);
         seoData.permalink = seo.permalink();
         var data = {
         'page-name': self.activePage.name(),
         'seo-data': seoData
         };

         executeAjax(url, data,
         function(response) {
         if(seo.permalink != response.permalink){
         seo.permalink(response.permalink);
         }
         }
         );*/
    };

    self.navigate = function(seo, event)
    {
        location.href = frontcontroller + "/backend/" + seo.permalink();
    };

    function pageExists(page, pages)
    {
        var result = false;
        var pageName = page.name;
        if (typeof(pageName) == "function") {
            pageName = page.name();
        }

        $(pages).each(function(){
            if (this != page && this.currentName == pageName) {
                result = true;

                return false;
            }
        });

        return result;
    }

    function initPage(page)
    {
        page.currentName = page.name;
        page.showDetails = ko.observable(false);
        page.name = ko.observable(page.name);
        page.isHome = ko.observable(page.isHome);
        ko.utils.arrayForEach(page.seo, function(seo){
            seo.permalink = ko.observable(seo.permalink);
        });
    }

    ko.utils.arrayForEach(pages, function(page){
        initPage(page);

        self.pages.push(page);
    });
};