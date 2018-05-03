<?php

require 'base.inc';
require BASE . '/../config.inc';

require (BASE . '/../includes/xajax_common.inc');

function ajoutProjet($origine=null) {
	global $lang;
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || (!$user->checkDroit('projects_manage_all') && !$user->checkDroit('projects_manage_own'))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());

	$projet = new Projet();
	// si droit limit�, on ne permet pas le choix du createur
	if($user->checkDroit('projects_manage_own')) {
		$projet->createur_id = $user->user_id;
	}
	$smarty->assign('projet', $projet->getSmartyData());

	// recupere les infos du owner/createur du projet
	$createur = new User();
	if($projet->createur_id != '') {
		$createur->db_load(array('user_id', '=', $projet->createur_id));
	}
	$smarty->assign('createur', $createur->getSmartyData());
	$smarty->assign('origine', $origine);

	$usersOwner = new GCollection('User');
	$usersOwner->db_load(array('user_id', '<>', 'publicspl'), array('nom' => 'ASC'));
	$smarty->assign('usersOwner', $usersOwner->getSmartyData());

	// modif gestion des groupes fonction statut
	$groupes = new GCollection('Groupe');
	$groupesSQL = "SELECT distinct pg.groupe_id, pg.nom, pg.ordre
			FROM planning_groupe pg
			LEFT JOIN planning_projet pp ON pg.groupe_id = pp.groupe_id
			WHERE (
			(pp.statut='a_faire')
			OR
			(pp.statut='en_cours')
			OR
			(pp.statut IS NULL)
			)";
	$groupesSQL .= " GROUP BY pg.groupe_id, pg.nom, pg.ordre
					ORDER BY pg.nom ";
	$groupes->db_loadSQL($groupesSQL);
	$smarty->assign('groupes', $groupes->getSmartyData());

	// liste des status
	$status = new GCollection('Status');
	$sql = "SELECT status_id,nom from planning_status where affichage in ('p','tp') order by priorite asc";
	$status->db_loadSQL($sql);
	$smarty->assign('listeStatus', $status->getSmartyData());

	$objResponse->addScript('jQuery("#myModal").modal("hide")');
	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('ajax_titreCreationProjet')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('projet_form.tpl')) . '")');
	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	$objResponse->addScript('jQuery("#myModal").modal()');
  // On n'affiche le color picker uniquement si il n'y a aucune couleur personnalis�e
	if (CONFIG_PROJECT_COLORS_POSSIBLE == '' && !$_SESSION['isMobileOrTablet']) {
		$objResponse->addScript("myPicker = new jscolor.color(document.getElementById('couleur'), {});myPicker.fromString('" . $projet->couleur . "');");
    }
	if (!$_SESSION['isMobileOrTablet'])
	{
		$objResponse->addScript('jQuery("#livraison").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
	}
	$objResponse->addScript('document.getElementById("projet_id").focus();');
	echo $objResponse->getHTML;
	return $objResponse->getXML();
}

function modifProjet($projet_id, $origine = '') {
	global $lang;
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || (!$user->checkDroit('projects_manage_all') && !$user->checkDroit('projects_manage_own'))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}

	$projet = new Projet();
	$projet->db_load(array('projet_id', '=', $projet_id));
	$smarty->assign('projet', $projet->getSmartyData());

	$usersOwner = new GCollection('User');
	$usersOwner->db_load(array('user_id', '<>', 'publicspl'), array('nom' => 'ASC'));
	$smarty->assign('usersOwner', $usersOwner->getSmartyData());

	// recupere les infos du owner/createur du projet
	$createur = new User();
	if($projet->createur_id != '') {
		$createur->db_load(array('user_id', '=', $projet->createur_id));
	}
	$smarty->assign('createur', $createur->getSmartyData());

	if($user->checkDroit('tasks_modify_own_project') && $projet->createur_id != $user->user_id) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());

	$smarty->assign('origine', $origine);

	$groupes = new GCollection('Groupe');
	$groupes->db_load(array(), array('ordre' => 'ASC', 'nom' => 'ASC'));
	$smarty->assign('groupes', $groupes->getSmartyData());

	// liste des status
	$status = new GCollection('Status');
	$sql = "SELECT status_id,nom from planning_status where affichage in ('p','tp') order by priorite asc";
	$status->db_loadSQL($sql);
	$smarty->assign('listeStatus', $status->getSmartyData());

	$objResponse->addScript('jQuery("#myModal").modal("hide")');
	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('ajax_titreCreationProjet')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('projet_form.tpl')) . '")');

	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	$objResponse->addScript('jQuery("#myModal").modal()');
    // On n'affiche le color picker uniquement si il n'y a aucune couleurs personnalis�es
	if (CONFIG_PROJECT_COLORS_POSSIBLE == '' && !$_SESSION['isMobileOrTablet']) {
		$objResponse->addScript("myPicker = new jscolor.color(document.getElementById('couleur'), {});myPicker.fromString('" . $projet->couleur . "')");
	}
	if($projet->couleur != ''){
		$_SESSION['couleurExProjet']=$projet->couleur;
	}
	if (!$_SESSION['isMobileOrTablet']) {
		$objResponse->addScript('jQuery("#livraison").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
	}
	$objResponse->addScript('document.getElementById("groupe_id").focus();');
	$objResponse->addScript('document.getElementById("createur_id").focus();');
	return $objResponse->getXML();
}


function submitFormProjet($projet_id, $origine, $new_projet_id, $nom, $groupe_id, $statut, $charge, $livraison, $lien, $couleur, $createur_id, $iteration) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();
	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || (!$user->checkDroit('projects_manage_all') && !$user->checkDroit('projects_manage_own'))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(trim($new_projet_id) == '' || !preg_match('<^[A-Za-z0-9]*$>', $new_projet_id)) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_saisirIDProjet')));
		return $objResponse;
	}
	if(trim($nom) == '') {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_saisirNomProjet')));
		return $objResponse;
	}
	$couleur=str_replace('#','',$couleur);
	if(strlen($couleur) != 6) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_saisirCouleur')));
		return $objResponse;
	}
	if(trim($charge) != '' && ($charge <= 0 || $charge > 999)) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_saisirCharge')));
		return $objResponse;
	}

	// French date forcing
	if(trim($livraison) != '')
	{
		$livraison=forceUserDateFormat($livraison);
	}

	$projetTest = new Projet();
	$sql = 'SELECT * FROM planning_projet WHERE projet_id = ' . val2sql($new_projet_id);
	if($projet_id != '') {
		$sql .= ' AND projet_id <> ' . val2sql($projet_id);
	}
	if($projetTest->db_loadSQL($sql)) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('projet_existant')));
		return $objResponse;
	}

	// modification de la cl� (projet_id) => update manuel
	if($projet_id != '' && $new_projet_id != $projet_id) {
		$sql = 'UPDATE planning_projet SET projet_id = ' . val2sql($new_projet_id) . ' WHERE projet_id = ' . val2sql($projet_id);
		db_query($sql);
	}

	$projet = new Projet();
	if($projet_id != '') {
		$projet->db_load(array('projet_id', '=', $new_projet_id));
	} else {
		$projet->projet_id = $new_projet_id;
	}
	$projet->nom = $nom;
	$projet->groupe_id = ($groupe_id != '' ? $groupe_id : NULL);
	$projet->statut = $statut;
	$projet->charge = ($charge != '' ? $charge : NULL);
	$projet->livraison = ($livraison != '' ? $livraison : NULL);
	$projet->lien = ($lien != '' ? $lien : NULL);
	$projet->couleur = ($couleur != '' ? $couleur : NULL);
	$projet->createur_id = ($createur_id != '' ? $createur_id : NULL);
	$projet->iteration = ($iteration != '' ? $iteration : NULL);

	if($user->checkDroit('projects_manage_all')) {
		// rien � faire sur le createur_id, pass? dans le POST
	} elseif($user->checkDroit('projects_manage_own')) {
		// si c'est un planner, on lui assigne le projet � la creation, et on checke qu'il n'a pas tent? de le changer en modif
		if($projet->isSaved() && $projet->createur_id != $user->user_id) {
			$objResponse->addAlert(addslashes($smarty->get_config_vars('droitsInsuffisants')));
			return $objResponse;
		} else {
			$projet->createur_id = $user->user_id;
		}
	}

	if(!is_null($projet->livraison)) {
		$projet->livraison = userdate2sqldate($projet->livraison);
	}

	if(strpos($projet->couleur, '#') !== FALSE) {
		$projet->couleur = substr($projet->couleur, 1, 6);
	}

	if (is_array($projet->check())) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreurChamps') . '<br>' . print_r($projet->check(), true)));
		return $objResponse;
	}
	if($projet->couleur != ''){
		$_SESSION['couleurExProjet']=$projet->couleur;
	}

	$projet->db_save();

	$_SESSION['message'] = 'changeOK';
	if($origine != '') {
		if($origine == 'projets') {
			$objResponse->addRedirect('projets.php');
			return $objResponse;
		}
	}

	$objResponse->addRedirect('planning.php');
	return $objResponse;
}


