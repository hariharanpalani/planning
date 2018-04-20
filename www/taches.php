<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$_POST = sanitize($_POST);
$_GET = sanitize($_GET);
$_SESSION['lastURL'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$_SESSION['planningView'] = 'taches';

// PARAMÈTRES
$pattern = '/^([1-9]|0[1-9]|1[0-9]|2[0-9]|3[01])\/([1-9]|0[1-9]|1[012])\/(19[0-9][0-9]|20[0-9][0-9])$/';

// Chargement date de début
$dateDebut = new DateTime();
if(isset($_COOKIE['date_debut_affiche_tache'])) {
	$_SESSION['date_debut_affiche_tache'] = $_COOKIE['date_debut_affiche_tache'];
}
if (isset($_SESSION['date_debut_affiche_tache'])) {
	$dateDebut = initDateTime($_SESSION['date_debut_affiche_tache']);	
} else {
	$dateDebut->modify('-' . CONFIG_DEFAULT_NB_PAST_DAYS . ' days');
	$_SESSION['date_debut_affiche_tache'] = $dateDebut->format(CONFIG_DATE_LONG);
}

// Chargement date de fin
$dateFin = new DateTime();
if(isset($_COOKIE['date_fin_affiche_tache'])) {
	$_SESSION['date_fin_affiche_tache'] = $_COOKIE['date_fin_affiche_tache'];
}
if (isset($_SESSION['date_fin_affiche_tache'])) {
	$dateFin = initDateTime($_SESSION['date_fin_affiche_tache']);	
} else {
	$dateFin = clone $dateDebut;
	$dateFin->modify('+' . CONFIG_DEFAULT_NB_MONTHS_DISPLAYED . ' months');
	$_SESSION['date_fin_affiche_tache'] = $dateFin->format(CONFIG_DATE_LONG);
}
// Conversion des dates en mode mobile au format french
if (isset($_POST['date_fin_affiche_tache']) && $_SESSION['isMobileOrTablet']) 
{
	$_POST['date_fin_affiche_tache']=forceUserDateFormat($_POST['date_fin_affiche_tache']);
}
if (isset($_POST['date_debut_affiche_tache']) && $_SESSION['isMobileOrTablet'])
{
	$_POST['date_debut_affiche_tache']=forceUserDateFormat($_POST['date_debut_affiche_tache']);
}
// changement date de début et fin
if(isset($_POST['date_debut_affiche_tache']) && isset($_POST['date_fin_affiche_tache'])) {

	$dateDebut = initDateTime($_POST['date_debut_affiche_tache']);	
	$dateFin = initDateTime($_POST['date_fin_affiche_tache']);	

	if((!$dateDebut || !$dateFin) || ($dateFin < $dateDebut)) {
		header('Location: taches.php');
	}

	$_SESSION['date_debut_affiche_tache'] = $_POST['date_debut_affiche_tache'];
	setcookie('date_debut_affiche_tache', $_SESSION['date_debut_affiche_tache'], time()+60*60*24*500, '/');
	$_SESSION['date_fin_affiche_tache'] = $_POST['date_fin_affiche_tache'];
	setcookie('date_fin_affiche_tache', $_SESSION['date_fin_affiche_tache'], time()+60*60*24*500, '/');
}

if (isset($_REQUEST['statut']) && is_array($_REQUEST['statut'])) {
	$listeStatuts = $_REQUEST['statut'];
} elseif (isset($_SESSION['statut']) && is_array($_SESSION['statut'])) {
	$listeStatuts = $_SESSION['statut'];
} else {
	$listeStatuts = array('a_faire', 'en_cours');
}
$_SESSION['statut'] = $listeStatuts;

if (isset($_REQUEST['filtreTaches'])) {
	$filtreTaches = $_REQUEST['filtreTaches'];
} elseif (isset($_SESSION['filtreTaches'])) {
	$filtreTaches = $_SESSION['filtreTaches'];
} else {
	$filtreTaches = 'mestaches';
}
$_SESSION['filtreTaches'] = $filtreTaches;

if (isset($_REQUEST['grouperpar'])) {
	$grouperpar = $_REQUEST['grouperpar'];
} elseif (isset($_SESSION['grouperpar'])) {
	$grouperpar = $_SESSION['grouperpar'];
} else {
	$grouperpar = 'status';
}
$_SESSION['grouperpar'] = $grouperpar;
if(isset($_POST['lieu']) && isset($_POST['lieu'])) {
	// si filtre sur les lieux, on boucle pour recuperer l'ensemble des lieux choisis
	$filtre = $_POST['lieu'];
	$_SESSION['filtreGroupeLieu'] = $filtre;
}else{
	$filtre = array();
	$_SESSION['filtreGroupeLieu'] = $filtre;
}
if(isset($_POST['ressource']) && isset($_POST['ressource'])) {
	// si filtre sur les ressources de tache, on boucle pour recuperer l'ensemble des ressources choisies
	$filtre = $_POST['ressource'];
	$_SESSION['filtreGroupeRessource'] = $filtre;
}else{
	$filtre = array();
	$_SESSION['filtreGroupeRessource'] = $filtre;
}
if(isset($_GET['filtreSurRessource'])) {
	$_SESSION['filtreGroupeRessource'] = array($_GET['filtreSurRessource']);
}
if(isset($_GET['filtreSurLieu'])) {
	$_SESSION['filtreGroupeLieu'] = array($_GET['filtreSurLieu']);
}
if(isset($_GET['desactiverFiltreGroupeProjet']) || (!isset($_SESSION['filtreGroupeProjet']))) {
	$_SESSION['filtreGroupeProjet'] = array();
}
if(isset($_GET['desactiverFiltreUser']) || (!isset($_SESSION['filtreUser']))) {
	$_SESSION['filtreUser'] = array();
}
if(isset($_GET['desactiverFiltreUser']) || (!isset($_SESSION['filtreUser']))) {
	$_SESSION['filtreUser'] = array();
}
if (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('nom_personne', 'titre', 'date_debut', 'date_fin'))) {
	$order = $_REQUEST['order'];
} elseif (isset($_SESSION['projet_order'])) {
	$order = $_SESSION['projet_order'];
} else {
	$order = 'titre';
}

