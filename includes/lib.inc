<?php

function date_diff2($date1, $date2) {
    return round(($date2->format('U') - $date1->format('U')) / (60*60*24));
}

function xajaxFormat($string) {
	return str_replace(array('"', "\r\n", "\n", "\'", "\\\\"), array('\"', "", "", "\\\'", "\\\\\\\\"), trim($string));
}

function afficherErreur($objResponse, $message) {
	$objResponse->addScript('win.destroy();');
	$objResponse->addScript('win = new Window({className: "charte1", title: "Message", width:430, height:200, destroyOnClose: true, recenterAuto:true});');
	$objResponse->addScript('win.setHTMLContent("' . xajaxFormat($message) . '");');
	$objResponse->addScript('win.showCenter();');
	return $objResponse->getXML();
}

// dates au format YYYY-MM-DD
function getNbJours($date_debut, $date_fin) {
	global $lang;
	$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);
	$joursFeriesTmp = getjoursFeries();
	$joursFeries = array();
	foreach($joursFeriesTmp as $b){
		$joursFeries[$b]=$b;
	}
	$nbJours = 1;
	$dateDebut = new DateTime();
	$dateDebut->setDate(substr($date_debut,0,4), substr($date_debut,5,2), substr($date_debut,8,2));
	while($dateDebut->format('Y-m-d') < $date_fin) {
		$dateDebut->modify('+1 days');
		if (in_array($dateDebut->format('w'), $DAYS_INCLUDED) && !isset($joursFeries[$dateDebut->format('Y-m-d')])) {
			$nbJours++;
		}
	}
	return $nbJours;
}

// dates au format YYYY-MM-DD
function getNbJoursFull($date_debut, $date_fin) {
	$nbJours = 1;
	$dateDebut = new DateTime();
	$dateDebut->setDate(substr($date_debut,0,4), substr($date_debut,5,2), substr($date_debut,8,2));
	while($dateDebut->format('Y-m-d') < $date_fin) {
		$dateDebut->modify('+1 days');
		$nbJours++;
	}
	return $nbJours;
}

function calculerDateFin($date_debut, $nbJours) {
	global $lang;
	$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);
	$joursFeries = getjoursFeries();

	$dateFin = new DateTime();
	$dateFin->setDate(substr($date_debut,0,4), substr($date_debut,5,2), substr($date_debut,8,2));
	$i = 1;
	while($i < $nbJours) {
		$dateFin->modify('+1 days');
		if (in_array($dateFin->format('w'), $DAYS_INCLUDED) && !in_array($dateFin->format('Y-m-d'), $joursFeries)) {
			$i++;
		}
	}
	return $dateFin->format('Y-m-d');
}

//modif ajout date debut
function calculerDateDebut($date_fin, $nbJours) {
	global $lang;
	$DAYS_INCLUDED = explode(',', CONFIG_DAYS_INCLUDED);
	$joursFeries = getjoursFeries();

	$dateDebut = new DateTime();
	$dateDebut->setDate(substr($date_fin,0,4), substr($date_fin,5,2), substr($date_fin,8,2));
	$i = 1;
	while($i < $nbJours) {
		$dateDebut->modify('-1 days');
		if (in_array($dateDebut->format('w'), $DAYS_INCLUDED) && !in_array($dateDebut->format('Y-m-d'), $joursFeries)) {
			$i++;
		}
	}
	return $dateDebut->format('Y-m-d');
}

// format hh:mm
function ajouterDuree($total, $ajout) {
	$dataTotal = explode(':', $total);
	$dataAjout = explode(':', $ajout);
	$heures = $dataTotal[0] + $dataAjout[0];
	$minutes = $dataTotal[1] + $dataAjout[1];
	if($minutes >= 60) {
		$heures += 1;
		$minutes -= 60;
	}
	$final = (strlen($heures) == 1 ? '0' . $heures : $heures) . ':' . (strlen($minutes) == 1 ? '0' . $minutes : $minutes);
	//echo '<br>total : ' . $total . ' - ajout : ' . $ajout . ' - final : ' . $final;
	return $final;
}

// calculer diff entre 2 heures (la 1ere est la plus t�t). format hh:mm
function soustraireDuree($heure1, $heure2) {
	$h1 = strtotime($heure1);
	$h2 = strtotime($heure2);
	$final = gmdate('H:i:s',$h2-$h1);
	return $final;
}

