<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <meta name="reply-to" content="support@soplanning.org" />
		<meta name="email" content="support@soplanning.org" />
		<meta name="Identifier-URL" content="http://www.soplanning.org" />
		<title>SoPlanning Installation</title>
		<link href="favicon.ico" rel="icon" type="image/x-icon" />
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
				<div class="alert alert-success">
					{#install_installResultOk#}
				</div>
				<ul class="list-inline flag text-right">
					<li><a tabindex="1" href="?language=pt" class="tooltipEvent"><img src="../assets/img/flag/pt.png" alt="Portuguese"/></a></li>
					<li><a tabindex="2" href="?language=es" class="tooltipEvent"><img src="../assets/img/flag/es.png" alt="Spanish"/></a></li>
					<li><a tabindex="3" href="?language=de" class="tooltipEvent"><img src="../assets/img/flag/de.png" alt="German"/></a></li>
					<li><a tabindex="4" href="?language=nl" class="tooltipEvent"><img src="../assets/img/flag/nl.png" alt="Dutch"/></a></li>
					<li><a tabindex="5" href="?language=it" class="tooltipEvent"><img src="../assets/img/flag/it.png" alt="Italian"/></a></li>
					<li><a tabindex="6" href="?language=fr" class="tooltipEvent"><img src="../assets/img/flag/fr.png" alt="French"/></a></li>
					<li><a tabindex="7" href="?language=en" class="tooltipEvent"><img src="../assets/img/flag/en.png" alt="English"/></a></li>
				</ul>
				<p class="text-right text-info">
					<small><a href="mailto:support@soplanning.org">{#proposerTrad#}</a></small>
				</p>
				<p class="text-right text-info"><small>{#install_intro#}</small></p>
			</div>
		</div>
		<script type="text/javascript"  src="../assets/js/jquery-3.2.0.min.js"></script>
		<script type="text/javascript" src="../assets/plugins/bootstrap-3.3.7/js/bootstrap.js"></script>
	</body>
</html>