if (isset($_REQUEST['by'])) {
	$by = $_REQUEST['by'];
} elseif (isset($_SESSION['projet_by'])) {
	$by = $_SESSION['projet_by'];
} else {
	$by = 'ASC';
}
// FIN PARAMÈTRES
$smarty->assign('listeStatuts', $listeStatuts);
$smarty->assign('filtreTaches', $filtreTaches);
$smarty->assign('grouperpar', $grouperpar);
$smarty->assign('order', $order);
$smarty->assign('by', $by);

$projets = new GCollection('Projet');

if(isset($_POST['desactiverfiltreGroupe'])) {
	$filtreGroupeProjet = array();
	$_SESSION['groupe_filtreEquipeProjet'] = $filtreGroupeProjet;
}
if(isset($_POST['desactiverfiltreUser'])) {
	$filtreUser = array();
	$_SESSION['groupe_filtreUser'] = $filtreUser;
}
if (isset($_REQUEST['filtreGroupeProjet'])) {
	$filtreGroupeProjet = array();
	if(isset($_REQUEST['gp'])) {
		$filtreGroupeProjet = $_REQUEST['gp'];
	}
	if(isset($_REQUEST['gp0'])) {
		$filtreGroupeProjet[] = 'gp0';
	}
} elseif (isset($_SESSION['groupe_filtreGroupeProjet'])) {
	$filtreGroupeProjet = $_SESSION['groupe_filtreGroupeProjet'];
} else {
	$filtreGroupeProjet = array();
}
if(isset($_POST['filtreGroupeProjet'])) {
	// si filtre sur les projets, on boucle pour recuperer l'ensemble des projets choisis
	$projetsFiltre = array();
	foreach ($_POST as $keyPost => $valPost) {
		if(strpos($keyPost, 'projet_') === 0) {
			$projetsFiltre[] = $valPost;
		}
	}
	$_SESSION['filtreGroupeProjet'] = $projetsFiltre;
}
if(isset($_POST['filtreUser'])) {
	// si filtre sur les projets, on boucle pour recuperer l'ensemble des projets choisis
	$projetsFiltre = array();
	foreach ($_POST as $keyPost => $valPost) {
		if(strpos($keyPost, 'user_') === 0) {
			$projetsFiltre[] = $valPost;
		}
	}
	$_SESSION['filtreUser'] = $projetsFiltre;
}