function supprimerProjet($projet_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || (!$user->checkDroit('projects_manage_all') && !$user->checkDroit('projects_manage_own'))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$projet = new projet();

	if (!$projet->db_load(array('projet_id', '=', $projet_id))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(!$user->checkDroit('projects_manage_all') && $projet->createur_id != $user->user_id) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$projet->db_delete();

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('projets.php');
	return $objResponse;
}

function ajoutPeriode($dateDebut = '', $ligne_id = '', $periode_id = '', $heureDebut = '') {
	global $lang;
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || $user->checkDroit('tasks_readonly')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());
	if (isset($_SESSION['public']) && ($_SESSION['public'] == 1) && (CONFIG_SOPLANNING_OPTION_ACCES == 2))
	{
	 $userdata['droits']='["tasks_modify_all","tasks_view_all_projects"]';
	 $user->droits='["tasks_modify_all","tasks_view_all_projects"]';
	 $user->decoderDroits();
	}

	// liste de tous les projets
	$listeProjets = new GCollection('Projet');
	if($user->checkDroit('tasks_modify_own_project')) {
		$sql = "SELECT ppr.*, pg.nom AS nom_groupe
				FROM planning_projet AS ppr
				LEFT JOIN planning_groupe pg ON pg.groupe_id = ppr.groupe_id
				WHERE  0 = 0
				AND (
						(ppr.createur_id =  " . val2sql($user->user_id) . " AND ppr.statut IN ('a_faire','en_cours'))
						OR ppr.projet_id = " . val2sql($projet->projet_id) . "
					)
				ORDER BY pg.nom, ppr.nom ASC
				";
	} elseif ($user->checkDroit('tasks_modify_own_task')) {
		$sql = "SELECT DISTINCT ppr.*, pg.nom AS nom_groupe
				FROM planning_projet AS ppr
				LEFT JOIN planning_groupe pg ON pg.groupe_id = ppr.groupe_id
				LEFT JOIN planning_periode AS ppe ON ppr.projet_id = ppe.projet_id AND ppe.user_id = '" . $user->user_id . "'
				WHERE (statut IN ('a_faire','en_cours')
						AND (ppe.periode_id IS NOT NULL OR ppr.createur_id = " . val2sql($user->user_id) . "))
				OR ppr.projet_id = " . val2sql($projet->projet_id) . "
				ORDER BY pg.nom, nom ASC
				";
	} else {
		$sql = "SELECT ppr.*, pg.nom AS nom_groupe
				FROM planning_projet AS ppr
				LEFT JOIN planning_groupe pg ON pg.groupe_id = ppr.groupe_id
				WHERE  0 = 0
				AND (
						(ppr.statut IN ('a_faire','en_cours') )
						OR ppr.projet_id = " . val2sql($projet->projet_id) . "
					)
				ORDER BY pg.nom, ppr.nom ASC
				";
	}
	$listeProjets->db_loadSQL($sql);
	$smarty->assign('listeProjets', $listeProjets->getSmartyData());

	// liste des groupes de projet
	$groupeProjets = new GCollection('Groupe');
	$sql = "SELECT groupe_id,nom from planning_groupe";
	$groupeProjets->db_loadSQL($sql);
	$smarty->assign('groupeProjets', $groupeProjets->getSmartyData());

	// liste des status
	$status = new GCollection('Status');
	$sql = "SELECT status_id,nom from planning_status order by priorite asc";
	$status->db_loadSQL($sql);
	$smarty->assign('listeStatus', $status->getSmartyData());

	// liste de tous les utilisateurs
	$listeUsers = new GCollection('User');
	if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
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

		$listeUsers->db_loadSQL($sql);

		//$listeUsers->db_load(array('visible_planning', '=', 'oui'), array('nom' => 'ASC'));
	}
	$smarty->assign('listeUsers', $listeUsers->getSmartyData());

	// liste de tous les lieux
	if (CONFIG_SOPLANNING_OPTION_LIEUX == 1)
	{
		$listeLieux = new GCollection('Lieu');
		$listeLieux->db_load(array(), array('nom' => 'ASC'));
		$smarty->assign('listeLieux', $listeLieux->getSmartyData());
	}

	// liste de toutes les ressources
	if (CONFIG_SOPLANNING_OPTION_RESSOURCES == 1)
	{
		$listeRessources = new GCollection('Ressource');
		$listeRessources->db_load(array(), array('nom' => 'ASC'));
		$smarty->assign('listeRessources', $listeRessources->getSmartyData());
	}

	// si il y a un user ou projet pr�-choisi, on le s�lectionne
	if($ligne_id != '') {
		$userTest = new User();
		if($userTest->db_load(array('user_id', '=', $ligne_id))) {
			$smarty->assign('user_id_choisi', $ligne_id);
		} else {
			$smarty->assign('projet_id_choisi', $ligne_id);
		}
	}

	$periode = new Periode();

	if($dateDebut != '') {
		$periode->date_debut = $dateDebut;
	} else {
		$periode->date_debut = date('Y-m-d');
	}
	if ($heureDebut != '') {
		$periode->duree = '01:00:00';
		if ($heureDebut == 23) {
			$periode->duree_details = '23:00:00;23:59:00';
		} else {
			$periode->duree_details = usertime2sqltime($heureDebut) . ';' . usertime2sqltime($heureDebut+1);
		}
	}

	// si periode_id present, veut dire qu'on duplique une p�riode, donc charge les donn?es
	if($periode_id != '') {
		$periodeCopie = new Periode();
		if($periodeCopie->db_load(array('periode_id', '=', $periode_id))) {
			$data = $periodeCopie->getData();
			$data['periode_id'] = 0;
			$data['saved'] = 0;
			$periode->setData($data);
		}
	}

	if(CONFIG_DEFAULT_PERIOD_LINK != '') {
		$periode->lien = CONFIG_DEFAULT_PERIOD_LINK;
	}
	$smarty->assign('periode', $periode->getSmartyData());

	$objResponse->addScript('jQuery("#myBigModal").modal("hide")');
	$objResponse->addScript('jQuery("#myBigModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('ajax_titreGestionPeriode')) . '")');
	$objResponse->addScript('jQuery("#myBigModal .modal-body").html("' . xajaxFormat($smarty->getHtml('periode_form.tpl')) . '")');

	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	// refresh title box when element is selected
	$objResponse->addScript('jQuery("#projet_id").on("select2-selecting", function(e){xajax_autocompleteTitreTache(e.val);});');
	$objResponse->addScript('jQuery("#myBigModal").modal()');
	if (!$_SESSION['isMobileOrTablet'])
	{
		$objResponse->addScript('jQuery("#date_debut").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#date_fin").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#dateFinRepetitionJour").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#dateFinRepetitionSemaine").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#dateFinRepetitionMois").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
	}
	$objResponse->addScript('document.getElementById("projet_id").focus();');

	return $objResponse->getXML();
}


function modifPeriode($periode_id) {
	global $lang;
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$periode = new Periode();
	$periode->db_load(array('periode_id', '=', $periode_id));
	$smarty->assign('periode', $periode->getSmartyData());

	$projet = new Projet();
	$projet->db_load(array('projet_id', '=', $periode->projet_id));
	$smarty->assign('projet', $projet->getSmartyData());

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());
	if (isset($_SESSION['public']) && ($_SESSION['public'] == 1) && (CONFIG_SOPLANNING_OPTION_ACCES == 2))
	{
	$userdata['droits']='["tasks_modify_all","tasks_view_all_projects"]';
	$user->droits='["tasks_modify_all","tasks_view_all_projects"]';
	$user->decoderDroits();
	}

	// liste de tous les projets
	$listeProjets = new GCollection('Projet');
	if($user->checkDroit('tasks_modify_own_project')) {
		$sql = "SELECT ppr.*, pg.nom AS nom_groupe
				FROM planning_projet AS ppr
				LEFT JOIN planning_groupe pg ON pg.groupe_id = ppr.groupe_id
				WHERE  0 = 0
				AND (
						(ppr.createur_id =  " . val2sql($user->user_id) . " AND ppr.statut IN ('a_faire','en_cours'))
						OR ppr.projet_id = " . val2sql($projet->projet_id) . "
					)
				ORDER BY pg.nom, ppr.nom ASC
				";
	} elseif ($user->checkDroit('tasks_modify_own_task')) {
		$sql = "SELECT DISTINCT ppr.*, pg.nom AS nom_groupe
				FROM planning_projet AS ppr
				LEFT JOIN planning_groupe pg ON pg.groupe_id = ppr.groupe_id
				LEFT JOIN planning_periode AS ppe ON ppr.projet_id = ppe.projet_id AND ppe.user_id = '" . $user->user_id . "'
				WHERE (statut IN ('a_faire','en_cours')
						AND (ppe.periode_id IS NOT NULL OR ppr.createur_id = " . val2sql($user->user_id) . "))
				OR ppr.projet_id = " . val2sql($projet->projet_id) . "
				ORDER BY pg.nom, nom ASC
				";
	} else {
		$sql = "SELECT ppr.*, pg.nom AS nom_groupe
				FROM planning_projet AS ppr
				LEFT JOIN planning_groupe pg ON pg.groupe_id = ppr.groupe_id
				WHERE  0 = 0
				AND (
						(ppr.statut IN ('a_faire','en_cours') )
						OR ppr.projet_id = " . val2sql($projet->projet_id) . "
					)
				ORDER BY pg.nom, ppr.nom ASC
				";
	}
	$listeProjets->db_loadSQL($sql);
	$smarty->assign('listeProjets', $listeProjets->getSmartyData());

	// liste des groupes de projet
	$groupeProjets = new GCollection('Groupe');
	$sql = "SELECT groupe_id,nom from planning_groupe";
	$groupeProjets->db_loadSQL($sql);
	$smarty->assign('groupeProjets', $groupeProjets->getSmartyData());

	// liste des status
	$status = new GCollection('Status');
	$sql = "SELECT status_id,nom from planning_status order by priorite asc";
	$status->db_loadSQL($sql);
	$smarty->assign('listeStatus', $status->getSmartyData());

	// liste de tous les utilisateurs
	$listeUsers = new GCollection('User');
	if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) {
		$sql = "SELECT pu.*, pug.nom AS groupe_nom
		FROM planning_user pu ";
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = pu.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}
		$sql .= "	LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id
					WHERE visible_planning = 'oui' ";
		if ($user->checkDroit('tasks_view_only_own')) {
			$sql .= " AND pu.user_id = " . val2sql($user->user_id);
		}
		$sql .=	" ORDER BY groupe_nom, pu.nom";
		$listeUsers->db_loadSQL($sql);
		//$listeUsers->db_load(array('visible_planning', '=', 'oui'), array('nom' => 'ASC'));
	}
	$smarty->assign('listeUsers', $listeUsers->getSmartyData());

	// liste de tous les lieux
	if (CONFIG_SOPLANNING_OPTION_LIEUX == 1)
	{
		$listeLieux = new GCollection('Lieu');
		$listeLieux->db_load(array(), array('nom' => 'ASC'));
		$smarty->assign('listeLieux', $listeLieux->getSmartyData());
	}

	// liste de toutes les ressources
	if (CONFIG_SOPLANNING_OPTION_RESSOURCES == 1)
	{
		$listeRessources = new GCollection('Ressource');
		$listeRessources->db_load(array(), array('nom' => 'ASC'));
		$smarty->assign('listeRessources', $listeRessources->getSmartyData());
	}

	// comptage du nombre de jours de la p�riode
	$nbJours = 0;
	if(!is_null($periode->date_fin)) {
		$nbJours = getNbJours($periode->date_debut, $periode->date_fin);
	}
	$smarty->assign('nbJours', $nbJours);

	if($periode->estFilleOuParente()) {
		$smarty->assign('estFilleOuParente', '1');
		$smarty->assign('prochaineOccurence', $periode->prochaineOccurence());
	}

	$objResponse->addScript('jQuery("#myBigModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('ajax_titreGestionPeriode')) . '")');
	$objResponse->addScript('jQuery("#myBigModal .modal-body").html("' . xajaxFormat($smarty->getHtml('periode_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myBigModal").modal()');

	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	// init select and title box typehead
	$objResponse->addScript('var projet = jQuery("#projet_id").val();xajax_autocompleteTitreTache(projet);');
	// refresh title box when element is selected
	$objResponse->addScript('jQuery("#projet_id").on("select2-selecting", function(e){xajax_autocompleteTitreTache(e.val);});');

	// hack pour textarea (sauts de ligne, et auto-ajustement)
	$objResponse->addScript('jQuery("#notes").val(jQuery("#notes").val().replace(/�/g, "\n"));');
	if (!$_SESSION['isMobileOrTablet'])
	{
		$objResponse->addScript('jQuery("#date_debut").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#date_fin").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#dateFinRepetitionJour").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#dateFinRepetitionSemaine").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
		$objResponse->addScript('jQuery("#dateFinRepetitionMois").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
	}
	$objResponse->addScript('jQuery("#lien").value = ' . $periode->lien);
	$objResponse->addScript('jQuery("#btnGotoLien").tooltip();');
	$objResponse->addScript('document.getElementById("projet_id").focus();');

	return $objResponse->getXML();
}


// check si l'identifiant de projet est disponible
function checkProjetId($newProjet_id, $currentProjet_id) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	if((preg_match("/^[a-zA-Z0-9]+$/", $newProjet_id) == 0) || strlen($newProjet_id) > 10) {
		$objResponse->addAssign('divStatutCheckProjetId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDProjetNonValide') . '</b></font>');
		return $objResponse->getXML();
	}

	$projetTest = new Projet();
	$sql = 'SELECT * FROM planning_projet WHERE projet_id = ' . val2sql($newProjet_id);
	if($currentProjet_id != '') {
		$sql .= ' AND projet_id <> ' . val2sql($currentProjet_id);
	}

	if($projetTest->db_loadSQL($sql)) {
		$objResponse->addAssign('divStatutCheckProjetId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDDejaPris') . '</b></font>');
	} else {
		$objResponse->addAssign('divStatutCheckProjetId', 'innerHTML', '<img src="assets/img/pictos/ok.gif" width="12" height="12" border="0">');
	}

	return $objResponse->getXML();
}


// check si l'identifiant de ressource est disponible
function checkRessourceId($newRessource_id, $currentRessource_id) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	if((preg_match("/^[a-zA-Z0-9]+$/", $newRessource_id) == 0) || strlen($newRessource_id) > 10) {
		$objResponse->addAssign('divStatutCheckRessourceId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDRessourceNonValide') . '</b></font>');
		return $objResponse->getXML();
	}

	$ressourceTest = new Ressource();
	$sql = 'SELECT * FROM planning_ressource WHERE ressource_id = ' . val2sql($newRessource_id);
	if($currentRessource_id != '') {
		$sql .= ' AND ressource_id <> ' . val2sql($currentRessource_id);
	}

	if($ressourceTest->db_loadSQL($sql)) {
		$objResponse->addAssign('divStatutCheckRessourceId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDDejaPris') . '</b></font>');
	} else {
		$objResponse->addAssign('divStatutCheckRessourceId', 'innerHTML', '<img src="assets/img/pictos/ok.gif" width="12" height="12" border="0">');
	}

	return $objResponse->getXML();
}


