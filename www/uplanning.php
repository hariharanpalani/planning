<?php
require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$html = '';
$js = '';
	
$joursFeries = getJoursFeries();
$droitAjoutPeriode = false;
$_SESSION['lastURL'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// Conversion de la durée maximale du jour en quantième
$TotalMaxJourExplode= explode (':',CONFIG_DURATION_DAY);
$TotalMaxJourH = $TotalMaxJourExplode[0];
if(count($TotalMaxJourExplode) > 1) {
	$TotalMaxJourM = $TotalMaxJourExplode[1];
} else {
	$TotalMaxJourM = 0;
}
$TotalMaxJour = ($TotalMaxJourH+$TotalMaxJourM/60);

// PARAMÈTRES ////////////////////////////////

// chargement date de début
$dateDebut = new DateTime();
if(isset($_COOKIE['date_debut_affiche'])) {
	$_SESSION['date_debut_affiche'] = $_COOKIE['date_debut_affiche'];
}
if (isset($_SESSION['date_debut_affiche'])) {
	$dateDebut = initDateTime($_SESSION['date_debut_affiche']);
} else {
	// $dateDebut->modify('-' . CONFIG_DEFAULT_NB_PAST_DAYS . ' days');
	$_SESSION['date_debut_affiche'] = $dateDebut->format(CONFIG_DATE_LONG);
}

// chargement date de fin
$dateFin = new DateTime();
if(isset($_COOKIE['date_fin_affiche'])) {
	$_SESSION['date_fin_affiche'] = $_COOKIE['date_fin_affiche'];
}
if (isset($_SESSION['date_fin_affiche'])) {
	$dateFin = initDateTime($_SESSION['date_fin_affiche']);
} else {
	$dateFin = clone $dateDebut;
	$dateFin->modify('+' . CONFIG_DEFAULT_NB_MONTHS_DISPLAYED . ' months');
	$_SESSION['date_fin_affiche'] = $dateFin->format(CONFIG_DATE_LONG);
}
// si param livraison existe, veut dire qu'on vient des projets et qu'on affiche la semaine demandée
if(isset($_GET['livraison'])) {
	if($_GET['livraison'] != '') {
		$dateDebut = initDateTime($_GET['livraison']);
		// on affiche 5 jours avant la semaine voulue
		$dateDebut->modify('-5 days');
		$_SESSION['date_debut_affiche'] = $dateDebut->format(CONFIG_DATE_LONG);
	} else {
		$dateDebut->modify('-5 days');
		$_SESSION['date_debut_affiche'] = $dateDebut->format(CONFIG_DATE_LONG);
	}
}

if(isset($_COOKIE['inverserUsersProjets'])) {
	$_SESSION['inverserUsersProjets'] = $_COOKIE['inverserUsersProjets'];
}
if (!isset($_SESSION['inverserUsersProjets'])) {
	// Si préférences utilisateurs est vueProjet
	if ($_SESSION['preferences']['vueDefaut']=='vueProjet')	{
		$_SESSION['inverserUsersProjets'] = true;
	} else {
		$_SESSION['inverserUsersProjets'] = false;
	}
}

if(isset($_COOKIE['scrolls'])) {
	$_SESSION['scrolls'] = $_COOKIE['scrolls'];
} elseif (!isset($_SESSION['scrolls'])) {
	$_SESSION['scrolls'] = 'H';
}

if(!isset($_SESSION['filtreGroupeProjet'])) {
	$_SESSION['filtreGroupeProjet'] = array();
}

if(!isset($_SESSION['filtreGroupeUser'])) {
	$_SESSION['filtreGroupeUser'] = array();
}

if(!isset($_SESSION['filtreGroupeLieu'])) {
	$_SESSION['filtreGroupeLieu'] = array();
}

if(!isset($_SESSION['filtreGroupeRessource'])) {
	$_SESSION['filtreGroupeRessource'] = array();
}

if(!isset($_SESSION['filtreUser'])) {
	if (isset($_COOKIE['filtreUser'])) {
		$_SESSION['filtreUser'] = explode(",", $_COOKIE['filtreUser']);
	} else {
		$_SESSION['filtreUser'] = array();
	}
}

if(!isset($_SESSION['filtreTexte'])) {
	$_SESSION['filtreTexte'] = '';
}

if(!isset($_SESSION['filtreStatutTache'])) {
	$_SESSION['filtreStatutTache'] = array();
}

if(!isset($_SESSION['filtreStatutProjet'])) {
	$_SESSION['filtreStatutProjet'] = array();
}

// Modif Tri Planning
$triPlanningPossibleUser = array('nom asc', 'nom desc', 'user_id asc', 'user_id desc', 'team_nom asc, nom asc', 'team_nom desc, nom desc', 'team_nom asc, user_id asc', 'team_nom desc, user_id desc');
$triPlanningPossibleProjet = array('nom asc', 'nom desc', 'projet_id asc', 'projet_id desc', 'groupe_nom asc, nom asc', 'groupe_nom desc, nom desc', 'groupe_nom asc, projet_id asc', 'groupe_nom desc, projet_id desc');
if((isset($_COOKIE['triPlanningUser']) && (in_array($_COOKIE['triPlanningUser'], $triPlanningPossibleUser) || in_array($_COOKIE['triPlanningUser'], $triPlanningPossibleProjet)))) {
	$_SESSION['triPlanningUser'] = $_COOKIE['triPlanningUser'];
}
if((isset($_SESSION['triPlanningUser']) && !in_array($_SESSION['triPlanningUser'], $triPlanningPossibleUser) && !in_array($_SESSION['triPlanningUser'], $triPlanningPossibleProjet)) || !isset($_SESSION['triPlanningUser'])) {

	$_SESSION['triPlanningUser'] = 'nom asc';
}
if((isset($_COOKIE['triPlanningProjet']) && (in_array($_COOKIE['triPlanningProjet'], $triPlanningPossibleUser) || in_array($_COOKIE['triPlanningProjet'], $triPlanningPossibleProjet)))) {
	$_SESSION['triPlanningProjet'] = $_COOKIE['triPlanningProjet'];
}
if((isset($_SESSION['triPlanningProjet']) && !in_array($_SESSION['triPlanningProjet'], $triPlanningPossibleUser) && !in_array($_SESSION['triPlanningProjet'], $triPlanningPossibleProjet)) || !isset($_SESSION['triPlanningProjet'])) {
	$_SESSION['triPlanningProjet'] = 'nom asc';
}

if($_SESSION['inverserUsersProjets'] == True) {
	$_SESSION['triPlanning'] = $_SESSION['triPlanningProjet'];
}else{
	$_SESSION['triPlanning'] = $_SESSION['triPlanningUser'];
}

// chargement nb lignes affichées
if(isset($_COOKIE['nb_lignes']) && is_numeric($_COOKIE['nb_lignes'])) {
	$_SESSION['nb_lignes'] = $_COOKIE['nb_lignes'];
}
if (isset($_SESSION['nb_lignes'])) {
	$nbLignes = $_SESSION['nb_lignes'];
} else {
	$nbLignes = CONFIG_DEFAULT_NB_ROWS_DISPLAYED;
	$_SESSION['nb_lignes'] = $nbLignes;
}
if (isset($_SESSION['page_lignes'])) {
	$pageLignes = $_SESSION['page_lignes'];
} else {
	$pageLignes = 1;
	$_SESSION['page_lignes'] = $pageLignes;
}

// chargement affichage lignes vides ou pas
if(isset($_COOKIE['masquerLigneVide'])) {
	$_SESSION['masquerLigneVide'] = $_COOKIE['masquerLigneVide'];
}
if (isset($_SESSION['masquerLigneVide'])) {
	$masquerLigneVide = $_SESSION['masquerLigneVide'];
} else {
	$masquerLigneVide = 0;
	$_SESSION['masquerLigneVide'] = $masquerLigneVide;
}

// chargement affichage lignes vides ou pas
if(isset($_COOKIE['afficherLigneTotal'])) {
	$_SESSION['afficherLigneTotal'] = $_COOKIE['afficherLigneTotal'];
}
if (isset($_SESSION['afficherLigneTotal'])) {
	$afficherLigneTotal = $_SESSION['afficherLigneTotal'];
} else {
	$afficherLigneTotal = 0;
	$_SESSION['afficherLigneTotal'] = $afficherLigneTotal;
}

$_SESSION['planningView'] = 'mois';

// chargement affichage large ou reduit
if(isset($_SESSION['dimensionCase']) and in_array($_SESSION['dimensionCase'],array('large','reduit'))) {
	$dimensionCase = $_SESSION['dimensionCase'];
}else{
	$_SESSION['dimensionCase'] = 'reduit';
	$dimensionCase = $_SESSION['dimensionCase'];
}

if(isset($_SESSION['direct_periode_id'])) {
	$smarty->assign('direct_periode_id', $_SESSION['direct_periode_id']);
	unset($_SESSION['direct_periode_id']);
}

$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);

