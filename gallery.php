<?
// Map short variables globals=off version's long syntax
$dir = "tools/gallery";
$gallery = $_GET['gallerylink'];
$src = $_GET['src'];
$w = $_GET['w'];
if (!isset($dir)) $dir = ".";

if (isset($src)) {		// Trim the filename off the end of the src link and ../.. off the beginning
	$lastslash =  strrpos($src, "/");
	$gallery =  substr($src, 11, $lastslash - 10);   
}

//  consider ".." in path an attempt to read dirs outside gallery, so redirect to gallery root
if (strstr($gallery, "..")) $gallery = "";

if ($gallery == "") {
	$gallery =  "";
} else {   	//  If $gallerylink is set and not "" then....
	
	//  Build the full gallery path into an array
	$gallerypath =  explode("/", $gallery);
	
	//  Render the Up directory links
	foreach ($gallerypath as $key => $level) {
		$parentpath = $parentpath . $level ;
		//  Unless it is the current directory
		if ($key < count($gallerypath) - 1) {
			print '<b> / <a href="?page_id=679&preview=true&gallerylink='. $parentpath .'" >'. $level .'</a></b>';
		}  else {
			//  In that case render the current gallery name, but don't hyperlink
			print "<b> / $level</b>";
		}
		$parentpath = $parentpath . "/";
	}
}

if ($gallery !== "") print '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=".?content=tools/gallery/zip.php&gallerylink=' . $gallery . '" title="Download a zipped archive of all photos in this gallery">-zip-</a>
<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

									// Create the arrays with the dir's media files
$dp = opendir($_SERVER['DOCUMENT_ROOT']."/pics/".$gallery);
while ($filename = readdir($dp)) {
	if (!is_dir($_SERVER['DOCUMENT_ROOT']."/pics/".$gallery. "/". $filename))  {  // If it's a file, begin
			$pic_types = array("JPG", "jpg", "GIF", "gif", "PNG", "png"); 		
			if (in_array(substr($filename, -3), $pic_types)) $pic_array[] = $filename;							// If it's a picture, add it to thumb array
			else {
				$movie_types = array("AVI", "avi", "MOV", "mov", "MP3", "mp3", "MP4", "mp4");								
				if (in_array(substr($filename, -3), $movie_types)) $movie_array[$filename] = size_readable(filesize($_SERVER['DOCUMENT_ROOT']."/pics/".$gallery. "/". $filename)); 	
									// If it's a movie, add name and size to the movie array
			}						
	}
} 
if($pic_array) sort($pic_array);  

									//print the movie items
if($movie_array) {
	print "Movies:&nbsp;&nbsp;";
	foreach ($movie_array as $filename => $filesize) {
		print  '
			<a href="../../pics/'. $parentpath.$subdir.$filename. '" title="Movies may take much longer to download.  This file size is '. $filesize .'">'	.$filename.'</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}
}
closedir($dp);

									//  If this is a gallery marked hidden, link to the index of other galleries marked hidden
if($level == "hidden") print '<a href="./?content=tools/gallery/hidden.php">- Index of all hidden galleries - </a><br>';
	
print 'Sub Galleries&nbsp;&nbsp;/&nbsp;&nbsp;';
									//  Render the Subdirectory links
$dp = opendir($_SERVER['DOCUMENT_ROOT']."/pics/".$gallery);

									//  If it is a subdir and not set as hidden, enter it into the array
while ($subdir = readdir($dp)) {
	if (is_dir($_SERVER['DOCUMENT_ROOT']."/pics/".$gallery. "/". $subdir) && $subdir !="thumb_cache" && $subdir != "." && $subdir != ".." && !strstr($subdir, "hid")) {
		$subdirs[] = $subdir;
	}
}
if($subdirs) {						//  List each subdir and link
	sort($subdirs);	
	foreach ($subdirs as $key => $subdir) {
		print  '<a href="?page_id=679&preview=true&gallerylink='. $parentpath.$subdir. '" >'	.$subdir.'</a> / ';
	}
}
closedir($dp);
print '</b><br>';