// check si l'identifiant de lieu est disponible
function checkLieuId($newLieu_id, $currentLieu_id) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	if((preg_match("/^[a-zA-Z0-9]+$/", $newLieu_id) == 0) || strlen($newLieu_id) > 10) {
		$objResponse->addAssign('divStatutCheckLieuId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDLieuNonValide') . '</b></font>');
		return $objResponse->getXML();
	}

	$lieuTest = new Lieu();
	$sql = 'SELECT * FROM planning_lieu WHERE lieu_id = ' . val2sql($newLieu_id);
	if($currentLieu_id != '') {
		$sql .= ' AND lieu_id <> ' . val2sql($currentLieu_id);
	}

	if($lieuTest->db_loadSQL($sql)) {
		$objResponse->addAssign('divStatutCheckLieuId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDDejaPris') . '</b></font>');
	} else {
		$objResponse->addAssign('divStatutCheckLieuId', 'innerHTML', '<img src="assets/img/pictos/ok.gif" width="12" height="12" border="0">');
	}

	return $objResponse->getXML();
}


// check si l'identifiant de categorie est disponible
function checkStatusId($newStatus_id, $currentStatus_id) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	if((preg_match("/^[a-zA-Z0-9]+$/", $newStatus_id) == 0) || strlen($newStatus_id) > 10) {
		$objResponse->addAssign('divStatutCheckStatusId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDStatusNonValide') . '</b></font>');
		return $objResponse->getXML();
	}

	$statusTest = new Status();
	$sql = 'SELECT * FROM planning_status WHERE status_id = ' . val2sql($newStatus_id);
	if($currentStatus_id != '') {
		$sql .= ' AND status_id <> ' . val2sql($currentStatus_id);
	}

	if($statusTest->db_loadSQL($sql)) {
		$objResponse->addAssign('divStatutCheckStatusId', 'innerHTML', '<font color="#FF3300"><b>' . $smarty->get_config_vars('ajax_IDDejaPris') . '</b></font>');
	} else {
		$objResponse->addAssign('divStatutCheckStatusId', 'innerHTML', '<img src="assets/img/pictos/ok.gif" width="12" height="12" border="0">');
	}

	return $objResponse->getXML();
}