function getJoursFeries() {
	$joursFeries = array();
	$feries = new GCollection('Ferie');
	$feries->db_load(array());
	while($ferie = $feries->fetch()) {
		$joursFeries[] = $ferie->date_ferie;
	}
	return $joursFeries;
}

// 2004-01-15 => 15/01/2004
function sqldate2userdate($s, $noYear = false){
	if ($s == '') {
		return '';
	}
	return (!$noYear ? date(CONFIG_DATE_LONG, strtotime($s)) : date(CONFIG_DATE_SHORT2, strtotime($s)));
}

// 2004-01-15 => 15/01/04
function sqldate2userdateShort($s){
	if ($s == '') {
		return '';
	}

	return date(CONFIG_DATE_SHORT, strtotime($s));
}

// 15/01/2004 => 2004-01-15
function userdate2sqldate($s){
	if ($s == '') {
		return '';
	}
	switch(CONFIG_DATE_FORMAT)	{
		case 'fr':
			return substr($s,6,4).'-'.substr($s,3,2).'-'.substr($s,0,2);
			break;
		case 'us':
			return substr($s,6,4).'-'.substr($s,0,2).'-'.substr($s,3,2);
			break;
		case 'jp':
			return substr($s,0,4).'-'.substr($s,5,2).'-'.substr($s,8,2);
			break;
	}

}

// 02:35:00 => 02:35
function sqltime2usertime($s) {
    return substr($s,0,5);
}
//modif ajout heures decimales
function decimalHours($time)
{
    $hms = explode(":", $time);
    return ($hms[0] + ($hms[1]/60));
}

// 2004-01-15 12:10:00 => 15/01/2004 12:10:00
function sqldatetime2userdatetime($s){
	if ($s == '') {
		return '';
	}
	$data = explode(' ', $s);
	if(count($data) != 2) {
		return '';
	}
    return sqldate2userdate($data[0]) . ' ' . $data[1];
}

// 2:35 => 02:35
// 2 => 02:00
// 2.5 => 02:30
function usertime2sqltime($s, $formatComplet = 'ok') {
	if(strpos($s, '.')) {
		$data = explode('.', $s);
		// on replace la partie decimale par son equivalent en minute
		if(isset($data[1]) && strlen($data[1]) > 0) {
			$s = $data[0] . ':' . ($data[1] * 6);
		} else {
			$s = $data[0];
		}
	}
	if(strlen($s) == 0) {
		$s = '00:00';
	}
	if(strlen($s) == 1) {
		$s = '0' . $s . ':00';
	}
	if(strlen($s) == 2) {
		$s = $s . ':00';
	}
	if(strlen($s) == 4) {
		$s = '0' . $s;
	}
	if($formatComplet == 'ok') {
		$s .= ':00';
	}
	return $s;
}

// valide une date aux diff�rents formats
function controlDate($date) {
	switch($_SESSION['preferences']['dateformat'])
	{
	case 'fr':
		if ($date != '' && !preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $date)) {
			return false;
		}
		break;
	case 'us':
		if ($date != '' && !preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $date)) {
			return false;
		}
		break;
	case 'jp':
		if ($date != '' && !preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/", $date)) {
			return false;
		}
		break;
	}
	return true;
}

function val2sql($val)
{
	global $cfgConnection;
	$val = trim($val);
	if (($val == '') || ($val == "\0")) {
	    return 'NULL';
	} else {
		return '\'' . mysqli_real_escape_string($cfgConnection, $val) . '\'';
	}
}


