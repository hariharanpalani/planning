<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <meta name="reply-to" content="support@soplanning.org" />
		<meta name="email" content="support@soplanning.org" />
		<meta name="Identifier-URL" content="http://www.soplanning.org" />
		<meta name="robots" content="noindex,follow" />
		<title>SoPlanning Installation</title>
		<link type="image/x-icon" href="favicon.ico" rel="icon" />		
		<link href="../assets/plugins/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="../assets/plugins/bootstrap-3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">
		<link type="text/css" href="../assets/css/styles.css" rel="stylesheet" />
		<link type="text/css" href="../assets/css/simplePage.css" rel="stylesheet" />
		{$xajax}
	</head>

	<body>
		<div class="container">
			<h3 class="text-center">
				<span class="soplanning_install_title">Simple Online Planning</span>
			</h3>
			<div class="small-container">
				{if isset($smartyData.message)}
					{assign var=messageFinal value=$smartyData.message|formatMessage}
					<div class="alert alert-danger" id="divMessage" >
						<div class="row noprint">
							<div class="col-md-10">
								{$messageFinal}
							</div>
						</div>
					</div>
				{/if}
				{if isset($checkInstall.checkPhpVersion)}
					<div class="alert alert-danger">
						<h4>{#install_wrongPhpVersion#}</h4>
						{#install_currentPhpVersion#} :{php}phpversion();{/php}
						<br />
						{#install_requiredPhpVersion#} : 5.2
					</div>
				{/if}
				{if isset($checkInstall.checkWritableDatabaseInc)}
					<div class="alert alert-danger">
						{#install_checkWritableDatabaseInc#}
					</div>
				{/if}
				{if isset($checkInstall.checkGD)}
					<div class="alert alert-danger">
						{#install_checkGD#}
					</div>
				{/if}
				{if isset($checkInstall.checkDatabaseVersion)}
					<div class="alert alert-warning">
						<h4>{#install_DBUpgradeResult#}</h4>
						{if isset($checkInstall.databaseUpgradeResult)}
							{$checkInstall.databaseUpgradeResult}
						{/if}
						<a href="../">{#install_clickLoginAgain#}</a><br />
					</div>
				{/if}
				{if isset($checkInstall.checkDBAccess)  || (isset($checkInstall.checkDatabaseVersion) && $checkInstall.checkDatabaseVersion eq 'database_empty')}
					<form action="database.php" method="post" class="form-horizontal box">
						<h2>{#install_installationDB#}</h2>
						<div class="form-group">
							<label class="col-md-3 control-label" for="cfgHostname">{#install_mysqlServer#} :</label>
							<div class="col-md-6">
								<input type="text" size="20" name="cfgHostname" id="cfgHostname" value="{$cfgHostname}" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="cfgDatabase">{#install_DBName#} :<br/><span>{#install_missingDBCreated#}.</span></label>
							<div class="col-md-6">
								<input type="text" size="20" name="cfgDatabase" id="cfgDatabase" value="{$cfgDatabase}" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="cfgHostname">{#install_mysqlLogin#} :</label>
							<div class="col-md-4">
								<input type="text" size="20" name="cfgUsername" id="cfgUsername" value="{$cfgUsername}" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="cfgPassword">{#install_mysqlPassword#} :</label>
							<div class="col-md-4">
								<input type="password" size="20" name="cfgPassword" id="cfgPassword" value="{$cfgPassword}" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label"></label>
							<div class="col-md-6">
								<input class="btn btn-primary" type="submit" value="{#install_startInstallButton#}" />
							</div>
						</div>
					</form>
				{/if}
				<div id="divTranslation">
					<ul class="list-inline flag text-right">
					<li><a tabindex="1" href="?language=pl" class="tooltipEvent" data-title="Polish"><img src="../assets/img/flag/pl.png" alt="Polish" title="Polish"/></a></li>
					<li><a tabindex="2" href="?language=pt" class="tooltipEvent" data-title="Portuguese"><img src="../assets/img/flag/pt.png" alt="Portuguese" title="Portuguese"/></a></li>
					<li><a tabindex="3" href="?language=es" class="tooltipEvent" data-title="Spanish"><img src="../assets/img/flag/es.png" alt="Spanish" title="Spanish" /></a></li>
					<li><a tabindex="4" href="?language=de" class="tooltipEvent" data-title="German"><img src="../assets/img/flag/de.png" alt="German" title="German"/></a></li>
					<li><a tabindex="5" href="?language=da" class="tooltipEvent" data-title="Danish"><img src="../assets/img/flag/da.png" alt="Danish" title="Danish"/></a></li>
					<li><a tabindex="6" href="?language=hu" class="tooltipEvent" data-title="Hungarian"><img src="../assets/img/flag/hu.png" alt="Hungarian" title="Hungarian"/></a></li>
					<li><a tabindex="7" href="?language=nl" class="tooltipEvent" data-title="Dutch"><img src="../assets/img/flag/nl.png" alt="Dutch" title="Dutch"/></a></li>
					<li><a tabindex="8" href="?language=it" class="tooltipEvent" data-title="Italian"><img src="../assets/img/flag/it.png" alt="Italian" title="Italian"/></a></li>
					<li><a tabindex="9" href="?language=fr" class="tooltipEvent" data-title="French"><img src="../assets/img/flag/fr.png" alt="French" title="French"/></a></li>
					<li><a tabindex="10" href="?language=en" class="tooltipEvent" data-title="English"><img src="../assets/img/flag/en.png" alt="English" title="English"/></a></li>
					</ul>
					<p class="text-right text-info"><small><a href="mailto:support@soplanning.org">{#proposerTrad#}</a></small></p>
				</div>
				<p class="text-right text-info"><small>{#install_intro#}</small></p>
			</div>
		</div>
		<div class="navbar-nav navbar-fixed-bottom footer">
			<div class="navbar-inner">
				<div class="container text-center">
					<p class="text-info">
						<a target="_blank" href="http://www.soplanning.org">www.soplanning.org</a>
						<span class="noprint">&nbsp;-&nbsp;</span>
						<a href="mailto:support@soplanning.org" class="noprint">{#soplanning_support#}</a>
						<span class="noprint">&nbsp;-&nbsp;</span>
						<a target="_blank" href="http://www.soplanning.org/en/donate.php" class="noprint">{#soplanning_donate#}</a>
						<span class="noprint">&nbsp;-&nbsp;</span>
						<a href="#divFormSupport" role="button" data-toggle="modal" class="noprint">{#formContact_titre#}</a>
					</p>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="../assets/js/jquery-3.2.0.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
	</body>
</html>