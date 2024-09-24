# Changelog

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

[Unreleased]: https://github.com/owncloud/notes/compare/v2.0.7...master
[2.0.7]: https://github.com/owncloud/notes/compare/v2.0.6...v2.0.7
[2.0.6]: https://github.com/owncloud/notes/compare/v2.0.5...v2.0.6
[2.0.5]: https://github.com/owncloud/notes/compare/v2.0.4...v2.0.5