// calcule la couleur (noir ou blanc) qui tranche le mieux avec celle pass�e en parametre
function buttonFontColor($bgcolor) {
	$values_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");

	if($bgcolor == '#black') {
		return "#FFFFFF";
	}

	if (strlen($bgcolor) != 7) {
		return false;
	}
	if (substr($bgcolor, 0, 1) != "#") {
		return false;
	}
	//$cpt = 0;
	$total = 0;
	for ($i = 1 ; $i < 7 ; $i++) {
		$mySubStr = strtoupper(substr($bgcolor, $i /*+ $cpt*/, 1));
		if (!in_array($mySubStr, $values_array)) {
			return false;
		} elseif ($i == 1) { // red
			if     ($mySubStr == "A") {$total += 10;}
			elseif ($mySubStr == "B") {$total += 11;}
			elseif ($mySubStr == "C") {$total += 12;}
			elseif ($mySubStr == "D") {$total += 13;}
			elseif ($mySubStr == "E") {$total += 14;}
			elseif ($mySubStr == "F") {$total += 15;}
			else {
				$total += $mySubStr;
			}
		} elseif ($i == 3 ) { // green big ponderation
			$pond = 9;
			if     ($mySubStr == "A") {$total += (10 );}
			elseif ($mySubStr == "B") {$total += (11 );}
			elseif ($mySubStr == "C") {$total += (12 + ($pond * 2));}
			elseif ($mySubStr == "D") {$total += (13 + ($pond * 2));}
			elseif ($mySubStr == "E") {$total += (14 + ($pond * 4));}
			elseif ($mySubStr == "F") {$total += (15 + ($pond * 4));}
			else {
				$total += $mySubStr;
			}
		} elseif ($i == 5) { // blue medium ponderation
			$pond = 5;
			if     ($mySubStr == "A") {$total += (10 );}
			elseif ($mySubStr == "B") {$total += (11 );}
			elseif ($mySubStr == "C") {$total += (12 + $pond);}
			elseif ($mySubStr == "D") {$total += (13 + $pond);}
			elseif ($mySubStr == "E") {$total += (14 + $pond);}
			elseif ($mySubStr == "F") {$total += (15 + $pond);}
			else {
				$total += $mySubStr;
			}
		}
	}
	if ($total < 36) {
		$fontColor = "#FFFFFF";
	} else {
		$fontColor = "#000000";
	}
	return $fontColor;
}

// r?cup?re le message ? afficher, en concat�nant un eventuel code d'erreur
function formatMessage($smartyTexte) {
	global $lang;
	$smartyTemp = new MySmarty('fr');

	$temp = explode(';', $smartyTexte);
	if(count($temp) == 1) {
		$texte = $smartyTemp->get_config_vars($smartyTexte);
		if($texte == '') {
			$texte = $smartyTexte;
		}
	} else {
		$texte = $smartyTemp->get_config_vars($temp[0]) . $smartyTemp->get_config_vars('code_technique') . $temp[1];
	}
	return $texte;
}


function detectLanguage($default) {
	global $cfgAvailableLanguages;
	$language = '';
	if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		$HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	if (!empty($_SERVER['HTTP_USER_AGENT']))
		$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];

	if (empty($language) && !empty($HTTP_ACCEPT_LANGUAGE)) {
		$accepted    = explode(',', $HTTP_ACCEPT_LANGUAGE);
		$acceptedCnt = count($accepted);
		reset($accepted);
		for ($i = 0; $i < $acceptedCnt && empty($language); $i++) {
			$tmpLang = $accepted[$i];

			// specific case for firefox
			$data = explode('-', $accepted[$i]);
			if(count($data) > 1) {
				$tmpLang = $data[0];
			}
			$data = explode(';', $accepted[$i]);
			if(count($data) > 1) {
				$tmpLang = $data[0];
			}

			if (array_key_exists($tmpLang, $cfgAvailableLanguages)) {
				$language = $tmpLang;
			}
		}
	}

	// 2. try to findout users language by checking it's HTTP_USER_AGENT variable
	if (empty($language) && !empty($HTTP_USER_AGENT)) {
		$language = isAvailableLanguage($HTTP_USER_AGENT, 2);
	}

	// 3. Didn't catch any valid lang : we use the default settings
	if (empty($language) || is_null($language)) {
		$language = $default;
	}
	return $language;
}

function isAvailableLanguage($str = '', $envType = '')
{
	global $cfgAvailableLanguages;

	$language = '';
	reset($cfgAvailableLanguages);
	while (list($key, $value) = each($cfgAvailableLanguages)) {
		if (($envType == 1 && preg_match('/^(' . $value[0] . ')(;q=[0-9]\\.[0-9])?$/i', $str)) ||
		($envType == 2 && preg_match('/(\(|\[|;[[:space:]])(' . $value[0] . ')(;|\]|\))/i', $str))) {
			$language     = $key;
			break;
		}
	}
	return $language;
}