// FIN PARAMÈTRES ////////////////////////////////
$now = new DateTime();

$nbJours = getNbJoursFull($dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d'));
$dateBoutonInferieur = clone $dateDebut;
$dateBoutonInferieur->modify('-' . $nbJours . 'days');
$dateBoutonSuperieur = clone $dateDebut;
$dateBoutonSuperieur->modify('+' . $nbJours . 'days');

$smarty->assign('dateDebut', $dateDebut->format(CONFIG_DATE_LONG));
$smarty->assign('dateFin', $dateFin->format(CONFIG_DATE_LONG));
$smarty->assign('dateDebutTexte', $smarty->get_config_vars('day_' . $dateDebut->format('w')) . ' ' . $dateDebut->format(CONFIG_DATE_LONG));
$smarty->assign('dateFinTexte', $smarty->get_config_vars('day_' . $dateFin->format('w')) . ' ' . $dateFin->format(CONFIG_DATE_LONG));
$smarty->assign('nbJours', $nbJours);
$smarty->assign('masquerLigneVide', $masquerLigneVide);
$smarty->assign('afficherLigneTotal', $afficherLigneTotal);
$smarty->assign('nbLignes', $nbLignes);
$smarty->assign('pageLignes', $pageLignes);
$smarty->assign('filtreGroupeUser', $_SESSION['filtreGroupeUser']);
$smarty->assign('filtreGroupeProjet', $_SESSION['filtreGroupeProjet']);
$smarty->assign('filtreStatutTache', $_SESSION['filtreStatutTache']);
$smarty->assign('filtreStatutProjet', $_SESSION['filtreStatutProjet']);
$smarty->assign('filtreGroupeLieu', $_SESSION['filtreGroupeLieu']);
$smarty->assign('filtreGroupeRessource', $_SESSION['filtreGroupeRessource']);
$smarty->assign('filtreUser', $_SESSION['filtreUser']);
$smarty->assign('filtreTexte', $_SESSION['filtreTexte']);
$smarty->assign('triPlanning', $_SESSION['triPlanning']);
$smarty->assign('triPlanningPossibleUser', $triPlanningPossibleUser);
$smarty->assign('triPlanningPossibleProjet', $triPlanningPossibleProjet);
$smarty->assign('inverserUsersProjets', $_SESSION['inverserUsersProjets']);
$smarty->assign('scrolls', $_SESSION['scrolls']);
$smarty->assign('dateBoutonInferieur', $dateBoutonInferieur->format(CONFIG_DATE_LONG));
$smarty->assign('dateBoutonSuperieur', $dateBoutonSuperieur->format(CONFIG_DATE_LONG));
$smarty->assign('modeAffichage', $_SESSION['planningView']);
//modif pour affichage etapes
$smarty->assign('dimensionCase', $_SESSION['dimensionCase']);

$headerMois = '' . CRLF;
$headerSemaines = '' . CRLF;
$headerNomJours = '' . CRLF;
$headerNumeroJours = '' . CRLF;
$colspanMois = '0';
$colspanSemaine = '1';
$tmpDate = clone $dateDebut;
$tmpMois = $smarty->get_config_vars('month_' . $tmpDate->format('n')) . ' ' . $tmpDate->format('Y');
$tmpMoisDateDebut = $tmpDate->format(CONFIG_DATE_FIRST_DAY_MONTH);
$tmp2Date = clone $tmpDate;
$tmp2Date->modify('+' . $nbJours . 'days');
$tmpMoisDateFin = $tmp2Date->format(CONFIG_DATE_LONG);

// GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)
while ($tmpDate <= $dateFin) {
	if (in_array($tmpDate->format('w'), $DAYS_INCLUDED) && !in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
		$sClass = 'week';
		$weekend = false;
	} else {
		$sClass = 'weekend';
		$weekend = true;
	}
	if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {
		$sClass .= ' today';
	}
	//id="tdHeaderNomJours"
	$tmpJourDateDebut = $tmpDate->format(CONFIG_DATE_LONG);
	$tmp2Date = clone $tmpDate;
	$tmp2Date->modify('+' . $nbJours . 'days');
	$tmpJourDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
	
	$headerNomJours .= '<th class="planning_head_dayname ' . $sClass . '"><div><a href="javascript:void(0);">' . strtoupper(substr($smarty->get_config_vars('day_' . $tmpDate->format('w')), 0, 1)) . '</a></div></th>' . CRLF;
	//id="tdHeaderNumeroJours"
	$headerNumeroJours .= '<th class="planning_head_day ' . $sClass . '"><a href="javascript:void(0);">' . $tmpDate->format('j') . '</a></th>' . CRLF;

	$nomMoisCourant = $smarty->get_config_vars('month_' . $tmpDate->format('n'));
	if ($nomMoisCourant . ' ' . $tmpDate->format('Y') == $tmpMois) {
		$colspanMois++;
	} else {
		$headerMois .= '<th class="planning_head_month" colspan="' . $colspanMois . '"><a href="javascript:void(0);">' . $tmpMois . '</a></th>' . CRLF;
		$colspanMois = '1';
		$tmpMois = $nomMoisCourant . ' ' . $tmpDate->format('Y');
		$tmpMoisDateDebut = $tmpDate->format(CONFIG_DATE_FIRST_DAY_MONTH);
		$tmp2Date = clone $tmpDate;
		$tmp2Date->modify('+' . $nbJours . 'days');
		$tmpMoisDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
	}
	// gestion des semaines
	if ($tmpDate->format('w') == 0) {
		// calcul du date de debut et fin de semaine
		$dateTime = strtotime( $tmpDate->format('d-m-Y'));
		$tmpSemaineDateDebut = date(CONFIG_DATE_LONG, strtotime('monday last week', $dateTime));
		$tmp2Date = clone $tmpDate;
		$tmp2Date->modify('+' . $nbJours . 'days');
		$tmpSemaineDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
		$headerSemaines .= '<th class="planning_head_week" colspan="' . $colspanSemaine . '"><a href="javascript:void(0);">' . $smarty->get_config_vars('planning_semaine') . ' ' . $tmpDate->format('W') . '</a></th>' . CRLF;
		
		$colspanSemaine = 1;
	} else {
		$colspanSemaine++;
	}
	$tmpDate->modify('+1 day');
}

// on cloture le colspan du mois en cours
$headerMois .= '<th class="planning_head_month" colspan="' . $colspanMois . '"><a href="javascript:void(0);">' . $tmpMois . '</a></th>' . CRLF;
// on cloture le colspan de la semaine en cours
if($colspanSemaine != 1) {
	// calcul du date de debut et fin de semaine
	$dateTime = strtotime( $tmpDate->format(CONFIG_DATE_LONG));
	$tmpSemaineDateDebut = date(CONFIG_DATE_LONG, strtotime('this week last monday', $dateTime));
	$tmp2Date = clone $tmpDate;
	$tmp2Date->modify('+' . $nbJours . 'days');
	$tmpSemaineDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
	$headerSemaines .= '<th class="planning_head_week" colspan="' . ($colspanSemaine-1) . '"><a href="javascript:void(0);">' . $smarty->get_config_vars('planning_semaine') .	' ' . $tmpDate->format('W') . '</a></th>' . CRLF;
}

$html .= '<table class="planningContent" id="tabContenuPlanning">' . CRLF;
$html .= '<tr>' . CRLF;
$html .= '<th id="tdUser_0" rowspan="4"></th>' .CRLF;
$html .= $headerMois . CRLF;
$html .= '</tr>' . CRLF;
$html .= '<tr>' . CRLF;
$html .= $headerSemaines . CRLF;
$html .= '</tr>' . CRLF;
$html .= '<tr>' . CRLF;
$html .= $headerNomJours . CRLF;
$html .= '</tr>' . CRLF;
$html .= '<tr>' . CRLF;
$html .= $headerNumeroJours . CRLF;
$html .= '</tr>' . CRLF;
// FIN GESTION DES ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)

// recuperation des projets couvrant la période, pour le filtre de projets
/*$projetsFiltre = new GCollection('Projet');
$sql = "	SELECT DISTINCT pp.projet_id, pp.nom, pg.groupe_id, pg.nom AS groupe_nom, 'cover' AS cover
			FROM planning_projet pp
			LEFT JOIN planning_periode pd ON pp.projet_id = pd.projet_id 
			LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
}
$sql .="		WHERE (
						(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
						OR
						(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
						) ";
if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propriétaire ou assigné
	$sql .= " AND (pp.createur_id = '" . $user->user_id . "' OR pd.user_id = '" . $user->user_id . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
$sql .= "	GROUP BY pp.nom, pp.projet_id 

			UNION

			SELECT DISTINCT pp.projet_id, pp.nom, pg.groupe_id, pg.nom AS groupe_nom, 'no_cover' AS cover
			FROM planning_projet pp 
			LEFT JOIN planning_periode pd ON pp.projet_id = pd.projet_id 
			LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
}
$sql .= "	WHERE (pp.statut='a_faire' OR pp.statut='en_cours')
			AND pp.projet_id NOT IN (
				SELECT projet_id
				FROM planning_periode pd
				WHERE (
						(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
						OR
						(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
						)
			)
				 ";
if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propriétaire ou assigné
	$sql .= " AND (pp.createur_id = '" . $user->user_id . "' OR pd.user_id = '" . $user->user_id . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
$sql .= "	GROUP BY pp.nom, pp.projet_id 
			ORDER BY cover, groupe_nom, nom ";
$projetsFiltre->db_loadSQL($sql);
$smarty->assign('listeProjets', $projetsFiltre->getSmartyData());
*/
$user_id = $_SESSION['user_id'];

$currentUser = new User();
$currentUser->db_load(array('user_id', '=', $user_id));
$smarty->assign('currentUser', $currentUser->getSmartyData());

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

if($user->checkDroit('tasks_view_own_projects')) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}

