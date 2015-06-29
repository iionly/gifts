Elgg Gifts Plugin for Elgg 1.10 - 1.11
======================================

Latest Version: 1.10.5  
Released: 2015-06-21  
Contact: iionly@gmx.de  
License: GNU General Public License version 2  
Copyright: (c) iionly, Galdrapiu, Christian Heckelmann


Installation
------------

1. If you have a previous version of the gifts plugin installed, please disable it and then remove the gifts plugin folder completely before installing the new version. You must only backup or keep the images subfolder with its content to keep your gift images,
2. Copy the gifts plugin folder into you mod folder,
3. !!!! Set the folder permissions of gifts/images to be writeable by the webserver (chmod 777) !!!!
4. Enable the gifts plugin in the admin section of your site (Configure - Plugins)
5. Configure your Gifts in the admin section of your site (Administer - Utilities - Gifts)


Important!
----------

If you are using a version below 0.1.0 and uploaded pictures to the images folder, you have to upload the pictures again within the Gifts admin menu

In case uploading gift images for example above slot 20 or 25 fails this can be due to file upload restrictions in php.ini and/or in suhosin.ini (if suhosin modul is used). The default setting of max_file_uploads in php.ini is 20. The default setting of suhosin.upload.max_uploads in suhosin.ini is 25. These parameters must be set to larger values than the number of gifts. If you can't increase these parameters, you can upload a gift image to a lower slot number to get the resized versions of the image and then rename the files according to the slot number you want the image to take.
