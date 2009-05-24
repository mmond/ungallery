<?
//check for https
// prompt for password
print "<h2>Hidden links</h2>";
$home = $_SERVER['DOCUMENT_ROOT']."/pics/";
hidden_search($home);

function hidden_search($home) {
	$dp = opendir($home);
	while ($dir = readdir($dp)) {
		if (is_dir($home.$dir) and $dir !== "." and $dir !== "..") {
			$hidtest = substr($dir, 0, 4);
			if($hidtest == "hid") {
				$gallerylink = substr($home.$dir, 37);
				print '<a href="?gallerylink='. $gallerylink .'">'. $gallerylink .'</a><br>';
			}
			hidden_search($home.$dir."/");
		}
	} 
}
?>