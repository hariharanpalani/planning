<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <meta name="reply-to" content="support@soplanning.org" />
		<meta name="email" content="support@soplanning.org" />
		<meta name="Identifier-URL" content="http://www.soplanning.org" />
		<meta name="robots" content="noindex,follow" />
		<title>{$smarty.const.CONFIG_SOPLANNING_TITLE|escape:'html'|escape:'javascript'}</title>
		<link href="{$BASE}/favicon.ico" rel="icon" type="image/x-icon" />
		{* font-awesome *}
		<link rel="stylesheet" href="{$BASE}/assets/plugins/font-awesome-4.7.0/css/font-awesome.min.css">
		{* bootstrap *}
		<link href="{$BASE}/assets/plugins/bootstrap-3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="{$BASE}/assets/plugins/select2-4.0.3/dist/css/select2.min.css" rel="stylesheet">
		<link href="{$BASE}/assets/css/select2-bootstrap.min.css" rel="stylesheet">
		{* theme *}
		<link href="{$BASE}/assets/css/themes/{$smarty.const.CONFIG_SOPLANNING_THEME}?{$infoVersion}" rel="stylesheet" type="text/css" />
		<link href="{$BASE}/assets/css/jauges.css?{$infoVersion}" rel="stylesheet" type="text/css" />
		<link href="{$BASE}/assets/css/styles.css?{$infoVersion}" rel="stylesheet" type="text/css" />
		<link href="{$BASE}/assets/css/mobile.css?{$infoVersion}" rel="stylesheet" media="screen and (max-width: 1165px)" type="text/css" />
		{* jquery *}
		<link href="{$BASE}/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
		<link href="{$BASE}/assets/plugins/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
		<script type="text/javascript" src="{$BASE}/assets/js/jquery-3.2.0.min.js"></script>
		<script type="text/javascript" src="{$BASE}/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js"></script>
		<script type="text/javascript" src="{$BASE}/assets/plugins/jquery-ui-1.12.1/i18n/jquery.ui.datepicker-{$lang}.js"></script>
		{* bootstrap *}
		<script type="text/javascript" src="{$BASE}/assets/plugins/bootstrap-3.3.7/js/bootstrap.min.js"></script>
		{* multiselect *}
		<script type="text/javascript" src="{$BASE}/assets/plugins/jquery-multiselect/jquery.multiselect.js"></script>
		<link href="{$BASE}/assets/plugins/jquery-multiselect/jquery.multiselect.css" rel="stylesheet">
		{* palette de couleur*}
		<script type="text/javascript" src="{$BASE}/assets/plugins/jscolor/jscolor.js"></script>
		{* tootlipster *}
		<link rel="stylesheet" type="text/css" href="{$BASE}/assets/plugins/tooltipster/css/tooltipster.bundle.min.css" />
		<link rel="stylesheet" type="text/css" href="{$BASE}/assets/plugins/tooltipster/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-soplanning.min.css" />
		<script type="text/javascript" src="{$BASE}/assets/plugins/tooltipster/js/tooltipster.bundle.min.js"></script>
		{* typeahead *}
		<script type="text/javascript" src="{$BASE}/assets/plugins/bootstrap3-typeahead/bootstrap3-typeahead.min.js"></script>
		{* layer pour choix des projets à afficher *}
		<script type="text/javascript" src="{$BASE}/assets/js/sousmenu.js"></script>
		<script type="text/javascript" src="{$BASE}/assets/js/fonctions.js"></script>
		<script type="text/javascript" src="{$BASE}/assets/plugins/select2-4.0.3/dist/js/select2.min.js"></script>
		<script type="text/javascript" src="{$BASE}/assets/plugins/select2-4.0.3/dist/js/i18n/{$lang}.js"></script>
		<link href="{$BASE}/assets/css/print.css" rel="stylesheet" media="print">
		{$xajax}
		<style>
		{if $smarty.const.CONFIG_SOPLANNING_LOGO != ''}
			{literal}
			.nav li a {line-height: 40px;}
			.navbar-nav>li>a {line-height: 40px;}
			.navbar-brand {line-height: 40px;}
			{/literal}
		{/if}
		</style>
	</head>
	<body>
		{if isset($user)}
			<div class="navbar navbar-inverse navbar-static-top noprint" role="navigation">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						{if $smarty.const.CONFIG_SOPLANNING_LOGO != ''}
							<a class="navbar-brand navbar-brand-logo" href="{$BASE}/"><img src="./upload/logo/{$smarty.const.CONFIG_SOPLANNING_LOGO}" alt='logo' class="logo" />
						{else}
							<a class="navbar-brand" href="{$BASE}/">
						{/if}
							<span id="soplanning_title">{$smarty.const.CONFIG_SOPLANNING_TITLE}&nbsp;<small>{$infoVersion}</small></span>
						</a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							<li class="dropdown pull-left">
								<a href="{$BASE}/planning.php" class="dropdown-toggle "><i class="fa fa-calendar fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#menuPlanning#}</a>
								<ul class="dropdown-menu dropdown-menu-hover">
									<li><a href="{$BASE}/uplanning.php"><i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuAfficherPlanning#}</a></li>
									<li><a href="{$BASE}/seats.php"><i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuResourceAllocation#}</a></li>
									<li><a href="{$BASE}/taches.php"><i class="fa fa-list fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuAfficherTaches#}</a></li>
									{if !in_array("tasks_readonly", $user.tabDroits)}
										<li role="separator" class="divider"></li>
										<li><a href="javascript:Reloader.stopRefresh();xajax_ajoutPeriode();undefined;"><i class="fa fa-calendar-plus-o fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuAjouterPeriode#}</a></li>
									{/if}
								</ul>
							</li>
							{if $smarty.session.isMobileOrTablet==1}
							<li class="dropdown pull-left">
								<a href="{$BASE}/taches.php" class="dropdown-toggle "><i class="fa fa-list fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#taches_liste_taches#}</a>
							</li>
							{/if}
							{if in_array("projects_manage_all", $user.tabDroits) || in_array("projects_manage_own", $user.tabDroits)}
								<li class="divider-vertical"></li>
								<li class="dropdown pull-left">
									<a href="{$BASE}/projets.php" class="dropdown-toggle"><i class="fa fa-book fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#menuProjets#}</a>
									<ul class="dropdown-menu dropdown-menu-hover">
										<li><a href="{$BASE}/projets.php"><i class="fa fa-book fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuListeProjets#}</a></li>
										{if in_array("projectgroups_manage_all", $user.tabDroits)}
											<li><a href="groupe_list.php"><i class="fa fa-folder-o fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuListeGroupes#}</a></li>
										{/if}
										<li role="separator" class="divider"></li>
										<li><a href="javascript:Reloader.stopRefresh();xajax_ajoutProjet();undefined;"><i class="fa fa-bookmark fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuAjouterProjet#}</a></li>
									</ul>
								</li>
							{/if}
							{if in_array("users_manage_all", $user.tabDroits)}
								<li class="divider-vertical"></li>
								<li class="dropdown pull-left">
									<a href="{$BASE}/user_list.php" class="dropdown-toggle"><i class="fa fa-users fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#menuUsers#}</a>
									<ul class="dropdown-menu dropdown-menu-hover">
										<li><a href="{$BASE}/user_list.php"><i class="fa fa-address-card-o fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuGestionUsers#}</a></li>
										<li><a href="{$BASE}/user_groupes.php"><i class="fa fa-users fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuGroupesUsers#}</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="javascript:xajax_modifUser();undefined;"><i class="fa fa-user-plus fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerUser#}</a></li>

									</ul>
								</li>
							{/if}
							{if in_array("stats_users", $user.tabDroits) || in_array("stats_projects", $user.tabDroits)}
								<li class="divider-vertical"></li>
								<li class="dropdown pull-left">
								<a href="{$BASE}/stats_users.php" class="dropdown-toggle"><i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#droits_stats#}</a>
										<ul class="dropdown-menu dropdown-menu-hover">
										{if in_array("stats_users", $user.tabDroits)}
											<li><a href="{$BASE}/stats_users.php"><i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#droits_stats_users#}</a></li>
										{/if}
										{if in_array("stats_projects", $user.tabDroits)}
											<li><a href="{$BASE}/stats_projects.php"><i class="fa fa-bar-chart fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#droits_stats_projects#}</a></li>
										{/if}
									</ul>
								</li>
							{/if}
							{if in_array("parameters_all", $user.tabDroits) || in_array("lieux_all", $user.tabDroits) || in_array("ressources_all", $user.tabDroits)}
								<li class="divider-vertical"></li>
								<li class="dropdown pull-left">
								<a href="{$BASE}/options.php" class="dropdown-toggle"><i class="fa fa-cogs fa-lg" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;{#menuOptions#}</a>
										<ul class="dropdown-menu dropdown-menu-hover">
										{if in_array("parameters_all", $user.tabDroits)}
											<li><a href="{$BASE}/options.php"><i class="fa fa-cogs fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuOptions#}</a></li>
											<li role="separator" class="divider"></li>
											<li><a href="{$BASE}/feries.php"><i class="fa fa-plane fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuFeries#}</a></li>
											<li><a href="{$BASE}/status.php"><i class="fa fa-tags fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuStatus#}</a></li>
										{/if}
										{if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX == 1 && in_array("lieux_all", $user.tabDroits) }
											<li><a href="{$BASE}/lieux.php"><i class="fa fa-map-marker fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuLieux#}</a></li>
										{/if}
										{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 1 && in_array("ressources_all", $user.tabDroits) }
											<li><a href="{$BASE}/ressources.php"><i class="fa fa-plug fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuRessources#}</a></li>
										{/if}
									</ul>
								</li>
							{/if}
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown pull-left">
								{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 1 and $user.user_id == 'publicspl' }
								<a href="#"><i class="fa fa-user-o fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#accesPublicUsername#}</a>
								{else}
								<a href="javascript:xajax_modifProfil();undefined;"><i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{$user.nom} ({$user.user_id})</a>
								{/if}
							</li>
							<li class="dropdown pull-left">
								<a href="process/login.php?action=logout&language={$lang}"><img src="{$BASE}/assets/img/pictos/logout.png" alt="" class="picto-logout"/></a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		{/if}
		{if isset($smartyData.message)}
			{assign var=messageFinal value=$smartyData.message|formatMessage}
			{if isset($smartyData.erreur)}
				{assign var=messageErreur value=$smartyData.erreur|formatMessage}
			{/if}
			<div class="container-fluid">
				<div id="divMessage" class="alert {if $smartyData.message eq 'changeNotOK'}alert-danger{else}alert-success{/if}">
					{if isset($messageErreur)}
					<p>{$messageErreur}</p>
					{else}
					<p>{$messageFinal}</p>
					{/if}
				</div>
			</div>
		{/if}
