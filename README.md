# We have moved!
## This app is now maintained at: https://github.com/nextcloud/notes

-------------------------

# Notes

[![build state](https://travis-ci.org/owncloud/notes.png)](https://travis-ci.org/owncloud/notes)[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/owncloud/notes/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/owncloud/notes/?branch=master)

The Notes app is a distraction free notes taking app. It offers a [RESTful API](https://github.com/owncloud/notes/wiki/API-0.2) for app developers. The source code is [available on GitHub](https://github.com/owncloud/notes)

![Screenshot of ownCloud Notes](https://cloud.githubusercontent.com/assets/4741199/17731273/e557b5fe-646c-11e6-9975-d6b242454482.png)

For further developer and user documentation please visit [the wiki](https://github.com/owncloud/notes/wiki)

## Maintainers
- [Hendrik Leppelsack](https://github.com/Henni)
- [Lukas Reschke](https://github.com/LukasReschke)

##Requirements
##### Minimum PHP Version
* PHP >= 5.4

##### Minimum ownCloud Version
* >= 8.1

##### Supported Webservers
* Apache

##### Supported Browsers
* latest 3 versions of Chrome, Firefox and Edge 
* we *do not* officially support Internet Explorer and Safari (Patches accepted though)


## Bugs
Before reporting bugs:

* check the requirements above
* get the newest version of the Notes app
* [check if they have already been reported](https://github.com/owncloud/notes/issues?state=open)


## Git (development version)

#### Installation

* Clone the **Notes** app into the **/var/www/owncloud/apps/** directory

    ```git clone https://github.com/owncloud/notes.git```

* Activate the **Notes** app in the apps menu


#### Keep up to date

To update the Notes app use::

    cd /var/www/owncloud/apps/notes
    git pull --rebase origin master
