/**
 * Copyright (c) 2013, Bernhard Posselt <dev@bernhard-posselt.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING file.
 */
angular.module('Notes').controller('NoteController', function($routeParams,
    NotesModel, SaveQueue, note) {
    'use strict';

    NotesModel.updateIfExists(note);

    this.note = NotesModel.get($routeParams.noteId);

    this.isSaving = () => {
        return SaveQueue.isSaving();
    };

    this.updateTitle = () => {
        this.note.title = this.note.content.split('\n')[0] ||
            t('notes', 'New note');
    };

    this.save = () => {
        var note = this.note;
        SaveQueue.add(note);
    };

});
