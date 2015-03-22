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

var SeoModel = function (seo)
{
    var self = this;
    DockableModel.call(self);

    var seoData = ko.utils.parseJson(seo);
    self.permalink = seoData.permalink;
    self.title = ko.observable(seoData.title);
    self.description = ko.observable(seoData.description);
    self.keywords = ko.observable(seoData.keywords);
    self.sitemapFrequency = ko.observable(seoData.sitemap_frequency);
    self.sitemapPriority = ko.observable(seoData.sitemap_priority);
    self.currentPermalink = seoData.current_permalink;
    self.changedPermalinks = seoData.changed_permalinks;
    self.toggleBlocksEditor = ko.observable(false);

    _prepareSeoData = function()
    {
        return {
            "permalink": self.permalink,
            "title": self.title,
            "description": self.description,
            "keywords": self.keywords,
            "sitemap_frequency": self.sitemapFrequency,
            "sitemap_priority": self.sitemapPriority,
            "language": language + '_' + country,
            "current_permalink": self.currentPermalink,
            "changed_permalinks": self.changedPermalinks
        }
    }
};

SeoModel.prototype = Object.create(DockableModel.prototype);
SeoModel.prototype.constructor = SeoModel;

SeoModel.prototype.toggle = function()
{
    this.toggleBlocksEditor(!this.toggleBlocksEditor());
};

SeoModel.prototype.editSeo = function()
{
    var url = frontcontroller + '/backend/page/edit';
    var data = {
        'page-name': page,
        'seo-data': _prepareSeoData()
    };

    executeAjax(url, data);
};

SeoModel.prototype.approve = function()
{
    var url = frontcontroller + '/backend/page/approve';
    var data = {
        'pageName': page,
        'seo-data': _prepareSeoData()
    };

    var message = redkitecmsDomain.frontend_confirm_seo_approved;
    confirmDialog(message, function(){
        executeAjax(url, data);
    });


};