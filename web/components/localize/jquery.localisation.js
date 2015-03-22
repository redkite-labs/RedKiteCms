/* http://keith-wood.name/localisation.html
   Localisation assistance for jQuery v1.1.0.
   Written by Keith Wood (kbwood{at}iinet.com.au) June 2007.
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses.
   Please attribute the author if you use it. */

(function($) { // Hide scope, no $ conflict

/* Load applicable localisation package(s) for one or more jQuery packages.
   Assumes that the localisations are named <base>-<lang>.js
   and loads them in order from least to most specific.
   For example, $.localise('mypackage');
   with the browser set to 'en-US' would attempt to load
   mypackage-en.js and mypackage-en-US.js.
   Also accepts an array of package names to process.
   Optionally specify the desired language, whether or not to include the base file,
   paths to the files, the timeout period, whether retrieval is asynchronous,
   and/or a callback upon completion, e.g.
   $.localise(['mypackage1', 'yourpackage'],
      {language: 'en-AU', loadBase: true, path: ['', 'i18n/'],
      timeout: 300, async: true, complete: function(pkg) {
         ...
      }});
   @param  packages  (string or string[]) names of package(s) to load
   @param  settings  omit for the current browser language or
                     (string) code for the language to load (aa[-AA]) or
                     (object} options for the call with
                       language  (string) the code for the language
                       loadBase  as below
                       path      as below
                       timeout   as below
                       async     as below
                       complete  as below
   @param  loadBase  (boolean, optional) true to also load the base package or false (default) to not -
                     omit this if settings is an object
   @param  path      (string or string[2], optional) the paths to the JavaScript,
                     either as both or [base, localisations] -
                     omit this if settings is an object
   @param  timeout   (number, optional) the time period in milliseconds (default 500) -
                     omit this if settings is an object
   @param  async     (boolean, optional) true to load asynchronously or false (default) to load synchronously -
                     omit this if settings is an object
   @param  complete  (function, optional) callback on completion of loading,
                     function receives package name as its parameter, 'this' is window -
                     omit this if settings is an object */
$.localise = function(packages, settings, loadBase, path, timeout, async, complete) {
	if (typeof settings != 'object' && typeof settings != 'string') {
		complete = async;
		async = timeout;
		timeout = path;
		path = loadBase;
		loadBase = settings;
		settings = '';
	}
	if (typeof loadBase != 'boolean') {
		complete = async;
		async = timeout;
		timeout = path;
		path = loadBase;
		loadBase = false;
	}
	if (typeof path != 'string' && !$.isArray(path)) {
		complete = async;
		async = timeout;
		timeout = path;
		path = '';
	}
	if (typeof timeout != 'number') {
		complete = async;
		async = timeout;
		timeout = 500;
	}
	if (typeof async != 'boolean') {
		complete = async;
		async = false;
	}
	settings = (typeof settings != 'string' ?
		$.extend({loadBase: false, path: '', timeout: 500, async: false}, settings || {}) :
		{language: settings, loadBase: loadBase, path: path,
		timeout: timeout, async: async, complete: complete});
	var paths = (!settings.path ? ['', ''] :
		($.isArray(settings.path) ? settings.path : [settings.path, settings.path]));
	var opts = {async: settings.async, dataType: 'script', timeout: settings.timeout};
	var localisePkg = function(pkg, lang) {
		var files = [];
		if (settings.loadBase) {
			files.push(paths[0] + pkg + '.js');
		}
		if (lang.length >= 2) {
			files.push(paths[1] + pkg + '-' + lang.substring(0, 2) + '.js');
		}
		if (lang.length >= 5) {
			files.push(paths[1] + pkg + '-' + lang.substring(0, 5) + '.js');
		}
		var loadFile = function() {
			$.ajax($.extend(opts, {url: files.shift(), complete: function() {
				if (files.length == 0) {
					if ($.isFunction(settings.complete)) {
						settings.complete.apply(window, [pkg]);
					}
				}
				else {
					loadFile();
				}
			}}));
		};
		loadFile();
	};
	var lang = normaliseLang(settings.language || $.localise.defaultLanguage);
	packages = ($.isArray(packages) ? packages : [packages]);
	for (var i = 0; i < packages.length; i++) {
		localisePkg(packages[i], lang);
	}
};

// Localise it!
$.localize = $.localise;

/* Retrieve the default language set for the browser. */
$.localise.defaultLanguage = normaliseLang(navigator.language /* Mozilla */ ||
	navigator.userLanguage /* IE */);

/* Ensure language code is in the format aa-AA. */
function normaliseLang(lang) {
	lang = lang.replace(/_/, '-').toLowerCase();
	if (lang.length > 3) {
		lang = lang.substring(0, 3) + lang.substring(3).toUpperCase();
	}
	return lang;
}

})(jQuery);
