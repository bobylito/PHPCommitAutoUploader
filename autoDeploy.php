<?php
/*PHPCommitAutoUploader : a simple AND STUPID webhook for deploying stuff commited on github to a webserver (might not be very secured)*/

//CONFIG
$config=array(
	"TARBALL_URL"=>"http://github.com/bobylito/HackingParty/tarball/master",
	"TEMP_TAR"=>"hackingparty_src.tar.gz",
	"TARGET_DIR"=>"hackingParty",
	"DEBUG"=>false
);

if($config["DEBUG"]){
	var_dump($config);
}

if(!chdir($config["TARGET_DIR"])){
	if($config["DEBUG"]){
		echo "can't change directory";
	}
	exit(1);
}

exec('wget '.$config['TARBALL_URL'].' -O '.$config['TEMP_TAR']);
exec('tar xzf '.$config['TEMP_TAR']);

if($config["DEBUG"]){
	echo 'OK';
}

unlink($config['TEMP_TAR']);

exit(0);

?>
