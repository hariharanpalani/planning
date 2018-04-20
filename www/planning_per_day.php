<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$html = '';
$js = '';

$joursFeries = getJoursFeries();
$droitAjoutPeriode=false;
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
$dateDebut = new DateTime();

// start date
if(isset($_COOKIE['date_debut_affiche'])) {
	$_SESSION['date_debut_affiche'] = $_COOKIE['date_debut_affiche'];
}
if (isset($_SESSION['date_debut_affiche'])) {
	$dateDebut->setDate(substr($_SESSION['date_debut_affiche'],6,4), substr($_SESSION['date_debut_affiche'],3,2), substr($_SESSION['date_debut_affiche'],0,2));
} else {
	$dateDebut->modify('-' . CONFIG_DEFAULT_NB_PAST_DAYS . ' days');
	$_SESSION['date_debut_affiche'] = $dateDebut->format('d/m/Y');
}

// end date
$dateFin = new DateTime();
if(isset($_COOKIE['date_fin_affiche'])) {
	$_SESSION['date_fin_affiche'] = $_COOKIE['date_fin_affiche'];
}
if (isset($_SESSION['date_fin_affiche'])) {
	$dateFin->setDate(substr($_SESSION['date_fin_affiche'],6,4), substr($_SESSION['date_fin_affiche'],3,2), substr($_SESSION['date_fin_affiche'],0,2));
} else {
	$dateFin = clone $dateDebut;
	$dateFin->modify('+' . CONFIG_DEFAULT_NB_DAYS_DISPLAYED . ' days');
	$_SESSION['date_fin_affiche'] = $dateFin->format('d/m/Y');
}

// if day view and more than 20 days displayed, we limit
$interval = date_diff2($dateDebut,$dateFin);
if($interval > 20) {
	$dateFin = clone $dateDebut;
	$dateFin->modify('+ 20 days');
}

if(isset($_COOKIE['inverserUsersProjets'])) {
	$_SESSION['inverserUsersProjets'] = $_COOKIE['inverserUsersProjets'];
}
if (!isset($_SESSION['inverserUsersProjets'])) {
	// Si préférences utilisateurs est vueProjet
	if ($_SESSION['preferences']['vueDefaut']=='vueProjet')
	{
		$_SESSION['inverserUsersProjets'] = true;
	}else $_SESSION['inverserUsersProjets'] = false;
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

$_SESSION['planningView'] = 'jour';

// chargement affichage large ou reduit
if(isset($_SESSION['dimensionCase']) and in_array($_SESSION['dimensionCase'],array('large','reduit'))) {
	$dimensionCase = $_SESSION['dimensionCase'];
}else{
	$_SESSION['dimensionCase'] = 'reduit';
	$dimensionCase = $_SESSION['dimensionCase'];
}

if($dimensionCase=='large'){
	$largeurCase = 130;
}else{
	$largeurCase = 34;
}

if(isset($_SESSION['direct_periode_id'])) {
	$smarty->assign($_SESSION['direct_periode_id'], $_SESSION['direct_periode_id']);
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

$smarty->assign('dateDebut', $dateDebut->format('d/m/Y'));
$smarty->assign('dateFin', $dateFin->format('d/m/Y'));
$smarty->assign('dateDebutTexte', $smarty->get_config_vars('day_' . $dateDebut->format('w')) . ' ' . $dateDebut->format(CONFIG_DATE_LONG));
$smarty->assign('dateFinTexte', $smarty->get_config_vars('day_' . $dateFin->format('w')) . ' ' . $dateFin->format(CONFIG_DATE_LONG));
$smarty->assign('nbJours', $nbJours);
$smarty->assign('masquerLigneVide', $masquerLigneVide);
$smarty->assign('afficherLigneTotal', $afficherLigneTotal);
$smarty->assign('nbLignes', $nbLignes);
$smarty->assign('pageLignes', $pageLignes);
$smarty->assign('filtreGroupeUser', $_SESSION['filtreGroupeUser']);
$smarty->assign('filtreGroupeProjet', $_SESSION['filtreGroupeProjet']);
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
$smarty->assign('dimensionCase', $_SESSION['dimensionCase']);
$tmpDate = clone $dateDebut;
$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);

$html .= '<table class="planningContent" id="tabContenuPlanning">' . CRLF;
$html .= '<tr>' . CRLF;
$html .= '<th id="tdUser_0" rowspan="2"></th>' . CRLF;
$tmpDateFin = clone $dateFin;
while ($tmpDate <= $dateFin) {
	if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
		$tmpDate->modify('+1 day');
		// IMPORTANT : on modifie la date de fin pour tenir compte des jours à sauter
		$dateFin->modify('+1 day');
		continue;
	} elseif (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
		$sClass = 'weekend';
		$weekend = true;
		$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
	} else {
		$weekend = false;
		$sClass = 'week';
	}
	if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {
		$sClass .= ' today';
	}
	$dateTime = strtotime( $tmpDate->format('d-m-Y'));
	$tmpSemaineDateDebut = date(CONFIG_DATE_LONG, strtotime('monday this week', $dateTime));
	$tmp2Date = clone $tmpDate;
	$tmp2Date->modify('+' . $nbJours . 'days');
	$tmpSemaineDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
	$tmpJourDateDebut = $tmpDate->format(CONFIG_DATE_LONG);
	$tmp2Date = clone $tmpDate;
	$tmp2Date->modify('+' . $nbJours . 'days');
	$tmpJourDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
	
	$html .= '<th colspan="' . count($tabTranchesHoraires) . '" class="planning_head_week ' . $sClass .'">' . CRLF;
	$html .= '<a href="process/planning.php?date_debut_affiche='.$tmpSemaineDateDebut.'&date_fin_affiche='.$tmpSemaineDateFin.'">'.$smarty->get_config_vars('planning_semaine2') . ' ' . $tmpDate->format('W') . '</a>&nbsp;&nbsp;&nbsp;' . $smarty->get_config_vars('planning_jour') . ' : <a href="process/planning.php?date_debut_affiche='.$tmpJourDateDebut.'&date_fin_affiche='.$tmpJourDateFin.'">' . $smarty->get_config_vars('day_' . $tmpDate->format('w')) . ' ' . $tmpDate->format(CONFIG_DATE_LONG) .'</a>'.CRLF;
	$html .= '</th>' . CRLF;
	$tmpDate->modify('+1 day');
}
$html .= '</tr>' . CRLF;
$html .= '<tr>' . CRLF;
$tmpDate = clone $dateDebut;
// On réinitialise la dateFin
$dateFin = clone $tmpDateFin;
while ($tmpDate <= $dateFin) {
	if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
		$tmpDate->modify('+1 day');
		// IMPORTANT : on modifie la date de fin pour tenir compte des jours à sauter
		$dateFin->modify('+1 day');
		continue;
	} elseif (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
		$sClass = 'weekend';
		$weekend = true;
		$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
	} else {
		$sClass = 'weekend';
		$weekend = false;
	}
	if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {
		$sClass .= ' today';
	}
	reset($tabTranchesHoraires);
	foreach ($tabTranchesHoraires as $trancheHeureCourante) {
		$trancheFin = $trancheHeureCourante + 1;
		if($trancheFin == 24) {
			$trancheFin = 0;
		}
		$html .= '<th class="planning_head_hour ' . $sClass . '" style="width:' . $largeurCase . 'px;">' . $trancheHeureCourante . '-' .  $trancheFin . $smarty->get_config_vars('tab_h') . '</th>';
	}
	$tmpDate->modify('+1 day');
}
$html .= '</tr>' . CRLF;

