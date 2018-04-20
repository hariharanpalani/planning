<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

if(!$user->checkDroit('lieux_all')) {
	$_SESSION['message'] = 'droitsInsuffisants';
	header('Location: ../index.php');
	exit;
}

$lieux = new GCollection('Lieu');
$lieux->db_load(array(), array('nom' => 'ASC'));
$smarty->assign('lieux', $lieux->getSmartyData());

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_lieux.tpl');

?>