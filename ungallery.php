<?
/*
Plugin Name: UnGallery
Plugin URI: http://markpreynolds.com/technology/wordpress-ungallery
Version: 0.8
Author: Mark Reynolds
Description: Imports directories of pictures as a browsable WordPress gallery.
*/

$dir = "wp-content/plugins/ungallery/";
$pic_root = $_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/ungallery/pics/";
$hidden = file_get_contents("wp-content/plugins/ungallery/hidden.txt");
$gallery = $_GET['gallerylink'];
$src = $_GET['src'];
$w = $_GET['w'];

if (isset($src)) {		 				//	If we are browsing a gallery, get the gallery name from the src url
	$lastslash =  strrpos($src, "/");	// 	Trim the filename off the end of the src link
	$gallery =  substr($src, 5, $lastslash - 5 );   
}

//	Is the following line needed any longer?
//  consider ".." in path an attempt to read dirs outside gallery, so redirect to gallery root
if (strstr($gallery, "..")) $gallery = "";

if ($gallery == "") {
	$gallery =  "";
} else {   	//  If $gallerylink is set and not "" then....
	
	//  Build the full gallery path into an array
	$gallerypath =  explode("/", $gallery);
	
	//  Render the Up directory links
	print '<a href="./gallery">Top</a>';
	foreach ($gallerypath as $key => $level) {
		$parentpath = $parentpath . $level ;
		//  Unless it is the current directory
		if ($key < count($gallerypath) - 1) {
			print ' / <a href="?gallerylink='. $parentpath .'" >'. $level .'</a>';
		}  else {
			//  In that case render the current gallery name, but don't hyperlink
			print " / $level";
		}
		$parentpath = $parentpath . "/";
	}
}
	
									// Create the arrays with the dir's media files
$dp = opendir($pic_root.$gallery);
while ($filename = readdir($dp)) {
	if (!is_dir($pic_root.$gallery. "/". $filename))  {  // If it's a file, begin
			$pic_types = array("JPG", "jpg", "GIF", "gif", "PNG", "png"); 		
			if (in_array(substr($filename, -3), $pic_types)) $pic_array[] = $filename;							// If it's a picture, add it to thumb array
			else {
				$movie_types = array("AVI", "avi", "MOV", "mov", "MP3", "mp3", "MP4", "mp4");								
				if (in_array(substr($filename, -3), $movie_types)) $movie_array[$filename] = size_readable(filesize($pic_root.$gallery. "/". $filename)); 	
									// If it's a movie, add name and size to the movie array
			}						
	}
} 
if($pic_array) sort($pic_array);  

									//print the movie items
if($movie_array) {
	print " / <br>Movies:&nbsp;&nbsp;";
	foreach ($movie_array as $filename => $filesize) {
		print  '
			<a href="'.$dir.'pics/'. substr($parentpath, 0, strlen($parentpath) -1).$subdir.'/'.$filename. '" title="Movies may take much longer to download.  This file size is '. $filesize .'">'	.$filename.'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}
}
closedir($dp);
print '&nbsp;/&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;Sub Galleries&nbsp;:&nbsp;&nbsp;';

$dp = opendir($pic_root.$gallery);	//  Render the Subdirectory links
while ($subdir = readdir($dp)) {	//  If it is a subdir and not set as hidden, enter it into the array
	if (is_dir($pic_root.$gallery. "/". $subdir) && $subdir !="thumb_cache" && $subdir != "." && $subdir != ".." && !strstr($subdir, $hidden)) {
		$subdirs[] = $subdir;
	}
}
if($subdirs) {						//  List each subdir and link
	sort($subdirs);	
	foreach ($subdirs as $key => $subdir) {
		print  '<a href="?gallerylink='. $parentpath.$subdir. '" >'	.$subdir.'</a> / ';
	}
}
closedir($dp);
print '</b><br>';

if (!isset($src) && isset($pic_array)) {							//	If we are not in browse view,
	if ($gallery == "") $w=650;										//  Set size of top level gallery picture
	print '<table class="one-cell"><tr><td class="cell1">';			//	Begin the WordPress Atahualpa 1 cell table
	if (file_exists("pics/".$gallery."/banner.txt")) {
		print '<div class="post-headline"><h1>'; 
		include ("pics/".$gallery."/banner.txt");					//	We also display the caption from banner.txt
		print "</h1>";
	}
	$column = 0;
	foreach ($pic_array as $filename) {								//  Use the pic_array to assign the links and img src
		if(stristr($filename, ".JPG")) {
			print '<a href="?src=pics/'.$gallery. "/" .$filename.'"><img src="'. $dir .'jpeg_rotate.php?src=pics/'.$gallery. "/". $filename.'&w=' .$w. '"></a>'; 				//  If it is a jpeg include the exif rotation logic
	   	} else {
			print '<a href="?src=pics/'.$gallery. "/" .$filename.'"><img src="'. $dir .'thumb.php?src=pics/'.$gallery. "/". $filename.'&w=' .$w. '"></a>';    
		}
		$column++;
		if ( $column == 5 ) {
			print '<br>';
			$column = 0;
		}            	
	}	
} else {														// Render the browsing version, link to original, last/next picture, and link to parent gallery
if (isset($src)) {
	if (!strstr($src, "pics/")) die;     						//  If "pics" is not in path it may be an attempt to read files outside gallery, so redirect to gallery root
	$filename = substr($src, $lastslash + 1);
	$before_filename = $pic_array[array_search($filename, $pic_array) - 1 ];
	$after_filename = $pic_array[array_search($filename, $pic_array) + 1 ];

																//  Display the current/websize pic
																//  If it is a jpeg include the exif rotation logic
	print '<table class="one-cell"><tr>';						//	Begin the WordPress Atahualpa 2 cell table
	if(stristr($src, ".JPG")) print '<td class="cell1"><a href="'. $dir .'source.php?pic=' . $src . '"><img src="./'. $dir .'jpeg_rotate.php?src='. $src. '&w=650"></a></td><td class="cell2">';
		else print '<td class="cell1"><a href="'. $dir .'source.php?pic=' . $src . '"><img src="./'. $dir .'thumb.php?src='. $src. '&w=650"></a></td><td class="cell2">';

	if ($before_filename) {										// Display the before thumb, if it exists
																//  If it is a jpeg include the exif rotation logic
		if(stristr($before_filename, ".JPG")) print '<a href="?src=pics/' . $gallery."/".$before_filename .'" title="Previous Gallery Picture"><img src="'. $dir .'jpeg_rotate.php?src=pics/' .$gallery."/".$before_filename .'"></a>';
		else print '<a href="?src=pics/' . $gallery."/".$before_filename .'" title="Previous Gallery Picture"><img src="'. $dir .'thumb.php?src=pics/' .$gallery."/".$before_filename .'"></a>';
	}
print "<br><br><br><br>";
	if ($after_filename) {										// Display the after thumb, if it exists
		if(stristr($after_filename, ".JPG")) print '<a href="?src=pics/' . $gallery."/".$after_filename .'" title="Next Gallery Picture"><img src="'. $dir .'jpeg_rotate.php?src=pics/' .$gallery."/".$after_filename .'"></a>';		
		else print '<a href="?src=pics/' . $gallery."/".$after_filename .'" title="Next Gallery Picture"><img src="'. $dir .'thumb.php?src=pics/' .$gallery."/".$after_filename .'"></a>';
	}
}
}
print "</td></tr></table>";

function size_readable ($size, $retstring = null) {
        // adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
}
?>