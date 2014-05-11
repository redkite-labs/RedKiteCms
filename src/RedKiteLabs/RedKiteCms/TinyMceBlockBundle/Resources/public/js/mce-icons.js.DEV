(function() {
	var ICONS;

	var icon = function(id) {
		return '<i class="fa fa-' + id + '"></i> ';
	}

	var createControl = function(name, controlManager) {
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
})();