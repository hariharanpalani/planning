<?php

@ini_set('memory_limit', '256M');
@set_time_limit(1000);

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$html = '';
$js = '';

$joursFeries = getJoursFeries();

// PARAM�TRES ////////////////////////////////
$dateDebut = new DateTime();
$dateFin = new DateTime();
$dateDebut->setDate(substr($_SESSION['date_debut_affiche'],6,4), substr($_SESSION['date_debut_affiche'],3,2), substr($_SESSION['date_debut_affiche'],0,2));
$dateFin->setDate(substr($_SESSION['date_fin_affiche'],6,4), substr($_SESSION['date_fin_affiche'],3,2), substr($_SESSION['date_fin_affiche'],0,2));

$nbLignes = $_SESSION['nb_lignes'];
$pageLignes = $_SESSION['page_lignes'];

if(isset($_GET['pdf_orientation'])) {
	setcookie('pdf_orientation', $_GET['pdf_orientation'], 0, '/');
	$pdf_orientation = $_GET['pdf_orientation'];
} else {
	$pdf_orientation = 'paysage';
}

if(isset($_GET['pdf_format'])) {
	setcookie('pdf_format', $_GET['pdf_format'], 0, '/');
	$pdf_format = $_GET['pdf_format'];
} else {
	$pdf_format = 'A4';
}

$masquerLigneVide = $_SESSION['masquerLigneVide'];

$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);

// FIN PARAM�TRES ////////////////////////////////

// on se cale sur les mois entiers
$dateDebut->modify('-' . ($dateDebut->format('d') - 1) . ' day');

$tmpDate = clone $dateDebut;
$tmpMois = $smarty->get_config_vars('month_' . $tmpDate->format('n')) . ' ' . $tmpDate->format('Y');



// recuperation des projets couvrant la p�riode, pour le filtre de projets
$projetsFiltre = new GCollection('Projet');
$sql = "SELECT distinct pp.*, pg.nom AS groupe_nom
		FROM planning_projet pp
		INNER JOIN planning_periode pd ON pp.projet_id = pd.projet_id
		LEFT JOIN planning_groupe AS pg ON pp.groupe_id = pg.groupe_id ";
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'�quipe de ce user
	$sql .= " INNER JOIN planning_user AS pu ON pd.user_id = pu.user_id ";
}
$sql .= "WHERE (
			(pd.date_debut <= '" . $dateDebut->format('Y-m-d') . "'
			AND pd.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(pd.date_debut <= '" . $dateFin->format('Y-m-d') . "'
			AND pd.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
	)";
if($user->checkDroit('tasks_view_own_projects')) {
	// on filtre sur les projets dont le user courant est propri�taire ou assign�
	$sql .= " AND (pp.createur_id = '" . $user->user_id . "' OR pd.user_id = '" . $user->user_id . "')";
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	// on filtre sur les projets de l'�quipe de ce user
	$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
}
if ($user->checkDroit('tasks_view_only_own')) {
	$sql .= " AND pd.user_id = " . val2sql($user->user_id);
}
$sql .= "	GROUP BY pp.nom, pp.projet_id
			ORDER BY pp.groupe_id, pp.nom";
$projetsFiltre->db_loadSQL($sql);
$smarty->assign('listeProjets', $projetsFiltre->getSmartyData());
if($user->checkDroit('tasks_view_own_projects')) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$listeProjetsPossibles = $projetsFiltre->get('projet_id');
}

// CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERS�)
if($_SESSION['inverserUsersProjets']) {
	$lines = new GCollection('Projet');
	$sql = "SELECT *
			FROM planning_projet
			WHERE 0=0 ";
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if($user->checkDroit('tasks_view_own_projects')) {
		$sql .= " AND projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
	}
	$sql .= " ORDER BY livraison";
} else {
	$lines = new GCollection('User');
	$sql = "SELECT * FROM planning_user ";
	if($user->checkDroit('tasks_view_specific_users')) {
		$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_user.user_id AND rou.owner_id = " . val2sql($user->user_id);
	}
	$sql .= "	WHERE visible_planning = 'oui'";
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	$sql .= " ORDER BY nom";
}
$lines->db_loadSQL($sql);