if (!isset($src) && isset($pic_array)) {		
	if ($gallery == "") {											//  Render top level of the gallery
		$w=650;														//  Set size of root picture
		print '<div class="post-headline"><h1>'; 
		include ("pics/caption.txt");								//	We also display the caption
		print '</h1>';
	}
	print '<table class="one-cell"><tr><td class="cell1">';			//	Begin the WordPress Atahualpa 1 cell table
	$column = 0;
	foreach ($pic_array as $filename) {								//  Use the pic_array to assign the links and img src
		if(stristr($filename, ".JPG")) {
			print '<a href="?page_id=689&preview=true&src=../../pics/'.$gallery. "/" .$filename.'"><img src="'. $dir .'/jpeg_rotate.php?src=../../pics/'.$gallery. "/". $filename.'&w=' .$w. '"></a>'; 				//  If it is a jpeg include the exif rotation logic
	   	} else {
			print '<a href="?page_id=689&preview=true&src=../../pics/'.$gallery. "/" .$filename.'"><img src="'. $dir .'/thumb.php?src=../../pics/'.$gallery. "/". $filename.'&w=' .$w. '"></a>';    
		}
		$column++;
		if ( $column == 5 ) {
			print '<br>';
			$column = 0;
		}            	
	}	
} else {														// Render the browsing version, link to original, last/next picture, and link to parent gallery
$page_id=689;													//	Set WordPress target page id (later page name) to use two-cell.php template page
if (isset($src)) {
	if (!strstr($src, "../pics/")) die;     					//  If "../pics" is not in path it may be an attempt to read files outside gallery, so redirect to gallery root
	$filename = substr($src, $lastslash + 1);
	$before_filename = $pic_array[array_search($filename, $pic_array) - 1 ];
	$after_filename = $pic_array[array_search($filename, $pic_array) + 1 ];

																//  Display the current/websize pic
																//  If it is a jpeg include the exif rotation logic
	print '<table class="two-cell"><tr>';						//	Begin the WordPress Atahualpa 2 cell table
	if(stristr($src, ".JPG")) print '<td class="cell1"><a href="tools/gallery/source.php?pic=' . $src . '"><img src="./'. $dir .'/jpeg_rotate.php?src='. $src. '&w=650"></a></td><td class="cell2">';
		else print '<td class="cell1"><a href="tools/gallery/source.php?pic=' . $src . '"><img src="./'. $dir .'/thumb.php?src='. $src. '&w=650"></a></td><td class="cell2">';

	if ($before_filename) {										// Display the before thumb, if it exists
																//  If it is a jpeg include the exif rotation logic
		if(stristr($before_filename, ".JPG")) print '<a href="?page_id=689&preview=true&src=../../pics/' . $gallery.$before_filename .'" title="Previous Gallery Picture"><img src="'. $dir .'/jpeg_rotate.php?src=../../pics/' .$gallery.$before_filename .'"></a>';
		else print '<a href="?page_id=689&preview=true&src=../../pics/' . $gallery.$before_filename .'" title="Previous Gallery Picture"><img src="'. $dir .'/thumb.php?src=../../pics/' .$gallery.$before_filename .'"></a>';
	}
print "<br><br><br><br>";
	if ($after_filename) {										// Display the after thumb, if it exists
		if(stristr($after_filename, ".JPG")) print '<a href="?page_id=689&preview=true&src=../../pics/' . $gallery.$after_filename .'" title="Next Gallery Picture"><img src="'. $dir .'/jpeg_rotate.php?src=../../pics/' .$gallery.$after_filename .'"></a>';		
		else print '<a href="?page_id=689&preview=true&src=../../pics/' . $gallery.$after_filename .'" title="Next Gallery Picture"><img src="'. $dir .'/thumb.php?src=../../pics/' .$gallery.$after_filename .'"></a>';
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