function getUrlInfo()
{
	$root = 'http';
	if ( isset ($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$root .= "s";
	}
	$root .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$root .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$root .= $_SERVER["SERVER_NAME"];
	}

	$fileName = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/")+1);
	$currentDir = substr($_SERVER["SCRIPT_NAME"], 0, strrpos($_SERVER["SCRIPT_NAME"], "/")+1);
	$result['root'] = $root;
	$result['currentDir'] = $currentDir;
	$result['fileName'] = $fileName;
	$result['currentUrl'] = $root.$currentDir.$fileName;
	$result['currentUrlWithArg'] = $root.$_SERVER['REQUEST_URI'];
	return $result;
}

function is__writable($path) {
//will work in despite of Windows ACLs bug
//NOTE: use a trailing slash for folders!!!
    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}


function VerifierAdresseMail($adresse) {
	$Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
	if(preg_match($Syntaxe,$adresse))
		return true;
	else
		return false;
}


function xss_protect($data, $strip_tags = false, $allowed_tags = "") {
	if(is_null($data) || is_bool($data) || is_array($data)) {
		return $data;
	}
	if($strip_tags) {
        $data = strip_tags($data, $allowed_tags . "<b>");
    }
    if(stripos($data, "script") !== false) {
        $result = str_replace("script","scr<b></b>ipt", htmlentities($data, ENT_QUOTES, "ISO-8859-1"));
    } else {
        $result = htmlentities($data, ENT_QUOTES, "ISO-8859-1");
    }
    return $result;
}

function detectionMobile() {
	$detect = new Mobile_Detect();
	if($detect->isMobile() || $detect->isTablet()) {
		return true;
	}
	return false;
}

// fonction pour affichage par jour
function couvreTranche($dureeDetails, $trancheHoraire) {
	if($dureeDetails == 'AM') {
		// on demarre la couverture sur les premieres heures autoris�es dans la config, jusqu'� couvrir le nombre d'heures indiqu� pour une matin�e (config �galement)
		$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);
		$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);
		for($i=1; $i<=CONFIG_DURATION_AM; $i++) {
			if(array_shift($tabTranchesHoraires) == $trancheHoraire) {
				return true;
			}
		}
		return false;

	} elseif ($dureeDetails == 'PM') {
		// on demarre la couverture apr�s que les heures du matin soient d�duites
		$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);
		for($i=1; $i<=CONFIG_DURATION_AM; $i++) {
			$tmp = array_shift($tabTranchesHoraires);
		}
		for($i=1; $i<=CONFIG_DURATION_PM; $i++) {
			if(array_shift($tabTranchesHoraires) == $trancheHoraire) {
				return true;
			}
		}
		return false;

	} elseif ($dureeDetails == 'duree') {
		// on couvre selon la dur�e, sur les heures selectionn�es
		$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);
		for($i=1; $i<=CONFIG_DURATION_DAY; $i++) {
			if(array_shift($tabTranchesHoraires) == $trancheHoraire) {
				return true;
			}
		}

	} elseif (strlen($dureeDetails) == 17) {
		// on calcule si l'horaire de d�but et de fin couvre.
		$data = explode(';', $dureeDetails);
		$heureDebut = $data[0];
		$heureFin = $data[1];
		$debutTranche = usertime2sqltime($trancheHoraire);
		$finTranche = usertime2sqltime($trancheHoraire + 1);
		if($heureDebut >= $debutTranche && $heureDebut < $finTranche) {
			return true;
		}
		if($heureFin > $debutTranche && $heureFin < $finTranche) {
			return true;
		}
		if($heureDebut < $debutTranche && $heureFin >= $finTranche) {
			return true;
		}
		return false;

	} else {
		// dans tous les autres cas on consid�re que �a couvre
		return true;
	}
}


function cleanInput($input) {

  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
    '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  );

    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize($input) {
	global $cfgConnection;
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
		if(!isset($output)) {
			$output = array();
		}
    }
    else {
        $input  = cleanInput($input);
        $output = mysqli_real_escape_string($cfgConnection, $input);
    }
    return $output;
}


