<?php

require('./base.inc');
require(BASE . '/../config.inc');

unset($_SESSION['installEnCours']);

$smarty = new MySmarty();

$smarty->assign('xajax', $xajax->getJavascript("", BASE . "/assets/js/xajax.js"));

$smarty->clear_all_cache();

$smarty->clear_compiled_tpl();
$smarty->display('install_ok.tpl');

?>