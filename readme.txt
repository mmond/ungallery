=== UnGallery ===
Contributors: mmond
Tags: gallery
Requires at least: 
Tested up to: 2.8
Stable tag: trunk

Publish existing picture directories as a WordPress gallery.  

== Description ==

UnGallery imports directories of pictures as a browsable WordPress gallery. 

Its main value is no management at the WordPress layer.  You can just point UnGallery at a directory hierarchy of photos, even thousands of them, and they are immediately displayed and browsable in your blog.  Any edits you make to the pictures or the organization of your directories are automatically current, on the next browser refresh.

If you've ever had to reorganize where your photos are stored, remove a few or edit the red-eye of a dozen, you know how inconvenient it is to return to the gallery management UI to update everything.  With UnGallery, all add/remove/edit changes you make to your pictures are automatically current in the WordPress UnGallery view.

This plugin is still young, so there will be things to fix.  Feel free to use it, extend it or contact me with questions.
Much of the script is taken from other published sources and noted inline.

Mark Reynolds http://markpreynolds.com

== Installation ==

1. Upload to /wp-content/plugins/ and activate on the Plugins menu in WordPress.
1. Enable Permalinks: Settings -> Permalinks -> Custom Structure -> /%category%/%postname%
1. Create a blank WordPress Page called "Gallery".
1. Create a directory or symlink called "pics" in plugins/ungallery/ to contain your pictures.  See readme for more detail.
1. For WordPress running on a Windows server:  after downloading, copy files from plugins/ungallery/windows/ to plugins/ungallery/.

== Features ==

* Unlimited depth, breadth, and number of photos in library. Mine has approx 6,000.
* Photo library is managed outside of WordPress, simply update via FTP, SCP, etc.  UnGallery sees changes immediately.
* Set optional banner captions
* Hidden, private galleries
* Thumbnail cache is created in photo directory for faster page loads
* Support for PNG, JPG, GIF, MP4, AVI and MOV
* Automatic image rotation to correct orientation of jpegs with exif information
* Gallery hierarchy breadcrumbs and with links to parent galleries and subgalleries
* Multiple gallery views:  Top level marquee, thumbnails, browsing previous and next pictures.

== Screenshots ==

1. The UnGallery top level view.  A single "Marquee" picture is displayed and the links to the subdirectories/subgalleries.
2. Selecting one of the subgallery links above displays the gallery thumbnail view of all JPGs, PNGs and GIFs in that directory.  A breadcrumb trail up to the top level of the galleries is displayed along with the subgalleries.  These are each generated automatically by reading the file system of your photo directories. The -zip- link provides a zip file of all photo originals in the current directory for convenient download.
3. Clicking on a thumbnail displays the browsing view.  One picture is larger and the previous and next picture thumbnail links are displayed.  There are movie files in this directory, so links to view them are displayed also.  UnGallery's sizes are adjustable to fill larger page widths as this site uses.

== Dependencies ==

* Permalinks enabled: Settings -> Permalinks -> Custom Structure -> /%category%/%postname% <br>
  More info here: http://teamtutorials.com/web-development-tutorials/clean-url%E2%80%99s-with-wordpress 
* Write permission to the photo directories. UnGallery creates a "thumb_cache" to greatly improve performance. 

== Notes ==

* In: ./wp-content/plugins/ungallery/ either create a symlink called "pics" to your picture directory (recommended) or copy/move/create a directory called pics there.  Please note, if you back up your WordPress install, including your plugins directory, be aware the gallery may be included.
* To display a caption over a gallery, add a file named banner.txt to that directory with the desired text.
* The top level directory is intended to have a larger, marquee picture displayed, so only one picture file should be placed in the "/pics/" directory. There is no limit on pictures in the subdirectories.
* To mark a gallery hidden, edit the /ungallery/hidden.txt file. If e.g., the content of hidden.txt is: "hidden", then any directories you create named "hidden", will not be visible via gallery browsing.  
* If you'd like to modify the size of the marquee pic, browsing pic or the thumbnails, please edit /ungallery/ungallery.php.  The options are noted near the top of the file.

== License ==

The MIT License

	
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.


