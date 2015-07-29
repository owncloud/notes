/**
 * Copyright (c) 2013, Bernhard Posselt <dev@bernhard-posselt.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */
angular.module('Notes').controller('NotesController', function($routeParams,
    $location, Restangular, NotesModel) {
    'use strict';

    this.route = $routeParams;
    this.notes = NotesModel.getAll();

    var notesResource = Restangular.all('notes');

    // initial request for getting all notes
    notesResource.getList().then((notes) => {
        NotesModel.addAll(notes);
    });

    this.create = () => {
        notesResource.post().then((note) => {
            NotesModel.add(note);
            $location.path('/notes/' + note.id);
        });
    };

    this.delete = (noteId) => {
        var note = NotesModel.get(noteId);
        note.remove().then(() => {
            NotesModel.remove(noteId);
            this.$emit('$routeChangeError');
        });
    };

});