//print_r($_POST);
if(isset($_REQUEST['rechercheTaches']) && $_REQUEST['rechercheTaches'] != ''){
	$search = $_REQUEST['rechercheTaches'];
	$search = explode( ' ', $search );

	$isLike = array('0');

	foreach($search as $word){
		$isLike[] = 'planning_periode.titre LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_user2.nom LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_projet.nom LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_groupe.nom LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_periode.notes LIKE '.val2sql('%' . $word . '%');
	}

	$isLike = implode(" OR ", $isLike);
	$sql = "select planning_periode.*, planning_projet.*, planning_groupe.nom AS nom_groupe, planning_user2.nom AS nom_personne , planning_user.nom AS nom_createur, planning_periode.lien as lien 
			FROM planning_periode
			LEFT JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id
			LEFT JOIN planning_groupe ON planning_groupe.groupe_id = planning_projet.groupe_id
			LEFT JOIN planning_user ON planning_user.user_id = planning_projet.createur_id 
			LEFT JOIN planning_lieu ON planning_lieu.lieu_id = planning_periode.lieu_id 
			LEFT JOIN planning_ressource ON planning_ressource.ressource_id = planning_periode.ressource_id 			
			LEFT JOIN planning_user as planning_user2 ON planning_user2.user_id = planning_periode.user_id";		
	if($user->checkDroit('tasks_view_specific_users')) {
		$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
	}
	$sql.= " WHERE (" . $isLike . ") AND planning_projet.statut in ('" . implode("','", $listeStatuts) . "')";
	if(count($_SESSION['filtreGroupeProjet']) > 0)	{
		$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0)	{
		$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if(count($_SESSION['filtreGroupeLieu']) > 0) {
		$sql.= " AND planning_periode.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
	}
	if(count($_SESSION['filtreGroupeRessource']) > 0) {
		$sql.= " AND planning_periode.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
	}	
	if(count($filtreGroupeProjet) > 0) {
		$sql .= "		AND (planning_projet.groupe_id IN ('" . implode("','", $filtreGroupeProjet) . "')";
		if(in_array('gp0', $filtreGroupeProjet)) {
			$sql .= '	OR planning_projet.groupe_id IS NULL ';
		}
		$sql .= ' )';
	}
	if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propriétaire ou assigné
	$sql .= " AND (planning_projet.createur_id = '" . $user->user_id . "' OR planning_periode.user_id = '" . $user->user_id . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND planning_user.user_groupe_id = " . $user->user_groupe_id;
	}
	$sql .= "ORDER BY $order ASC," . $order . ' ' . $by;
	$smarty->assign('rechercheTaches', $_REQUEST['rechercheTaches']);

}  else {
	// recuperation des projets couvrant la période
	$sql = "select planning_periode.*, planning_projet.*, planning_groupe.nom AS nom_groupe, planning_user2.nom AS nom_personne , planning_user.nom AS nom_createur, planning_periode.lien as lien 
			FROM planning_periode
			LEFT JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id
			LEFT JOIN planning_groupe ON planning_groupe.groupe_id = planning_projet.groupe_id
			LEFT JOIN planning_user ON planning_user.user_id = planning_projet.createur_id 
			LEFT JOIN planning_lieu ON planning_lieu.lieu_id = planning_periode.lieu_id 
			LEFT JOIN planning_ressource ON planning_ressource.ressource_id = planning_periode.ressource_id 	
			LEFT JOIN planning_user as planning_user2 ON planning_user2.user_id = planning_periode.user_id";		
	if($user->checkDroit('tasks_view_specific_users')) {
		$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
	}
	$sql .= " WHERE planning_periode.statut_tache in ('" . implode("','", $listeStatuts) . "') 
			AND (
			(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			)";
	if ($filtreTaches=='mestaches')
	{
		$sql .= "AND planning_periode.user_id='".$_SESSION['user_id']."'";
	}
	if(count($_SESSION['filtreGroupeProjet']) > 0) 
	{
	$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0) 
	{
	$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if(count($_SESSION['filtreGroupeLieu']) > 0) {
		$sql.= " AND planning_periode.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
	}
	if(count($_SESSION['filtreGroupeRessource']) > 0) {
		$sql.= " AND planning_periode.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
	}	
	if(count($filtreGroupeProjet) > 0) {
	$sql .= "		AND (planning_projet.groupe_id IN ('" . implode("','", $filtreGroupeProjet) . "')";
	if(in_array('gp0', $filtreGroupeProjet)) {
		$sql .= '	OR planning_projet.groupe_id IS NULL ';
	}
	$sql .= ' )';
	}
	if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propriétaire ou assigné
	$sql .= " AND (planning_projet.createur_id = '" . $user->user_id . "' OR planning_periode.user_id = '" . $user->user_id . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND planning_user2.user_groupe_id = " . $user->user_groupe_id;
	}
	if ($grouperpar=='project')
	{
	 $sql .=" ORDER BY planning_periode.projet_id ASC,planning_periode.statut_tache ASC," . $order . ' ' . $by;
	}elseif ($grouperpar=='status') 
	{
		$sql .=" ORDER BY planning_periode.statut_tache ASC,planning_periode.projet_id ASC," . $order . ' ' . $by;
	}elseif ($grouperpar=='utilisateur') 
	{
		$sql .=" ORDER BY nom_personne ASC,planning_periode.projet_id ASC," . $order . ' ' . $by;
	}
	$smarty->assign('rechercheTaches', '');
 }
$projets->db_loadSQL($sql);


// recuperation des projets couvrant la période, pour le filtre de projets
$projetsFiltre = new GCollection('Projet');
$sql = "SELECT distinct pp.*, pg.nom AS groupe_nom
		FROM planning_projet pp
		LEFT JOIN planning_periode pd ON pp.projet_id = pd.projet_id
		LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
}
if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propriétaire ou assigné
	$sql .= " AND (pp.createur_id = '" . $user->user_id . "' OR pd.user_id = '" . $user->user_id . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
