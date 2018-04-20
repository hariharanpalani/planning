<?php
// Accès public
if ((CONFIG_SOPLANNING_OPTION_ACCES == 1) && isset($_GET['public']))
{
  $_SESSION['public']=1;
  $_SESSION['user_id']='publicspl';
}
// Accès public avec clé
if ( CONFIG_SOPLANNING_OPTION_ACCES == 2  && isset($_GET['public']) && isset($_GET['cle']))
{
  if (strcmp($_GET['cle'],CONFIG_SECURE_KEY)==0)
  {
   $_SESSION['public']=1;
   $_SESSION['user_id']='publicspl';
  }
}
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '') {
	$_SESSION['message'] = 'erreur_fin_connexion';
	header('Location: ' . BASE);
	exit;
  }

 $user = New User();
 if(!$user->db_load(array('user_id', '=', $_SESSION['user_id']))) {
	$_SESSION['message'] = 'erreur_fin_connexion';
	header('Location: ' . BASE);
	exit;
 }

if(isset($smarty)) {
    // Si on n'est pas dans un cas de page publique
	$userdata=$user->getSmartyData();
	//if (isset($_SESSION['public']) && ($_SESSION['public'] == 1) && (CONFIG_SOPLANNING_OPTION_VISITEUR == 1))
	//{  			
	//  $userdata['droits']='["tasks_modify_all","tasks_view_all_projects"]';
	//  $userdata['tabDroits']=array(0=>'tasks_modify_all',1=>'tasks_view_all_projects');
	//} 	
	$smarty->assign('user', $userdata);
    $_SESSION['preferences']=$userdata['tabPreferences'];
	$version = new Version();
	$infoVersion = $version->getVersion();
	$smarty->assign('infoVersion', $infoVersion);
}

?>