function checkConflitRessource($ressource,$date_debut,$date_fin,$user_id,$periode_id) {
		// V?rification de l'exclusivit� de la ressource
		if($ressource != '')
		{
	      $nressource = new Ressource();
		  $nressource->db_load(array('ressource_id', '=', $ressource));
		}else $nressource->exclusif=0;
		if ($nressource->exclusif==1)
		{
		  $texte = '';
		  $smarty = new MySmarty();
		  $str_user="(";
		  if (is_array($user_id))
		  {
			foreach($user_id as $current_user_id) {
			if ($str_user!="(") {$str_user.=",";}
			$str_user.="'".$current_user_id."'";
		  }
		  }else $str_user.="'".$user_id."'";
		  $str_user.=")";
		  // si pas de date de fin, c'est sur un jour seulement
		if (is_null($date_fin)) $date_fin=$date_debut;
		 if (is_null($date_fin) or empty($date_fin)) $date_fin=$date_debut;
		  if ($date_debut == $date_fin)
	 	  {
            $sql = "SELECT *, planning_user.nom AS nom_user";
            $sql .= " FROM planning_periode ";
            $sql .= " INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id ";
            $sql .= " WHERE ((date_fin IS NOT NULL AND date_debut <= '" . $date_debut . "' AND date_fin >= '" . $date_fin . "')";
            $sql .= " OR (date_fin IS NULL AND date_debut = '" . $date_debut . "'))";
            $sql .= " AND planning_periode.ressource_id = '".$ressource."'";
		  }else
		  {
            $sql = "SELECT *, planning_user.nom AS nom_user";
            $sql .= " FROM planning_periode ";
            $sql .= " INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id ";
            $sql .= " WHERE ((date_fin IS NOT NULL AND date_debut >= '" . $date_debut . "' AND date_fin <= '" . $date_fin . "')";
            $sql .= " OR (date_fin IS NOT NULL AND date_debut <= '" . $date_debut . "' AND date_fin >= '" . $date_fin . "')";
            $sql .= " OR (date_fin IS NOT NULL AND date_debut <= '" . $date_debut . "' AND date_fin >= '" . $date_fin . "')";
            $sql .= " OR (date_fin IS NOT NULL AND '" . $date_debut . "' >= date_debut AND '" . $date_debut . "' <= '" . $date_fin . "')";
            $sql .= " OR (date_fin IS NOT NULL AND '" . $date_fin . "' >= date_debut AND '" . $date_fin . "' <= '" . $date_fin . "')";
            $sql .= " OR (date_fin IS NULL AND date_debut >= '" . $date_debut . "' AND date_debut <= '" . $date_fin . "'))";
            $sql .= " AND planning_periode.ressource_id = '".$ressource."'";
		  }
	      if (!is_null($periode_id)) $sql.= " AND planning_periode.periode_id<> $periode_id ";
	      $result = db_query($sql);
	      if (db_num_rows($result)>0)
	      {
	       return false;
	      }else return true;
		}else return true;
	}