/* drag and drop d'une case
	param $casePeriode, de la forme : c_PERIODEID_DATEJOUR, exemple : c_25_20081103
	param $jourCible, de la forme : td_USERID_DATEJOUR, exemple : td_RS_20081225
	si $copie = true, on ne deplace pas la case, on la copie simplement
*/
function moveCasePeriode($casePeriode, $jourCible, $copie = false) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	// check securit�
	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || $user->checkDroit('tasks_readonly')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}

	// d?coupage des chaine pour r�cup des valeurs
	$chaines1 = explode('_', $casePeriode);
	$chaines2 = explode('_', $jourCible);

	// chargement de la p�riode
	$periode = new Periode();
	if(!$periode->db_load(array('periode_id' , '=', $chaines1[1]))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_periodeIntrouvable')));
		return $objResponse->getXML();
	}
	$periodeBackup= clone $periode; // modif ajout clonage de la periode

	$projetDepart = new projet();
	$projetDepart->db_load(array('projet_id', '=', $periode->projet_id));

	// reformatage de la date du jour d'origine
	$jourOrigine = substr($chaines1[2], 0, 4) . '-' . substr($chaines1[2], 4, 2) . '-' . substr($chaines1[2], 6, 2);
	// reformatage de la date du jour de destination
	$jourDestination = substr($chaines2[2], 0, 4) . '-' . substr($chaines2[2], 4, 2) . '-' . substr($chaines2[2], 6, 2);

	$userCible = new User();
	if($userCible->db_load(array('user_id', '=', $chaines2[1]))) {
		// si on change de user
		if($user->checkDroit('tasks_modify_own_task') && $userCible->user_id != $user->user_id) {
			// si droit modif des taches assign?es uniquement, on check le user final
			$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible')));
			$objResponse->addScript('location.reload();');
			return $objResponse->getXML();
		}
		if($user->checkDroit('tasks_modify_own_project') && $projetDepart->createur_id != $user->user_id) {
			// si droit modif des taches assign?es uniquement, on check le user final
			$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible')));
			$objResponse->addScript('location.reload();');
			return $objResponse->getXML();
		}
	} else {
		// si pas un user, veut dire que c'est peut-etre un projet (si affichage par projet et non par user)
		$projetCible = new Projet();
		if(!$projetCible->db_load(array('projet_id', '=', $chaines2[1])) || $user->checkDroit('tasks_readonly') || ($user->checkDroit('tasks_modify_own_project') && ($projetCible->createur_id != $user->user_id || $projetDepart->createur_id != $user->user_id))) {
			$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible')));
			$objResponse->addScript('location.reload();');
			return $objResponse->getXML();
		}
		if($user->checkDroit('tasks_modify_own_task')) {
			// si droits limit�s aux taches on checke que le projet cible est autoris?
			$projTmp = new Projet();
			if(!$projTmp->db_loadSQL("SELECT DISTINCT ppr.* FROM planning_projet AS ppr INNER JOIN planning_periode AS ppe ON ppr.projet_id = ppe.projet_id WHERE ppe.user_id = '" . $user->user_id . "' AND ppr.projet_id = '" . $projetCible->projet_id . "'")) {
				$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible')));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}
	}

	if($copie == 'true') {
		$copie = new Periode();
		$data = $periode->getData();
		unset($data['saved']);
		$copie->setData($data);
		if(isset($projetCible)) {
			$copie->projet_id = $projetCible->projet_id;
		} else {
			$copie->user_id = $userCible->user_id;
		}
		$copie->modifier_id = NULL;
		$copie->date_modif = NULL;
		if(!is_null($periode->date_fin)) {
			$nbJours = 0;
			$nbJours = getNbJours($periode->date_debut, $periode->date_fin);
			$copie->date_fin = calculerDateFin($jourDestination, $nbJours);
		}
		$copie->date_debut = $jourDestination;

		// si on vient du planning par jour on modifie la tranche horaire
		if(count($chaines2) == 4 && strlen($copie->duree_details) == 17) {
			$dureeData = explode(';', $copie->duree_details);
			$duree = soustraireDuree($dureeData[0], $dureeData[1]);
			$heureDebut = usertime2sqltime($chaines2[3]);
			$heureFin = usertime2sqltime(ajouterDuree($heureDebut, $duree));
			$copie->duree_details = $heureDebut . ';' . $heureFin;
			$copie->duree = usertime2sqltime($duree);
		}
		// V�rification que la ressource est disponible
		if (!is_null($periode->ressource))
		{
			if (!checkConflitRessource($periode->ressource,$copie->date_debut,$copie->date_fin,$copie->user_id,null))
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible_erreurRessource')));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}
		// V�rification que le lieu est disponible
		if (!is_null($periode->lieu))
		{
			if (!checkConflitLieu($periode->lieu,$copie->date_debut,$copie->date_fin,$copie->user_id,null))
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible_erreurLieu')));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}
		if(CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY == 1) {
			//on checke qu'il n'y ait aucun jour en commun entre cette tache et les autres taches du meme user
			$sql = "SELECT * FROM planning_periode ";
			if(!is_null($copie->date_fin)) {
					$sql .= " WHERE	((date_debut >= '" . $copie->date_debut . "' 	AND	date_debut <= '" . $copie->date_fin . "')";
					$sql .= " OR (date_fin IS NOT NULL AND date_fin >= '" . $copie->date_debut . "' AND date_fin <= '" . $copie->date_fin . "')";
			} else {
					$sql .= " WHERE	((date_fin IS NOT NULL AND date_debut <= '" . $copie->date_debut . "' AND	date_fin >= '" . $copie->date_debut . "')";
					$sql .= " OR (date_fin IS NULL AND date_debut = '" . $copie->date_debut . "')";
			}
			$sql .= " ) 	AND user_id = '" . $copie->user_id . "'";
			if($copie->isSaved()) {
				$sql .= ' AND periode_id <> ' . $copie->periode_id;
			}
			$periodesTest = new GCollection('Periode');
			$periodesTest->db_loadSQL($sql);
			if($periodesTest->getCount() > 0) {
				$periodeTmp = $periodesTest->fetch();
				$projetTmp = new Projet();
				$projetTmp->db_load(array('projet_id', '=', $periodeTmp->projet_id));
				$objResponse->addAlert(addslashes(sprintf($smarty->get_config_vars('ajax_jourDejaOccupe'), $projetTmp->nom, $periodeTmp->date_debut, $periodeTmp->date_fin)));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}

		if(!$copie->db_save()){
			$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_erreurDeplacement')));
			return $objResponse->getXML();
		}

		// on fait la notification ici et non dans le db_save() sinon ?a va s'appliquer ? toutes les taches filles
		// on envoie que si la personne assign�e n'est pas la personne connect?e
		if($copie->user_id != $user->user_id) {
			$copie->envoiNotification('modification');
		}


	} else {
		// mise ? jour des infos de la p�riode d�plac�e
		if(isset($projetCible)) {
			$periode->projet_id = $projetCible->projet_id;
		} else {
			$periode->user_id = $userCible->user_id;
		}
		// modif calcul du nombre de jour de decalage entre le debut de la periode et la case cliqu�e
		$nbJoursDecalOrig = 0;
		$nbJoursDecalOrig = getNbJours($periode->date_debut, $jourOrigine);

		// modif calcul du nombre de jour de la p�riode pour report sur la nouvelle date
		if(!is_null($periode->date_fin)) {
			$nbJours = 0;
			$nbJours = getNbJours($periode->date_debut, $periode->date_fin);
			$periode->date_debut = calculerDateDebut($jourDestination, $nbJoursDecalOrig);
			$periode->date_fin = calculerDateFin($periode->date_debut, $nbJours);
		}else{
			$periode->date_debut = $jourDestination;
		}

		// modif calcul du nombre de jour de decalage entre le debut de la periode pr�c�dente et la nouvelle
		$nbJoursDecalDest = 0;
		if ($periode->date_debut < $periodeBackup->date_debut){
			$nbJoursDecalDest = getNbJours($periode->date_debut, $periodeBackup->date_debut);
			$nbJoursDecalDest = $nbJoursDecalDest*-1;
		}else if ($periode->date_debut > $periodeBackup->date_debut){
			$nbJoursDecalDest = getNbJours($periodeBackup->date_debut, $periode->date_debut);
		}
		//$nbJoursDecalDest = 0;
		// V�rification que la ressource est disponible
		if (!is_null($periode->ressource))
		{
			if (!checkConflitRessource($periode->ressource,$periode->date_debut,$periode->date_fin,$periode->user_id,$periode->periode_id))
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible_erreurRessource')));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}
		// V�rification que le lieu est disponible
		if (!is_null($periode->lieu))
		{
			if (!checkConflitLieu($periode->lieu,$periode->date_debut,$periode->date_fin,$periode->user_id,$periode->periode_id))
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_deplacementImpossible_erreurLieu')));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}

		// si on vient du planning par jour on modifie la tranche horaire
		if(count($chaines2) == 4 && strlen($periode->duree_details) == 17) {
			$dureeData = explode(';', $periode->duree_details);
			$duree = soustraireDuree($dureeData[0], $dureeData[1]);
			$heureDebut = usertime2sqltime($chaines2[3]);
			$heureFin = usertime2sqltime(ajouterDuree($heureDebut, $duree));
			$periode->duree_details = $heureDebut . ';' . $heureFin;
			$periode->duree = $duree;
		}

		if(CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY == 1) {
			//on checke qu'il n'y ait aucun jour en commun entre cette tache et les autres taches du meme user
			$sql = "SELECT * FROM planning_periode ";
			if(!is_null($periode->date_fin)) {
					$sql .= " WHERE	((date_debut >= '" . $periode->date_debut . "' 	AND	date_debut <= '" . $periode->date_fin . "')";
					$sql .= " OR (date_fin IS NOT NULL AND date_fin >= '" . $periode->date_debut . "' AND date_fin <= '" . $periode->date_fin . "')";
			} else {
					$sql .= " WHERE	((date_fin IS NOT NULL AND date_debut <= '" . $periode->date_debut . "' AND	date_fin >= '" . $periode->date_debut . "')";
					$sql .= " OR (date_fin IS NULL AND date_debut = '" . $periode->date_debut . "')";
			}
			$sql .= " ) 	AND user_id = '" . $periode->user_id . "'";
			if($periode->isSaved()) {
				$sql .= ' AND periode_id <> ' . $periode->periode_id;
			}
			$periodesTest = new GCollection('Periode');
			$periodesTest->db_loadSQL($sql);
			if($periodesTest->getCount() > 0) {
				$periodeTmp = $periodesTest->fetch();
				$projetTmp = new Projet();
				$projetTmp->db_load(array('projet_id', '=', $periodeTmp->projet_id));
				$objResponse->addAlert(addslashes(sprintf($smarty->get_config_vars('ajax_jourDejaOccupe'), $projetTmp->nom, $periodeTmp->date_debut, $periodeTmp->date_fin)));
				$objResponse->addScript('location.reload();');
				return $objResponse->getXML();
			}
		}
	//modif ajout gestion des occurences
		if($periode->estFilleOuParente()) {
			$periode->updateOcurrences($nbJoursDecalDest); //modif ajout argument decal
		}else{
			$periode->parent_id = NULL;
		}
		//recup dbsave avant modif
		if(!$periode->db_save()){
			$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_erreurDeplacement')));
			return $objResponse->getXML();
		}

		// on fait la notification ici et non dans le db_save() sinon �a va s'appliquer ? toutes les taches filles
		// on envoie que si la personne assign�e n'est pas la personne connect?e
		if($periode->user_id != $user->user_id) {
			$periode->envoiNotification('modification');
		}

	}


	// chargement de la fenetre de r�ussite
	//$objResponse->addScript('windowDeplacementOK();');
	$objResponse->addScript('location.reload();');

	return $objResponse->getXML();
}

function checkAvailableVersion() {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	if(isset($_COOKIE['infosVersionInactif'])) {
		return $objResponse->getXML();
	}

	$version = new Version();
	$infos = $version->checkAvailableVersion();

	if(!$infos) {
		return $objResponse->getXML();
	}

	$smarty = new MySmarty();

	$smarty->assign('infos', $infos);
	$objResponse->addAssign('infosVersion', 'innerHTML', $smarty->getHtml('version.tpl'));
	$objResponse->addAssign('infosVersion', 'style.display', 'block');

	return $objResponse->getXML();
}


function choixPDF() {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());

	if(isset($_COOKIE['pdf_orientation'])) {
		$smarty->assign('pdf_orientation', $_COOKIE['pdf_orientation']);
	} else {
		$smarty->assign('pdf_orientation', 'paysage');
	}
	if(isset($_COOKIE['pdf_format'])) {
		$smarty->assign('pdf_format', $_COOKIE['pdf_format']);
	} else {
		$smarty->assign('pdf_format', 'A4');
	}

	$objResponse->addScript('masquerSousMenu("divOptions");');

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("PDF")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('choix_pdf.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');

	return $objResponse->getXML();
}

function choixIcal() {
	global $lang;
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());

	$lienIcal = $user->lienIcal(array($user->user_id), array());
	$smarty->assign('lienIcal', $lienIcal);

	// liste de tous les projets
	$listeProjets = new GCollection('Projet');
	if($user->checkDroit('tasks_modify_own_project')) {
		$listeProjets->db_load(array('createur_id', '=', $user->user_id, 'statut', 'IN', array('a_faire','en_cours')), array('nom' => 'ASC'));
	} elseif ($user->checkDroit('tasks_modify_own_task')) {
		$sql = "SELECT DISTINCT ppr.*
				FROM planning_projet AS ppr
				LEFT JOIN planning_periode AS ppe ON ppr.projet_id = ppe.projet_id AND ppe.user_id = '" . $user->user_id . "'
				WHERE statut IN ('a_faire','en_cours')
				AND (ppe.periode_id IS NOT NULL OR ppr.createur_id = " . val2sql($user->user_id) . ")
				ORDER BY nom ASC
				";
		$listeProjets->db_loadSQL($sql);
	} else {
		$listeProjets->db_load(array('statut', 'IN', array('a_faire','en_cours')), array('nom' => 'ASC'));
	}
	$smarty->assign('listeProjets', $listeProjets->getSmartyData());
	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("ICAL")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('choix_ical.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	return $objResponse->getXML();
}


function modifUser($user_id=null) {
	global $lang;
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user_form = new User();
	if($user_id != '') {
		$user_form->db_load(array('user_id', '=', $user_id));
	}
	$smarty->assign('user_form', $user_form->getSmartyData());

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());

	$groupes = new GCollection('User_groupe');
	$groupes->db_load(array(), array('nom' => 'ASC'));
	$smarty->assign('groupes', $groupes->getSmartyData());

	// recuperation de la liste des utilisateurs pour filtre sur users
	$usersFiltre = new GCollection('User');
	$sql = "SELECT pu.*, pug.nom AS groupe_nom
			FROM planning_user pu ";
	$sql .= " LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id
			WHERE visible_planning = 'oui' ";
	$sql .=	" ORDER BY groupe_nom, pu.nom";
	$usersFiltre->db_loadSQL($sql);
	$smarty->assign('listeUsers', $usersFiltre->getSmartyData());

	$smarty->assign('listUsersRights', $user_form->getRightsOnUsers());

	if (CONFIG_SOPLANNING_OPTION_RESSOURCES == 1)
	{
		$listeRessources = new GCollection('Ressource');
		$sql = "SELECT pr.* FROM planning_ressource pr 
				left outer join `planning_user` pu on pr.ressource_id = pu.resource_id
				where (pu.user_id is null";
		
		if ($user_id != null) {
			$sql .= " or pu.user_id='" . $user_id . "'";
		}

		$sql .= ") order by pr.nom";
		$listeRessources->db_loadSQL($sql);
		$smarty->assign('listeRessources', $listeRessources->getSmartyData());
	}

	$objResponse->addScript('jQuery("#myBigModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('ajax_ajoutModifuser')) . '")');
	$objResponse->addScript('jQuery("#myBigModal .modal-body").html("' . xajaxFormat($smarty->getHtml('user_form.tpl')) . '")');

	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	// refresh title box when element is selected
	$objResponse->addScript('jQuery("#user_groupe_id").on("select2-selecting", function(e){xajax_autocompleteTitreTache(e.val);});');

	$objResponse->addScript('jQuery("#myBigModal").modal()');
	if (!$_SESSION['isMobileOrTablet'])
	{
		$objResponse->addScript("myPicker = new jscolor.color(document.getElementById('couleur'), {});myPicker.fromString('" . $user_form->couleur . "')");
	}
	if($user_form->couleur != ''){
		$_SESSION['couleurExUser']=$user_form->couleur;
	}
	return $objResponse->getXML();
}



function submitFormUser($user_id, $user_id_origine, $user_groupe_id, $nom, $email, $login, $password, $visible_planningOui, $couleur, $notificationsOui, $envoiMailPwd, $droits, $adresse, $telephone, $mobile, $metier, $commentaire, $specific_users_ids, $resource_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();
	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(trim($user_id) == '') {
		$objResponse->addAlert($smarty->get_config_vars('user_user_idManquant'));
		return $objResponse;
	}
	$user_form = new User();
	if(!$user_form->db_load(array('user_id', '=', $user_id))) {
	}

	// on checke que le user_id n'existe pas d�j�
	if($user_id_origine == '') {
		//si cr?ation de user
		$userTest = new USer();
		if($userTest->db_load(array('user_id', '=', $user_id))) {
			$objResponse->addAlert($smarty->get_config_vars('user_id_existant'));
			return $objResponse;
		}
		if(trim($login) != '' && $userTest->db_load(array('login', '=', $login))) {
			$objResponse->addAlert($smarty->get_config_vars('login_existant'));
			return $objResponse;
		}
	} else {
		// si user existant on v�rifie que les champs ne vont pas ?craser un existant (login et identifiant)
		$userTest = new USer();
		if($login != '' && $userTest->db_load(array('login', '=', $login, 'user_id', '<>', $user_form->user_id))) {
			$objResponse->addAlert($smarty->get_config_vars('login_existant'));
			return $objResponse;
		}
	}

	if(trim($nom) == '') {
		$objResponse->addAlert($smarty->get_config_vars('user_nomManquant'));
		return $objResponse;
	}
	if(trim($email) != '' && !VerifierAdresseMail($email)) {
		$objResponse->addAlert($smarty->get_config_vars('user_emailInvalide'));
		return $objResponse;
	}
	if($user_id_origine == '') {
		// on met � jour le user_id uniquement � la creation pour �viter l'�crasement par un petit rus?
		$user_form->user_id = $user_id;
	}
	$user_form->nom = $nom;
	$user_form->email = ($email != '' ? $email : null);

	$user_form->adresse = ($adresse != '' ? $adresse : null);
	$user_form->telephone = ($telephone != '' ? $telephone : null);
	$user_form->mobile = ($mobile != '' ? $mobile : null);
	$user_form->metier = ($metier != '' ? $metier : null);
	$user_form->commentaire = ($commentaire != '' ? $commentaire : null);

	$user_form->user_groupe_id = ($user_groupe_id != '' ? $user_groupe_id : null);
	$user_form->resource_id = ($resource_id != '' ? $resource_id : null);
	$user_form->login = ($login != '' ? $login : null);
	if($password != '') {
		$user_form->password = sha1($password);

	}
	if($visible_planningOui == 'true') {
		$user_form->visible_planning = 'oui';
	} else {
		$user_form->visible_planning = 'non';
	}
	if($notificationsOui == 'true') {
		$user_form->notifications = 'oui';
	} else {
		$user_form->notifications = 'non';
	}
	$user_form->couleur = ($couleur != '' ? $couleur : null);
	$_SESSION['couleurExUser'] = $user_form->couleur;

	$user_form->setDroits($droits);
	$test = $user_form->check();
	if($test !== TRUE) {
		if(!is_array($test)) {
			$objResponse->addAlert(addslashes($smarty->get_config_vars($test)));
			return $objResponse;
		}
	}

	if(!$user_form->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}

	$user_form->updateRightsOnUsers($specific_users_ids);

	if($envoiMailPwd == 'true') {
		$user_form->mailChangerPwd();
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect($_SERVER['HTTP_REFERER']);
	return $objResponse;
}


function supprimerUser($user_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$user_form = new User();
	if($user_id == '' || !$user_form->db_load(array('user_id', '=', $user_id))) {
		$objResponse->addAlert($smarty->get_config_vars('changeNotOK'));
		return $objResponse;
	}

	// on reassigne les projets au user courant
	$sql = "UPDATE planning_projet
			SET createur_id = '{$user->user_id}'
			WHERE createur_id = '{$user_form->user_id}'";
	db_query($sql);

	// on empeche la suppression de l'admin
	if($user_form->user_id != 'ADM') {
		$user_form->db_delete();
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('user_list.php');
	return $objResponse;
}


function modifProfil() {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user_form', $user->getSmartyData());

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('ajax_editionProfil')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('profil_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');


	return $objResponse->getXML();
}


function submitFormProfil($user_id, $email, $password, $dateformat, $notificationsOui, $vueDefautPlanning, $vueDefautPersonne, $vueDefautMois, $vueDefautLarge) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || $user->user_id != $user_id) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(trim($password) != '') {
		$user->password = sha1($password);
	}

	if(trim($email) != '' && !VerifierAdresseMail($email)) {
		$objResponse->addAlert($smarty->get_config_vars('user_emailInvalide'));
		return $objResponse;
	}

	$user->email = ($email != '' ? $email : null);
	$preferences['dateformat']=$dateformat;
	if($notificationsOui == 'true') {
		$user->notifications = 'oui';
	} else {
		$user->notifications = 'non';
	}
	if ($vueDefautPlanning == 'true')
	{
		$preferences['vuePlanning']='vuePlanning';
	}else
	{
		$preferences['vuePlanning']='vueTaches';
	}
	if ($vueDefautPersonne == 'true')
	{
		$preferences['vueDefaut']='vuePersonne';
	}else
	{
		$preferences['vueDefaut']='vueProjet';
	}

	if ($vueDefautMois == 'true')
	{
		$preferences['vueJourMois']='vueMois';
	}else
	{
		$preferences['vueJourMois']='vueJour';
	}

	if ($vueDefautLarge == 'true')
	{
		$preferences['vueLargeReduit']='vueLarge';
	}else
	{
		$preferences['vueLargeReduit']='vueReduit';
	}
	// Creation du tableau json pour stockage de pr�f�rences
	$user->preferences=json_encode($preferences);
	if(!$user->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}
	// Si on est dans le cas d'un changement de format de date, on efface les anciennes donn�es de la session
	if (CONFIG_DATE_FORMAT<>$dateformat)
	{
		unset($_SESSION['dateDebut']);
		unset($_SESSION['debutFin']);
		unset($_SESSION['date_debut_affiche']);
		unset($_SESSION['date_fin_affiche']);
		unset($_SESSION['date_debut_affiche_tache']);
		unset($_SESSION['date_fin_affiche_tache']);
		setcookie("dateDebut",'', time() - 3600,'/');
		setcookie("dateFin",'', time() - 3600,'/');
		setcookie("date_debut_affiche",'', time() - 3600,'/');
		setcookie("date_debut_affiche_tache",'', time() - 3600,'/');
		setcookie("date_fin_affiche",'', time() - 3600,'/');
		setcookie("date_fin_affiche_tache",'', time() - 3600,'/');
		session_destroy();
	}
	//$_SESSION['preferences'] = $user->getPreferences();
	$_SESSION['message'] = 'changeOKReconnect';
	$objResponse->addRedirect('planning.php');
	return $objResponse;
}


function changerPwd($email) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	if(trim($email) == '') {
		return $objResponse;
	}
	$users = new Gcollection('User');
	$users->db_load(array('email', '=', $email));
	if($users->getCount() == 0) {
		$objResponse->addAlert($smarty->get_config_vars('rappelPwdKo'));
		return $objResponse;
	}
	while($userTmp = $users->fetch()) {
		$userTmp->mailChangerPwd();
	}

	$objResponse->addAlert($smarty->get_config_vars('rappelPwdOk'));
	return $objResponse;
}


function nouveauPwd($password) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	if(!isset($_SESSION['change_password'])) {
		$objResponse->addAlert($smarty->get_config_vars('erreur'));
		return $objResponse;
	}
	if(trim($password) == '') {
		return $objResponse;
	}
	$userTmp = new User();
	if(!$userTmp->db_load(array('user_id', '=', $_SESSION['change_password']))) {
		return $objResponse;
	}
	$userTmp->password = sha1($password);
	if(!$userTmp->db_save()) {
		$objResponse->addAlert($smarty->get_config_vars('erreur'));
		return $objResponse;
	}

	unset($_SESSION['change_password']);
	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('index.php');
	return $objResponse;
}


function submitFormPeriode($periode_id, $projet_id, $user_id, $date_debut, $conserver_duree, $date_fin, $nb_jours, $duree, $heure_debut, $heure_fin, $matin, $apresmidi, $repetition, $dateFinRepetitionJour, $dateFinRepetitionSemaine, $dateFinRepetitionMois, $nbRepetitionJour, $nbRepetitionSemaine, $nbRepetitionMois, $jourSemaine,$exceptionRepetition, $appliquerATous, $statut_tache, $lieu, $ressource, $livrable, $titre, $notes, $lien, $custom, $notif_email) {

	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();
	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || $user->checkDroit('tasks_readonly')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if($projet_id == '') {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_choisirProjet')));
		return $objResponse;
	}

	if($user_id[0] == "") {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_choisirUtilisateur')));
		return $objResponse;
	}

	if ($date_debut == "") {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_choisirDateDebut')));
		return $objResponse;
	}

	// Forcement des dates en cas de formulaire html5
	$date_debut=forceUserDateFormat($date_debut);
	$date_fin=forceUserDateFormat($date_fin);
	$dateFinRepetitionJour=forceUserDateFormat($dateFinRepetitionJour);
	$dateFinRepetitionSemaine=forceUserDateFormat($dateFinRepetitionSemaine);
	$dateFinRepetitionMois=forceUserDateFormat($dateFinRepetitionMois);
	if (!controlDate($date_debut) || !controlDate($date_fin)) {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_saisirFormatDate')));
		return $objResponse;
	}

	if ($conserver_duree === 'false' && $date_fin != '' && userdate2sqldate($date_fin) < userdate2sqldate($date_debut)) {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_dateFinInferieure')));
		return $objResponse;
	}

	if($repetition != '' && $repetition == 'jour' ) {
		if($dateFinRepetitionJour == '' || !controlDate($dateFinRepetitionJour) || userdate2sqldate($dateFinRepetitionJour) == $periode->date_debut) {
			$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
			$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
			$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_dateFinRepetition')));
			return $objResponse;
		}
	}
	if($repetition != '' && $repetition == 'semaine' ) {
		if($dateFinRepetitionSemaine == '' || !controlDate($dateFinRepetitionSemaine) || userdate2sqldate($dateFinRepetitionSemaine) == $periode->date_debut) {
			$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
			$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
			$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_dateFinRepetition')));
			return $objResponse;
		}
	}
	if($repetition != '' && $repetition == 'mois' ) {
		if($dateFinRepetitionMois == '' || !controlDate($dateFinRepetitionMois) || userdate2sqldate($dateFinRepetitionMois) == $periode->date_debut) {
			$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
			$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
			$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_dateFinRepetition')));
			return $objResponse;
		}
	}

	$duree = usertime2sqltime($duree);
	if ($duree != '00:00:00' && !is_valid_time($duree)) {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_dureeNonValide')));
		return $objResponse;
	}

	$heure_debut = usertime2sqltime($heure_debut);
	if ($heure_debut != '00:00:00' && !is_valid_time($heure_debut)) {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_heureDebutNonValide')));
		return $objResponse;
	}

	$heure_fin = usertime2sqltime($heure_fin);
	if (($heure_debut != '00:00:00' &&  $heure_fin == '00:00:00') || ($heure_fin != '00:00:00' && !is_valid_time($heure_fin)) || $heure_fin < $heure_debut) {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_heureFinNonValide')));
		return $objResponse;
	}

	$periode = new Periode();
	if($periode_id != 0) {
		$periode->db_load(array('periode_id', '=', $periode_id));
		$periodeBackup= clone $periode; // modif ajout clonage de la periode
	}else{
		$periode->createur_id = $user->user_id;
	}

	$periode->projet_id = $projet_id;
	$periode->titre = ($titre != '' ? $titre : null);
	$periode->statut_tache = ($statut_tache != '' ? $statut_tache : null);
	$periode->livrable = ($livrable != '' ? $livrable : null);
	$periode->lieu = ($lieu != '' ? $lieu : null);
	$periode->ressource = ($ressource != '' ? $ressource : null);
	// $periode->ressource = (null);
	$periode->notes = ($notes != '' ? $notes : null);
	$periode->lien = ($lien != '' ? $lien : null);
	$periode->custom = ($custom != '' ? $custom : null);
	$periode->date_debut = userdate2sqldate($date_debut);
	$periode->date_fin = userdate2sqldate($date_fin);

	if($periode_id != 0) {
		$periodeBackup= clone $periode; // modif ajout clonage de la periode
	}

	// V�rification que la ressource est disponible
	if (!is_null($periode->ressource))
	{
	 if (!checkConflitRessource($ressource,userdate2sqldate($date_debut),userdate2sqldate($date_fin),$user_id,$periode_id))
	 {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_ressource_utilisee')));
		return $objResponse;
	 }
	}
	// V�rification que le lieu est disponible
	if (!is_null($periode->lieu))
	{
	 if (!checkConflitLieu($lieu,userdate2sqldate($date_debut),userdate2sqldate($date_fin),$user_id,$periode_id))
	 {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_lieu_utilise')));
		return $objResponse;
	 }
	}

	if($conserver_duree === 'true') {
		// on reprend la dur�e existante (seulement en modif de p�riode)

		// on charge la p�riode de la BD pour r�cup�rer les anciennes date, pour calculer nb de jour
		$Oldperiode = new Periode();
		$Oldperiode->db_load(array('periode_id', '=', $periode_id));
		$nbJours = getNbJours($Oldperiode->date_debut, $Oldperiode->date_fin);
		//modif pour ajouter possibilite modif date fin ou debut en conservant dureee
			if($periode->date_debut != $Oldperiode->date_debut) {
				$periode->date_fin = calculerDateFin($periode->date_debut, $nbJours);
			}elseif($periode->date_fin != $Oldperiode->date_fin){
				$periode->date_debut = calculerDateDebut($periode->date_fin, $nbJours);
			}
		$periode->duree = NULL;
		$periode->duree_details = NULL;
	} elseif ($date_fin != '') {
		$periode->date_fin = userdate2sqldate($date_fin);
		$periode->duree = NULL;
		$periode->duree_details = NULL;
	} elseif ($nb_jours != '' && (int)$nb_jours > 1) {
		$joursFeries = getJoursFeries();
		// on calcule la date finale en rajoutant le nb de jours, sans les WE.
		// affich� seulement en cr�ation
		$dateFin = new DateTime();
		$dateFin->setDate(substr($periode->date_debut,0,4), substr($periode->date_debut,5,2), substr($periode->date_debut,8,2));
		$nbJours = (int)$nb_jours - 1;
		$i = 1;
		while($i <= $nbJours) {
			$dateFin->modify('+1 days');
			if (in_array($dateFin->format('w'), explode(',', CONFIG_DAYS_INCLUDED)) && !in_array($dateFin->format('Y-m-d'), $joursFeries)) {
				$i++;
			}
		}

		$periode->date_fin = $dateFin->format('Y-m-d');
		$periode->duree = NULL;
		$periode->duree_details = NULL;

	} else {
		// pas de date de fin renseign�e, on g�re la dur�e

		// si aucune info renseign�e, on met la journ�e enti�re pour la tache
		if($duree == '00:00:00' && $heure_debut == '00:00:00' && $heure_fin == '00:00:00' && $matin == 'false' && $apresmidi == 'false') {
			$periode->duree = CONFIG_DURATION_DAY . ':00';
			if(strlen(CONFIG_DURATION_DAY) < 8) {
				$periode->duree = '0' . $periode->duree;
			}
			$periode->duree_details = 'duree';

		} elseif ($duree != '00:00:00') {
			$periode->duree= $duree;
			$periode->duree_details = 'duree';

		} elseif ($heure_fin != '00:00:00') {
			$periode->duree = soustraireDuree($heure_debut, $heure_fin);
			$periode->duree_details = $heure_debut . ';' . $heure_fin;

		} elseif ($matin == 'true') {
			$periode->duree = CONFIG_DURATION_AM . ':00';
			if(strlen(CONFIG_DURATION_AM) < 8) {
				$periode->duree = '0' . $periode->duree;
			}
			$periode->duree_details = 'AM';

		} elseif ($apresmidi == 'true') {
			$periode->duree = CONFIG_DURATION_PM . ':00';
			if(strlen(CONFIG_DURATION_PM) < 8) {
				$periode->duree = '0' . $periode->duree;
			}
			$periode->duree_details = 'PM';

		}

		if(!is_null($periode->duree)) {
			$periode->date_fin = NULL;
		}
	}

	// Si on est dans le cas d'une modification de p�riode avec du multiuser on supprime d'abord l'ancienne p�riode
	if ($periode_id !=0 && count($user_id)>1)
	{
		$periode->db_deleteAll();
		$periode_id=0;
	}

	foreach($user_id as $current_user_id) {

		$data = $periode->getData();
		if($periode_id != 0) {
			$data['saved'] = 1;
		}else{
			$data['saved'] = 0;
		}

		$periode = new Periode();
		$periode->setData($data);
		$periode->user_id = $current_user_id;

        if ($periode->check() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
			$objResponse->addScript('location.reload();');
			return $objResponse;
        }

        $projet = new Projet();
        $projet->db_load(array('projet_id', '=', $periode->projet_id));

        if($user->checkDroit('tasks_modify_own_project') && $projet->createur_id != $user->user_id) {

			$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
            $objResponse->addScript('location.reload();');
            return $objResponse;
        }

        if($user->checkDroit('tasks_modify_own_task') && $projet->createur_id != $user->user_id && $periode->user_id != $user->user_id) {
            $objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
            $objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
            $objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
            return $objResponse;
        }

		if ($user->checkDroit('tasks_view_only_own') && $periode->user_id != $user->user_id) {
            $objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
            $objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
            $objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
            return $objResponse;
		}

        if(CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY == 1) {
            //on checke qu'il n'y ait aucun jour en commun entre cette tache et les autres taches du meme user
            $sql = "SELECT * FROM planning_periode ";
            if(!is_null($periode->date_fin)) {
                    $sql .= " WHERE	((date_debut >= '" . $periode->date_debut . "' 	AND	date_debut <= '" . $periode->date_fin . "')";
                    $sql .= " OR (date_fin IS NOT NULL AND date_fin >= '" . $periode->date_debut . "' AND date_fin <= '" . $periode->date_fin . "')";
            } else {
                    $sql .= " WHERE	((date_fin IS NOT NULL AND date_debut <= '" . $periode->date_debut . "' AND	date_fin >= '" . $periode->date_debut . "')";
                    $sql .= " OR (date_fin IS NULL AND date_debut = '" . $periode->date_debut . "')";
            }
            $sql .= " ) 	AND user_id = '" . $periode->user_id . "'";
            if($periode->isSaved()) {
                $sql .= ' AND periode_id <> ' . $periode->periode_id;
            }
            $periodesTest = new GCollection('Periode');
            $periodesTest->db_loadSQL($sql);
            if($periodesTest->getCount() > 0) {
                $periodeTmp = $periodesTest->fetch();
                $projetTmp = new Projet();
                $projetTmp->db_load(array('projet_id', '=', $periodeTmp->projet_id));
                $objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
                $objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
                $objResponse->addAlert(addslashes(sprintf($smarty->get_config_vars('ajax_jourDejaOccupe'), $projetTmp->nom, $periodeTmp->date_debut, $periodeTmp->date_fin)));
                return $objResponse->getXML();
            }
        }
        // on fait la notification ici et non dans le db_save() sinon �a va s'appliquer � toutes les taches filles
        // on envoie que si la personne assign�e n'est pas la personne connect�e

        if($notif_email == 'true' && $periode->user_id != $user->user_id) {
            $periode->envoiNotification('modification', $repetition);
        }
        if(!$periode->db_save()) {
            $objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
            $objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
            $objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
            return $objResponse;
        }

		if($repetition != '' && $repetition != 'undefined' ) {
			switch ($repetition)
			{
				case 'jour' :
				        $dateFinRepetition=$dateFinRepetitionJour;
						$nbRepetition=$nbRepetitionJour;
						break;
				case 'semaine' :
						$dateFinRepetition=$dateFinRepetitionSemaine;
						$nbRepetition=$nbRepetitionSemaine;
						break;
				case 'mois' :
						$dateFinRepetition=$dateFinRepetitionMois;
						$nbRepetition=$nbRepetitionMois;
						break;
				default :
				   $objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
			}

			$sqldate_FinRepetition = userdate2sqldate($dateFinRepetition);
			$dt_Debut = userdate2sqldate($date_debut);
			$date_FinRepetition = new DateTime();
			$date_FinRepetition->setDate(substr($sqldate_FinRepetition,0,4), substr($sqldate_FinRepetition,5,2), substr($sqldate_FinRepetition,8,2));
			$dt_FinRepetition=$date_FinRepetition->format('Y-m-d');

			$nbjours = getNbJours($dt_Debut, $dt_FinRepetition);
			// Controle que la date de fin de r�p�tition est sup�rieure � la date de d?but
			if(userdate2sqldate($dateFinRepetition) <= userdate2sqldate($date_debut))
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('js_dateFinInferieure')));
				$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
				$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
				return $objResponse;
			}
			// Controle que la date de fin de r�p�tition est au moins 7 jours apr�s celle du d�but si on choisit semaine
			if($repetition=="semaine" && $nbjours<7)
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('js_dateFinInferieure_7jours')));
				$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
				$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
				return $objResponse;
			}

			// Controle que la date de fin de r�p�tition est au moins 30 jours apr�s celle du d�but si on choisit mois
			if($repetition=="mois" && $nbjours<30)
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('js_dateFinInferieure_30jours')));
				$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
				$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
				return $objResponse;
			}

			// Controle que l'on ne choisit pas de poser un jour qui n'est pas dans la liste des jours choisis
			$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);

			if($repetition=="semaine" && !in_array($jourSemaine,  $DAYS_INCLUDED))
			{
				$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_jourNonValide')));
				$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
				$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
				return $objResponse;
			}

			// Si la r�p�tition ne fonctionne pas...
			$reponse_repetition=$periode->repeter($repetition, userdate2sqldate($dateFinRepetition),$nbRepetition,$jourSemaine,$exceptionRepetition);
			if($reponse_repetition != 1) {
				$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur_repetition_ressourcelieu'))."$reponse_repetition");
				$objResponse->addScript('location.reload();');
				return $objResponse;
			}
		}
	}

	if($appliquerATous === 'true') {
		// modif calcul du nombre de jour de decalage entre le debut de la periode pr�c�dente et la nouvelle
		$nbJoursDecalDest = 0;
		if($periode->date_debut < $periodeBackup->date_debut){
			$nbJoursDecalDest = getNbJours($periode->date_debut, $periodeBackup->date_debut);
			$nbJoursDecalDest = $nbJoursDecalDest*-1;
		}else if ($periode->date_debut > $periodeBackup->date_debut){
			$nbJoursDecalDest = getNbJours($periodeBackup->date_debut, $periode->date_debut);
		}
		$periode->updateOcurrences($nbJoursDecalDest);
	} else {
		$periode->parent_id = NULL;
		$periode->db_save();
	}
		$periode_id=0;

	if($_SESSION['planningView'] == 'taches') {
		$objResponse->addRedirect('taches.php');
	} elseif($_SESSION['planningView'] == 'mois') {
		$objResponse->addRedirect('planning.php');
	} else {
		$objResponse->addRedirect('planning_per_day.php');
	}
	return $objResponse;

}