// recuperation des projets couvrant la période, pour le filtre de projets
$projetsFiltre = new GCollection('Projet');
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

if($user->checkDroit('tasks_view_own_projects')) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}
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
if(count($_SESSION['filtreUser']) > 0) {
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
		INNER JOIN planning_status ON planning_status.status_id = planning_periode.statut_tache
		INNER JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id ";
if($user->checkDroit('tasks_view_specific_users')) {
	$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
}
$sql .= "	WHERE planning_periode.projet_id=planning_projet.projet_id and (
			(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			)";
if(count($_SESSION['filtreUser']) > 0) {
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
if($user->checkDroit('tasks_view_own_projects')) {
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'équipe de ce user
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}

if(count($_SESSION['filtreStatutTache']) > 0) {
	$sql.= " AND planning_periode.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
}
if(count($_SESSION['filtreStatutProjet']) > 0) {
	$sql.= " AND planning_projet.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
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
		// on additionne le total des jours
		if(!isset($totalParJour[$tmpDate->format('Ymd')])) {
			$totalParJour[$tmpDate->format('Ymd')] = '00:00';
		}
		if($infosJour['date_fin'] != '') {
			$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
		} else {
			$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
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
$cooltip_liste = array();
// CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERSÉ)
if($_SESSION['inverserUsersProjets']) {
	$lines = new GCollection('Projet');
	//modif sql
	$sql = "SELECT distinct planning_projet.*, pg.nom AS groupe_nom
						FROM planning_projet
						LEFT JOIN planning_periode pd ON planning_projet.projet_id = pd.projet_id
						LEFT JOIN planning_groupe AS pg ON planning_projet.groupe_id = pg.groupe_id 
			LEFT JOIN planning_lieu as pl ON pd.lieu_id = pl.lieu_id 
			LEFT JOIN planning_ressource as pr ON pd.ressource_id = pr.ressource_id ";
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
	if(count($_SESSION['filtreStatutProjet']) > 0) {
		$sql.= " AND planning_projet.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
	}
	if(count($_SESSION['filtreGroupeLieu']) > 0) {
		$sql.= " AND pl.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
	}
	if(count($_SESSION['filtreGroupeRessource']) > 0) {
		$sql.= " AND pr.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
	}
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND planning_projet.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		// on filtre sur les projets de l'équipe de ce user
		$sql .= " AND planning_projet.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	$sql .= " ORDER BY " . $_SESSION['triPlanning'];
} else {
	$lines = new GCollection('User');
	$sql = "SELECT DISTINCT planning_user.*, planning_user_groupe.nom AS team_nom
			FROM planning_user ";
	if($user->checkDroit('tasks_view_specific_users')) {
		$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_user.user_id AND rou.owner_id = " . val2sql($user->user_id);
	}
	$sql .= " LEFT JOIN planning_user_groupe ON planning_user.user_groupe_id = planning_user_groupe.user_groupe_id
				LEFT JOIN planning_periode pd ON planning_user.user_id = pd.user_id
				WHERE visible_planning = 'oui'";
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND planning_user.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND planning_user.user_groupe_id = " . $user->user_groupe_id;
	}
	if ($user->checkDroit('tasks_view_only_own')) {
		$sql .= " AND planning_user.user_id = " . val2sql($user->user_id);
	}
	if($masquerLigneVide) {
		$sql .= " AND (
					(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
					AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
					OR
					(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
					AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
					)
				";
	}
	$sql .= " ORDER BY " . $_SESSION['triPlanning'];
}

$lines->db_loadSQL($sql);
//echo $sql;
$nbLignesTotal = $lines->getCount();

// on refiltre la liste des user sur le nombre max à afficher
$sql .= " LIMIT " . ($nbLignes*($pageLignes-1)) . "," . $nbLignes;
$lines->db_loadSQL($sql);

// on recupere le nombre de pages pour afficher le pager
$smarty->assign('nbPagesLignes', ceil($nbLignesTotal/$nbLignes));

// FIN CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERSÉ)


$nbLine = 1;
$groupeCourant = false;
$idGroupeCourant = -1;

while($ligneTmp = $lines->fetch()) {
	if($_SESSION['inverserUsersProjets']) {
		$ligneId = $ligneTmp->projet_id;
	} else {
		$ligneId = $ligneTmp->user_id;
	}

	$nbLine++;

	// gestion de l'affichage des groupes (de user ou projet) dans le planning
	if(strpos($_SESSION['triPlanning'], 'groupe_nom') !== FALSE || strpos($_SESSION['triPlanning'], 'team_nom') !== FALSE) {
		if($_SESSION['inverserUsersProjets']) {
			if($ligneTmp->groupe_nom !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<th class="planning_team_div" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligneTmp->groupe_nom != '' ? xss_protect($ligneTmp->groupe_nom) : $smarty->get_config_vars('planning_pasDeGroupe')) . '&nbsp;' . CRLF;
				$html .= '</th>' . CRLF;
				$tmpDate = clone $dateDebut;
				while ($tmpDate <= $dateFin) {
					if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
						$tmpDate->modify('+1 day');
						continue;
					}
					foreach ($tabTranchesHoraires as $trancheHeureCourante){
						$html .= '<td>&nbsp;</td>' . CRLF;
					}
					$tmpDate->modify('+1 day');
				}
				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligneTmp->groupe_nom;
		} else {
			if($ligneTmp->team_nom !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<th class="planning_team_div" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligneTmp->team_nom != '' ? xss_protect($ligneTmp->team_nom) : $smarty->get_config_vars('planning_pasDeTeam')) . '&nbsp;' . CRLF;
				$html .= '</th>' . CRLF;
				$tmpDate = clone $dateDebut;
				while ($tmpDate <= $dateFin) {
					if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
						$tmpDate->modify('+1 day');
						continue;
					}
					foreach ($tabTranchesHoraires as $trancheHeureCourante){
						$html .= '<td class="planning_team_div">&nbsp;</td>' . CRLF;
					}
					$tmpDate->modify('+1 day');
				}
				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligneTmp->team_nom;
		}
	}

	// on charge les jours occupés pour cette ligne
	$periodes = new GCollection('Periode');
	if( $_SESSION['inverserUsersProjets']) {
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
					WHERE planning_periode.projet_id = '" . $ligneId . "'";
	} else {
		$sql = "SELECT planning_periode.*, planning_projet.nom, planning_projet.couleur, pc.nom AS nom_createur, pc2.nom AS nom_modifier, pl.nom as lieu, pr.nom as ressource, ps.nom as status,
				CASE WHEN duree_details = 'AM' THEN '08:00:00;08:01:00' WHEN duree_details = 'PM' THEN '14:00:00;14:01:00' ELSE duree_details END AS tri_heures_taches
				FROM planning_periode ";
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}
		$sql .= "INNER JOIN planning_status ps ON ps.status_id = planning_periode.statut_tache
				INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id
				INNER JOIN planning_projet ON planning_periode.projet_id = planning_projet.projet_id
				LEFT JOIN planning_user pc ON planning_periode.createur_id = pc.user_id
				LEFT JOIN planning_user pc2 ON planning_periode.modifier_id = pc2.user_id
				LEFT JOIN planning_lieu AS pl ON planning_periode.lieu_id = pl.lieu_id 
				LEFT JOIN planning_ressource AS pr ON planning_periode.ressource_id = pr.ressource_id 				
				WHERE planning_periode.user_id = '" . $ligneId . "'";
	}
	$sql .= "	AND (
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
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if($_SESSION['filtreTexte'] != "") {
		$sql.= " AND (planning_periode.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." OR planning_periode.titre LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.custom LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " )";
	}
	$sql.= " ORDER BY planning_periode.date_debut, tri_heures_taches";
	// echo $sql . '<br>';
	$periodes->db_loadSQL($sql);

	$ordreJourPrec = array();

	$joursOccupes = array();

	// pour chaque période de cette ligne, on remplie le tableau des jours occupés
	while ($periode = $periodes->fetch()) {
		$infosJour = $periode->getSmartyData();
		if( $_SESSION['inverserUsersProjets']) {
			$infosJour['projet_nom'] = xss_protect($ligneTmp->nom);
			$infosJour['user_nom'] = xss_protect($periode->nom);
		} else {
			$infosJour['projet_nom'] = xss_protect($periode->nom);
			$infosJour['user_nom'] = xss_protect($ligneTmp->nom);
		}

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
	$html .= '<th id="tdUser_' . ($nbLine-1) . '"' . ((!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF') ? ' style="background-color:#'.$ligneTmp->couleur. ';color:' . buttonFontColor('#' . $ligneTmp->couleur) . '"' : '') . '>&nbsp;';
	// si le user a le droit, on permet de cliquer pour afficher la fiche de l'item (user ou projet)
	if($_SESSION['inverserUsersProjets'] && $user->checkDroit('projects_manage_all')) {
		$html .= '<a style="color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '' : '#ffffff') . '" href="javascript:xajax_modifProjet(\'' . urlencode($ligneTmp->projet_id) . '\');undefined;">' . xss_protect($ligneTmp->nom) . '</a>';
	} elseif (!$_SESSION['inverserUsersProjets'] && $user->checkDroit('users_manage_all')) {
		$html .= '<a style="color:' . (!is_null($ligneTmp->couleur) && $ligneTmp->couleur != 'FFFFFF' ? buttonFontColor('#' . $ligneTmp->couleur) . '' : '#ffffff') . '" href="javascript:xajax_modifUser(\'' . urlencode($ligneTmp->user_id) . '\');undefined;">' . xss_protect($ligneTmp->nom) . '</a>';
	} else {
		$html .= xss_protect($ligneTmp->nom);
	}
	$html .= '&nbsp;</th>' . CRLF;

	$tmpDate = clone $dateDebut;
	// on boucle sur la durée de l'affichage
	while ($tmpDate <= $dateFin) {
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
			$tmpDate->modify('+1 day');
			continue;
		}

	  // Calcul de la largeur ligne / hauteur colonne 
		if (CONFIG_PLANNING_LINE_HEIGHT > 0 || CONFIG_PLANNING_COL_WIDTH > 0 || CONFIG_PLANNING_COL_WIDTH_LARGE > 0) {
			$styleLigne = ' style="';
			if(CONFIG_PLANNING_LINE_HEIGHT > 0) {
				$styleLigne .= 'height:' . CONFIG_PLANNING_LINE_HEIGHT . 'px;';
			}
			if ($dimensionCase=='large') {
				if(CONFIG_PLANNING_COL_WIDTH_LARGE > 0) {
					$styleLigne .= 'min-width:' . CONFIG_PLANNING_COL_WIDTH_LARGE . 'px;';
				}			
			} else {
				if(CONFIG_PLANNING_COL_WIDTH > 0) {
					if(CONFIG_PLANNING_COL_WIDTH > 37) {
						$styleLigne .= 'min-width:' . CONFIG_PLANNING_COL_WIDTH . 'px;';
					} else {
						$styleLigne .= 'min-width:37px;';
					}
				}
			}
			$styleLigne .= '"';
		} else {
			$styleLigne .= ' style="min-width:37px;"';
		}

		reset($tabTranchesHoraires);
		foreach ($tabTranchesHoraires as $trancheHeureCourante) {
			// définit le style pour case semaine et WE
			if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
				$classTD = 'weekend';
				$weekend = true;
				$opacity = 'filter:alpha(opacity=60);-moz-opacity:.60;opacity:.60';
			} else {
				$classTD = 'week';
				$weekend = false;
				$opacity = '';
			}

			if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
				// on ajoute le jour à la liste des destinations possible pour drag and drop
				$js .= 'destinationsDrag[destinationsDrag.length] = "td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante  . '";' . CRLF;
			}

			if (in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
				// jours fériés
				$ferieObj = new Ferie();
				$ferie = true;
				if($ferieObj->db_load(array('date_ferie', '=', $tmpDate->format('Y-m-d'))) && trim($ferieObj->libelle) != "") {
					$cooltip = '<b>' . $ferieObj->libelle . '</b>';
					$ferie = '<div class="cellHolidays tooltipster" data-tooltip-content="#tooltip-'. $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante  . '">' . $smarty->get_config_vars('planning_ferie') . '</div><div class="tooltip-html"><div id="tooltip-'. $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante  . '">' . addslashes($cooltip) . '</div></div>' . CRLF;
				}
			} else {
				$ferie = false;
			}

			if (isset($joursOccupes[$tmpDate->format('Y-m-d')])) {
				// jours avec au moins une case remplie
				$html .= '<td ' . $styleLigne . ' id="td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '"';
				if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
					$html .= ' onClick="Reloader.stopRefresh();xajax_ajoutPeriode(\'' . $tmpDate->format('Y-m-d') . '\', \'' . $ligneId . '\', \'\', ' . $trancheHeureCourante . ');"';
					$droitAjoutPeriode=true;
				}else $droitAjoutPeriode=false;
				$html .= ' class="' . $classTD . (($tmpDate->format('Y-m-d') == date('Y-m-d')) ? ' today' : '') . '">' . CRLF;

				if($ferie !== false) {
					$html .= $ferie;
				}

				$niveauCourant = 0;
				// si il y a des periodes pour le jour courant, on boucle pour toutes les afficher
				foreach ($joursOccupes[$tmpDate->format('Y-m-d')] as $jour) {
					// on checke que la tache couvre la tranche horaire en cours
					if(!couvreTranche($jour['duree_details'], $trancheHeureCourante)) {
						continue;
					}

					if(in_array($jour['periode_id'], $ordreJourPrec) && $niveauCourant != array_search($jour['periode_id'], $ordreJourPrec)) {					
						$nbVides = (array_search($jour['periode_id'], $ordreJourPrec)-$niveauCourant);
						for($i=1; $i<=$nbVides; $i++) {
							$html .= '<div class="cellProject cellEmpty">&nbsp;</div>' . CRLF;
							$niveauCourant++;
						}
						$niveauCourant++;
						$ordreJourCourant[array_search($jour['periode_id'], $ordreJourPrec)] = $jour['periode_id'];
					} else {
						$ordreJourCourant[] = $jour['periode_id'];
						$niveauCourant++;
					}

					// mouseover sur la case
					$cooltip =  '<b>' . $smarty->get_config_vars('tab_projet') . '</b> : ' . $jour['projet_nom'] . ' (' . $jour['projet_id'] . ')<br/>'
						. '<b>' . $smarty->get_config_vars('tab_personne') . '</b> : ' . $jour['user_nom'] . ' (' . $jour['user_id'] . ')';
					if($jour['titre'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_titre') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['titre']));
					}
					if($jour['livrable'] == 'oui') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_livrable') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $smarty->get_config_vars('oui')));
					}
					if($jour['statut_tache'] != '') {
						$cooltip .= '<br/><b>' . $smarty->get_config_vars('winPeriode_statut') . '</b> : ' . (str_replace(array("\r\n", "\n"), array("<br>", "<br>"), $jour['status']));
					}
					$cooltip .= '<br/><b>' . $smarty->get_config_vars('tab_dateDebut') . '</b> : ' . $jour['date_debut'];
					if($jour['date_fin'] != '') {
						$cooltip .= '<br/><b>'  . $smarty->get_config_vars('tab_dateFin') .  '</b> : ' . $jour['date_fin'];
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
					// On masque la case 
					if (CONFIG_PLANNING_HIDE_WEEKEND_TASK == 0 && ($weekend==true || $ferie!=false)) {$classHidden=" hidden";}else $classHidden="";

					// la case avec le code du projet
					$html .= '<div id="c_' . $jour['periode_id'] . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '" class="cellProject  tooltipster '.$classHidden.'" data-tooltip-content="#tooltip-'.$jour['periode_id'].'" style="color:' . $couleurTexte . ';' . $opacity . ';background-color:#' . $couleurFond . ';';
					if($dimensionCase=='reduit') {
						if ($jour['duree'] > 0 && $jour['duree'] < 8) {
							if ($jour['duree_details'] == "AM")
							{
								$html .= 'border-radius: 10px 0px 0px 0px;margin:0px;';
							}else $html .= 'border-radius: 0px 0px 10px 0px;margin:0px;';
						}
					}else
					{
						$html .= 'min-height:30px;';
					}
					if($jour['statut_tache'] == 'fait' || $jour['statut_tache'] == 'abandon') {
						$html .= 'text-decoration:line-through;';
					}
					if(!$user->checkDroit('tasks_modify_all') && !($user->checkDroit('tasks_modify_own_project') && $jour['createur_id'] == $user->user_id) && !($user->checkDroit('tasks_modify_own_task') && ($jour['user_id'] == $user->user_id || $jour['createur_id'] == $user->user_id))) {
						if ($jour['lien'] != '')
						{
							//si pas les droits mais qu'un lien existe on affiche curseur lien
							$html .= 'cursor:url(\'assets/img/pictos/weblink.png\'), not-allowed';
						}else
						{
							//si pas les droits on affiche curseur interdit
							$html .= 'cursor:url(\'assets/img/pictos/interdit.png\'), not-allowed';
						}
					}

					$html .= '"';
					$html .=">";

					//modif ajout picto charge journaliere
					if($dimensionCase=='large'){
						$ratioCharge=round(decimalHours($totauxJourUsers[$jour['user_id']][$tmpDate->format('Ymd')])/$TotalMaxJour,1);
						$ratio=round($ratioCharge*10);
						if ($ratio > 10){
							$ratio=11;
						}
						if($ratio == 0) {
							if($jour['lien'] != '') {
								$html .= '<div class="jauge0"></div>';
								$html .= '<img src="assets/img/pictos/link.gif" class="picto-link" alt="" />';
							} else {
								$html .= '<div class="jauge0"></div>';
							}
						} else{
							if($jour['lien'] != '') {
								$html .= '<div class="jauge0">';
								$html .= '<div class="jauge' . $ratio .'">';
								if ($ratio==10){
									$html .= '100';
								}
								$html .= '</div></div>';
								$html .= '<img src="assets/img/pictos/link.gif" class="picto-link" alt="" />';
							} else {
								$html .= '<div class="jauge0">';
								$html .= '<div class="jauge' . $ratio .'">';
								if ($ratio==10){
									$html .= '100';
								}
								$html .= '</div></div>';
							}
						}
					}
					// Modif affichage simple ou complet

					if( $_SESSION['inverserUsersProjets']) {
						$L1=$jour['user_id'];
					}else{
						$L1=$jour['projet_id'];
					}
					$L2=$jour['titre'];
					$styleL2reduit = 'text-align:center';


					if (isset($jour['duree_details_heure_debut'])){
						$infoDebut=sqltime2usertime($jour['duree_details_heure_debut']);
					}else{
						$infoDebut='09:00';
					}

					if (isset($jour['duree'])){
						$affichageDuree=$jour['duree'];
					}else{
						$affichageDuree= CONFIG_DURATION_DAY . ':00';
					}
					if ($L2==''){
						$L2='...';
					}

					if($dimensionCase=='large'){
						$nom = '&nbsp;'.substr($L1, 0, CONFIG_PLANNING_CODE_WIDTH_LARGE);
						$nom.= '<div class="largeCode">'.substr($L2,0,CONFIG_PLANNING_CODE_WIDTH_LARGE).'</div>';
						$nom.= '<div class="startDateCell">'.$infoDebut.'h</div>';
						$nom.= '<div class="percentCell">'.round(decimalHours($affichageDuree),1).'h/'.round(decimalHours($totauxJourUsers[$jour['user_id']][$tmpDate->format('Ymd')]),1).'h</div>';
					}else{
						$nom = '<div class="miniCode">'.substr($L1, 0, CONFIG_PLANNING_CODE_WIDTH).'</div>';
					}


					if($jour['livrable'] == 'oui') {
						if($dimensionCase=='large'){
							$html .= $nom . '<img src="assets/img/pictos/milestone.png" class="picto-milestone" />';
						}else{
							$html .= $nom . '<img src="assets/img/pictos/milestone.png" class="picto-milestone" />';
						}
					} else {
						$html .= $nom;
					}
					$html .= '</div>';

					if($user->checkDroit('tasks_modify_all') || ($user->checkDroit('tasks_modify_own_project') && $jour['createur_id'] == $user->user_id) || ($user->checkDroit('tasks_modify_own_task') && ($jour['user_id'] == $user->user_id || $jour['createur_id'] == $user->user_id))) {
						// on rend draggable la case du projet
						$js .= 'Drag.init(document.getElementById("c_' . $jour['periode_id'] . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '"));' . CRLF;
					}
				}

				$ordreJourPrec = $ordreJourCourant;
				$ordreJourCourant = array();

				// espace vide pour permettre de cliquer en dessous d'une case assignée
				$html.= '<div class="cellEmpty"></div>';

				$html .= '</td>' . CRLF;

			} else {
				// jour vide
				$html .= '<td ' . $styleLigne . ' id="td_' . $ligneId . '_' . $tmpDate->format('Ymd') . '_' . $trancheHeureCourante . '"';
				if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
					//$html .= ' onClick="Reloader.stopRefresh();xajax_ajoutPeriode(\'' . $tmpDate->format('Y-m-d') . '\', \'' . $ligneId . '\', \'\', ' . $trancheHeureCourante . ');"';
					$droitAjoutPeriode=true;
				}else $droitAjoutPeriode=true;
				$html .= ' class="' . $classTD . (($tmpDate->format('Y-m-d') == date('Y-m-d')) ? ' today' : '') . '">';
				if($ferie !== false) {
					$html .= $ferie;
				} else {
					$html .= '&nbsp;';
				}
				$html .= '</td>' . CRLF;
			}
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
	$html .= '<tr><th id="tdTotal" '.$styleLigne.'>' . $smarty->get_config_vars('tab_totalJour') . '</th>' .CRLF;
	$tmpDate = clone $dateDebut;

	// on boucle sur la durée de l'affichage
	while ($tmpDate <= $dateFin) {
		// définit le style pour case semaine et WE
		if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED)) {
			$tmpDate->modify('+1 day');
			continue;
		} else {
			$classTD = 'week';
			$weekend = false;
		}
		if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {$classTD .= ' today';}
		if(isset($totalParJour[$tmpDate->format('Ymd')]) && $classTD == 'week')  {
			$i=1;
			$capitalCharge=$nbRealUsers*$TotalMaxJour;
			if($capitalCharge != 0){
				$ratioCharge=round(decimalHours($totalParJour[$tmpDate->format('Ymd')])/$capitalCharge,1);
			}else{
				$ratioCharge=0;
			}
			$ratio = round($ratioCharge*10);
			if ($ratio > 10){
				$ratio=11;
			}
			foreach ($tabTranchesHoraires as $trancheHeureCourante) {
				if ($i==count($tabTranchesHoraires)){
					if($dimensionCase=='large'){
						if($ratio == 0) {
							$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'><div class="sumLargeCell">' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '<div class="jaugeTD"><div class="jauge0"></div></div></div></td>' . CRLF;

						} else{
							$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'><div class="sumLargeCell">' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '</div><div class="jaugeTD"><div class="jauge0">';
							$html .= '<div class="jauge' . $ratio .'">';
							if ($ratio==10){
								$html .= '100';
							}
							$html .= '</div></div></div></td>' . CRLF;
						}
					}else{
						if($ratio == 0) {
							$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'>' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '</td>' . CRLF;

						} else{
							$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'>' . $totalParJour[$tmpDate->format('Ymd')];
							$html .= '</td>' . CRLF;
						}
					}
				} else {
					$html .= '<td class="' . $classTD . '" '.$styleLigne.'>&nbsp;</td>' . CRLF;
				}
				$i++;
			}
		} elseif ($classTD == 'week') {
			
			if(in_array($tmpDate->format('Y-m-d'), $joursFeries)) $classTD='weekend';
			$i=1;
			foreach ($tabTranchesHoraires as $trancheHeureCourante) {
				$html .= '<td class="' . $classTD . ' sumCell" '.$styleLigne.'>&nbsp;</td>' . CRLF;
				$i++;
			}
		}
		$tmpDate->modify('+1 day');
	}
	$html .= '</tr>';
}

