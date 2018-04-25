<?php
require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$html = '';
$js = '';

$todayDate = new DateTime();

$ressources = new GCollection('Ressource');
$sql = "select A.ressource_id as resource_id, A.resourcename, A.actualuser, pu.nom as assigneduser, pra.allocation_id from (
        select PR.ressource_id, PR.nom as resourcename, pu.nom as actualuser, pu.user_id from planning_ressource PR
        left outer join planning_user pu on pu.resource_id = pr.ressource_id
        left outer join planning_periode pp on pu.user_id = pp.user_id
        where pp.user_id is null or (pp.projet_id in('WFH') and pp.date_debut <='" . $todayDate->format('Y-m-d') . "' 
        and pp.date_fin>='" . $todayDate->format('Y-m-d') . "')) A
        left outer join planning_resource_allocation pra on pra.resource_id = A.ressource_id
        and pra.allocated_date = '" . $todayDate->format('Y-m-d') . "'
        left outer join planning_user pu on pu.user_id = pra.user_id
        order by A.resourcename";
$ressources->db_loadSQL($sql);
$ressources->setPagination(NB_RESULT_PER_PAGE);
if (!empty($_GET['page'])) {
	$ressources->setCurrentPage($_GET['page']);
} else {
	$ressources->setCurrentPage(1);
}

$_SESSION['user_currentPage'] = $ressources->getCurrentPage();
$smarty->assign('currentPage', $ressources->getCurrentPage());
$smarty->assign('nbPages', $ressources->getNbPages());
$smarty->assign('todayDate', $todayDate->format(CONFIG_DATE_LONG));
$smarty->assign('resources', $ressources->getSmartyData(TRUE));
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('seats.tpl');
?>