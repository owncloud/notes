app.filter('noteFilter', function() {
	'use strict';
	return function (items, searchString) {
        if (!searchString || searchString.length == 0)
            return items;
        
        var regex = new RegExp(searchString, 'i');
		return items.filter(x => x.title.match(regex) || x.content.match(regex));
	};
});