function supprimerPeriode($periode_id, $fullscope = 'true', $notif_email) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || $user->checkDroit('tasks_readonly')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$periode = new Periode();
	if(!$periode->db_load(array('periode_id', '=', $periode_id))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
		$objResponse->addScript('location.reload();');
	}

	$projet = new Projet();
	$projet->db_load(array('projet_id', '=', $periode->projet_id));

	if($user->checkDroit('tasks_modify_own_project') && $projet->createur_id != $user->user_id) {
		$_SESSION['message'] = 'droitsInsuffisants';
		header('Location: ../index.php');
		exit;
	}

	// on fait la notification ici et non dans le db_save() sinon ca va s'appliquer ? toutes les taches filles
	// on envoie que si la personne assign�e n'est pas la personne connect�e
	if($notif_email == 'true' && $periode->user_id != $user->user_id) {
		$periode->envoiNotification('delete');
	}

	if($fullscope === 'true') {
		$periode->db_deleteAll();
	} else if($fullscope === 'avant') {
		$periode->db_deleteAllAvant();
		if ($periode->estFilleOuParente()) {
			$periode->updateOcurrences();
		}
	} else if($fullscope === 'apres') {
		$periode->db_deleteAllApres();
		if ($periode->estFilleOuParente()) {
			$periode->updateOcurrences();
		}
	} else {
		$periode->db_delete();
		if ($periode->estFilleOuParente()) {
			$periode->updateOcurrences();
		}
	}
	$objResponse->addScript('location.reload();');
	return $objResponse;

}


