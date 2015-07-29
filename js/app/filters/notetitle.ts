/**
 * Copyright (c) 2015, Hendrik Leppelsack
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */

/**
 * removes whitespaces and leading #
 */
app.filter('noteTitle', function () {
	'use strict';
	return function (value) {
        	value = value.split('\n')[0] || 'newNote';
		return value.trim().replace(/^#+/g, '');
	};
});
