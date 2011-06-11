<?php
/*PHPCommitAutoUploader : a simple AND STUPID webhook for deploying stuff commited on github to a webserver (might not be very secured)*/

//CONFIG

function getGrabber()
{
	$grab = array("curl --version",
                      "wget --version");

        foreach($grab as $i){
		exec($i,&$tabOutput,&$valRet);
		unset($tabOutput);

		if (0 === $valRet){
			unset($valRet);
			$ret = preg_split("/[\s]/", $i);

                        if (count($ret) > 0)
				return $ret[0];
                }
	}

	unset($valRet);
        die("Grabber is not available\n");
}

function buildExecCmd($aGrabber, $aConfig)
{
	if (!isset($aGrabber) || empty($aGrabber) ||
            strlen($aGrabber) === 0){
		die("I need a Grabber\n");
	}
	if (!isset($aConfig))
		die("config is not valid\n");

	switch($aGrabber)
	{
		case "curl" :
			return sprintf("%s -L %s -o %s",
					$aGrabber,
					$aConfig['TARBALL_URL'],
					$aConfig['TEMP_TAR']);
		case "wget" :
			return sprintf("%s %s -O %s",
					$aGrabber,
					$aConfig['TARBALL_URL'],
					$aConfig['TEMP_TAR']);
		default:
			die("Unknown Grabber\n");
	}
}

/* begin */

$config=array(
	"TARBALL_URL"=>"http://github.com/bobylito/HackingParty/tarball/master",
	"TEMP_TAR"=>"hackingparty_src.tar.gz",
	"TARGET_DIR"=>"hackingParty",
	"DEBUG"=>false
);

$grabCmd = getGrabber();

if(true === $config["DEBUG"]){
	var_dump($config);
}

if(false === chdir($config["TARGET_DIR"])){
	if(true === $config["DEBUG"]){
		die("can't change target directory \n");
	}
}

$cmd =buildExecCmd($grabCmd, $config);

exec($cmd,&$tabOpt,&$valRet);

if ($valRet != 0)
	die("the grabbing process failed\n");

unset($valRet);
unset($tabOpt);

$tarCmd = sprintf("tar xzf %s", $config['TEMP_TAR']);
exec($tarCmd, &$tabOpt, &$valRet);

if ($valRet != 0)
	die("extracting process failed\n");

unset($tabOpt);
unset($valRet);

if(true === $config["DEBUG"]){
	echo "OK\n";
}


unlink($config['TEMP_TAR']);

exit(0);

?>