function modifFerie($date_ferie) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$ferie = new Ferie();
	if($date_ferie != '') {
		$ferie->db_load(array('date_ferie', '=', $date_ferie));
	}
	$smarty->assign('ferie', $ferie->getSmartyData());

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('menuFeries')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('ferie_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	if (!$_SESSION['isMobileOrTablet'])
	{
		$objResponse->addScript('jQuery("#date_ferie").datepicker({ dateFormat: "'.CONFIG_DATE_DATEPICKER.'" });');
	}
	return $objResponse->getXML();
}


function submitFormFerie($date_ferie, $libelle) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('parameters_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}
	// French date forcing
	if(trim($date_ferie) != '')
	{
		$date_ferie=forceUserDateFormat($date_ferie);
	}
	if(trim($date_ferie) == '' || !controlDate($date_ferie)) {
		$objResponse->addAlert($smarty->get_config_vars('feries_dateNonValide'));
		return $objResponse;
	}

	$ferie = new Ferie();
	$ferie->db_load(array('date_ferie', '=', userdate2sqldate($date_ferie)));
	$ferie->date_ferie = userdate2sqldate($date_ferie);
	$ferie->libelle = ($libelle != '' ? $libelle : null);

	if(!$ferie->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('feries.php');
	return $objResponse;
}


function supprimerFerie($date_ferie) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('parameters_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$ferie = new Ferie();
	if(!$ferie->db_load(array('date_ferie', '=', $date_ferie))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
		$objResponse->addScript('location.reload();');
	}

	$ferie->db_delete();

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('feries.php');
	return $objResponse;
}


function modifUserGroupe($user_groupe_id= '') {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$groupe = new User_groupe();
	if($user_groupe_id != '') {
		$groupe->db_load(array('user_groupe_id', '=', $user_groupe_id));
	}
	$smarty->assign('groupe', $groupe->getSmartyData());

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}
	$smarty->assign('user', $user->getSmartyData());

	$listeLieux = new GCollection('Lieu');
	$listeLieux->db_load(array(), array('nom' => 'ASC'));
	$smarty->assign('listeLieux', $listeLieux->getSmartyData());

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('menuGroupesUsers')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('user_group_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");

	return $objResponse->getXML();
}


function submitFormUserGroupe($user_groupe_id, $nom, $lieu_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(trim($nom) == '') {
		$objResponse->addAlert($smarty->get_config_vars('user_groupe_nomInvalide'));
		return $objResponse;
	}

	if(trim($lieu_id) == '') {
		$objResponse->addAlert($smarty->get_config_vars('place_mandate'));
		return $objResponse;
	}

	$groupe = new User_groupe();
	if($user_groupe_id > 0) {
		$groupe->db_load(array('user_groupe_id', '=', $user_groupe_id));
	}
	$groupe->nom = $nom;
	$groupe->lieu_id = $lieu_id;

	if(!$groupe->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addScript('location.reload();');
	return $objResponse;
}


function supprimerUserGroupe($user_groupe_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$groupe = new User_groupe();
	if(!$groupe->db_load(array('user_groupe_id', '=', $user_groupe_id))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
		$objResponse->addScript('location.reload();');
	}

	$groupe->db_delete();

	$_SESSION['message'] = 'changeOK';
	$objResponse->addScript('location.reload();');
	return $objResponse;
}


function autocompleteTitreTache($projet_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}

	// on recupere les titres existants pour le projet courant
	if($projet_id != '') {
		$taches = new GCollection('Periode');
		$sql = 'SELECT DISTINCT titre FROM planning_periode WHERE titre IS NOT NULL AND projet_id = ' . val2sql($projet_id) . ' ORDER BY titre';
		$taches->db_loadSQL($sql);
		$jsTitreAutocomplete = 'var listeTitres = [';
		while($tache = $taches->fetch()) {
			$jsTitreAutocomplete .= '"' . addslashes($tache->titre) . '", ';
		}
		if($taches->getCount() > 0) {
			$jsTitreAutocomplete = substr($jsTitreAutocomplete, 0, strlen($jsTitreAutocomplete)-2);
		}
		$jsTitreAutocomplete .= '];';
		$jsTitreAutocomplete .= 'var autocomplete = jQuery("#titre").typeahead();autocomplete.data("typeahead").source = listeTitres;';
		$objResponse->addScript($jsTitreAutocomplete);
	}
	return $objResponse;

}

function submitFormContact($version = '', $email = '', $commentaire = '', $newsletter = '') {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	if(trim($version) == '' || trim($email) == '' || trim($commentaire) == '' || trim($newsletter) == '') {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('formContact_erreurChamps')));
		return $objResponse;
	}

	$infos = array();
	$context = @stream_context_create(array('http' => array('header'=>'Connection: close', 'timeout' => 3, 'user_agent' => 'Mozilla/5.0')));
	global $lang;
	$url = 'https://www.soplanning.org/ws/form_contact.php?version=' . $version . '&email=' . $email . '&newsletter=' . $newsletter . '&lang=' . $lang . '&commentaire=' . urlencode($commentaire);
	//@file_put_contents(BASE . '/../debug.txt', $url . "\r\n", FILE_APPEND);
	

	$data = @file_get_contents($url, false, $context);
	if(strlen($data) == 0 || trim($data) != 'OK') {
		$objResponse->addAlert($smarty->get_config_vars('formContact_envoiKO'));
		return $objResponse;
	}

	$objResponse->addAlert($smarty->get_config_vars('formContact_envoiOK'));
	return $objResponse;
}