// recuperation de la liste des utilisateurs pour filtre sur users
$usersFiltre = new GCollection('User');
$sql = "SELECT pu.*, pug.nom AS groupe_nom
		FROM planning_user pu ";
if($user->checkDroit('tasks_view_specific_users')) {
	$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = pu.user_id AND rou.owner_id = " . val2sql($user->user_id);
}
$sql .= " LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id
		WHERE visible_planning = 'oui' ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
if ($user->checkDroit('tasks_view_only_own')) {
	$sql .= " AND pu.user_id = " . val2sql($user->user_id);
}
$sql .=	" ORDER BY groupe_nom, pu.nom";
$usersFiltre->db_loadSQL($sql);
$smarty->assign('listeUsers', $usersFiltre->getSmartyData());

// liste des status pour tâches
$status = new GCollection('Status');
$sql = "SELECT status_id,nom from planning_status where affichage in ('t','tp') order by priorite asc";
$status->db_loadSQL($sql);
$smarty->assign('listeStatusTaches', $status->getSmartyData());

// liste des status pour projets
$status = new GCollection('Status');
$sql = "SELECT status_id,nom from planning_status where affichage in ('p','tp') order by priorite asc";
$status->db_loadSQL($sql);
$smarty->assign('listeStatusProjets', $status->getSmartyData());

