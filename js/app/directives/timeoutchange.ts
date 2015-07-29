/**
 * Copyright (c) 2013, Bernhard Posselt <dev@bernhard-posselt.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */
angular.module('Notes').directive('notesTimeoutChange', function ($timeout) {
    'use strict';

    return {
        restrict: 'A',
        link: function (scope, element, attributes) {
            var interval = 300;  // 300 miliseconds timeout after typing
            var timeout;

            element.on('input propertychange', function () {
                $timeout.cancel(timeout);

                timeout = $timeout(function () {
                    scope.$apply(attributes.notesTimeoutChange);
                }, interval);
            });
        }
    };
});