function icalGenererLien($ical_users = '', $ical_projets = '', $ical_projets_cb = array()) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse->getXML();
	}

	if($ical_users == 'ical_users_moi') {
		$users = array($user->user_id);
	} else {
		$users = array();
	}

	if($ical_projets == 'ical_projets_tous' || count($ical_projets_cb) == 0) {
		$projets = array();
	} else {
		$projets = $ical_projets_cb;
	}

	$lienIcal = $user->lienIcal($users, $projets);
	$objResponse->addAssign('inputLienIcal', 'value', $lienIcal);
	return $objResponse;
}


function modifLieu($lieu_id = '') {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('lieux_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$lieu = new Lieu();
	if($lieu_id != '') {
		$lieu->db_load(array('lieu_id', '=', $lieu_id));
	}

	$smarty->assign('lieu', $lieu->getSmartyData());

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('menuLieux')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('lieu_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	return $objResponse->getXML();
}


function submitFormLieu($lieu_id, $new_lieu_id, $nom, $commentaire, $exclusif) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('lieux_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(trim($new_lieu_id) == '' || !preg_match('<^[A-Za-z0-9]*$>', $new_lieu_id)) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_erreur_lieu_idnom_vide')));
		return $objResponse;
	}

	if ($new_lieu_id == "" || $nom == "") {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_erreur_lieu_idnom_vide')));
		return $objResponse;
	}

	$lieu = new Lieu();
	if($lieu_id != '') {
		$lieu->db_load(array('lieu_id', '=', $lieu_id));
	} else {
		$lieu->lieu_id = $new_lieu_id;
	}
	$lieu->nom = $nom;
	$lieu->commentaire = ($commentaire != '' ? $commentaire : null);
	if ($exclusif=='true')
    {$lieu->exclusif=1;
    }else $lieu->exclusif=0;
	if(!$lieu->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('lieux.php');
	return $objResponse;
}