function checkConflitLieu($lieu,$date_debut,$date_fin,$user_id,$periode_id) {
	// V?rification de l'exclusivit� du lieu
	if($lieu != '')
	{
	  $nlieu = new Lieu();
	  $nlieu->db_load(array('lieu_id', '=', $lieu));
	}else $nlieu->exclusif=0;
	if ($nlieu->exclusif==1)
	{
	 $texte = '';
	 $smarty = new MySmarty();
	 $str_user="(";
	 if (is_array($user_id))
	 {
		foreach($user_id as $current_user_id)
		{
		 if ($str_user!="(") {$str_user.=",";}
		 $str_user.="'".$current_user_id."'";
		}
	 }else $str_user.="'".$user_id."'";
	 $str_user.=")";
	 // si pas de date de fin, c'est sur un jour seulement
	 if (is_null($date_fin) or empty($date_fin)) $date_fin=$date_debut;
	 if ($date_debut == $date_fin)
	 {
		$sql = "SELECT *, planning_user.nom AS nom_user";
		$sql .= " FROM planning_periode ";
		$sql .= " INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id ";
		$sql .= " WHERE	((date_fin IS NOT NULL AND date_debut <= '" . $date_debut . "' AND date_fin >= '" . $date_fin . "')";
		$sql .= " OR (date_fin IS NULL AND date_debut = '" . $date_debut . "'))";
		$sql .= " AND planning_periode.lieu_id = '".$lieu."'";
	 }else
	 {
		$sql = "SELECT *, planning_user.nom AS nom_user";
		$sql .= " FROM planning_periode ";
		$sql .= " INNER JOIN planning_user ON planning_periode.user_id = planning_user.user_id ";
		$sql .= " WHERE	((date_fin IS NOT NULL AND date_debut >= '" . $date_debut . "' AND date_fin <= '" . $date_fin . "')";
		$sql .= " OR (date_fin IS NOT NULL AND date_debut <= '" . $date_debut . "' AND date_fin >= '" . $date_fin . "')";
		$sql .= " OR (date_fin IS NOT NULL AND date_debut <= '" . $date_debut . "' AND date_fin >= '" . $date_fin . "')";
		$sql .= " OR (date_fin IS NOT NULL AND '" . $date_debut . "' >= date_debut AND '" . $date_debut . "' <= '" . $date_fin . "')";
		$sql .= " OR (date_fin IS NOT NULL AND '" . $date_fin . "' >= date_debut AND '" . $date_fin . "' <= '" . $date_fin . "')";
		$sql .= " OR (date_fin IS NULL AND date_debut >= '" . $date_debut . "' AND date_debut <= '" . $date_fin . "'))";
		$sql .= " AND planning_periode.lieu_id = '".$lieu."'";
	 }
	 if (!is_null($periode_id)) $sql.= " AND planning_periode.periode_id<> $periode_id ";
	 $result = db_query($sql);
	 if (db_num_rows($result)>0)
	 {
	  return false;
	}else return true;
  }else return true;
}


function upload_image($target,$fichier)
{
  $smarty = new MySmarty();
  // Constantes
  define('MAX_SIZE', 100000);    // Taille max en octets du fichier
  define('WIDTH_MAX', 400);    // Largeur max de l'image en pixels
  define('HEIGHT_MAX', 40);    // Hauteur max de l'image en pixels
  // Tableaux de donnees
  $tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
  $infosImg = array();
  // Variables
  $extension = '';
  $message = '';
  $nomImage = '';
  if (!extension_loaded('gd'))
  {
	// Sinon erreur sur le type de l'image
	$_SESSION['erreur'] = $smarty->get_config_vars('erreur_extension_gd');
	return 'KO';
  }
  if( !is_dir($target) ) {
    if( !mkdir($target, 0755) ) {
	$_SESSION['erreur'] = $smarty->get_config_vars('erreur_creation_repertoire_logo');
	return 'KO';
	return 2;
    }
  }
    // Recuperation de l'extension du fichier
    $extension  = pathinfo($fichier['name'], PATHINFO_EXTENSION);

    // On verifie l'extension du fichier
    if(in_array(strtolower($extension),$tabExt))
    {
      // On recupere les dimensions du fichier
      $infosImg = getimagesize($fichier['tmp_name']);

      // On verifie le type de l'image
      if($infosImg[2] >= 1 && $infosImg[2] <= 14)
      {
        // On verifie les dimensions et taille de l'image
        if(($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX))
        {
          if (filesize($fichier['tmp_name']) < MAX_SIZE)
		  {
				// Parcours du tableau d'erreurs
				if(isset($fichier['error']) && UPLOAD_ERR_OK === $fichier['error'])
				{
					$new=$target.'/'.$fichier['name'];
					// Si c'est OK, on teste l'upload
					if(move_uploaded_file($fichier['tmp_name'], $new))
					{
					 $message = '';
					}else
					{
					// Sinon erreur sur le type de l'image
					$_SESSION['erreur'] = $smarty->get_config_vars('erreur_upload_fichier');
					return 'KO';
					}
				}else
				{
					// Sinon erreur sur le type de l'image
					$_SESSION['erreur'] = $smarty->get_config_vars('erreur_upload_fichier');
					return 'KO';
				}
				}
			}else
			{
				$NouvelleLargeur = 40; //Largeur choisie � 40 px mais modifiable
				$NouvelleHauteur = ( ($infosImg[1] * (($NouvelleLargeur)/$infosImg[0])) );
				$NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur");

				$gdinfo=gd_info();
				// Sinon erreur sur les dimensions et taille de l'image
				if (preg_match('/jpg|jpeg/i',$extension))
				{
					if ($gdinfo["JPEG Support"]==true)
					{
						$ImageChoisie=imagecreatefromjpeg($fichier['tmp_name']);
						imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $infosImg[0],$infosImg[1]);
					}else
					{
						// Sinon erreur sur le type de l'image
						$_SESSION['erreur'] = $smarty->get_config_vars('erreur_support_jpeg');
						return 'KO';
					}
				}else if (preg_match('/png/i',$extension))
				{
					if ($gdinfo["PNG Support"]==true)
					{
						$ImageChoisie=imagecreatefrompng($fichier['tmp_name']);
						imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $infosImg[0],$infosImg[1]);
					}else
					{
						// Sinon erreur sur le type de l'image
						$_SESSION['erreur'] = $smarty->get_config_vars('erreur_support_png');
						return 'KO';
					}
				}else if (preg_match('/gif/i',$extension))
				{
					if ($gdinfo["GIF Read Support"]==true)
					{
						$ImageChoisie=imagecreatefromgif($fichier['tmp_name']);
						imagecopyresampled($NouvelleImage , $ImageChoisie  , 0,0, 0,0, $NouvelleLargeur, $NouvelleHauteur, $infosImg[0],$infosImg[1]);
					}else
					{
						// Sinon erreur sur le type de l'image
						$_SESSION['erreur'] = $smarty->get_config_vars('erreur_support_png');
						return 'KO';
					}
				}else return 1;
				imagedestroy($ImageChoisie);
				$NomImageExploitable = $fichier['name'];
				imagejpeg($NouvelleImage , $target.'/'.$NomImageExploitable, 100);
			}
		}else
		{
			// Sinon erreur sur le type de l'image
			$_SESSION['erreur'] = $smarty->get_config_vars('erreur_fichier_not_image');
			return 'KO';
		}
    }
    else
    {
      // Sinon on affiche une erreur pour l'extension
	  $_SESSION['erreur'] = $smarty->get_config_vars('erreur_image_extension');
	  return 'KO';
    }
	return $message;
}