$html .= '</table>' . CRLF;

$smarty->assign('htmlTableau', $html);

if($_SESSION['inverserUsersProjets']) {
	//////////////////////////
	// TABLEAU RECAP DES PROJETS
	//////////////////////////
	$html = '<table id="divProjectTable" class="table table-striped" ' . (isset($_COOKIE['divProjectTable']) && $_COOKIE['divProjectTable'] == 'none' ? 'style="display:none;"' : '') . '>' . CRLF;
	$html .= '	<tr>' . CRLF;
	$html .= '		<td class="w50"></td>' . CRLF;
	$html .= '		<td class="planningTabName">' . $smarty->get_config_vars('tab_projet2') . '</td>' . CRLF;
	$html .= '		<td class="planningTabTask">' . $smarty->get_config_vars('tab_periode2') . '</td>' . CRLF;
	$html .= '		<td class="w220 planningTabCharge">' . $smarty->get_config_vars('tab_charge') . '</td>' . CRLF;
	$html .= '	</tr>' . CRLF;
	// recuperation des projets couvrant la période, pour le filtre de projets
	$projets = new GCollection('Projet');
	$sql= "SELECT distinct pp.*, pg.nom AS groupe_nom
		FROM planning_projet pp
		INNER JOIN planning_periode pd ON pp.projet_id = pd.projet_id
		LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		// on filtre sur les projets de l'équipe de ce user
		$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
	}
	if ($user->checkDroit('tasks_view_only_own')) {
		// on filtre sur les projets de l'équipe de ce user
		$sql .= " INNER JOIN planning_periode AS pd2 ON pd2.user_id = " . val2sql($user->user_id);
	}
	$sql .= " WHERE (
			(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
			AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
			AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
		)";
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql .= " AND pp.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND pd.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if(count($_SESSION['filtreStatutTache']) > 0) {
		$sql.= " AND pd.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
	}
	if(count($_SESSION['filtreStatutProjet']) > 0) {
		$sql.= " AND pp.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
	}
	if(count($_SESSION['filtreGroupeLieu']) > 0) {
		$sql.= " AND pd.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
	}
	if(count($_SESSION['filtreGroupeRessource']) > 0) {
		$sql.= " AND pd.ressource_id IN ('" . implode("','", $_SESSION['filtreGroupeRessource']) . "')";
	}
	if($_SESSION['filtreTexte'] != "") {
		$sql.= " AND (pd.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR pd.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
	}
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND pp.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND pd.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}

	$sql .= "	GROUP BY pp.nom, pp.projet_id
				ORDER BY pp.groupe_id, pp.nom";
	$projets->db_loadSQL($sql);

	while($projet = $projets->fetch()) {
		$html .= '	<tr>' . CRLF;
		$couleurTexte = buttonFontColor('#' . $projet->couleur);
		$tooltipProjet = '<b>' . $smarty->get_config_vars('tab_projet') . '</b> : ' . $projet->nom . '(' . $projet->projet_id . ')<br />' .
			'<b>' . $smarty->get_config_vars('tab_statut') . '</b> :' . $projet->statut . '<br />' .
			'<b>' . $smarty->get_config_vars('tab_chargeEstimee') . '</b> : ' . $projet->charge . '<br />' .
			'<b>' . $smarty->get_config_vars('tab_livraison') . '</b> : ' . $projet->livraison .
			($projet->iteration != '' ? '<br /><b>' . $smarty->get_config_vars('tab_commentaire') . '</b> : ' . htmlentities($projet->iteration) : '');
		$html .= '<td onClick="javascript:Reloader.stopRefresh();xajax_modifProjet(\'' . $projet->projet_id . '\', \'planning\');undefined;" class="w25"><span data-tooltip-content="#tooltipprojet-'.$projet->projet_id.'" class="smallFontSize pastille-projet tooltipster" style="background-color:#' . $projet->couleur . ';color:'. $couleurTexte.'">' . $projet->projet_id . '</span></td>' . CRLF;
		$html .= "<div class='tooltip-html'><div id='tooltipprojet-".$projet->projet_id."'>$tooltipProjet</div></div>" . '</td>' . CRLF;
		$html .= '<td class="planningTabName"><b>' . $projet->nom . '</b>' . (!is_null($projet->iteration) ? '<br />' . $projet->iteration : '');
		$html .= '<td class="vbottom planningTabTask">';
		// on charge les périodes liées à ce projet
		$periodes = new GCollection('Periode');
		$sql = "SELECT pp.*,ps.nom as statut_nom, ps.couleur as statut_couleur, ps.pourcentage as statut_pourcentage
				FROM planning_periode AS pp ";
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = pp.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}
		$sql .= "	INNER JOIN planning_user ON planning_user.user_id = pp.user_id
					INNER JOIN planning_status ps ON ps.status_id = pp.statut_tache
					WHERE planning_user.visible_planning = 'oui'
					AND projet_id = '" . $projet->projet_id . "'
					AND (
						(pp.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND pp.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
						OR (pp.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND pp.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
						)";
		if ($user->checkDroit('tasks_view_only_own')) {
			$sql .= " AND planning_user.user_id = " . val2sql($user->user_id);
		}
		if(count($_SESSION['filtreUser']) > 0) {
			$sql.= " AND pp.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
		}
		if($_SESSION['filtreTexte'] != "") {
			$sql.= " AND (pp.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR pp.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
		}
		$sql .= " ORDER BY pp.date_debut";
		//echo $sql . '<br>';
		$periodes->db_loadSQL($sql);

		// si aucune période dispo pour ce projet (par exemple si user non visible) on masque le projet
		if($periodes->getCount() == 0) {
			$html .= '</td>' . CRLF;
			$html .= '<td>' . CRLF;
			$html .= '</td>' . CRLF;
			continue;
		}

		$totalJours = 0;
		$totalJoursPassed = 0;
		$totalHeures = "00:00";
		$totalHeuresPassed = "00:00";
		while ($periode = $periodes->fetch()) {
			$html .= '<div class="smallFontSize taskDivComment" onclick="javascript:xajax_modifPeriode(' . $periode->periode_id . ');undefined;">';
			if (CONFIG_PLANNING_AFFICHAGE_STATUS == 'pastille')
			{
				$html .= '<div class="pastille-statut tooltipster" style="float:left;margin-right:7px;background-color:#'.$periode->statut_couleur.'" title="'.$periode->statut_nom.'"></div>';
			}
			$html .= '<b>' . $periode->projet_id;
			if (!is_null($periode->titre)) {
				$html .= ' - ' . xss_protect($periode->titre) . '</b>';
			}
			$html .= '</b><br />';

			$html .= '<i class="fa fa-calendar" aria-hidden="true"></i> '.sqldate2userdate($periode->date_debut) . '</a> <i class="fa fa-caret-right" aria-hidden="true"></i> ';
			if (is_null($periode->date_fin)) {
				$html .= sqltime2usertime($periode->duree);
				$testHeures = $periode->getHeureDebutFin();
				if(!is_null($testHeures)) {
					$html .= ' (' . sqltime2usertime($testHeures['duree_details_heure_debut']) . ' => ' . sqltime2usertime($testHeures['duree_details_heure_fin']) . ')';
				}
				if($periode->duree_details == 'AM') {
					$html .= ' (' . $smarty->get_config_vars('tab_matin') . ')';
				}
				if($periode->duree_details == 'PM') {
					$html .= ' (' . $smarty->get_config_vars('tab_apresmidi') . ')';
				}
			} else {
				$html .= sqldate2userdate($periode->date_fin);
			}
			// Statut
			if (CONFIG_PLANNING_AFFICHAGE_STATUS == 'aucun')
			{
				$html .='<br />';
			}elseif (CONFIG_PLANNING_AFFICHAGE_STATUS == 'nom')
			{
				$html .= '<br />'.$periode->statut_nom.'<br />';
			}elseif (CONFIG_PLANNING_AFFICHAGE_STATUS == 'pourcentage')
			{
				$couleurTexte=buttonFontColor('#'.$periode->statut_couleur);
				$html .= '<div class="progress tooltipster" title="'.$periode->statut_nom.'"><div class="progress-bar" style="width: '.$periode->statut_pourcentage.'%;background-color:#'.$periode->statut_couleur.';color:'.$couleurTexte.'">'.$periode->statut_pourcentage.'%</div></div>';
			}
			if (!is_null($periode->notes)) {
				$html .= '<br>' .	xss_protect($periode->notes). '';
			}
			if (!is_null($periode->lien)) {
				$html .= '<br><a href="' . xss_protect($periode->lien) . '" target="_blank">' . $smarty->get_config_vars('tab_lien') . '</a>';
			}
			$html .= '</div>';

			$date1 = new DateTime();
			$date1->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

			// on additionne les jours de travail
			if(!is_null($periode->date_fin)) {
				$date2 = new DateTime();
				$date2->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
				while ($date1 <= $date2) {
					// on ne compte pas le jour si c'est WE ou jour férié
					if (in_array($date1->format('w'), $DAYS_INCLUDED) && !in_array($date1->format('Y-m-d'), $joursFeries)) {
						$totalJours +=1;
						if($date1 < $now) {
							$totalJoursPassed +=1;
						}
					}
					$date1->modify('+1 day');
				}
			} else {
				$totalHeures = ajouterDuree($totalHeures, $periode->duree);
				if($date1 < $now) {
					$totalHeuresPassed = ajouterDuree($totalHeuresPassed, $periode->duree);
				}

			}
		}

		$html .= '</td>' . CRLF;
		$html .= '<td>' . CRLF;
		if(!is_null($projet->charge)) {
			$html .= $smarty->get_config_vars('tab_chargeProjet') . ' : ' . $projet->charge . $smarty->get_config_vars('tab_j') . '<br />' . CRLF;
		}

		$nbJourTot=0;
		$TotalHeureExplode = explode (':',$totalHeures);
		$TotalHeureH=$TotalHeureExplode[0];
		$TotalHeureM=$TotalHeureExplode[1];
		if($totalHeures != '00:00') {
			$nbJourTot = round (($TotalHeureH+$TotalHeureM/60)/$TotalMaxJour,2);
		}
		$nbHeuresTotal = ($totalJours*$TotalMaxJourH+$TotalHeureH).'h';
		$nbheures = ($totalJours*$TotalMaxJourH+$TotalHeureH);
		$nbminutes = ($totalJours*$TotalMaxJourM+$TotalHeureM);
		if ($nbminutes >= 60)
		{
			$nbh=floor($nbminutes/60);
			$nbminutes = $nbminutes - $nbh*60;
			$nbheures = $nbheures + $nbh;
		}
		$nbHeuresTotal="$nbheures"."h";
		if ($nbminutes > 0) $nbHeuresTotal=$nbHeuresTotal.sprintf("%'.02d\n", $nbminutes);

		$html .= "<b>". $smarty->get_config_vars('tab_total') . ' : '  . ($totalJours+$nbJourTot) .$smarty->get_config_vars('tab_j'). " ( = ".$nbHeuresTotal.") </b>" . CRLF;

		$html .= '<br />' . CRLF;
		$nbJourTotPassed=0;
		$TotalHeurePassedH=0;
		$TotalHeurePassedM=0;
		if($totalHeuresPassed > 0) {
			$TotalHeurePassedExplode = explode (':',$totalHeuresPassed);
			$TotalHeurePassedH=$TotalHeurePassedExplode[0];
			$TotalHeurePassedM=$TotalHeurePassedExplode[1];
			$nbJourTotPassed = round (($TotalHeurePassedH+$TotalHeurePassedM/60)/$TotalMaxJour,2);
		}
		if($totalJoursPassed > 0 || $totalHeuresPassed > 0) {
			$nbHeuresTotalPassed = (($totalJoursPassed*$TotalMaxJour)+$TotalHeurePassedH).'h'.($TotalHeurePassedM!="00"?($TotalHeurePassedM):"");
			$html .= $smarty->get_config_vars('tab_passe') . ' : ' . ($totalJoursPassed+$nbJourTotPassed) .$smarty->get_config_vars('tab_j'). " ( = ".$nbHeuresTotalPassed." / ".round(($totalJoursPassed+$nbJourTotPassed)/($totalJours+$nbJourTot)*100,1) ."% ) " . CRLF;

		}

		$html .= '</td>' . CRLF;
		$html .= '	</tr>' . CRLF;
	}
	$html .= '</table>' . CRLF;

} else {

	//////////////////////////
	// TABLEAU RECAP DES USERS
	//////////////////////////
	$html = '<table id="divProjectTable" class="table table-striped" ' . (isset($_COOKIE['divProjectTable']) && $_COOKIE['divProjectTable'] == 'none' ? 'style="display:none;"' : '') . '>' . CRLF;
	$html .= '	<tr>' . CRLF;
	$html .= '		<td class="w50"></td>' . CRLF;
	$html .= '		<td class="planningTabName">' . $smarty->get_config_vars('tab_personne') . '</td>' . CRLF;
	$html .= '		<td class="planningTabTask">' . $smarty->get_config_vars('tab_periode2') . '</td>' . CRLF;
	$html .= '		<td class="w220 planningTabCharge">' . $smarty->get_config_vars('tab_charge') . '</td>' . CRLF;
	$html .= '	</tr>' . CRLF;

	// recuperation des personnes
	$users = new GCollection('User');
	$sql= "SELECT *
			FROM planning_user ";
	if($user->checkDroit('tasks_view_specific_users')) {
		$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_user.user_id AND rou.owner_id = " . val2sql($user->user_id);
	}
	$sql .= "	WHERE visible_planning = 'oui' ";
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
		$sql .= " AND planning_user.user_groupe_id = " . $user->user_groupe_id;
	}
	if ($user->checkDroit('tasks_view_only_own')) {
		$sql .= " AND planning_user.user_id = " . val2sql($user->user_id);
	}
	if(strpos($_SESSION['triPlanning'], 'nom') !== FALSE) {
		$sql .= "	ORDER BY nom ASC";
	} else {
		$sql .= "	ORDER BY nom DESC";
	}
	$users->db_loadSQL($sql);

	while($userTemp = $users->fetch()) {
		$html .= '	<tr>' . CRLF;
		$couleurTexte = buttonFontColor('#' . $userTemp->couleur);
		$html .= '<td onClick="javascript:Reloader.stopRefresh();xajax_modifUser(\'' . $userTemp->user_id . '\');undefined;" class="w25">
		<span class="smallFontSize pastille-user" style="background-color:#' . $userTemp->couleur . ';color:'. $couleurTexte.'">' . $userTemp->user_id . '</span></td>' . CRLF;
		$html .= '<td class="planningTabName"><b>' . $userTemp->nom . '</b>';

		$html .= '<td class="vbottom planningTabTask">';
		// on charge les périodes liées aux projets
		$periodes = new GCollection('Periode');
		$sql = "SELECT pp.*,ps.nom as statut_nom, ps.couleur as statut_couleur, ps.pourcentage as statut_pourcentage
				FROM planning_periode AS pp
				INNER JOIN planning_user ON planning_user.user_id = pp.user_id
				INNER JOIN planning_status ps ON ps.status_id = pp.statut_tache
				WHERE planning_user.visible_planning = 'oui'
				AND pp.user_id = '" . $userTemp->user_id . "'
				AND (
					(pp.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND pp.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
					OR (pp.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND pp.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
					)";
		if(count($_SESSION['filtreUser']) > 0) {
			$sql.= " AND pp.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
		}
		if($_SESSION['filtreTexte'] != "") {
			$sql.= " AND (pp.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR pp.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
		}
		if(count($_SESSION['filtreGroupeProjet']) > 0) {
			$sql .= " AND pp.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
		}
		if($user->checkDroit('tasks_view_own_projects')) {
			$sql .= " AND pp.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
		}
		if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
			$sql .= " AND pp.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
		}
		if ($user->checkDroit('tasks_view_only_own')) {
			$sql .= " AND planning_user.user_id = " . val2sql($user->user_id);
		}

		$sql .= " ORDER BY pp.date_debut";
		//echo $sql . '<br>';
		$periodes->db_loadSQL($sql);

		// si aucune période dispo pour ce projet (par exemple si user non visible) on masque le projet
		if($periodes->getCount() == 0) {
			$html .= '</td>' . CRLF;
			$html .= '<td>' . CRLF;
			$html .= '</td>' . CRLF;
			continue;
		}

		$totalJours = 0;
		$totalJoursPassed = 0;
		$totalHeures = "00:00";
		$totalHeuresPassed = "00:00";
		while ($periode = $periodes->fetch()) {
			$html .= '<div class="smallFontSize taskDivComment" onclick="javascript:xajax_modifPeriode(' . $periode->periode_id . ');undefined;">';
			if (CONFIG_PLANNING_AFFICHAGE_STATUS == 'pastille')
			{
				$html .= '<div class="pastille-statut tooltipster" style="float:left;margin-right:7px;background-color:#'.$periode->statut_couleur.'" title="'.$periode->statut_nom.'"></div>';
			}
			$html .= '<b>' . $periode->projet_id;
			if (!is_null($periode->titre)) {
				$html .= ' - ' . xss_protect($periode->titre);
			}
			$html .= '</b><br />';
			$html .=  '<i class="fa fa-calendar" aria-hidden="true"></i> '.sqldate2userdate($periode->date_debut) . ' <i class="fa fa-caret-right" aria-hidden="true"></i> ';
			if (is_null($periode->date_fin)) {
				$html .= sqltime2usertime($periode->duree) . ' (' . $periode->user_id . ')';
			} else {
				$html .= sqldate2userdate($periode->date_fin) . ' (' . $periode->user_id . ')';
			}
			// Statut
			if (CONFIG_PLANNING_AFFICHAGE_STATUS == 'aucun')
			{
				$html .='<br />';
			}elseif (CONFIG_PLANNING_AFFICHAGE_STATUS == 'nom')
			{
				$html .= '<br />'.$periode->statut_nom.'<br />';
			}elseif (CONFIG_PLANNING_AFFICHAGE_STATUS == 'pourcentage')
			{
				$couleurTexte=buttonFontColor('#'.$periode->statut_couleur);
				$html .= '<div class="progress tooltipster" title="'.$periode->statut_nom.'"><div class="progress-bar" style="width: '.$periode->statut_pourcentage.'%;background-color:#'.$periode->statut_couleur.';color:'.$couleurTexte.'">'.$periode->statut_pourcentage.'%</div></div>';
			}
			if (!is_null($periode->notes)) {
				$html .= '<br>' .	xss_protect($periode->notes). '';
			}
			if (!is_null($periode->lien)) {
				$html .= '<br><a href="' . xss_protect($periode->lien) . '" target="_blank">' . $smarty->get_config_vars('tab_lien') . '</a>';
			}
			$html .= '</div>';


			$date1 = new DateTime();
			$date1->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));

			// on additionne les jours de travail
			if(!is_null($periode->date_fin)) {
				$date2 = new DateTime();
				$date2->setDate(substr($periode->date_fin,0,4), substr($periode->date_fin,5,2), substr($periode->date_fin,8,2));
				while ($date1 <= $date2) {
					// on ne compte pas le jour si c'est WE ou jour férié
					if (in_array($date1->format('w'), $DAYS_INCLUDED) && !in_array($date1->format('Y-m-d'), $joursFeries)) {
						$totalJours +=1;
						if($date1 < $now) {
							$totalJoursPassed +=1;
						}
					}
					$date1->modify('+1 day');
				}
			} else {
				$totalHeures = ajouterDuree($totalHeures, $periode->duree);
				if($date1 < $now) {
					$totalHeuresPassed = ajouterDuree($totalHeuresPassed, $periode->duree);
				}

			}
		}

		$html .= '</td>' . CRLF;
		$html .= '<td class="planningTabCharge">' . CRLF;

		$nbJourTot=0;
		$TotalHeureExplode = explode (':',$totalHeures);
		$TotalHeureH=$TotalHeureExplode[0];
		$TotalHeureM=$TotalHeureExplode[1];
		if($totalHeures != '00:00') {
			$nbJourTot = round (($TotalHeureH+$TotalHeureM/60)/$TotalMaxJour,2);
		}
		$nbHeuresTotal = ($totalJours*$TotalMaxJourH+$TotalHeureH).'h';
		$nbheures = ($totalJours*$TotalMaxJourH+$TotalHeureH);
		$nbminutes = ($totalJours*$TotalMaxJourM+$TotalHeureM);
		if ($nbminutes >= 60)
		{
			$nbh=floor($nbminutes/60);
			$nbminutes = $nbminutes - $nbh*60;
			$nbheures = $nbheures + $nbh;
		}
		$nbHeuresTotal="$nbheures"."h";
		if ($nbminutes > 0) $nbHeuresTotal=$nbHeuresTotal.sprintf("%'.02d\n", $nbminutes);
		$html .= "<b>". $smarty->get_config_vars('tab_total') . ' : '  . ($totalJours+$nbJourTot) .$smarty->get_config_vars('tab_j'). " ( = ".$nbHeuresTotal.") </b>" . CRLF;

		$html .= '<br />' . CRLF;
		$nbJourTotPassed=0;
		$TotalHeurePassedH=0;
		$TotalHeurePassedM=0;
		if($totalHeuresPassed > 0) {
			$TotalHeurePassedExplode = explode (':',$totalHeuresPassed);
			$TotalHeurePassedH=$TotalHeurePassedExplode[0];
			$TotalHeurePassedM=$TotalHeurePassedExplode[1];
			$nbJourTotPassed = round (($TotalHeurePassedH+$TotalHeurePassedM/60)/$TotalMaxJour,2);
		}
		if($totalJoursPassed > 0 || $totalHeuresPassed > 0) {
			$nbHeuresTotalPassed = (($totalJoursPassed*$TotalMaxJour)+$TotalHeurePassedH).'h'.($TotalHeurePassedM!="00"?($TotalHeurePassedM):"");
			$html .= $smarty->get_config_vars('tab_passe') . ' : ' . ($totalJoursPassed+$nbJourTotPassed) .$smarty->get_config_vars('tab_j'). " ( = ".$nbHeuresTotalPassed." / ".round(($totalJoursPassed+$nbJourTotPassed)/($totalJours+$nbJourTot)*100,1) ."% ) " . CRLF;
		}


		$html .= '</td>' . CRLF;
		$html .= '	</tr>' . CRLF;
	}

	$html .= '</table>' . CRLF;

}
// Affichage des tooltip
$html .= "<div class='tooltip-html'>";
foreach ($cooltip_liste as $cle => $valeur)
{
	$html .= "<div id='tooltip-$cle'>$valeur</div>";
}
$html .= "</div>";
// anchor for show/hide, move the page to be the entire project table
$html .= '<a id="anchorProjectTable"></a>' . CRLF;

// pour savoir combien de groupes à afficher dans colonne de gauche
$smarty->assign('nbGroupes', ($idGroupeCourant+1));

$smarty->assign('droitAjoutPeriode',$droitAjoutPeriode);

$smarty->assign('htmlRecap', $html);

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->assign('js', $js);

$smarty->display('www_planning.tpl');
