{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/feries.php" class="btn btn-sm btn-default" ><i class="fa fa-plane fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuFeries#}</a>
					<a href="{$BASE}/lieux.php" class="btn btn-sm btn-default" ><i class="fa fa-map-marker fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuLieux#}</a>
					<a href="{$BASE}/ressources.php" class="btn btn-sm btn-default" ><i class="fa fa-plug fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuRessources#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row top-10" id="optionsRow">
		<div class="col-md-3">
			<ul class="nav nav-pills nav-stacked soplanning-box" id="myTab">
				<li class="active">
					<a href="#param-global">{#options_configGenerale#}</a>
				</li>
				<li>
					<a href="#param-modules">{#options_modules#}</a>
				</li>
				<li>
					<a href="#param-planning">{#options_planning#}</a>
				</li>
				<li>
					<a href="#param-divers">{#options_divers#}</a>
				</li>
				<li>
					<a href="#param-smtp">{#options_smtp#}</a>
				</li>
				<li>
					<a href="#param-testmail">{#options_envoyerMailTest#}</a>
				</li>
			</ul>
		</div>
		<div class="col-md-9">
			<div class="soplanning-box">
				<div class="tab-content">
					<div class="tab-pane active" id="param-global">
						<form action="process/options.php" method="POST" class="form-horizontal" enctype="multipart/form-data" id="setupForm">
							<fieldset>
								<legend>
									{#options_configGenerale#}
								</legend>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label" for="SOPLANNING_TITLE">{#options_titre#} :</label>
									<div class="col-md-5">
										<input type="text" class="form-control input-xlarge" name="SOPLANNING_TITLE" id="SOPLANNING_TITLE" value="{$smarty.const.CONFIG_SOPLANNING_TITLE|escape:'html'|escape:'javascript'}">
										&nbsp;<span title="{#options_aide_titre#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label" for="SOPLANNING_URL">{#options_url#} :</label>
									<div class="col-md-6">
										<input type="text" class="form-control input-xlarge" name="SOPLANNING_URL" id="SOPLANNING_URL" value="{$smarty.const.CONFIG_SOPLANNING_URL}">
										&nbsp;<span data-tooltip-content="#tooltip-CONFIG_SOPLANNING_URL" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										<div class="tooltip-html"><span id="tooltip-CONFIG_SOPLANNING_URL">{#options_aide_url#|cat:'<br>'|cat:$urlSuggeree}</span></div>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label" for="SOPLANNING_LOGO">{#options_logo#} :</label>
									<div class="col-md-8">
										<input type="hidden" name="old_logo" value="{$smarty.const.CONFIG_SOPLANNING_LOGO}"/>
										<input type="file" accept=".jpg, .png, .jpeg, .gif" name="SOPLANNING_LOGO" id="SOPLANNING_LOGO" class="pull-left" />
										&nbsp;<span title="{#options_aide_logo#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								{if $smarty.const.CONFIG_SOPLANNING_LOGO != ''}
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label" for="SOPLANNING_LOGO"></label>
									<div class="col-md-8">
										<img src="./upload/logo/{$smarty.const.CONFIG_SOPLANNING_LOGO}" alt='logo' />
										<label class="checkbox-inline">
										<input type="checkbox" name="SOPLANNING_LOGO_SUPPRESSION" id="SOPLANNING_LOGO_SUPPRESSION">
										&nbsp;{#options_logo_supprimer#}
										</label>
									</div>
								</div>
								{/if}
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label" for="SOPLANNING_THEME">{#options_theme#} :</label>
									<div class="col-md-6">
										<input type="hidden" name="old_theme" value="{$smarty.const.CONFIG_SOPLANNING_THEME}"/>
										<select name='SOPLANNING_THEME' id='SOPLANNING_THEME' class="form-control input-xlarge">
										{foreach from=$themes item=t}
											<option value='{$t}.css' {if $t|cat:'.css' == $smarty.const.CONFIG_SOPLANNING_THEME}selected="selected"{/if}>{$t}</option>
										{/foreach}
										</select>
										&nbsp;<span title="{#options_aide_theme#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#config_options_acces#} :</label>
									<div class="col-md-7">
										<div class="radio">
											<label>
											<input type="radio" name="SOPLANNING_OPTION_ACCES" onclick="$('#optionscle').hide();" {if $smarty.const.CONFIG_SOPLANNING_OPTION_ACCES ==0}checked="checked"{/if} value="0">
											&nbsp;{#config_options_accesprive#} <span title="{#config_aide_options_accesprive#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
											</label>
										</div>
										<div class="radio">
											<label>
											<input type="radio" name="SOPLANNING_OPTION_ACCES" onclick="$('#optionscle').hide();" {if $smarty.const.CONFIG_SOPLANNING_OPTION_ACCES ==1}checked="checked"{/if} value="1">
											&nbsp;{#config_options_accespublic#} <span title="{#config_aide_options_accespublic#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
											</label>
										</div>
										<div class="radio">
											<label>
											<input type="radio" name="SOPLANNING_OPTION_ACCES" onclick="$('#optionscle').show();" {if $smarty.const.CONFIG_SOPLANNING_OPTION_ACCES ==2}checked="checked"{/if} value="2">
											&nbsp;{#config_options_accespubliccle#} <span title="{#config_aide_options_accespubliccle#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
											</label>
										</div>
										<div id="optionscle" style="display:{if $smarty.const.CONFIG_SOPLANNING_OPTION_ACCES ==2}block{else}none{/if}">
										<div class="form-group col-md-12 form-inline">
											<label class="col-md-1 control-label"></label>
											<label class="col-md-5 control-label" for="CONFIG_SECURE_KEY">{#config_options_clesecurite#} :</label>
											<div class="col-md-6">
												<input type="text" class="form-control input-large" name="CONFIG_SECURE_KEY" id="CONFIG_SECURE_KEY" value="{$smarty.const.CONFIG_SECURE_KEY}">
												<a onclick="javascript:token();" class="cursor-pointer"><img src="{$BASE}/assets/img/pictos/options.png" title="{#config_options_genererclesecurite#}" class="picto tooltipster" alt="" /></a>
											</div>
										</div>
										<div class="form-group col-md-12 form-inline">
											<label class="col-md-1 control-label"></label>
											<label class="col-md-5 control-label">{#config_options_urlpubliccle#} :</label>
											<div class="col-md-6">
											{if $smarty.const.CONFIG_SOPLANNING_URL|substr:-1 == '/' }{assign var='sep' value=''}{else}{assign var='sep' value='/'}{/if}
											<a href="{if $smarty.const.CONFIG_SOPLANNING_URL == ''}{$urlSuggeree}planning.php?public=1&cle={$smarty.const.CONFIG_SECURE_KEY}" target="_blank">{$urlSuggeree}{else}{$smarty.const.CONFIG_SOPLANNING_URL}{$sep}planning.php?public=1&cle={$smarty.const.CONFIG_SECURE_KEY}" target="_blank">{$smarty.const.CONFIG_SOPLANNING_URL}{$sep}{/if}planning.php?public=1&cle={$smarty.const.CONFIG_SECURE_KEY}</a>
											</div>
										</div>
										</div>
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_visiteur#} :</label>
									<div class="col-md-7">
										<label class="checkbox margin-left-5">
											<input type="hidden" id="SOPLANNING_OPTION_VISITEUR" name="SOPLANNING_OPTION_VISITEUR" value="{$smarty.const.SOPLANNING_OPTION_VISITEUR}">
											<input type="checkbox" name="SOPLANNING_OPTION_VISITEUR_checkbox" id="SOPLANNING_OPTION_VISITEUR_checkbox" {if $smarty.const.CONFIG_SOPLANNING_OPTION_VISITEUR == 1}checked="checked"{/if} onChange="document.getElementById('SOPLANNING_OPTION_VISITEUR').value=(document.getElementById('SOPLANNING_OPTION_VISITEUR_checkbox').checked ? '1' : '0');">
											&nbsp;{#options_visiteur_modification#} <span title="{#config_aide_options_visiteur#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										</label>
									</div>
								</div>
								<div class="form-group col-md-12">
									<div class="col-md-4"></div>
									<div class="col-md-8">
										<br />
										<input type="submit" class="btn btn-primary" value="{#submit#}"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="param-modules">
						<form action="process/options.php" method="POST" class="form-horizontal">
							<fieldset>
								<legend>
									{#modules#}
								</legend>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#config_options_lieux#}  :</label>
									<div class="col-md-3">
										<select name="SOPLANNING_OPTION_LIEUX" class="form-control input-middle">
											<option value="0" {if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX eq 0}selected="selected"{/if}>{#non#}</option>
											<option value="1" {if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX eq 1}selected="selected"{/if}>{#oui#}</option>
										</select>
										&nbsp;<span title="{#config_aide_options_lieux#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#config_options_ressources#}  :</label>
									<div class="col-md-3">
										<select name="SOPLANNING_OPTION_RESSOURCES" class="form-control input-middle">
											<option value="0" {if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES eq 0}selected="selected"{/if}>{#non#}</option>
											<option value="1" {if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES eq 1}selected="selected"{/if}>{#oui#}</option>
										</select>
										&nbsp;<span title="{#config_aide_options_ressources#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4"></div>
									<div class="col-md-6">
										<br />
										<input type="submit" class="btn btn-primary" value="{#submit#}"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="param-planning">
						<form action="process/options.php" method="POST" class="form-horizontal">
							<fieldset>
								<legend>
									{#options_planning#}
								</legend>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{assign var=jours value=","|explode:$smarty.const.CONFIG_DAYS_INCLUDED} {#options_joursInclus#} :</label>
									<div class="col-md-8 form-inline">
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="1" id="chklundi" {if in_array('1', $jours)}checked="checked"{/if}>{#day_1#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="2" id="chkmardi" {if in_array('2', $jours)}checked="checked"{/if}>{#day_2#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="3" id="chkmercredi" {if in_array('3', $jours)}checked="checked"{/if}>{#day_3#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="4" id="chkjeudi" {if in_array('4', $jours)}checked="checked"{/if}>{#day_4#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="5" id="chkvendredi" {if in_array('5', $jours)}checked="checked"{/if}>{#day_5#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="6" id="chksamedi" {if in_array('6', $jours)}checked="checked"{/if}>{#day_6#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="DAYS_INCLUDED[]" value="0" id="chkdimanche" {if in_array('0', $jours)}checked="checked"{/if}>{#day_0#}
										</label>
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{assign var=heuresAffichees value=","|explode:$smarty.const.CONFIG_HOURS_DISPLAYED} {#options_heuresIncluses#} :</label>
									<div class="col-md-8 form-inline">
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="0" {if in_array('0', $heuresAffichees)}checked="checked"{/if}> 0{#tab_h#}-1{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="1" {if in_array('1', $heuresAffichees)}checked="checked"{/if}> 1{#tab_h#}-2{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="2" {if in_array('2', $heuresAffichees)}checked="checked"{/if}> 2{#tab_h#}-3{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="3" {if in_array('3', $heuresAffichees)}checked="checked"{/if}> 3{#tab_h#}-4{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="4" {if in_array('4', $heuresAffichees)}checked="checked"{/if}> 4{#tab_h#}-5{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="5" {if in_array('5', $heuresAffichees)}checked="checked"{/if}> 5{#tab_h#}-6{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="6" {if in_array('6', $heuresAffichees)}checked="checked"{/if}> 6{#tab_h#}-7{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="7" {if in_array('7', $heuresAffichees)}checked="checked"{/if}> 7{#tab_h#}-8{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="8" {if in_array('8', $heuresAffichees)}checked="checked"{/if}> 8{#tab_h#}-9{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="9" {if in_array('9', $heuresAffichees)}checked="checked"{/if}> 9{#tab_h#}-10{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="10" {if in_array('10', $heuresAffichees)}checked="checked"{/if}> 10{#tab_h#}-11{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="11" {if in_array('11', $heuresAffichees)}checked="checked"{/if}> 11{#tab_h#}-12{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="12" {if in_array('12', $heuresAffichees)}checked="checked"{/if}> 12{#tab_h#}-13{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="13" {if in_array('13', $heuresAffichees)}checked="checked"{/if}> 13{#tab_h#}-14{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="14" {if in_array('14', $heuresAffichees)}checked="checked"{/if}> 14{#tab_h#}-15{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="15" {if in_array('15', $heuresAffichees)}checked="checked"{/if}> 15{#tab_h#}-16{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="16" {if in_array('16', $heuresAffichees)}checked="checked"{/if}> 16{#tab_h#}-17{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="17" {if in_array('17', $heuresAffichees)}checked="checked"{/if}> 17{#tab_h#}-18{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="18" {if in_array('18', $heuresAffichees)}checked="checked"{/if}> 18{#tab_h#}-19{#tab_h#}
										</label>									
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="19" {if in_array('19', $heuresAffichees)}checked="checked"{/if}> 19{#tab_h#}-20{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="20" {if in_array('20', $heuresAffichees)}checked="checked"{/if}> 20{#tab_h#}-21{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="21" {if in_array('21', $heuresAffichees)}checked="checked"{/if}> 21{#tab_h#}-22{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="22" {if in_array('22', $heuresAffichees)}checked="checked"{/if}> 22{#tab_h#}-23{#tab_h#}
										</label>
										<label class="checkbox-inline">
											<input type="checkbox" name="HOURS_DISPLAYED[]" value="23" {if in_array('23', $heuresAffichees)}checked="checked"{/if}> 23{#tab_h#}-0{#tab_h#}
										</label>
									</div>
								</div>
								<div class="form-group form-inline col-md-12">
									<label class="col-md-4 control-label">{#options_dureeDefautJour#} :</label>
									<div class="col-md-8">
										<input name="DURATION_DAY" {if $smarty.session.isMobileOrTablet==1}type="time" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DURATION_DAY}"/>
										&nbsp;<span title="{#options_aide_dureeDefaut#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										{#options_dureeDefautMatin#} :
										<input name="DURATION_AM" {if $smarty.session.isMobileOrTablet==1}type="time" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DURATION_AM}" />
										&nbsp;<span title="{#options_aide_dureeDefaut#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										{#options_dureeDefautApresmidi#} :
										<input name="DURATION_PM" {if $smarty.session.isMobileOrTablet==1}type="time" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DURATION_PM}" />
										&nbsp;<span title="{#options_aide_dureeDefaut#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<input type="hidden" name="PLANNING_DATE_FORMAT" value="1"/>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{#options_nbMoisDefaut#} :</label>
									<div class="col-md-2">
										<input name="DEFAULT_NB_MONTHS_DISPLAYED" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DEFAULT_NB_MONTHS_DISPLAYED}" />
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{#options_nbjoursDefaut#} :</label>
									<div class="col-md-2">
										<input name="DEFAULT_NB_DAYS_DISPLAYED" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DEFAULT_NB_DAYS_DISPLAYED}" />
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{#options_nbLignes#} :</label>
									<div class="col-md-2">
										<input name="DEFAULT_NB_ROWS_DISPLAYED" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DEFAULT_NB_ROWS_DISPLAYED}" />
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{#options_nbJoursPasses#} :</label>
									<div class="col-md-2">
										<input name="DEFAULT_NB_PAST_DAYS" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_DEFAULT_NB_PAST_DAYS}" />
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#option_masquer_projet_weekend#} :</label>
									<div class="col-md-3">
										<select name="PLANNING_HIDE_WEEKEND_TASK" class="form-control input-middle">
											<option value="0" {if $smarty.const.CONFIG_PLANNING_HIDE_WEEKEND_TASK eq 0}selected="selected"{/if}>{#non#}</option>
											<option value="1" {if $smarty.const.CONFIG_PLANNING_HIDE_WEEKEND_TASK eq 1}selected="selected"{/if}>{#oui#}</option>
										</select>
										&nbsp;<span data-tooltip-content="#tooltip-PLANNING_HIDE_WEEKEND_TASK" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										<div class="tooltip-html"><span id="tooltip-PLANNING_HIDE_WEEKEND_TASK">{#options_aide_hide_weekend_task#}</span></div>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_statusAffichage#} :</label>
									<div class="col-md-4">
										<select name="PLANNING_AFFICHAGE_STATUS" class="form-control">
											<option value="aucun" {if $smarty.const.CONFIG_PLANNING_AFFICHAGE_STATUS eq 'aucun'}selected="selected"{/if}>{#options_statusAffichage_aucun#}</option>
											<option value="nom" {if $smarty.const.CONFIG_PLANNING_AFFICHAGE_STATUS eq 'nom'}selected="selected"{/if}>{#options_statusAffichage_nom#}</option>
											<option value="pourcentage" {if $smarty.const.CONFIG_PLANNING_AFFICHAGE_STATUS eq 'pourcentage'}selected="selected"{/if}>{#options_statusAffichage_pourcentage#}</option>
											<option value="pastille" {if $smarty.const.CONFIG_PLANNING_AFFICHAGE_STATUS eq 'pastille'}selected="selected"{/if}>{#options_statusAffichage_pastille#}</option>
										</select>
										&nbsp;<span title="{#options_aide_statusAffichage#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">{#options_raffraichissement#} :</label>
									<div class="col-md-2">
										<input name="REFRESH_TIMER" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_REFRESH_TIMER}" />
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_hauteurLigne#} :</label>
									<div class="col-md-3">
										<input name="PLANNING_LINE_HEIGHT" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_PLANNING_LINE_HEIGHT}" />
										&nbsp;<span title="{#options_aide_hauteurLigne#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_largeurColonne#} :</label>
									<div class="col-md-3">
										<input name="PLANNING_COL_WIDTH" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_PLANNING_COL_WIDTH}" />
										&nbsp;<span title="{#options_aide_largeurColonne#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_largeurColonneLarge#} :</label>
									<div class="col-md-3">
										<input name="PLANNING_COL_WIDTH_LARGE" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_PLANNING_COL_WIDTH_LARGE}" />
										&nbsp;<span title="{#options_aide_largeurColonneLarge#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>						
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_largeurCode#} :</label>
									<div class="col-md-3">
										<input name="PLANNING_CODE_WIDTH" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_PLANNING_CODE_WIDTH}" />
										&nbsp;<span title="{#options_aide_largeurCode#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_largeurCodeLarge#} :</label>
									<div class="col-md-3">
										<input name="PLANNING_CODE_WIDTH_LARGE" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_PLANNING_CODE_WIDTH_LARGE}" />
										&nbsp;<span title="{#options_aide_largeurCodeLarge#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_uneTacheParJour#} :</label>
									<div class="col-md-3">
										<select name="PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY" class="form-control input-middle">
											<option value="0" {if $smarty.const.CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY eq 0}selected="selected"{/if}>{#non#}</option>
											<option value="1" {if $smarty.const.CONFIG_PLANNING_ONE_ASSIGNMENT_MAX_PER_DAY eq 1}selected="selected"{/if}>{#oui#}</option>
										</select>
										&nbsp;<span title="{#options_aide_uneTacheParJour#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12 form-inline">
									<label class="col-md-4 control-label">{#options_repeterHeaderDate#} :</label>
									<div class="col-md-3">
										<input name="PLANNING_REPEAT_HEADER" {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_PLANNING_REPEAT_HEADER}" />
										&nbsp;<span title="{#options_aide_repeterHeaderDate#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group col-md-12">
									<div class="col-md-4"></div>
									<div class="col-md-8">
										<input type="submit" class="btn btn-primary" value="{#submit#}"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="param-divers">
						<form action="process/options.php" method="POST" class="form-horizontal">
							<fieldset>
								<legend>
									{#options_divers#}
								</legend>
								<div class="form-group form-inline">
									<label class="col-md-4 control-label">{#options_couleursProjets#} :</label>
									<div class="col-md-6">
										<input name="PROJECT_COLORS_POSSIBLE" type="text" value="{$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE}" class="form-control input-xlarge" />
										&nbsp;<span data-tooltip-content="#tooltip-PROJECT_COLORS_POSSIBLE" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										<div class="tooltip-html"><span id="tooltip-PROJECT_COLORS_POSSIBLE">{#options_aide_couleursPossibles#}</span></div>
									</div>
								</div>
								<div class="form-group form-inline">
									<label class="col-md-4 control-label">{#options_lienDefaut#} :</label>
									<div class="col-md-6">
										<input name="DEFAULT_PERIOD_LINK" type="text" value="{$smarty.const.CONFIG_DEFAULT_PERIOD_LINK}" class="form-control input-xlarge" />
										&nbsp;<span title="{#options_aide_LinkPeriod#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group form-inline">
									<label class="col-md-4 control-label">{#options_urlRedirection#} :</label>
									<div class="col-md-6">
										<input name="LOGOUT_REDIRECT" type="text" value="{$smarty.const.CONFIG_LOGOUT_REDIRECT}" class="form-control input-xlarge" />
										&nbsp;<span title="{#options_aide_redirect#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4"></div>
									<div class="col-md-6">
										<br />
										<input type="submit" class="btn btn-primary" value="{#submit#}"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="param-smtp">
						<form action="process/options.php" method="POST" class="form-horizontal">
							<fieldset>
								<legend>
									{#options_smtp_titre#}
								</legend>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_smtp_host#} :</label>
									<div class="col-md-6">
										<input name="SMTP_HOST" type="text" value="{$smarty.const.CONFIG_SMTP_HOST|escape:'html'|escape:'javascript'}" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_smtp_port#} :</label>
									<div class="col-md-6">
										<input name="SMTP_PORT"  {if $smarty.session.isMobileOrTablet==1}type="number" class="form-control"{else}type="text" class="form-control input-small"{/if} value="{$smarty.const.CONFIG_SMTP_PORT|escape:'html'|escape:'javascript'}" />
										&nbsp;<span data-tooltip-content="#tooltip-SMTP_PORT" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
										<div class="tooltip-html"><span id="tooltip-SMTP_PORT">{#options_aide_smtp#}</span></div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_smtp_secure#}</label>
									<div class="col-md-6">
										<select name="SMTP_SECURE" class="form-control input-large">
											<option value="" {if $smarty.const.CONFIG_SMTP_SECURE eq ""}selected="selected"{/if}>{#options_smtp_nonSecurise#}</option>
											<option value="ssl" {if $smarty.const.CONFIG_SMTP_SECURE eq "ssl"}selected="selected"{/if}>SSL</option>
											<option value="tls" {if $smarty.const.CONFIG_SMTP_SECURE eq "tls"}selected="selected"{/if}>TLS</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_smtp_from#} :</label>
									<div class="col-md-6">
										<input name="SMTP_FROM" type="text" value="{$smarty.const.CONFIG_SMTP_FROM|escape:'html'|escape:'javascript'}" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_smtp_login#} :</label>
									<div class="col-md-6">
										<input name="SMTP_LOGIN" type="text" value="{$smarty.const.CONFIG_SMTP_LOGIN|escape:'html'|escape:'javascript'}" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_smtp_password#} :</label>
									<div class="col-md-6">
										<input name="SMTP_PASSWORD" type="password" size="30" value="{if $smarty.const.CONFIG_SMTP_LOGIN neq ""}XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX{/if}" class="form-control"/>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4"></div>
									<div class="col-md-6">
										<br />
										<input type="submit" class="btn btn-primary" value="{#submit#}"/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="tab-pane" id="param-testmail">
						<form action="process/options.php" method="POST" class="form-horizontal">
							<fieldset>
								<legend>
									{#options_envoyerMailTest#}
								</legend>
								<div class="form-group">
									<label class="col-md-4 control-label">{#options_envoyerMailTest_destinataire#} :</label>
									<div class="col-md-6">
										<input name="mailTestDestinataire" type="text" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label"></label>
									<div class="col-md-6">
										<input name="smtp_traces" type="checkbox" /> {#afficher_logs_smtp#}
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-4"></div>
									<div class="col-md-6">
										<br />
										<input type="submit" class="btn btn-primary" value="{#submit#}" {if $smarty.const.CONFIG_SMTP_HOST eq '' || $smarty.const.CONFIG_SMTP_PORT eq '' || $smarty.const.CONFIG_SMTP_FROM eq ''}disabled="disabled"{/if}/>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
{literal}
	jQuery(document).ready(function(){
		jQuery("a[data-toggle=popover]")
			.popover()
			.click(function(e) {
			e.preventDefault()
		});
	});

	jQuery('#myTab a').click(function (e) {
		e.preventDefault();
		jQuery(this).tab('show');
	})
	
	var rand = function() {
	return Math.random().toString(36).substr(2); // remove `0.`
	};
	
	var token = function() {
	var key=rand() + rand() + rand(); // to make it longer
	jQuery('#CONFIG_SECURE_KEY').attr('value', key);	
	};
{/literal}
</script>

{include file="www_footer.tpl"}