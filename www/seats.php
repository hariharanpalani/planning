<?php
require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$html = '';
$js = '';

$todayDate = new DateTime();

$ressources = new GCollection('Ressource');
$sql = "select A.ressource_id as resource_id, A.nom as resourcename, pl.lieu_id, pl.nom as locationname, pru.user_id, pru.nom as assigneduser,
        pra.allocation_id
        from (select pr.ressource_id, pr.nom, pr.lieu_id from planning_ressource pr 
                left outer join planning_user pu on pr.ressource_id = pu.resource_id
                where pu.resource_id is null
                UNION
                select PR.ressource_id, PR.nom,pr.lieu_id from planning_ressource PR
                inner join planning_user pu on pr.ressource_id = pu.resource_id
                inner join planning_user_unavailability puu on pu.user_id = puu.user_id
                inner join planning_type pt on puu.type_id = pt.type_id
                where (puu.start_date between puu.start_date AND '" . $todayDate->format('Y-m-d') ."') 
                and puu.end_date > '" . $todayDate->format('Y-m-d') ."') A
                left outer join planning_resource_allocation pra on a.ressource_id = pra.resource_id
                left outer join planning_lieu pl on A.lieu_id = pl.lieu_id
                left outer join planning_user pru on pra.user_id = pru.user_id
                where pra.resource_id is null or pra.allocated_date ='" . $todayDate->format('Y-m-d') . "' order by a.nom asc";
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