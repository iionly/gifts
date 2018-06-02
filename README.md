Elgg Gifts Plugin for Elgg 2.3 and newer Elgg 2.X
=================================================

Latest Version: 2.3.1  
Released: 2018-06-02  
Contact: iionly@gmx.de  
License: GNU General Public License version 2  
Copyright: (c) iionly, Galdrapiu, Christian Heckelmann


Installation
------------

If upgrading from a version older than version 2.3.1 please read the upgrade instructions below first!

1. If you have a previous version of the Gifts plugin installed, please disable it and then remove the gifts plugin folder completely before installing the new version,
2. Copy the gifts plugin folder into you mod folder,
3. Enable the Gifts plugin in the admin section of your site (Configure - Plugins),
4. Configure your gifts in the admin section of your site (Configure - Settings - Gifts).


Upgrading
---------

Starting with version 2.3.1 of the Gifts plugin the gift images are no longer saved in and served from the images subdirectory within the gifts plugin folder in mod but are saved in and served from the Elgg data directory. There's a migration script available to re-create the gifts images and the gift image thumbnails in the Elgg data directory if you have already used a version of the Gifts plugin older than 2.3.1. The execution of the upgrade script can be triggered from the Gifts plugin settings page. Please check if there's a pending upgrade displayed there after upgrading to version 2.3.1 or newer of the Gifts plugin.

The upgrade script can't handle the migration. if you have used a version below 0.1.0 of the Gifts plugin up to now and uploaded pictures to the images folder. In this case you have to upload the pictures again within the Gifts admin menu.

When upgrading from a version newer than 0.1.0 to 2.3.1 or newer, you will need to keep the images subdirectory in the Gifts plugin folder until you have successfully finished the migration upgrade. Do as follows:

1. Backup the database and data directory of your Elgg site (there can always go something wrong when running a upgrade script that alters database and data directory),
2. Backup the images subdirectory (mod/gifts/images) in the gifts plugin folder. Do not remove the images subdirectory yet!
3. Disable the Gifts plugin in the admin section of your site,
4. Remove all Gifts plugin files and subfolders within the gifts directory EXCEPT the images folder,
5. Copy the new version of the Gifts plugin (2.3.1 or newer) into your mod folder,
6. Enable the Gifts plugin in the admin section of your site,
7. Go to the Gifts plugin settings page (Configure - Settings - Gifts). There should be an upgrade button shown. Click it to execute the migration upgrade,
8. After the upgrade has finished the gift images should show up again (on the individual Gifts settings tab page, on the pages of gifts, in the gifts widgets, etc.). Make sure they do to know that the migration has been successful.
9. If the migration has been done, you can remove the images folder (mod/gifts/images).

If the migration has failed for some reason, you can either try again (in this case restory the database and data directory from your backup first) or you would have to re-upload the gift images manually again. Hopefully, this won't be necessary. In case you ever need to access the gift images files directory, they are saved within the site_entity subfolder in the data directory (in most cases the subfolder is 1/1/gifts within the data directory).