//////////////////////////
// MODIF CALCUL TOTAUX USERS
//////////////////////////

//modif ajout SQL sur user groupe
$realUsers = new GCollection('User');
$sql = "SELECT pu.*, pug.nom
		FROM planning_user pu
		LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id";
if(is_array($_SESSION['filtreUser']) && count($_SESSION['filtreUser']) > 0) {
	$sql .= " WHERE pu.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	$sql .= " AND pu.visible_planning='oui' ";
}else{
	$sql .= " WHERE pu.visible_planning='oui' ";
}
$realUsers->db_loadSQL($sql);
$nbRealUsers = $realUsers->getCount();

$totalParJour = array();
$totauxJourUsers = array();

// on charge les jours occupés pour toutes les lignes
$periodes = new GCollection('Periode');

$sql = "SELECT planning_periode.*,planning_projet.statut, planning_status.nom
		FROM planning_periode
		INNER JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id
		INNER JOIN planning_status ON planning_status.status_id = planning_periode.statut_tache ";

if($user->checkDroit('tasks_view_specific_users')) {
	$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
}
$sql .= "	WHERE planning_periode.projet_id = planning_projet.projet_id and (
			(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			)";
if(is_array($_SESSION['filtreUser']) && count($_SESSION['filtreUser']) > 0) {
	$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
}
if(count($_SESSION['filtreGroupeProjet']) > 0) {
	$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
}
if(count($_SESSION['filtreGroupeLieu']) > 0) {
	$sql.= " AND planning_periode.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
}
if(count($_SESSION['filtreGroupeRessource']) > 0) {
	$sql.= " AND planning_periode.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
}
if(count($_SESSION['filtreStatutTache']) > 0) {
	$sql.= " AND planning_periode.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
}
if(count($_SESSION['filtreStatutProjet']) > 0) {
	$sql.= " AND planning_projet.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
}
if($user->checkDroit('tasks_view_own_projects')) {
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}
if ($user->checkDroit('tasks_view_only_own')) {
	$sql .= " AND planning_periode.user_id = " . val2sql($user->user_id);
}
if($_SESSION['filtreTexte'] != "") {
	$sql.= " AND (planning_periode.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." OR planning_periode.titre LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.custom LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.projet_id LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.user_id LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.statut_tache LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.custom LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " )";
}

$periodes->db_loadSQL($sql);

