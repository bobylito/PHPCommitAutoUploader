<?php
/*PHPCommitAutoUploader : a simple AND STUPID webhook for deploying stuff commited on github to a webserver (might not be very secured)*/

//CONFIG
$config=array(
	"TARBALL_URL"=>"http://github.com/bobylito/HackingParty/tarball/master",
	"TEMP_TAR"=>"hackingparty_src.tar.gz",
	"DEPLOY_DIR"=>"deploy",
	"TARGET_DIR"=>"hackingParty",
	"DEBUG"=>false
);

if($config["DEBUG"]){
	var_dump($config);
}

if(!chdir($config["DEPLOY_DIR"])){
	if($config["DEBUG"]){
		echo "can't change directory";
	}
	exit(1);
}

//GET ARCHIVE
exec('wget '.$config['TARBALL_URL'].' -O '.$config['TEMP_TAR']);

//DIR STATE
$dirStateBefore=scandir(".");

//UNCOMPRESS
exec('tar xzf '.$config['TEMP_TAR']);

//IDENTIFY UNCOMPRESSED DIR
$dirStateAfter=scandir(".");
$diffDir=array_diff($dirStateAfter, $dirStateBefore);
if($config["DEBUG"]){
	print_r($diffDir);
}

//RENAME DIR
$newDirName=array_pop($diffDir);
if(file_exists($config["TARGET_DIR"]) && is_file($config["TARGET_DIR"])){
	unlink($config["TARGET_DIR"]);
}
else if(file_exists($config["TARGET_DIR"]) && is_dir($config["TARGET_DIR"])){
	rrmdir($config["TARGET_DIR"]);
}
rename($newDirName, $config["TARGET_DIR"]);

if($config["DEBUG"]){
	echo 'OK';
}

//DESTROY TAR FILE
unlink($config['TEMP_TAR']);

exit(0);



/* This function was found on PHP.net and was posted by holger1 at NOSPAMzentralplan dot de*/
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object);
				else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
} 
?>
