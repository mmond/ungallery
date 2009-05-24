<?
print '<font size="5" color="#5571B0">Hidden links</font><br><br>';
print '<tr><td align="left">';
$home = $_SERVER['DOCUMENT_ROOT']."/pics/";
hidden_search($home);

function hidden_search($home) {
	$dp = opendir($home);
	while ($dir = readdir($dp)) {
		if (is_dir($home.$dir) and $dir !== "." and $dir !== "..") {
			if($dir == "hidden") {
				$hiddendir = substr($home, 21);
				print '<a href="http://markpreynolds.com/?gallerylink='. $hiddendir .'hidden">'. $hiddendir .'</a><br>';
			}
			hidden_search($home.$dir."/");
		}
	} 
}
print '</td></tr>';
?>