// Force the User Date Format
function forceUserDateFormat($date,$force=0)
{
	// Si je suis en date ISO
	if (!empty($date) && ($_SESSION['isMobileOrTablet'] == 1 || $force==1))
	{
		switch(CONFIG_DATE_FORMAT)	{
		case 'fr':
			return substr($date,8,2).'/'.substr($date,5,2).'/'.substr($date,0,4);
			break;
		case 'us':
			return substr($date,5,2).'/'.substr($date,8,2).'/'.substr($date,0,4);
			break;
		case 'jp':
			return $date;
			break;
		}
	}else return $date;
}

// Force the ISO Date Format
function forceISODateFormat($date)
{
	if(preg_match('/\d{2}-\d{2}-\d{4}/',$date))
	{
		return substr($date,6,4).'-'.substr($date,0,2).'-'.substr($date,3,2);
	}elseif(preg_match('/\d{2}\/\d{2}\/\d{4}/',$date))
	{
		return substr($date,6,4).'-'.substr($date,3,2).'-'.substr($date,0,2);
	}else return $date;
}

function convertHourToDecimal($heure) {
	$data = explode(':', $heure);
	if(count($data) == 1) {
		// we cast if necessary
		return ($data + 0);
	}
	return $data[0] + ($data[1]*5/3/100);
}

// Init datetime object
function initDateTime($date)
{
	$object = new Datetime();
	switch(CONFIG_DATE_FORMAT)	{
		case 'fr':
			if (!($object->setDate(substr($date,6,4), substr($date,3,2), substr($date,0,2))))
			{
				$object=false;
			}
			break;
		case 'us':
			$ret=$object->setDate(substr($date,6,4), substr($date,0,2), substr($date,3,2));
			if (!$ret)
			{
				$object=false;
			}
			break;
		case 'jp':
			if (!($object->setDate(substr($date,0,4), substr($date,5,2), substr($date,8,2))))
			{
				$object=false;
			}
			break;
	}
	return $object;
}

// check valid time
function is_valid_time($val)
{
  return ((!is_null($val) && preg_match("/([0-9]{2}):([0-9]{2}):([0-9]{2})/", $val)));
}
