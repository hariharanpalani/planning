<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

if(!$user->checkDroit('ressources_all')) {
	$_SESSION['message'] = 'droitsInsuffisants';
	header('Location: ../index.php');
	exit;
}

$ressources = new GCollection('Ressource');
$ressources->db_loadSQL('SELECT DISTINCT pr.ressource_id, pr.nom, pr.commentaire, pr.exclusif, pr.lieu_id, 
						pr.user_groupe_id, pl.nom as lieuname, pug.nom as teamname from planning_ressource PR
						inner join planning_lieu pl on pr.lieu_id = pl.lieu_id
						left outer join planning_user_groupe pug on pr.user_groupe_id = pug.user_groupe_id
						order by pr.nom asc');
// $ressources->db_load(array(), array('nom' => 'ASC'));
$smarty->assign('ressources', $ressources->getSmartyData());

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_ressources.tpl');

?>