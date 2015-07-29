/**
 * Copyright (c) 2013, Bernhard Posselt <dev@bernhard-posselt.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */

// type declarations of global functions used
declare var angular: any;
declare var oc_requesttoken: string;
declare var mdEdit: any;

'use strict';

angular.module('Notes', ['ngMock', 'restangular', 'ngRoute']).
config(['RestangularProvider', function (RestangularProvider) {
    RestangularProvider.setBaseUrl('/');
}]);

function t (app: string, text: string) : string  {
    return text;
};