$sql .= "WHERE (
			(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
			AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
			AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			)";
$sql .= "	GROUP BY pp.nom, pp.projet_id
			ORDER BY pg.nom, pp.nom";
$projetsFiltre->db_loadSQL($sql);


// Filtre pour les lieux
if (CONFIG_SOPLANNING_OPTION_LIEUX == 1)
{
	$listeLieux = new GCollection('Lieu');
	$listeLieux->db_load(array(), array('nom' => 'ASC'));
	$smarty->assign('listeLieux', $listeLieux->getSmartyData());
}

// Filtre pour les ressources
if (CONFIG_SOPLANNING_OPTION_RESSOURCES == 1)
{
	$listeRessources = new GCollection('Ressource');
	$listeRessources->db_load(array(), array('nom' => 'ASC'));
	$smarty->assign('listeRessources', $listeRessources->getSmartyData());
}
$smarty->assign('dateDebut', $dateDebut->format(CONFIG_DATE_LONG));
$smarty->assign('dateFin', $dateFin->format(CONFIG_DATE_LONG));
$smarty->assign('filtreGroupeProjet', $_SESSION['filtreGroupeProjet']);
$smarty->assign('filtreUser', $_SESSION['filtreUser']);
$smarty->assign('filtreGroupeLieu', $_SESSION['filtreGroupeLieu']);
$smarty->assign('filtreGroupeRessource', $_SESSION['filtreGroupeRessource']);
$smarty->assign('listeProjets', $projetsFiltre->getSmartyData());

$groupeProjets = new GCollection('Groupe');
$groupeProjets->db_load(array(), array('nom' => 'ASC'));
$smarty->assign('groupeProjets', $groupeProjets->getSmartyData());

$users = new GCollection('User');
$sql = "SELECT pu.*, pug.nom AS groupe_nom
FROM planning_user pu
LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id ";
if($user->checkDroit('tasks_view_specific_users')) {
	$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = pu.user_id AND rou.owner_id = " . val2sql($user->user_id);
}
$sql .= "	WHERE visible_planning = 'oui' ";
if ($user->checkDroit('tasks_view_only_own')) {
	$sql .= " AND pu.user_id = " . val2sql($user->user_id);
}
$sql .=	" ORDER BY groupe_nom, pu.nom";
$users->db_loadSQL($sql);
$smarty->assign('users', $users->getSmartyData());

$smarty->assign('projets', $projets->getSmartyData());

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->display('www_taches.tpl');
?>