// FIN CHARGEMENT DES LIGNES (USERS SI NORMAL, PROJET SI INVERS�)

$joursOccupes = array();

while($lineTmp = $lines->fetch()) {
	if( $_SESSION['inverserUsersProjets']) {
		$ligneId = $lineTmp->projet_id;
	} else {
		$ligneId = $lineTmp->user_id;
	}

	// on charge les jours occup�s pour cette ligne
	$periodes = new GCollection('Periode');
	if( $_SESSION['inverserUsersProjets']) {
		$sql = "SELECT planning_periode.*, planning_user.*, planning_projet.createur_id
				FROM planning_periode
				INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id
				INNER JOIN planning_projet ON planning_projet.projet_id = planning_periode.projet_id ";
		if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
			// on filtre sur les projets de l'�quipe de ce user
			$sql .= " INNER JOIN planning_user AS pu ON planning_periode.user_id = pu.user_id ";
		}
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_user.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}
		$sql .= " WHERE planning_periode.projet_id = '" . $ligneId . "'";
	} else {
		$sql = "SELECT planning_periode.*, planning_projet.*
				FROM planning_periode
					INNER JOIN planning_projet ON planning_periode.projet_id = planning_projet.projet_id ";
		if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
			// on filtre sur les projets de l'�quipe de ce user
			$sql .= " INNER JOIN planning_user AS pu ON planning_periode.user_id = pu.user_id ";
		}
		if($user->checkDroit('tasks_view_specific_users')) {
			$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_user.user_id AND rou.owner_id = " . val2sql($user->user_id);
		}
		$sql .= " WHERE planning_periode.user_id = '" . $ligneId . "'";
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
		// on filtre sur les projets de l'�quipe de ce user
		$sql .= " AND pu.user_groupe_id = " . $user->user_groupe_id;
	}
	if ($user->checkDroit('tasks_view_only_own')) {
		$sql .= " AND planning_periode.user_id = " . val2sql($user->user_id);
	}
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
	}
	if(count($_SESSION['filtreUser']) > 0) {
		$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	}
	if($_SESSION['filtreTexte'] != "") {
		$sql.= " AND (planning_periode.notes LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.lien LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." )";
	}
	$sql.= " ORDER BY planning_periode.date_debut";
	$periodes->db_loadSQL($sql);
	//echo $sql . ' : ' . $periodes->getCount() . '<br>' ;

	$ordreJourPrec = array();

	// pour chaque p�riode de cette ligne, on remplie le tableau des jours occup�s
	while ($periode = $periodes->fetch()) {
		$infosJour = $periode->getData();
		if( $_SESSION['inverserUsersProjets']) {
			$infosJour['projet_nom'] = $lineTmp->nom;
			$infosJour['user_nom'] = $periode->nom;
		} else {
			$infosJour['projet_nom'] = $periode->nom;
			$infosJour['user_nom'] = $lineTmp->nom;
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

	$ordreJourCourant = array();
}

reset($joursOccupes);

$html .= '<table border="0" cellpadding="0" cellspacing="0">' . CRLF;
$html .= '<tr>' . CRLF;

$tmpDate = clone $dateDebut;
while ($tmpDate <= $dateFin) {
	$html .= '<td valign="top">' . CRLF;
	$html .= '<table class="calendarContent">' . CRLF;
	$html .= '<tr>' . CRLF;
	$html .= '<td colspan="3" class="calMonth">' . $tmpMois . '</td>' . CRLF;
	$html .= '</tr>' . CRLF;
	while (0==0) {
		$html .= '<tr>' . CRLF;
		$html .= '<td class="calCell"';
		if (in_array($tmpDate->format('w'), $DAYS_INCLUDED) && !in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
			$html .= ' class="calOpenDay"';
		} else {
			$html .= ' class="calCloseDay"';
		}
		$html .= '>' . strtoupper(substr($smarty->get_config_vars('day_' . $tmpDate->format('w')), 0, 1)) . '</td>' . CRLF;
		$html .= '<td class="calCell"';
		if (in_array($tmpDate->format('w'), $DAYS_INCLUDED) && !in_array($tmpDate->format('Y-m-d'), $joursFeries)) {
			$html .= ' class="calOpenDay"';
		} else {
			$html .= ' class="calCloseDay"';
		}
		$html .= '>' . $tmpDate->format('j') . '</td>' . CRLF;
		$html .= '<td class="w40">' . CRLF;
		// on boucle pour afficher les cases de ce jour

		if (isset($joursOccupes[$tmpDate->format('Y-m-d')])) {
			foreach ($joursOccupes[$tmpDate->format('Y-m-d')] as $jour) {

				// couleur du texte dans la case, selon la couleur de fond de la case
				$couleurTexte = buttonFontColor('#' . $jour['couleur']);

				// la case avec le code du projet
				$html .= '<div id="c_' . $jour['periode_id'] . '_' . $tmpDate->format('Ymd') . '" class="calCellProject" style="color:' . $couleurTexte . ';background-color:#' . $jour['couleur'] . ';';
				if($jour['duree'] > 0 && $jour['duree'] < 8) {
					if($jour['lien'] != '') {
						//$html .= 'background-image:url(\'images/half_link.gif\'); background-repeat:no-repeat; background-position:bottom right;';
					} else {
						//$html .= 'background-image:url(\'images/half.gif\'); background-repeat:no-repeat; background-position:bottom left;';
					}
				} else {
					if($jour['lien'] != '') {
						//$html .= 'background-image:url(\'images/link.jpg\'); background-repeat:no-repeat; background-position:bottom right;';
					}
				}
				if( $_SESSION['inverserUsersProjets']) {
					$nom = substr($jour['user_id'], 0, 5);
				} else {
					$nom = substr($jour['projet_id'], 0, 5);
				}
				$html .= '">' . $nom . '</div>';

			}

		}

		$html .= '</td>' . CRLF;
		$html .= '</tr>' . CRLF;
		$tmpDate->modify('+1 day');
		if(($smarty->get_config_vars('month_' . $tmpDate->format('n')) . ' ' . $tmpDate->format('Y')) != $tmpMois) {
			$tmpDate2 = clone $tmpDate;
			$tmpDate2->modify('-1 day');
			if($tmpDate2->format('j') < 31) {
				for($i=$tmpDate2->format('j');$i<31;$i++) {
					$html .= '<tr>' . CRLF;
					$html .= '<td class="calDisabledDay">&nbsp;</td>' . CRLF;
					$html .= '<td class="calDisabledDay">&nbsp;</td>' . CRLF;
					$html .= '<td class="calDisabledDay">&nbsp;</td>' . CRLF;
					$html .= '</tr>' . CRLF;
				}
			}
			break;
		}
	}
	$html .= '</table>' . CRLF;
	$html .= '</td>' . CRLF;
	$tmpMois = $smarty->get_config_vars('month_' . $tmpDate->format('n')) . ' ' . $tmpDate->format('Y');
}
$html .= '</tr>' . CRLF;
$html .= '</table>' . CRLF;

if($pdf_orientation == 'paysage') {
	$orientation = 'L';
} else {
	$orientation = 'P';
}
$html = '<page orientation="' . $orientation . '"><style>' . file_get_contents('assets/css/export_pdf_calendrier.css') .  file_get_contents('assets/css/themes/'.CONFIG_SOPLANNING_THEME) .'</style>' . $html . '</page>';

/*
if(isset($_GET['debug'])) {
	echo $html;
	die;
}
*/

require_once ('../html2pdf/html2pdf.class.php');
try
{
//	$html2pdf = new HTML2PDF($orientation, $pdf_format, 'fr', true, 'iso-8859-1');
	$html = utf8_encode($html);
	$html2pdf = new HTML2PDF($orientation, $pdf_format, 'fr', true);
	$html2pdf->setTestTdInOnePage(false);
	$html2pdf->pdf->SetDisplayMode('fullpage');
//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
	$html2pdf->writeHTML($html);
	$html2pdf->Output('soplanning-' . date('Y-m-d-H:i:s') . '.pdf');
}
catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}