// pour chaque période du planning, on remplie le tableau des jours occupés
while ($periode = $periodes->fetch()) {
	$infosJour = $periode->getSmartyData();
	$dateDebut_projet = new DateTime();
	$dateDebut_projet->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

	$dateFin_projet = new DateTime();

	$tmpDate = clone $dateDebut_projet;
	if (is_null($periode->date_fin)) {
		$dateFin_projet = clone $dateDebut_projet;
	}
	else {
		$dateFin_projet->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
	}

	while ($tmpDate <= $dateFin_projet) {
		if(!isset($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')])) {
			$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = '00:00';
		}
		if($infosJour['date_fin'] != '') {
			$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
		} else {
			$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
		}
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {$weekend=true;}else $weekend=false;
		// on additionne le total des jours
		if (CONFIG_PLANNING_HIDE_WEEKEND_TASK == 1 || (CONFIG_PLANNING_HIDE_WEEKEND_TASK == 0 && $weekend==false))
		{
		if(!isset($totalParJour[$tmpDate->format('Ymd')])) {
			$totalParJour[$tmpDate->format('Ymd')] = '00:00';
		}
			if($infosJour['date_fin'] != '') {
				$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
			} else {
				$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
			}
		}
		$tmpDate->modify('+1 day');
	}
}
//////////////////////////
// FIN MODIF CALCUL TOTAUX USERS
//////////////////////////
$nbLine = 1;
$groupeCourant = false;
$idGroupeCourant = -1;

// CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERSÉ)
/*if($_SESSION['inverserUsersProjets']) {
	$lines = new GCollection('Projet');
	//modif sql
	$sql = "SELECT distinct planning_projet.*, pg.nom AS groupe_nom
			FROM planning_projet
			LEFT JOIN planning_periode pd ON planning_projet.projet_id = pd.projet_id
			LEFT JOIN planning_groupe AS pg ON planning_projet.groupe_id = pg.groupe_id 
			LEFT JOIN planning_lieu as pl ON pd.lieu_id = pl.lieu_id 
			LEFT JOIN planning_ressource as pr ON pd.ressource_id = pr.ressource_id 
			";
	$sql .= "WHERE (
				(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
				AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
				OR
				(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
				AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			";
			// si on ne masque pas les lignes vides, on inclue également les projets en cours ou à faire, même s'ils ne couvrent pas la période en cours
			// par défaut, les projets terminés ou abandonnés ne sont affichés que s'ils ont au moins une tache sur la periode
			if(!$masquerLigneVide) {
				$sql .= "OR
						(statut='a_faire')
						OR
						(statut='en_cours')
						";
			}
	$sql .= "	)";
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND planning_projet.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreGroupeLieu']) > 0) {
		$sql.= " AND pl.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
	}
	if(count($_SESSION['filtreGroupeRessource']) > 0) {
		$sql.= " AND pr.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
	}
	if(count($_SESSION['filtreStatutProjet']) > 0) {
		$sql.= " AND planning_projet.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
	}
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND planning_projet.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		// on filtre sur les projets de l'équipe de ce user
		$sql .= " AND planning_projet.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	$sql .= " ORDER BY " . $_SESSION['triPlanning'];
} else*/ 
{
	//Hariharan - Change logic to fetch the users
	$lines = new GCollection('User');
	$sql = "SELECT DISTINCT PU.*, pug.nom as team_nom from planning_user PU
				inner join planning_user_groupe pug on pu.user_groupe_id = pug.user_groupe_id";
	
	if($currentUser->user_groupe_id != '') {
		$sql .= " WHERE pug.user_groupe_id=" . $currentUser->user_groupe_id;
	}

	$sql .= " ORDER BY " . $_SESSION['triPlanning'];
}
$lines->db_loadSQL($sql);
$nbLignesTotal = $lines->getCount();

// on refiltre la liste des user sur le nombre max à afficher
$sql .= " LIMIT " . ($nbLignes*($pageLignes-1)) . "," . $nbLignes;
$lines->db_loadSQL($sql);
//echo $sql;
// on recupere le nombre de pages pour afficher le pager
$smarty->assign('nbPagesLignes', ceil($nbLignesTotal/$nbLignes));

// FIN CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERSÉ)
$nbLine = 1;
$groupeCourant = false;
$idGroupeCourant = -1;
$cooltip_liste = array();

while($ligneTmp = $lines->fetch()) {
	if($_SESSION['inverserUsersProjets']) {
		$ligneId = $ligneTmp->projet_id;
	} else {
		$ligneId = $ligneTmp->user_id;
	}

	// every 10 lines, repeat days/month/etc rows
	if(CONFIG_PLANNING_REPEAT_HEADER > 0) {
		if (($nbLine % CONFIG_PLANNING_REPEAT_HEADER) == 0) {
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerMois . CRLF;
			$html .= '</tr>' . CRLF;
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerSemaines . CRLF;
			$html .= '</tr>' . CRLF;
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerNomJours . CRLF;
			$html .= '</tr>' . CRLF;
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerNumeroJours . CRLF;
			$html .= '</tr>' . CRLF;
		}
	}
	$nbLine++;

	// gestion de l'affichage des groupes (de user ou projet) dans le planning
	if(strpos($_SESSION['triPlanning'], 'groupe_nom') !== FALSE || strpos($_SESSION['triPlanning'], 'team_nom') !== FALSE) {
		/*if($_SESSION['inverserUsersProjets']) {
			if($ligneTmp->groupe_nom !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<th class="planning_team_div" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligneTmp->groupe_nom != '' ? xss_protect($ligneTmp->groupe_nom) : $smarty->get_config_vars('planning_pasDeGroupe')) . '&nbsp;' . CRLF;
				$html .= '</th>' . CRLF;
				$tmpDate = clone $dateDebut;
				while ($tmpDate <= $dateFin) {
					$html .= '<td class="planning_team_div">&nbsp;</td>' . CRLF;
					$tmpDate->modify('+1 day');
				}
				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligneTmp->groupe_nom;
		} else */
		{
			if($ligneTmp->team_nom !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<th class="planning_team_div" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligneTmp->team_nom != '' ? xss_protect($ligneTmp->team_nom) : $smarty->get_config_vars('planning_pasDeTeam')) . '&nbsp;' . CRLF;
				$html .= '</th>' . CRLF;
				$tmpDate = clone $dateDebut;
				while ($tmpDate <= $dateFin) {
					$html .= '<td class="planning_team_div">&nbsp;</td>' . CRLF;
					$tmpDate->modify('+1 day');
				}
				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligneTmp->team_nom;
		}
	}

	//Hariharan - Change logic to fetch the resource availability
	// on charge les jours occupés pour cette ligne
	//$periodes = new GCollection('Periode');
	$userNotAvailable = new GCollection('UserNotAvailable');
	/*if( $_SESSION['inverserUsersProjets']) {
		$sql = "SELECT planning_periode.*, planning_user.*, planning_projet.createur_id, pc.nom AS nom_createur, pc2.nom AS nom_modifier, pl.nom as lieu, pr.nom as ressource, ps.nom as status,
				CASE WHEN duree_details = 'AM' THEN '08:00:00;08:01:00' WHEN duree_details = 'PM' THEN '14:00:00;14:01:00' ELSE duree_details END AS tri_heures_taches
				FROM planning_periode ";
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}

		$sql .= "	INNER JOIN planning_status ps ON ps.status_id = planning_periode.statut_tache
					INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id
					INNER JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id
					LEFT JOIN planning_user pc ON planning_periode.createur_id = pc.user_id
					LEFT JOIN planning_user pc2 ON planning_periode.modifier_id = pc2.user_id
					LEFT JOIN planning_lieu AS pl ON planning_periode.lieu_id = pl.lieu_id 
					LEFT JOIN planning_ressource AS pr ON planning_periode.ressource_id = pr.ressource_id 
				WHERE planning_periode.projet_id = " . val2sql($ligneId);
	} else */ 
	{
	/*	$sql = "SELECT planning_periode.*, planning_projet.nom, planning_projet.couleur, pc.nom AS nom_createur, pc2.nom AS nom_modifier, pl.nom as lieu, pr.nom as ressource, ps.nom as status,
				CASE WHEN duree_details = 'AM' THEN '08:00:00;08:01:00' WHEN duree_details = 'PM' THEN '14:00:00;14:01:00' ELSE duree_details END AS tri_heures_taches
				FROM planning_periode ";
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}
		$sql .= "	INNER JOIN planning_status ps ON ps.status_id = planning_periode.statut_tache
					INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id
					INNER JOIN planning_projet ON planning_periode.projet_id = planning_projet.projet_id
					LEFT JOIN planning_user pc ON planning_periode.createur_id = pc.user_id
					LEFT JOIN planning_user pc2 ON planning_periode.modifier_id = pc2.user_id
					LEFT JOIN planning_lieu AS pl ON planning_periode.lieu_id = pl.lieu_id 
					LEFT JOIN planning_ressource AS pr ON planning_periode.ressource_id = pr.ressource_id 
					WHERE planning_periode.user_id = " . val2sql($ligneId);*/
	}
	/*$sql .= "	AND (
					(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
						OR
					(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
						)";
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
		$sql .= " AND (planning_user.user_groupe_id IS NULL OR planning_user.user_groupe_id = " . $user->user_groupe_id . ')';
	}
	if ($user->checkDroit('tasks_view_only_own')) {
		$sql .= " AND planning_user.user_id = " . val2sql($user->user_id);
	}

	if(count($_SESSION['filtreStatutTache']) > 0) {
		$sql.= " AND planning_periode.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
	}
	if(count($_SESSION['filtreStatutProjet']) > 0) {
		$sql.= " AND planning_projet.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
	}
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreGroupeRessource']) > 0) {
		$sql.= " AND planning_periode.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
	}
	if(count($_SESSION['filtreGroupeLieu']) > 0) {
		$sql.= " AND planning_periode.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
	}
	if(is_array($_SESSION['filtreUser']) && count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if($_SESSION['filtreTexte'] != "") {
		$sql.= " AND (planning_periode.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." OR planning_periode.titre LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.custom LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . ")";
	}
	$sql.= " ORDER BY planning_periode.date_debut, tri_heures_taches";*/
	$sql = "select PRA.id, PRA.user_id, PRA.type_id, PRA.start_date, PRA.end_date, PT.name, PT.description from planning_user_unavailability pra
			inner join planning_TYPE PT ON PT.type_id = PRA.type_id
			INNER JOIN PLANNING_USER PU ON PU.user_id = PRA.user_id
			INNER JOIN planning_user_groupe PUG ON PUG.user_groupe_id = PU.user_groupe_id WHERE ";
	
	if(!is_null($currentUser->user_groupe_id)) {
		$sql .= ' PUG.user_groupe_id =' . $currentUser->user_groupe_id . ' and ';
	}
	$sql .= "pra.user_id='" . $ligneId . "'";
	$userNotAvailable->db_loadSQL($sql);
	$ordreJourPrec = array();

	$joursOccupes = array();
	// pour chaque période de cette ligne, on remplie le tableau des jours occupés

	while ($availability = $userNotAvailable->fetch()) {
		$infosJour = $availability->getSmartyData();
		$infosJour['user_nom'] = xss_protect($ligneTmp->nom);
		$dateDebut_projet = new DateTime();
		$dateDebut_projet->setDate(substr($availability->start_date,0,4), substr($availability->start_date,5,2), substr($availability->start_date,8,2));

		$dateFin_projet = new DateTime();

		$tmpDate = clone $dateDebut_projet;
		if (is_null($availability->end_date)) {
			$dateFin_projet = clone $dateDebut_projet;
		}
		else {
			$dateFin_projet->setDate(substr($availability->end_date,0,4), substr($availability->end_date,5,2), substr($availability->end_date,8,2));
		}

		while ($tmpDate <= $dateFin_projet) {
			if (isset($joursOccupes[$tmpDate->format('Y-m-d')])) {
				if(CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY == 0) {
					$tmpArray = $joursOccupes[$tmpDate->format('Y-m-d')];
					$tmpArray[] = $infosJour;
					$joursOccupes[$tmpDate->format('Y-m-d')] = $tmpArray;
				}
			} else {
				$tmpArray = array($infosJour);
				$joursOccupes[$tmpDate->format('Y-m-d')] = $tmpArray;
			}
			$tmpDate->modify('+1 day');
		}
	}

	// si option activée, on masque la ligne si elle est vide
	if($masquerLigneVide == 1 && count($joursOccupes) == 0) {
		continue;
	}

	$ordreJourCourant = array();
	// on genere la ligne courante
	$html .= '<tr>' . CRLF;
	$html .= '<th id="tdUser_' . ($nbLine-1) . '" ' . ((!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF') ? ' style="background-color:#'.$ligneTmp->couleur. ';color:' . buttonFontColor('#' . $ligneTmp->couleur) . '"' : '') . '>&nbsp;';
	// si le user a le droit, on permet de cliquer pour afficher la fiche de l'item (user ou projet)
	if($_SESSION['inverserUsersProjets'] && $user->checkDroit('projects_manage_all')) {
		$html .= '<a style="color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '' : '#ffffff') . '" href="javascript:xajax_modifProjet(\'' . urlencode($ligneTmp->projet_id) . '\');undefined;">' . xss_protect($ligneTmp->nom) . '</a>';
	} elseif (!$_SESSION['inverserUsersProjets'] && $user->checkDroit('users_manage_all')) {
		$html .= '<a style="color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '' : '#ffffff') . '" href="javascript:void(0);">' . xss_protect($ligneTmp->nom) . '</a>';
	} else {
		if ($_SESSION['inverserUsersProjets'] && $ligneTmp->lien != '')
		{
			$html .= '<a style="cursor:url(\'assets/img/pictos/weblink.png\');font-weight: normal;color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '"' : '#ffffff') . '" onclick="javascript:window.open(\''.$ligneTmp->lien.'\')">' . xss_protect($ligneTmp->nom) . '</a>';
		}else $html .= xss_protect($ligneTmp->nom);
	}
	$html .= '&nbsp;</th>' . CRLF;
	$tmpDate = clone $dateDebut;
	// Calcul de la largeur ligne / hauteur colonne 
	if (CONFIG_PLANNING_LINE_HEIGHT > 0 || CONFIG_PLANNING_COL_WIDTH > 0 || CONFIG_PLANNING_COL_WIDTH_LARGE > 0)
	{
		$styleLigne = ' style="';
		if(CONFIG_PLANNING_LINE_HEIGHT > 0) 
		{
			$styleLigne .= 'height:' . CONFIG_PLANNING_LINE_HEIGHT . 'px;';
		}
		if ($dimensionCase=='large')
		{
			if(CONFIG_PLANNING_COL_WIDTH_LARGE > 0) 
			{
				$styleLigne .= 'min-width:' . CONFIG_PLANNING_COL_WIDTH_LARGE . 'px;';
			}			
		}else
		{
			if(CONFIG_PLANNING_COL_WIDTH > 0) 
			{
				$styleLigne .= 'min-width:' . CONFIG_PLANNING_COL_WIDTH . 'px;';
			}
		}
		$styleLigne .= '"';
	}else $styleLigne = '';
	// on boucle sur la durée de l'affichage
	while ($tmpDate <= $dateFin) {
		// définit le style pour case semaine et WE
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
			$classTD = 'weekend';
			$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
			$weekend = true;
		} else {
			$classTD = 'week';
			$opacity = '';
			$weekend = false;
		}
		if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
			// on ajoute le jour à la liste des destinations possible pour drag and drop
			$js .= 'destinationsDrag[destinationsDrag.length] = "td_' . $ligneId . '_' . $tmpDate->format('Ymd')	. '";';
		}

		if (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
			// jours fériés
			$ferieObj = new Ferie();
			if($ferieObj->db_load(array('date_ferie', '=', $tmpDate->format('Y-m-d'))) && trim($ferieObj->libelle) != "") {
				$cooltip = '<b>' . $ferieObj->libelle . '</b>';
				$ferie = '<div class="cellHolidays tooltipster" data-tooltip-content="#tooltip-'.$ferieObj->date_ferie.'-'.($nbLine-1).'">' . $smarty->get_config_vars('planning_ferie') . '</div><div class="tooltip-html"><div id="tooltip-'.$ferieObj->date_ferie.'-'.($nbLine-1).'">' . addslashes($cooltip) . '</div></div>' . CRLF;
			}
		} else {
			$ferie = false;
		}
			
		if (isset($joursOccupes[$tmpDate->format('Y-m-d')])) {
			// jours avec au moins une case remplie
			$html .= '<td ' . $styleLigne . ' id="td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '"';
			if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
				$droitAjoutPeriode = true;
			}else {
				$droitAjoutPeriode = false;
			}
			$html .= ' class="' . $classTD . (($tmpDate->format('Y-m-d') == date('Y-m-d')) ? ' today' : '') . '">' . CRLF;

			if($ferie !== false) {
				$html .= $ferie;
			}

			$niveauCourant = 0;
			// si il y a des periodes pour le jour courant, on boucle pour toutes les afficher
			foreach ($joursOccupes[$tmpDate->format('Y-m-d')] as $jour) {
				// generation des cellules vides pour aligner les cases d'une meme periode
				if(in_array($jour['id'], $ordreJourPrec) && $niveauCourant != array_search($jour['id'], $ordreJourPrec)) {					
					$nbVides = (array_search($jour['id'], $ordreJourPrec)-$niveauCourant);
					for($i=1; $i<=$nbVides; $i++) {
						$html .= '<div class="cellProject cellEmpty">&nbsp;</div>' . CRLF;
						$niveauCourant++;
					}
					$niveauCourant++;
					$ordreJourCourant[array_search($jour['id'], $ordreJourPrec)] = $jour['id'];
				} else {
					$ordreJourCourant[] = $jour['id'];
					$niveauCourant++;
				}

				// mouseover sur la case
				/*$cooltip="";
				if($jour['titre'] != '') {
					$cooltip .= '<b>' . $smarty->get_config_vars('winPeriode_titre') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['titre'])).'<br />';
				}
				$cooltip .=	'<b>' . $smarty->get_config_vars('tab_projet') . '</b> : ' . $jour['projet_nom'] . ' (' . $jour['projet_id'] . ')<br/>'
					. '<b>' . $smarty->get_config_vars('tab_personne') . '</b> : ' . $jour['user_nom'] . ' (' . $jour['user_id'] . ')';
				if($jour['livrable'] == 'oui') {
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_livrable') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $smarty->get_config_vars('oui')));
				}
				if($jour['statut_tache'] != '') {
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_statut') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['status']));
				}
				$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_dateDebut') . '</b> : ' . sqldate2userdate($jour['date_debut']);
				if($jour['date_fin'] != '') {
					$cooltip .= '<br/><b>'	. $smarty->get_config_vars('tab_dateFin') .	'</b> : ' . sqldate2userdate($jour['date_fin']);
				} else {
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_duree') . '</b> : ' . sqltime2usertime($jour['duree']);
					if(isset($jour['duree_details_heure_debut'])) {
						$cooltip .= ' (' . sqltime2usertime($jour['duree_details_heure_debut']) . ' => ' . sqltime2usertime($jour['duree_details_heure_fin']) . ')';
					}
					if($jour['duree_details'] == 'AM') {
						$cooltip .= ' (' . $smarty->get_config_vars('tab_matin') . ')';
					}
					if($jour['duree_details'] == 'PM') {
						$cooltip .= ' (' . $smarty->get_config_vars('tab_apresmidi') . ')';
					}
				}
				if (CONFIG_SOPLANNING_OPTION_LIEUX == 1)
				{
					if($jour['lieu'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_lieu') . '</b> : ' . $jour['lieu'] ;
					}
				}
				if (CONFIG_SOPLANNING_OPTION_RESSOURCES == 1)
				{
					if($jour['ressource'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_ressource') . '</b> : ' . $jour['ressource'] ;
					}
				}
				if($jour['notes'] != '') {
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_commentaires') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['notes']));
				}
				if($jour['custom'] != '') {
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_custom') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['custom']));
				}
				if($jour['lien'] != '') {
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_lien') . '</b> : ' . ($jour['lien']);
				}
				$cooltip.="<hr />";
				if($jour['nom_createur'] != '') {
					$cooltip .= $smarty->get_config_vars('periode_creation').' '.$smarty->get_config_vars('periode_modifier_par').' '.($jour['nom_createur'])." ".$smarty->get_config_vars('periode_le')." ".sqldatetime2userdatetime($jour['date_creation']);
				}
				if($jour['nom_modifier'] != '') {
					$cooltip .= "<br />".$smarty->get_config_vars('periode_modification').' '.$smarty->get_config_vars('periode_modifier_par').' ' . ($jour['nom_modifier'])." ".$smarty->get_config_vars('periode_le')." ".sqldatetime2userdatetime($jour['date_modif']);
				}

				// couleur du texte dans la case, selon la couleur de fond de la case
				$couleurTexte = buttonFontColor('#' . $jour['couleur']);
				$couleurFond = $jour['couleur'];
				if($couleurFond =='FFFFFF'){
					$couleurFond = 'fafafa';
				}
				$cooltip_liste[$jour['periode_id']]=$cooltip;
				*/
				$couleurTexte = buttonFontColor('#FAFAFA');
				$couleurFond = $couleurTexte;
				
				// On masque la case 
				if (CONFIG_PLANNING_HIDE_WEEKEND_TASK == 0 && ($weekend==true || $ferie!=false)) {$classHidden=" hidden";}else $classHidden="";

				if($dimensionCase=='large') {$classCell="cellProjectLarge";}else $classCell="";
				// la case avec le code du projet
				$html .= '<div id="c_' . $jour['id'] . '_' . $tmpDate->format('Ymd') . '" class="cellProject '.$classCell.' tooltipster '.$classHidden.'" data-tooltip-content="#tooltip-'.$jour['id'].'"	style="color:' . $couleurTexte . ';' . $opacity . ';background-color:#' . $couleurFond . ';';
				if($dimensionCase=='reduit') {
 					$html .= 'border-radius: 10px;text-align:center;';
				}
				$html .= 'cursor:url(\'assets/img/pictos/weblink.png\')';
				$html .= '"';
				$html .=">";

			
				//modif ajout picto charge journaliere
				if($dimensionCase=='large') {
					$ratioCharge = round(decimalHours($totauxJourUsers[$jour['user_id']][$tmpDate->format('Ymd')]) / $TotalMaxJour, 1);
					$ratio = round($ratioCharge * 10);
					if ($ratio > 10) {
						$ratio = 11;
					}

					if ($ratio == 0) {
						if ($jour['lien'] != '') {
							$html .= '<div class="jauge0"></div>';
							$html .= '<img src="assets/img/pictos/link.gif" class="picto-link" alt="" />';
						} else {
							$html .= '<div class="jauge0"></div>';
						}
					} else {
						if ($jour['lien'] != '') {
							$html .= '<div class="jauge0">';
							$html .= '<div class="jauge' . $ratio . '">';
							if ($ratio == 10) {
								$html .= '100';
							}
							$html .= '</div></div>';
							$html .= '<img src="assets/img/pictos/link.gif" class="picto-link" alt="" />';
						} else {
							$html .= '<div class="jauge0">';
							$html .= '<div class="jauge' . $ratio . '">';
							if ($ratio == 10) {
								$html .= '100';
							}
							$html .= '</div></div>';
						}
					}
				}
				// Modif affichage simple ou complet

				$L1 = $jour['name'];
				$L2 = '';
				/*if($jour['livrable'] == 'non') {
					if( $_SESSION['inverserUsersProjets']) {
						$L1=$jour['user_id'];
					}else{
						$L1=$jour['projet_id'];
					}
				}
				$L2=$jour['titre'];
				//$styleL2reduit = '';


				if (isset($jour['duree_details_heure_debut'])){
					$infoDebut=sqltime2usertime($jour['duree_details_heure_debut']);
				}else{
					$infoDebut='09:00';
				} 

				if (isset($jour['duree'])){
					$affichageDuree=$jour['duree'];
				}else{
					$affichageDuree= CONFIG_DURATION_DAY . ':00:00';
				}
				*/
				if ($L2==''){
					$L2='...';
				}

				$nom = substr($L1, 0, CONFIG_PLANNING_CODE_WIDTH);

				/*if($jour['livrable'] == 'oui') {
						$html .= $nom . '<img src="assets/img/pictos/milestone.png" class="picto-milestone-'.$dimensionCase.'" />';
				} else */{
					$html .= $nom;
				}
				$html .= '</div>';

				// we allow click for everyone, to be able to see task detail
				$js .= 'Drag.init(document.getElementById("c_' . $jour['id'] . '_' . $tmpDate->format('Ymd') . '"));' . CRLF;
			}

			$ordreJourPrec = $ordreJourCourant;
			$ordreJourCourant = array();

			// espace vide pour permettre de cliquer en dessous d'une case assignée
			//$html.= '<div class="cellEmpty"></div>';

			$html .= '</td>' . CRLF;
		} else {
			// jour vide
			$html .= '<td ' . $styleLigne . ' id="td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '"';
			if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
				$droitAjoutPeriode = true;
			} else {
				$droitAjoutPeriode = true;
			}
			$html .= ' class="' . $classTD . (($tmpDate->format('Y-m-d') == date('Y-m-d')) ? ' today' : '') . '">';
			if($ferie !== false) {
				$html .= $ferie;
			} else {
				$html .= '&nbsp;';
			}
			$html .= '</td>' . CRLF;
		}
		$tmpDate->modify('+1 day');
	}
	$html .= '</tr>' . CRLF;
}
// ligne de total
if($afficherLigneTotal == 1) {
	// Calcul de la largeur ligne / hauteur colonne 
	if (CONFIG_PLANNING_LINE_HEIGHT > 0 || CONFIG_PLANNING_COL_WIDTH > 0 || CONFIG_PLANNING_COL_WIDTH_LARGE > 0)
	{

		$styleLigne = ' style="';
		if(CONFIG_PLANNING_LINE_HEIGHT > 0) 
		{
			$styleLigne .= 'height:' . CONFIG_PLANNING_LINE_HEIGHT . 'px;';
		}
		if ($dimensionCase=='large')
		{
			if(CONFIG_PLANNING_COL_WIDTH_LARGE > 0) 
			{
				$styleLigne .= 'width:' . CONFIG_PLANNING_COL_WIDTH_LARGE . 'px;';
			}			
		}else
		{
			if(CONFIG_PLANNING_COL_WIDTH > 0) 
			{
				$styleLigne .= 'width:' . CONFIG_PLANNING_COL_WIDTH . 'px;';
			}
		}
		$styleLigne .= '"';
	}else $styleLigne = '';
	$html .= '<tr><th id="tdTotal" '.$styleLigne.'>' . $smarty->get_config_vars('tab_totalJour') . '</th>' .CRLF;
	$tmpDate = clone $dateDebut;
	
	// on boucle sur la durée de l'affichage
	while ($tmpDate <= $dateFin) {
		
		// définit le style pour case semaine et WE
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
			$classTD = 'weekend';
			$weekend = true;
		} else {
			$classTD = 'week';
			$weekend = false;
		}
		
		if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {$classTD .= ' today';}
		if(isset($totalParJour[$tmpDate->format('Ymd')])) {
			$capitalCharge=$nbRealUsers*CONFIG_DURATION_DAY;
			if($capitalCharge != 0){
				$ratioCharge=round(decimalHours($totalParJour[$tmpDate->format('Ymd')])/$capitalCharge,1);
			}else{
				$ratioCharge=0;
			}

			$ratio=round($ratioCharge*10);
			if ($ratio > 10){
				$ratio=11;
			}
			if($dimensionCase=='large'){
				$symboleH1='h/';
				$symboleH2='h';
			}else{
				$symboleH1='/';
				$symboleH2='';
			}

			if($dimensionCase=='large') {
				if($ratio == 0) {
					$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'><div class="sumLargeCell">' . $totalParJour[$tmpDate->format('Ymd')];
					$html .= '</div><div class="jaugeTD"><div class="jauge0"></div></div></td>' . CRLF;
				} else{
					$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'><div class="sumLargeCell">' . $totalParJour[$tmpDate->format('Ymd')];
					$html .= '</div><div class="jaugeTD"><div class="jauge0">';
					$html .= '<div class="jauge' . $ratio . '">';
					if ($ratio == 10) {
						$html .= '100';
					}
					$html .= '</div></div></div></td>' . CRLF;
				}
			} else{
				$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'>' . $totalParJour[$tmpDate->format('Ymd')];
				$html .= '</td>' . CRLF;
			}
		} else {
			$html .= '<td class="' . $classTD . '">&nbsp;</td>' . CRLF;
		}
		$tmpDate->modify('+1 day');
	}
	$html .= '</tr>';
}

$html .= '</table>' . CRLF;

$smarty->assign('htmlTableau', $html);

// Affichage des tooltip
$html .= "<div class='tooltip-html'>";
foreach ($cooltip_liste as $cle => $valeur)
{
	$html .= "<div id='tooltip-$cle'>$valeur</div>";
}
$html .= "</div>";
// anchor for show/hide, move the page to be the entire project table
$html .= '<a id="anchorProjectTable"></a>';

// pour savoir combien de groupes à afficher dans colonne de gauche
$smarty->assign('nbGroupes', ($idGroupeCourant+1));
$smarty->assign('droitAjoutPeriode',$droitAjoutPeriode);
//$smarty->assign('htmlRecap', $html);
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->assign('js', $js);
$smarty->display('www_uplanning.tpl');
