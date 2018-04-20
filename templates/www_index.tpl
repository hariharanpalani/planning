<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="reply-to" content="support@soplanning.org" />
	<meta name="email" content="support@soplanning.org" />
	<meta name="Identifier-URL" content="http://www.soplanning.org" />
	<meta name="robots" content="noindex,follow" />
	<title>
		{if $smarty.const.CONFIG_SOPLANNING_TITLE neq "SOPlanning"}
			{$smarty.const.CONFIG_SOPLANNING_TITLE|escape:'html'|escape:'javascript'}
		{else}
			SOPlanning - Simple Online Planning
		{/if}
	</title>
	<link type="image/x-icon" href="favicon.ico" rel="icon" />
	<link type="text/css" href="{$BASE}/assets/plugins/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet" />
	<link type="text/css" href="{$BASE}/assets/plugins/bootstrap-3.3.7/css/bootstrap-theme.min.css" rel="stylesheet" />
	<link href="{$BASE}/assets/css/themes/{$smarty.const.CONFIG_SOPLANNING_THEME}" rel="stylesheet">
	<link type="text/css" href="{$BASE}/assets/css/styles.css" rel="stylesheet" />
	<link type="text/css" href="{$BASE}/assets/css/simplePage.css" rel="stylesheet" />	
	<link href="{$BASE}/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="{$BASE}/assets/plugins/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script type="text/javascript" src="{$BASE}/assets/js/jquery-3.2.0.min.js"></script>
	<script type="text/javascript" src="{$BASE}/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{$BASE}/assets/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="{$BASE}/assets/plugins/tooltipster/css/tooltipster.bundle.min.css" />
	<link rel="stylesheet" type="text/css" href="{$BASE}/assets/plugins/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-soplanning.min.css" />
	<script type="text/javascript" src="{$BASE}/assets/plugins/tooltipster/js/tooltipster.bundle.min.js"></script>
	{$xajax}
</head>
<body>
<div class="container">
	<h3 class="text-center">
		{if $smarty.const.CONFIG_SOPLANNING_LOGO != ''}
				<img src="./upload/logo/{$smarty.const.CONFIG_SOPLANNING_LOGO}" alt='logo' style='height:40px;' id="logo" /><br />
		{/if}
		{if $smarty.const.CONFIG_SOPLANNING_TITLE neq "SOPlanning"}
			<span class="soplanning_index_title1">{$smarty.const.CONFIG_SOPLANNING_TITLE|escape:'html'|escape:'javascript'}</span>
		{else}
			<span class="soplanning_index_title2">Simple Online Planning</span>
		{/if}
		{if isset($infoVersion)}
			<small>v{$infoVersion}</small>
		{/if}
	</h3>
	<div class="small-container">
		{if isset($alerte)}
			<div>{$alerte}</div>
		{/if}

		{if isset($smartyData.message)}
			{assign var=messageFinal value=$smartyData.message|formatMessage}
			<div class="alert alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				{$messageFinal}
			</div>
		{/if}
		<form action="process/login.php" method="post" class="form-horizontal box" id="formLogin">
			<div class="form-group">
				<label for="login" class="col-md-2 control-label">{#login_login#} :</label>
				<div class="col-md-5">
					<input type="text" class="form-control" name="login" id="login" />
				</div>
			</div>
			<div class="form-group">			
				<label for="password" class="col-md-2 control-label">{#login_password#} :</label>
				<div class="col-md-5">
					<input type="password" class="form-control" name="password" id="password" />
				</div>
			</div>
			<div class="form-group">
				<div  class="col-md-12 text-center">
					<input class="btn btn-primary" type="submit" value="{#loginTxt#}" />
				</div >
			</div>
		</form>
		<div class="form-group text-center">
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_ACCES ==1}
			<a href="planning.php?public=1">{#accesPublic#}</a> &middot;
		{/if}
		<a href="#pwdReminderModal" role="button" data-toggle="modal">{#rappelPwdTitre#}</a><br /><br /><br /><br />
		</div>
		<div id="divTranslation">
			<ul class="list-inline flag text-right">
				<li><a tabindex="1" href="?language=pl" class="tooltipEvent" data-title="Polish"><img src="assets/img/flag/pl.png" alt="Polish" title="Polish"/></a></li>
				<li><a tabindex="2" href="?language=pt" class="tooltipEvent" data-title="Portuguese"><img src="assets/img/flag/pt.png" alt="Portuguese" title="Portuguese"/></a></li>
				<li><a tabindex="3" href="?language=es" class="tooltipEvent" data-title="Spanish"><img src="assets/img/flag/es.png" alt="Spanish" title="Spanish" /></a></li>
				<li><a tabindex="4" href="?language=de" class="tooltipEvent" data-title="German"><img src="assets/img/flag/de.png" alt="German" title="German"/></a></li>
				<li><a tabindex="5" href="?language=da" class="tooltipEvent" data-title="Danish"><img src="assets/img/flag/da.png" alt="Danish" title="Danish"/></a></li>
				<li><a tabindex="6" href="?language=hu" class="tooltipEvent" data-title="Hungarian"><img src="assets/img/flag/hu.png" alt="Hungarian" title="Hungarian"/></a></li>
				<li><a tabindex="7" href="?language=nl" class="tooltipEvent" data-title="Dutch"><img src="assets/img/flag/nl.png" alt="Dutch" title="Dutch"/></a></li>
				<li><a tabindex="8" href="?language=it" class="tooltipEvent" data-title="Italian"><img src="assets/img/flag/it.png" alt="Italian" title="Italian"/></a></li>
				<li><a tabindex="9" href="?language=fr" class="tooltipEvent" data-title="French"><img src="assets/img/flag/fr.png" alt="French" title="French"/></a></li>
				<li><a tabindex="10" href="?language=en" class="tooltipEvent" data-title="English"><img src="assets/img/flag/en.png" alt="English" title="English"/></a></li>
			</ul>
			<p class="text-right text-info"><small><a href="mailto:support@soplanning.org">{#proposerTrad#}</a></small></p>
		</div>
		<div id="infosVersion" style="display:none"></div>
	</div>
</div>
	<script type="text/javascript" src="assets/js/login.js"></script>
	<script type="text/javascript" src="assets/js/fonctions.js"></script>
	<script type="text/javascript">
	document.getElementById('login').focus();
	setTimeout("xajax_checkAvailableVersion();", 5000);
	</script>
{include file="www_footer.tpl"}