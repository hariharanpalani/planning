<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

if(!$user->checkDroit('parameters_all')) {
	$_SESSION['message'] = 'droitsInsuffisants';
	header('Location: ../index.php');
	exit;
}

$themes=array();
$dirname = './assets/css/themes/';
$dir = opendir($dirname); 
while($file = readdir($dir)) {
	if($file != '.' && $file != '..' && !is_dir($dirname.$file))
		{
		 $fichier=basename($file,'.css');
		 $themes[]=$fichier;
	}
}
closedir($dir);

$urlSuggeree = getUrlInfo();
$smarty->assign('urlSuggeree', $urlSuggeree['root'] . $urlSuggeree['currentDir']);
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->assign('themes', $themes);

$smarty->display('www_options.tpl');

?>