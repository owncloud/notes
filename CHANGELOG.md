# Changelog

## [Unreleased]

## [2.2.0] - 2026-07-27

### Added
- Show an empty-state placeholder in the content pane when no note is selected,
  instead of a blank white area.

### Fixed
- Unfavoriting a note no longer returns HTTP 500. When a note had no tags left
  after removing the favorite, `getTagsForObjects()` returns no entry for the
  note id, so `in_array()` was called with `null` and threw a `TypeError`. Guard
  the lookup with `array_key_exists()`, matching the pattern already used
  elsewhere in `NotesService`.
- Restore the empty AngularJS hash prefix so clicking a note in the sidebar
  opens it again. The AngularJS 1.8 upgrade changed the default hash prefix to
  `!`, which broke the `#/notes/{id}` sidebar links so only the first note (or
  the last-viewed note) could be opened.

### Security
- Upgrade vendored JS libraries to patched versions, resolving Trivy findings:
  - `underscore` 1.7.0 → 1.13.8 (CVE-2021-23358 critical, CVE-2026-27601 high)
  - `angular` 1.4.14 → 1.8.3 (CVE-2019-10768 high)
  - `prism` 1.0.1 → 1.30.0 (CVE-2021-23341, CVE-2021-32723 high)


## [2.1.2] - 2026-07-22

### Changed
- Re-release to correct a build error in the previous package for the ownCloud 11.0.0 release.

## [2.1.1] - 2026-07-22

### Changed
- Maintenance release re-signed with the ownCloud G2 code-signing certificate for the ownCloud 11.0.0 release.

## [2.1.0] - 2026-07-06

### Changed
 * [#526](https://github.com/owncloud/notes/pull/526) - Compatible with ownCloud 11
 * [#528](https://github.com/owncloud/notes/pull/528) - Require PHP 8.3
 * Development dependencies updated

## [2.0.7] - 2024-09-23

### Fixed
 * [#488](https://github.com/owncloud/notes/pull/488) - Fix: ensure that notes are not stored inside a shared folder
 * Dependencies updated:
   - [#398](https://github.com/owncloud/notes/pull/398)
   - [#399](https://github.com/owncloud/notes/pull/399)
   - [#400](https://github.com/owncloud/notes/pull/400)
   - [#401](https://github.com/owncloud/notes/pull/401)
   - [#402](https://github.com/owncloud/notes/pull/402)
   - [#403](https://github.com/owncloud/notes/pull/403) 
   - [#486](https://github.com/owncloud/notes/pull/486)
 * Dropped support for PHP 7.2 in [#404](https://github.com/owncloud/notes/pull/404) 


## owncloud-notes [2.0.6]

* Fixing [App only visible for admins](https://github.com/owncloud/notes/issues/314)

--

owncloud-notes [2.0.5]
* Ready for ownCloud 10.2+

owncloud-notes (2.0.4)
* Fix api calls with basic auth

owncloud-notes (2.0.3)
* Ready for ownCloud 10
* First release on the marketplace

owncloud-notes (1.1.0)
* **New Dependency**: Require ownCloud 8.1
* **Enhancement**: Add back markdown support because a secure enough library was available. The supported markdown dialect is GitHub flavored markdown and includes syntax highlightning

owncloud-notes (1.0.0)
* **Bugfix**: Remove flying loading icon
* **Enhancement**: Make app ready for ownCloud 8
* **Enhancement**: Show a spinner to signal when app is saving
* **Enhancement**: Prevent closing the window when app is saving

owncloud-notes (0.9)
* **Security**: Remove markdown support because of [XSS in markdown-js library](https://github.com/evilstreak/markdown-js/pull/52)

owncloud-notes (0.7)
* Port to ownCloud internal app framework. Additional installation of the appframework app is not needed anymore
* Require ownCloud 6.0.3

owncloud-notes (0.6)
* Use markdown-js instead of showdown for rendering markdown because showdown has XSS problems and doesn't seem to be maintained anymore
* Provide option to exclude fields when getting a single note through the API

owncloud-notes (0.5)
* Support Markdown preview when editing
* Provide option to exclude fields when getting all notes through the API
* Fix error that renders notes twice when selected

owncloud-notes (0.4)
* remove shipped flag to make it install fine in owncloud 6 and owncloud 5.13+

owncloud-notes (0.3)
* Adjust to work on ownCloud 5 after bugfix was backported
* Add a delete button

owncloud-notes (0.2)
* Move to App Framework and AngularJS
* Remember last note
* Fixed various bugs

[Unreleased]: https://github.com/owncloud/notes/compare/v2.2.0..master
[2.2.0]: https://github.com/owncloud/notes/compare/v2.1.2..v2.2.0
[2.1.2]: https://github.com/owncloud/notes/compare/v2.1.1..v2.1.2
[2.1.1]: https://github.com/owncloud/notes/compare/v2.1.0..v2.1.1
[2.1.0]: https://github.com/owncloud/notes/compare/v2.0.7..v2.1.0
[2.0.7]: https://github.com/owncloud/notes/compare/v2.0.6...v2.0.7
[2.0.6]: https://github.com/owncloud/notes/compare/v2.0.5...v2.0.6
[2.0.5]: https://github.com/owncloud/notes/compare/v2.0.4...v2.0.5