function supprimerLieu($lieu_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('lieux_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$lieu = new Lieu();
	if(!$lieu->db_load(array('lieu_id', '=', $lieu_id))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
		$objResponse->addScript('location.reload();');
	}

	$lieu->db_delete();

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('lieux.php');
	return $objResponse;
}

function modifStatus($status_id=null) {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$status = new Status();
	if($status_id != '') {
		$status->db_load(array('status_id', '=', $status_id));
	}

	$smarty->assign('status', $status->getSmartyData());
	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('menuStatus')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('status_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	// On n'affiche le color picker uniquement si il n'y a aucune couleurs personnalis�es
	if (CONFIG_PROJECT_COLORS_POSSIBLE == '' && !$_SESSION['isMobileOrTablet']) {
		$objResponse->addScript("myPicker = new jscolor.color(document.getElementById('couleur'), {});myPicker.fromString('" . $status->couleur . "')");
	}
	if($status->couleur != ''){
		$_SESSION['couleurExStatus']=$status->couleur;
	}
	
	return $objResponse->getXML();
}


function submitFormStatus($status_id, $new_status_id, $nom, $commentaire, $affichage, $pourcentage, $couleur, $priorite) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if ($new_status_id == "" || $nom == "") {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_erreur_status_idnom_vide')));
		return $objResponse;
	}
	
	$couleur=str_replace('#','',$couleur);
	if(strlen($couleur) != 6) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_saisirCouleur')));
		return $objResponse;
	}
	
	$status = new Status();
	if($status_id != '') {
		$status->db_load(array('status_id', '=', $status_id));
	} else {
		$status->status_id = $new_status_id;
	}
	$status->nom = $nom;
	$status->commentaire = ($commentaire != '' ? $commentaire : null);
	$status->affichage = ($affichage != '' ? $affichage : null);
	$status->pourcentage = $pourcentage;
	$status->couleur = ($couleur != '' ? $couleur : NULL);
	$status->priorite = $priorite;

	if(!$status->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('status.php');
	return $objResponse;
}


function supprimerStatus($status_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$status = new Status();
	if(!$status->db_load(array('status_id', '=', $status_id))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
		$objResponse->addScript('location.reload();');
	}

	$status->db_delete();

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('status.php');
	return $objResponse;
}


function modifRessource($ressource_id = '') {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('ressources_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$ressource = new Ressource();
	if($ressource_id != '') {
		$ressource->db_load(array('ressource_id', '=', $ressource_id));
	}

	$smarty->assign('ressource', $ressource->getSmartyData());

	$listeLieux = new GCollection('Lieu');
	$listeLieux->db_load(array(), array('nom' => 'ASC'));
	$smarty->assign('listeLieux', $listeLieux->getSmartyData());

	$groupes = new GCollection('User_groupe');
	$groupes->db_load(array(), array('nom' => 'ASC'));
	$smarty->assign('teams', $groupes->getSmartyData());

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('menuRessources')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('ressource_form.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	return $objResponse->getXML();
}


function submitFormRessource($ressource_id, $new_ressource_id, $nom, $commentaire, $exclusif, $lieu_id, $team_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('ressources_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(trim($lieu_id) == '') {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('place_mandate')));
		return $objResponse;
	}

	if(trim($new_ressource_id) == '' || !preg_match('<^[A-Za-z0-9]*$>', $new_ressource_id)) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_erreur_ressource_idnom_vide')));
		return $objResponse;
	}

	if ($new_ressource_id == "" || $nom == "") {
		$objResponse->addScript("document.getElementById('butSubmitPeriode').disabled=false;");
		$objResponse->addScript("document.getElementById('divPatienter').style.display='none';");
		$objResponse->addAlert(addslashes($smarty->get_config_vars('js_erreur_ressource_idnom_vide')));
		return $objResponse;
	}

	$ressource = new Ressource();
	if($ressource_id != '') {
		$ressource->db_load(array('ressource_id', '=', $ressource_id));
	} else {
		$ressource->ressource_id = $new_ressource_id;
	}

	$ressource->nom = $nom;
	$ressource->commentaire = ($commentaire != '' ? $commentaire : null);
	$ressource->user_groupe_id = ($team_id != '' ? $team_id : null);
	$ressource->lieu_id = $lieu_id;
	if ($exclusif=='true')
    {$ressource->exclusif=1;
    }else $ressource->exclusif=0;

	if(!$ressource->db_save()) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
		return $objResponse;
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('ressources.php');
	return $objResponse;
}


function supprimerRessource($ressource_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('ressources_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$ressource = new Ressource();
	if(!$ressource->db_load(array('ressource_id', '=', $ressource_id))) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('erreur')));
		$objResponse->addScript('location.reload();');
	}

	$ressource->db_delete();

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('ressources.php');
	return $objResponse;
}


function usersBulkRightsForm() {
	$objResponse = new xajaxResponse('ISO-8859-1');
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	// recuperation de la liste des utilisateurs pour filtre sur users
	$usersFiltre = new GCollection('User');
	$sql = "SELECT pu.*, pug.nom AS groupe_nom
			FROM planning_user pu ";
	$sql .= " LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id
			WHERE visible_planning = 'oui' ";
	$sql .=	" ORDER BY groupe_nom, pu.nom";
	$usersFiltre->db_loadSQL($sql);
	$smarty->assign('listeUsers', $usersFiltre->getSmartyData());

	$objResponse->addScript('jQuery("#myBigModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('menuUsersBulkRights')) . '")');
	$objResponse->addScript('jQuery("#myBigModal .modal-body").html("' . xajaxFormat($smarty->getHtml('user_bulk_rights_form.tpl')) . '")');

	// Initialize select2 box by generic function
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");

	$objResponse->addScript('jQuery("#myBigModal").modal()');

	return $objResponse->getXML();
}


function usersBulkRightsSubmit($bulk_users_ids, $droits, $specific_users_ids) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('users_manage_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	if(count($bulk_users_ids) == 0) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('usersBulkRights_error1')));
		return $objResponse;
	}

	foreach ($bulk_users_ids as $bulk_users_id) {
		$userTmp = new User();
		if(!$userTmp->db_load(array('user_id', '=', $bulk_users_id))) {
			continue;
		}

		$userTmp->setDroits($droits);
		$test = $userTmp->check();
		if($test !== TRUE) {
			if(!is_array($test)) {
				$objResponse->addAlert(addslashes($smarty->get_config_vars($test)));
				return $objResponse;
			}
		}

		if(!$userTmp->db_save()) {
			$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
			return $objResponse;
		}

		$userTmp->updateRightsOnUsers($specific_users_ids);
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addScript('location.reload();');
	return $objResponse;
}

function allocateResourceToUser($resource_name, $resource_id = '', $allocationId = '') {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('ressources_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$date = new DateTime();
	$user = new GCollection('User');
	$user->db_load(array('user_id', '<>', 'publicspl'), array('nom' => 'ASC'));
	$smarty->assign('users', $user->getSmartyData());

	$allocation = new ResourceAllocation();
	if($allocationId != '') {
		$allocation->db_load(array('allocation_id', '=', (int)$allocationId), array());
	}
	$smarty->assign('allocation', $allocation->getSmartyData());
	$smarty->assign('resourcename', $resource_name);
	$smarty->assign('resource_id', $resource_id);
	$smarty->assign('allocation_id', $allocationId);

	$objResponse->addScript('jQuery("#myModal .modal-header h3").html("' . addslashes($smarty->get_config_vars('resourceModalHeader')) . '")');
	$objResponse->addScript('jQuery("#myModal .modal-body").html("' . xajaxFormat($smarty->getHtml('resource_allocation.tpl')) . '")');
	$objResponse->addScript('jQuery("#myModal").modal()');
	$objResponse->addScript("initselect2('$lang','".$smarty->get_config_vars('choix_option')."')");
	
	return $objResponse->getXML();
}

function saveResourceAllocationToUser($allocation_id='', $resource_id, $user_id) {
	$objResponse = new xajaxResponse();
	$smarty = new MySmarty();

	$user = new User();
	if($user->chargerUserFromSession() !== TRUE || !$user->checkDroit('ressources_all')) {
		$objResponse->addAlert(addslashes($smarty->get_config_vars('ajax_droitsInsuffisants')));
		$objResponse->addScript('location.reload();');
		return $objResponse;
	}

	$today = new DateTime();
	$formateDate = userdate2sqldate($today->format(CONFIG_DATE_LONG));

	$resource = new ResourceAllocation();

	$sql = "SELECT allocation_id from planning_resource_allocation where 
			user_id='" . $user_id . "' and allocated_date='" . $formateDate . "'";
	$resource->db_loadSQL($sql);

	if($resource->allocation_id != 0 && $allocation_id != $resource.allocation_id) {
		$objResponse->addAlert('User already allocated to different seat');
		return $objResponse;
	}

	$resource = new ResourceAllocation();
	if($allocation_id != '') {
		$resource->db_load(array('allocation_id', '=', $allocation_id));
	} else {
		$resource->created_by = $_SESSION['user_id'];
		$resource->created_date = date('Y-m-d H:i:s');
	}
	$resource->modified_by = $_SESSION['user_id'];
	$resource->modified_date = date('Y-m-d H:i:s');

	$resource->resource_id = $resource_id;
	$resource->user_id = $user_id;
	$resource->allocated_date = $formateDate;

	if($resource->user_id != '') {
		if(!$resource->db_save()) {
			$objResponse->addAlert(addslashes($smarty->get_config_vars('changeNotOK')));
			return $objResponse;
		}
	} else {
		$resource->db_delete();
	}

	$_SESSION['message'] = 'changeOK';
	$objResponse->addRedirect('seats.php');
	return $objResponse;
}

$xajax->processRequests();
?>