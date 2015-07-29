/**
 * Copyright (c) 2015, Hendrik Leppelsack
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */
angular.module('Notes').directive('editor', ['$timeout', function ($timeout) {
	'use strict';
	return {
		restrict: 'A',
		scope: {
			editor: '=',
			beforeSave: '&'
		},
		link: (scope, element) => {
			var editor = mdEdit(element[0], {change: (value) => {
				$timeout(() => {
					scope.$apply(() => {
						scope.editor.content = value;
						scope.beforeSave();
					});
				});
			}});
			editor.setValue(scope.editor.content);
		}
	};
}]);
