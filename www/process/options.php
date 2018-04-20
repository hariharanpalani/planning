<?php

require 'base.inc';
require BASE . '/../config.inc';

require BASE . '/../includes/header.inc';

$smarty = new MySmarty();

if(!$user->checkDroit('parameters_all')) {
	$_SESSION['message'] = 'droitsInsuffisants';
	header('Location: ../index.php');
	exit;
}


if(isset($_POST['SOPLANNING_TITLE'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_TITLE'));
	$config->valeur = ($_POST['SOPLANNING_TITLE'] != '' ? $_POST['SOPLANNING_TITLE'] : NULL);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_URL'));
	$config->valeur = ($_POST['SOPLANNING_URL'] != '' ? $_POST['SOPLANNING_URL'] : NULL);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if((isset($_FILES['SOPLANNING_LOGO']) && !empty($_FILES['SOPLANNING_LOGO']['name'])) || isset($_POST['SOPLANNING_LOGO_SUPPRESSION'])) {	
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_LOGO'));
	if (isset($_POST['SOPLANNING_LOGO_SUPPRESSION']))
	{
		$config->valeur = NULL;
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
		# Effacement de l'ancien logo
		if (!empty($_POST['old_logo']))
		{
			if (!is_dir(BASE.'/upload/logo/'.$_POST['old_logo']) && file_exists(BASE.'/upload/logo/'.$_POST['old_logo'])) unlink(BASE.'/upload/logo/'.$_POST['old_logo']);
		}
	}else
	{
		# Vérification que le répertoire upload/logo est accessible en écriture
		if(!is__writable(BASE.'/upload/logo') && !is__writable(ini_get('upload_tmp_dir')))
		{
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;			
		}
		$res=upload_image(BASE.'/upload/logo',$_FILES['SOPLANNING_LOGO']);
		if ($res != "")
		{
			switch ($res)
			{
			case 1 : $_SESSION['message'] = 'changeNotOKImageSize';break;
			case 2 : $_SESSION['message'] = 'changeNotOKImageRepertoire';break;
			default : $_SESSION['message'] = 'changeNotOKImageErreur';break;
			}
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
		$config->valeur = $_FILES['SOPLANNING_LOGO']['name'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
		# Effacement de l'ancien logo
		if ($_POST['old_logo'] <> $_FILES['SOPLANNING_LOGO']['name'])
		{
		 if (file_exists(BASE.'/upload/logo/'.$_POST['old_logo'])) unlink(BASE.'/upload/logo/'.$_POST['old_logo']);
		}
	}
}

if(isset($_POST['SOPLANNING_THEME'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_THEME'));
	$config->valeur=$_POST['SOPLANNING_THEME'];
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['SOPLANNING_OPTION_ACCES'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_OPTION_ACCES'));
	$config->valeur=$_POST['SOPLANNING_OPTION_ACCES'];
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['CONFIG_SECURE_KEY'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SECURE_KEY'));
	$config->valeur=$_POST['CONFIG_SECURE_KEY'];
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['SOPLANNING_OPTION_LIEUX'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_OPTION_LIEUX'));
	if($_POST['SOPLANNING_OPTION_LIEUX'] == 0 || $_POST['SOPLANNING_OPTION_LIEUX'] == 1) {
		$config->valeur = $_POST['SOPLANNING_OPTION_LIEUX'];
	} else {
		$config->valeur = 0;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['SOPLANNING_OPTION_RESSOURCES'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_OPTION_RESSOURCES'));
	if($_POST['SOPLANNING_OPTION_RESSOURCES'] == 0 || $_POST['SOPLANNING_OPTION_RESSOURCES'] == 1) {
		$config->valeur = $_POST['SOPLANNING_OPTION_RESSOURCES'];
	} else {
		$config->valeur = 0;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['SOPLANNING_OPTION_TACHES'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_OPTION_TACHES'));
	if($_POST['SOPLANNING_OPTION_TACHES'] == 0 || $_POST['SOPLANNING_OPTION_TACHES'] == 1) {
		$config->valeur = $_POST['SOPLANNING_OPTION_TACHES'];
	} else {
		$config->valeur = 0;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['SOPLANNING_OPTION_VISITEUR_checkbox'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_OPTION_VISITEUR'));
	if ($_POST['SOPLANNING_OPTION_VISITEUR_checkbox']=='on')
	{
		$droits='["tasks_modify_all","tasks_view_all_projects"]';
		$config->valeur=1;
	}
	else{
		$droits='["tasks_readonly","tasks_view_all_projects"]';
		$config->valeur=0;
	}
	// on reassigne les droits du user guest
	$sql = "UPDATE planning_user
			SET droits = '$droits'
			WHERE user_id='publicspl'";
	db_query($sql);
	
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}else
{
	$config = new Config();
	$config->db_load(array('cle', '=', 'SOPLANNING_OPTION_VISITEUR'));
	$droits='["tasks_readonly","tasks_view_all_projects"]';
	$config->valeur=0;
	// on reassigne les droits du user guest
	$sql = "UPDATE planning_user
			SET droits = '$droits'
			WHERE user_id='publicspl'";
	db_query($sql);
	
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}	
}

if(isset($_POST['DAYS_INCLUDED'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'DAYS_INCLUDED'));
	$config->valeur = implode(',', $_POST['DAYS_INCLUDED']);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

//if(isset($_POST['PLANNING_DATE_FORMAT'])) {
//	$config = new Config();
//	$config->db_load(array('cle', '=', 'PLANNING_DATE_FORMAT'));
//	$config->valeur = $_POST['PLANNING_DATE_FORMAT'];
//	echo $_POST['PLANNING_DATE_FORMAT'];exit;
//	if(!$config->db_save()) {
//		$_SESSION['message'] = 'changeNotOK';
//		header('Location: ../options.php');
//		exit;
//	}
//}

if(isset($_POST['HOURS_DISPLAYED'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'HOURS_DISPLAYED'));
	$config->valeur = implode(',', $_POST['HOURS_DISPLAYED']);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DEFAULT_NB_MONTHS_DISPLAYED'])) {
	if(is_numeric($_POST['DEFAULT_NB_MONTHS_DISPLAYED']) && round($_POST['DEFAULT_NB_MONTHS_DISPLAYED']) > 0) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DEFAULT_NB_MONTHS_DISPLAYED'));
		$config->valeur = $_POST['DEFAULT_NB_MONTHS_DISPLAYED'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
		// on change aussi la valeur en session
		$_SESSION['nb_mois'] = $config->valeur;
	} else {
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_nbMoisDefaut_erreur');
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DEFAULT_NB_DAYS_DISPLAYED'])) {
	if(is_numeric($_POST['DEFAULT_NB_DAYS_DISPLAYED']) && round($_POST['DEFAULT_NB_DAYS_DISPLAYED']) > 0) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DEFAULT_NB_DAYS_DISPLAYED'));
		$config->valeur = $_POST['DEFAULT_NB_DAYS_DISPLAYED'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
		// on change aussi la valeur en session
		$_SESSION['nb_jours'] = $config->valeur;
	} else {
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_nbjoursDefaut_erreur');
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DEFAULT_NB_ROWS_DISPLAYED'])) {
	if(is_numeric($_POST['DEFAULT_NB_ROWS_DISPLAYED']) && round($_POST['DEFAULT_NB_ROWS_DISPLAYED']) > 0) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DEFAULT_NB_ROWS_DISPLAYED'));
		$config->valeur = $_POST['DEFAULT_NB_ROWS_DISPLAYED'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
		// on change aussi la valeur en session
		$_SESSION['nb_lignes'] = $config->valeur;
	} else {
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_nbLignes_erreur');
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DEFAULT_NB_PAST_DAYS'])) {
	if(is_numeric($_POST['DEFAULT_NB_PAST_DAYS']) && round($_POST['DEFAULT_NB_PAST_DAYS']) >= 0) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DEFAULT_NB_PAST_DAYS'));
		$config->valeur = $_POST['DEFAULT_NB_PAST_DAYS'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	} else {
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_nbJoursPasses_erreur');
		header('Location: ../options.php');
		exit;
	}
}
if(isset($_POST['PLANNING_HIDE_WEEKEND_TASK'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_HIDE_WEEKEND_TASK'));
	if($_POST['PLANNING_HIDE_WEEKEND_TASK'] == 0 || $_POST['PLANNING_HIDE_WEEKEND_TASK'] == 1) {
		$config->valeur = $_POST['PLANNING_HIDE_WEEKEND_TASK'];
	} else {
		$config->valeur = 0;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}
if(isset($_POST['PLANNING_LINE_HEIGHT'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_LINE_HEIGHT'));
	if(is_numeric($_POST['PLANNING_LINE_HEIGHT']) && round($_POST['PLANNING_LINE_HEIGHT']) > 0) {
		$config->valeur = $_POST['PLANNING_LINE_HEIGHT'];
	} else {
		$config->valeur = null;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['PLANNING_COL_WIDTH'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_COL_WIDTH'));
	if(is_numeric($_POST['PLANNING_COL_WIDTH']) && round($_POST['PLANNING_COL_WIDTH']) > 0) {
		$config->valeur = $_POST['PLANNING_COL_WIDTH'];
	} else {
		$config->valeur = null;
	}
	if (($_POST['PLANNING_COL_WIDTH'] < MIN_CELL_SIZE) or ($_POST['PLANNING_COL_WIDTH'] > MAX_CELL_SIZE))
	{
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_largeurColonne_erreur');
		header('Location: ../options.php');
		exit;		
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['PLANNING_COL_WIDTH_LARGE'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_COL_WIDTH_LARGE'));
	if(is_numeric($_POST['PLANNING_COL_WIDTH_LARGE']) && round($_POST['PLANNING_COL_WIDTH_LARGE']) > 0) {
		$config->valeur = $_POST['PLANNING_COL_WIDTH_LARGE'];
	} else {
		$config->valeur = null;
	}
	if (($_POST['PLANNING_COL_WIDTH_LARGE'] < MIN_CELL_SIZE) or ($_POST['PLANNING_COL_WIDTH_LARGE'] > MAX_CELL_SIZE))
	{
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_largeurColonneLarge_erreur');
		header('Location: ../options.php');
		exit;		
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['PLANNING_CODE_WIDTH'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_CODE_WIDTH'));
	if(is_numeric($_POST['PLANNING_CODE_WIDTH']) && round($_POST['PLANNING_CODE_WIDTH']) > 0) {
		$config->valeur = $_POST['PLANNING_CODE_WIDTH'];
	} else {
		$config->valeur = null;
	}
	if (($_POST['PLANNING_CODE_WIDTH'] < MIN_CODE_SIZE) or ($_POST['PLANNING_CODE_WIDTH'] > MAX_CODE_SIZE))
	{
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_largeurCode_erreur');
		header('Location: ../options.php');
		exit;		
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['PLANNING_CODE_WIDTH_LARGE'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_CODE_WIDTH_LARGE'));
	if(is_numeric($_POST['PLANNING_CODE_WIDTH_LARGE']) && round($_POST['PLANNING_CODE_WIDTH_LARGE']) > 0) {
		$config->valeur = $_POST['PLANNING_CODE_WIDTH_LARGE'];
	} else {
		$config->valeur = null;
	}
	if (($_POST['PLANNING_CODE_WIDTH_LARGE'] < MIN_CODE_SIZE) or ($_POST['PLANNING_CODE_WIDTH_LARGE'] > MAX_CODE_SIZE))
	{
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_largeurCodeLarge_erreur');
		header('Location: ../options.php');
		exit;		
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}
if(isset($_POST['PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY'));
	if($_POST['PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY'] == 0 || $_POST['PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY'] == 1) {
		$config->valeur = $_POST['PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY'];
	} else {
		$config->valeur = 0;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['PLANNING_AFFICHAGE_STATUS'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_AFFICHAGE_STATUS'));
	$config->valeur = $_POST['PLANNING_AFFICHAGE_STATUS'];
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['REFRESH_TIMER'])) {
	if(is_numeric($_POST['REFRESH_TIMER']) && round($_POST['REFRESH_TIMER']) > 0) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'REFRESH_TIMER'));
		$config->valeur = $_POST['REFRESH_TIMER'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	} else {
		$_SESSION['message'] = 'changeNotOK';
		$_SESSION['erreur'] = $smarty->get_config_vars('options_raffraichissement_erreur');
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['PROJECT_COLORS_POSSIBLE'])) {
	if(strlen($_POST['PROJECT_COLORS_POSSIBLE']) == 0 || strlen($_POST['PROJECT_COLORS_POSSIBLE']) > 6) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'PROJECT_COLORS_POSSIBLE'));
		if(strlen($_POST['PROJECT_COLORS_POSSIBLE']) == 0) {
			$config->valeur = null;
		} else {
			$config->valeur = $_POST['PROJECT_COLORS_POSSIBLE'];
		}
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	} else {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DEFAULT_PERIOD_LINK'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'DEFAULT_PERIOD_LINK'));
	if(strlen($_POST['DEFAULT_PERIOD_LINK']) == 0) {
		$config->valeur = null;
	} else {
		$config->valeur = $_POST['DEFAULT_PERIOD_LINK'];
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['LOGOUT_REDIRECT'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'LOGOUT_REDIRECT'));
	if(strlen($_POST['LOGOUT_REDIRECT']) == 0) {
		$config->valeur = null;
	} else {
		$config->valeur = $_POST['LOGOUT_REDIRECT'];
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DURATION_DAY'])) {
	$TotalJourExplode = explode (':',$_POST['DURATION_DAY']);
	$TotalJourH=$TotalJourExplode[0];
	$TotalJourM=$TotalJourExplode[1];
	if((is_numeric($TotalJourH) && round($TotalJourH) > 0)&&(is_numeric($TotalJourM) && round($TotalJourM) >= 0)) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DURATION_DAY'));
		$config->valeur = $_POST['DURATION_DAY'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	} else {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DURATION_AM'])) {
	$TotalJourAMExplode = explode (':',$_POST['DURATION_AM']);
	$TotalJourAMH=$TotalJourAMExplode[0];
	$TotalJourAMM=$TotalJourAMExplode[1];
	if((is_numeric($TotalJourAMH) && round($TotalJourAMH) > 0)&&(is_numeric($TotalJourAMM) && round($TotalJourAMM) >= 0)) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DURATION_AM'));
		$config->valeur = $_POST['DURATION_AM'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	} else {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['DURATION_PM'])) {
	$TotalJourPMExplode = explode (':',$_POST['DURATION_PM']);
	$TotalJourPMH=$TotalJourPMExplode[0];
	$TotalJourPMM=$TotalJourPMExplode[1];
	if((is_numeric($TotalJourPMH) && round($TotalJourPMH) > 0)&&(is_numeric($TotalJourPMM) && round($TotalJourPMM) >= 0)) {
		$config = new Config();
		$config->db_load(array('cle', '=', 'DURATION_PM'));
		$config->valeur = $_POST['DURATION_PM'];
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	} else {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['SMTP_HOST'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'SMTP_HOST'));
	$config->valeur = ($_POST['SMTP_HOST'] != '' ? $_POST['SMTP_HOST'] : NULL);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
	$config = new Config();
	$config->db_load(array('cle', '=', 'SMTP_PORT'));
	$config->valeur = ($_POST['SMTP_PORT'] != '' ? $_POST['SMTP_PORT'] : NULL);
	if(!$config->db_save()) {
		die;
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
	$config = new Config();
	$config->db_load(array('cle', '=', 'SMTP_SECURE'));
	$config->valeur = ($_POST['SMTP_SECURE'] != '' ? $_POST['SMTP_SECURE'] : NULL);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
	$config = new Config();
	$config->db_load(array('cle', '=', 'SMTP_FROM'));
	$config->valeur = ($_POST['SMTP_FROM'] != '' ? $_POST['SMTP_FROM'] : NULL);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
	$config = new Config();
	$config->db_load(array('cle', '=', 'SMTP_LOGIN'));
	$config->valeur = ($_POST['SMTP_LOGIN'] != '' ? $_POST['SMTP_LOGIN'] : NULL);
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
	if($_POST['SMTP_PASSWORD'] != 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX') {
		// hack pour ne pas écraser le password si submit tel quel
		$config = new Config();
		$config->db_load(array('cle', '=', 'SMTP_PASSWORD'));
		$config->valeur = ($_POST['SMTP_PASSWORD'] != '' ? $_POST['SMTP_PASSWORD'] : NULL);
		if(!$config->db_save()) {
			$_SESSION['message'] = 'changeNotOK';
			header('Location: ../options.php');
			exit;
		}
	}
}

if(isset($_POST['PLANNING_REPEAT_HEADER'])) {
	$config = new Config();
	$config->db_load(array('cle', '=', 'PLANNING_REPEAT_HEADER'));
	if(is_numeric($_POST['PLANNING_REPEAT_HEADER']) && round($_POST['PLANNING_REPEAT_HEADER']) > 0) {
		$config->valeur = $_POST['PLANNING_REPEAT_HEADER'];
	} else {
		$config->valeur = null;
	}
	if(!$config->db_save()) {
		$_SESSION['message'] = 'changeNotOK';
		header('Location: ../options.php');
		exit;
	}
}

if(isset($_POST['mailTestDestinataire'])) {
	echo "<html><head><title>Test smtp</title><style>body{padding:10px;}</style></head><body><h2>Test smtp</h2><hr><pre>";
	$mail = new Mailer($_POST['mailTestDestinataire'], 'SOPLANNING - test email', 'OK');
	if(isset($_POST['smtp_traces'])) {
		$mail->SMTPDebug = 3;
	}
	
	if(!$mail->send()) {
		echo 'error while sending the email :';
		echo '<pre></body></html>';
		die;
	}
	echo "</pre>";
	if(isset($_POST['smtp_traces'])) {
		echo '<hr>' . $smarty->get_config_vars('options_envoyerMailTest_envoye');
		echo '<br><br><a href="../options.php">' . $smarty->get_config_vars('back_to_soplanning') . '<a>';
		exit;
	}

	$_SESSION['message'] = 'options_envoyerMailTest_envoye';
	header('Location: ../options.php');
	exit;
}

$_SESSION['message'] = 'changeOK';
header('Location: ../options.php');
exit;

?>