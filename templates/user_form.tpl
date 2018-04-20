{* Smarty *}

<form class="form-horizontal" method="post" action="" onsubmit="return false;" name="formUser" autocomplete="off">
{* pour tester si compte d�j� existant ou pas *}
<input type="hidden" id="user_id_origine" value="{$user_form.user_id}">
<div class="container-fluid">
	<div class="form-group col-md-12">
		<div class="form-group col-md-6">
			<label class="control-label col-md-4">{#user_identifiant#} :</label>
			<div class="col-md-6">
				{if $user_form.saved eq 1}
					<p class="form-control-static">{$user_form.user_id|escape:"html"}</p>
					<input id="user_id" type="hidden" value="{$user_form.user_id|escape:"html"}" />
				{else}
					<input class="form-control" id="user_id" type="text" value="{$user_form.user_id|escape:"html"}" maxlength="20" />
				{/if}
			</div>
		</div>
		<div class="form-group col-md-6">
			<label class="control-label col-md-4">{#user_groupe#} :</label>
			<div class="col-md-6">
				<select id="user_groupe_id" class="form-control{if $smarty.session.isMobileOrTablet==0} select2{/if}">
					<option value="">- - - - - - - - - - -</option>
					{foreach from=$groupes item=groupe}
						<option value="{$groupe.user_groupe_id}" {if $user_form.user_groupe_id eq $groupe.user_groupe_id}selected="selected"{/if}>{$groupe.nom|escape}</option>
					{/foreach}
				</select>
			</div>
		</div>
	</div>
	<div class="form-group col-md-12">
		<div class="form-group col-md-6">
				<label class="control-label col-md-4">{#user_nom#} :</label>
				<div class="col-md-6">
					<input id="nom" class="form-control" type="text" value="{$user_form.nom|escape:"html"}" maxlength="100" />
				</div>
		</div>
		<div class="form-group col-md-6">
				<label class="control-label col-md-4">{#user_email#} :</label>
				<div class="col-md-6">
					<input id="email_user" class="form-control" type="text" value="{$user_form.email|escape:"html"}" maxlength="255" />
				</div>
		</div>
	</div>
	<div class="form-group col-md-12">
		<div class="form-group col-md-6">
				<label class="control-label col-md-4">{#user_login#} :</label>
				<div class="col-md-6">
					<input id="tmp_lo" class="form-control" type="text" value="{$user_form.login|escape:"html"}" maxlength="30" autocomplete="off" />
				</div>
		</div>
		<div class="form-group col-md-6">
				<label class="control-label col-md-4">{#user_password#} :</label>
				<div class="col-md-6">
					<input id="tmp_pa" class="form-control" type="password" value="" maxlength="20" autocomplete="off" />
				</div>
		</div>
	</div>
	{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 1 }
		<div class="form-group col-md-12">
			<div class="form-group col-md-6">
				<div class="col-md-4 control-label">{#winPeriode_ressource#}:</div>
				<div class="col-md-6">
					<select name="ressource" id="ressource" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="20" style="width:100%" >
						<option value=""></option>
						{foreach from=$listeRessources item=ressourceTmp}
							<option value="{$ressourceTmp.ressource_id}" {if $user_form.resource_id eq $ressourceTmp.ressource_id} selected="selected" {/if}>{$ressourceTmp.nom}</option>
						{/foreach}
					</select>
				</div>
			</div>
		</div>
	{/if}
	<div class="form-group col-md-12">
	<ul class="nav nav-tabs">
        <li class="active"><a href="#droits" data-toggle="tab">{#user_droits_court#}</a></li>
        <li><a href="#perso" data-toggle="tab">{#user_perso_notif#}</a></li>
        <li><a href="#infos" data-toggle="tab">{#user_infos_perso#}</a></li>
	</ul>
	</div>
	<div class="form-group col-md-12">
	<div class="tab-content">	
		<div class="tab-pane fade in active" id="droits">
			<div class="form-group">
				<label class="control-label col-md-2">{#droits_utilisateurs#} :</label>
				<div class="col-md-10">
					<label class="radio-inline tailleDroits">
						<input type="radio"name="users_manage" id="droit1" value="" {if !in_array("users_manage_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_aucundroitUser#}
					</label>
					<label class="radio-inline tailleDroits">
						<input type="radio"name="users_manage" id="users_manage_all" value="users_manage_all" {if in_array("users_manage_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_gererTousUsers#}
					</label>
				</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_projets#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projects_manage" id="droit2" value="" {if !in_array("projects_manage_all", $user_form.tabDroits) && !in_array("projects_manage_own", $user_form.tabDroits)}checked="checked"{/if}>{#droits_aucunDroitProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projects_manage" id="projects_manage_all" value="projects_manage_all" {if in_array("projects_manage_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_gererTousProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projects_manage" id="projects_manage_own" value="projects_manage_own" {if in_array("projects_manage_own", $user_form.tabDroits)}checked="checked"{/if}>{#droits_uniquementProjProprio#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_groupesProjets#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projectgroups_manage" id="droit3" value="" {if !in_array("projectgroups_manage_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_groupesProjetsAucun#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projectgroups_manage" id="projectgroups_manage_all" value="projectgroups_manage_all" {if in_array("projectgroups_manage_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_gererTousGroupes#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_modifPlanning#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_readonly" value="tasks_readonly" {if in_array("tasks_readonly", $user_form.tabDroits) || (!in_array("tasks_modify_all", $user_form.tabDroits) && !in_array("tasks_modify_own_project", $user_form.tabDroits) && !in_array("tasks_modify_own_task", $user_form.tabDroits))}checked="checked"{/if}>{#droits_planningLectureSeule#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_modify_all" value="tasks_modify_all" {if in_array("tasks_modify_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_planningTousProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_modify_own_project" value="tasks_modify_own_project" {if in_array("tasks_modify_own_project", $user_form.tabDroits)}checked="checked"{/if}>{#droits_planningProjetsProprio#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_modify_own_task" value="tasks_modify_own_task" {if in_array("tasks_modify_own_task", $user_form.tabDroits)}checked="checked"{/if}>{#droits_planningTachesAssignees#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_projets_visibles#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_all_projects" value="tasks_view_all_projects" {if in_array("tasks_view_all_projects", $user_form.tabDroits) || !in_array("tasks_view_own_projects", $user_form.tabDroits)}checked="checked"{/if}>{#droits_vueTousProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_team_projects" value="tasks_view_team_projects" {if in_array("tasks_view_team_projects", $user_form.tabDroits)}checked="checked"{/if}>{#droits_vueProjetsEquipe#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_own_projects" value="tasks_view_own_projects" {if in_array("tasks_view_own_projects", $user_form.tabDroits)}checked="checked"{/if}>{#droits_vueProjetsAssignes#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_only_own" value="tasks_view_only_own" {if in_array("tasks_view_only_own", $user_form.tabDroits)}checked="checked"{/if}>{#droits_tasks_view_only_own#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_users_visibles#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view_users" id="tasks_view_all_users" value="tasks_view_all_users" {if in_array("tasks_view_all_users", $user_form.tabDroits) || !in_array("tasks_view_all_users", $user_form.tabDroits)}checked="checked"{/if} onChange="{literal}if(this.checked){document.getElementById('divSpecificUsers').style.display='none';}{/literal}">{#droits_tasks_view_all_users#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view_users" id="tasks_view_specific_users" value="tasks_view_specific_users" {if in_array("tasks_view_specific_users", $user_form.tabDroits)}checked="checked"{/if} onChange="{literal}if(this.checked){document.getElementById('divSpecificUsers').style.display='inline-block';}{/literal}">{#droits_tasks_view_specific_users#}
						</label>
						<div id="divSpecificUsers" style="display:{if in_array("tasks_view_specific_users", $user_form.tabDroits)}inline-block{else}none{/if}">
							<select name="specific_user_id" multiple="multiple" id="specific_user_id" class="hidden multiselect" style="clear:both">
								{if $listeUsers|@count eq 0}
									<option>&nbsp;{#formFiltreUserAucunProjet#}</option>
								{else}
									<optgroup id="g0" value="1" label="{#cocheUserSansGroupe#}">
									{assign var=groupeTemp value=""}
									{foreach from=$listeUsers item=userCourant name=loopUsers}
										{if $userCourant.user_groupe_id neq $groupeTemp}
											</optgroup><optgroup id="g{$userCourant.user_groupe_id}" value="1" label="{$userCourant.groupe_nom}">
										{/if}
									<option value="{$userCourant.user_id}" {if in_array($userCourant.user_id, $listUsersRights)}selected="selected"{/if}>{$userCourant.nom|escape} ({$userCourant.user_id|escape})</option> 								
									{assign var=groupeTemp value=$userCourant.user_groupe_id}
									{/foreach}
								{/if}
								</optgroup>
							</select>
							{literal}
							<script language="javascript">
								$("#specific_user_id").multiselect({
									selectAll:false,
									validateCloseOnly:true,
									noUpdatePlaceholderText:true,
									nameSuffix: 'user',
									desactivateUrl: 'process/planning.php?desactiverFiltreUser=1',
									placeholder: '{/literal}{#formChoixUser#}{literal}',
									texts: {
									   selectAll    : '{/literal}{#formFiltreUserCocherTous#}{literal}',
									   unselectAll    : '{/literal}{#formFiltreUserDecocherTous#}{literal}',
									   disableFilter : '{/literal}{#formFiltreUserDesactiver#}{literal}',
									   validateFilter : '{/literal}{#submit#}{literal}',
									   search : '{/literal}{#search#}{literal}'
									},
								});
								$("#specific_user_id").show();
							</script>
							{/literal}

						</div>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_lieux#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="lieux" id="droit6" value="" {if !in_array("lieux_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_aucunLieux#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="lieux" id="lieux_all" value="lieux_all" {if in_array("lieux_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_lieuxAcces#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_ressources#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="ressources" id="droit7" value="" {if !in_array("ressources_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_aucunRessources#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="ressources" id="ressources_all" value="ressources_all" {if in_array("ressources_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_ressourcesAcces#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_parametres#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="parameters" id="droit5" value="" {if !in_array("parameters_modify", $user_form.tabDroits)}checked="checked"{/if}>{#droits_aucunParametres#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="parameters" id="parameters_modify" value="parameters_all" {if in_array("parameters_all", $user_form.tabDroits)}checked="checked"{/if}>{#droits_parametresAcces#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_stats#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits" style="padding-left:0px">
							<input type="checkbox" id="stats_users" value="stats_users" {if in_array("stats_users", $user_form.tabDroits)}checked="checked"{/if}>&nbsp;{#droits_stats_users#}
						</label>
						<label class="radio-inline tailleDroits" style="padding-left:0px">
							<input type="checkbox" id="stats_projects" value="stats_projects" {if in_array("stats_projects", $user_form.tabDroits)}checked="checked"{/if}>&nbsp;{#droits_stats_projects#}
						</label>
					</div>
			</div>
		</div>
		<div class="tab-pane fade" id="perso">
			<div class="form-group">
					<label class="control-label col-md-3">{#user_visiblePlanning#} :</label>
					<div class="col-md-3">
						<label class="radio-inline tailleDroits">
							<input type="radio" id="visible_planningOui" name="visible_planning" value="oui" {if ($user_form.saved eq 0) || ($user_form.visible_planning eq "oui")}checked="checked"{/if}>{#oui#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" id="visible_planningNon" name="visible_planning" value="non" {if ($user_form.saved eq 1) && ($user_form.visible_planning eq "non")}checked="checked"{/if}>{#non#}
						</label>
					</div>
					<label class="control-label col-md-2">{#user_couleur#} :</label>
						<div class="col-md-3">
							{if $smarty.session.couleurExUser neq ""}
								{assign var=couleurExUser value=$smarty.session.couleurExUser}
							{else}
								{assign var=couleurExUser value="ffffff"}
							{/if}
							{if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}
								{if $smarty.session.isMobileOrTablet==1}
									<input class="form-control" name="couleur" id="couleur" maxlength="6" type="color" list="colors" value="#{if $projet.couleur eq ''}{$couleurExProjet}{else}{$projet.couleur}{/if}" />
									<datalist id="colors">
										{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
										<option>{$couleurTmp}</option>
										{/foreach}
									</datalist>
								{else}
									<select name="couleur2" id="couleur2" style="background-color:#{$user_form.couleur};color:{"#"|cat:$user_form.couleur|buttonFontColor}" class="form-control jscolor" >
									{if $user_form.couleur eq ""}<option value="">{#winProjet_couleurchoix#}</option>{/if}
									{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
										<option value="{$couleurTmp|replace:'#':''}" style="background-color:{$couleurTmp};color:{$couleurTmp|buttonFontColor}" {if $couleurTmp eq "#"|cat:$user_form.couleur}selected="selected"{/if}>{$couleurTmp|replace:'#':''}</option>
									{/foreach}
								</select>
								{/if}
							{else}
								<input id="couleur" {if $smarty.session.isMobileOrTablet==1}type="color"{else}type="text"{/if} class="form-control" value="#{$user_form.couleur|escape:"html"}"/>
							{/if}
						</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-3">{#user_notifications#} :</label>
					<div class="col-md-3">
						<label class="radio-inline tailleDroits">
							<input type="radio" id="notificationsOui" name="notifications" value="oui" {if $user_form.notifications eq "oui"}checked="checked"{/if}>{#oui#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" id="notificationsNon" name="notifications" value="non" {if $user_form.notifications eq "non"}checked="checked"{/if}>{#non#}
						</label>
					</div>
				{if $user_form.saved eq 0}
					<div class="col-md-5">
						<label class="checkbox-inline">
							<input type="checkbox" id="envoiMailPwd" name="envoiMailPwd" value="true" />{#user_mailPwd#}
						</label>
					</div>
				{else}
					<input type="hidden" id="envoiMailPwd" name="envoiMailPwd" value="false" />
				{/if}
				</div>
		</div>	
        <div class="tab-pane fade" id="infos">
			<div class="form-group">
				<label class="control-label col-md-3">{#user_adress#} :</label>
				<div class="col-md-6">
					<input id="user_adress" class="form-control" type="text" value="{$user_form.adresse|escape:"html"}" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">{#user_phone#} :</label>
				<div class="col-md-2">
					<input id="user_phone" class="form-control input-large" type="text" value="{$user_form.telephone|escape:"html"}" maxlength="20" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">{#user_mobile#} :</label>
				<div class="col-md-2">
					<input id="user_mobile" class="form-control input-large" type="text" value="{$user_form.mobile|escape:"html"}" maxlength="20" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">{#user_metier#} :</label>
				<div class="col-md-6">
					<input id="user_metier" class="form-control" type="text" value="{$user_form.metier|escape:"html"}" maxlength="100" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">{#user_comment#} :</label>
				<div class="col-md-6">
					<textarea id="user_comment" class="form-control">{$user_form.commentaire|escape:"html"}</textarea>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div class="form-group col-md-12">
		<div class="col-md-12">
			<div class="col-md-2"></div>
			<div class="col-md-6">
				<input type="button" class="btn btn-primary" value="{#submit#}" onClick="specific_users_ids=getSelectValue('specific_user_id');xajax_submitFormUser($('#user_id').val(), $('#user_id_origine').val(), $('#user_groupe_id').val(), $('#nom').val(), $('#email_user').val(), $('#tmp_lo').val(), $('#tmp_pa').val(), $('#visible_planningOui').is(':checked'), {if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}$('#couleur2 option:selected').val(){else}$('#couleur').val(){/if}, $('#notificationsOui').is(':checked'), $('#envoiMailPwd').is(':checked'), new Array(getRadioValue('users_manage'), getRadioValue('projects_manage'), getRadioValue('projectgroups_manage'), getRadioValue('planning_modif'), getRadioValue('planning_view'), getRadioValue('planning_view_users'), getRadioValue('lieux'), getRadioValue('ressources'), getRadioValue('parameters'), ($('#stats_users').is(':checked') ? $('#stats_users').val() : ''), ($('#stats_projects').is(':checked') ? $('#stats_projects').val() : '')), $('#user_adress').val(), $('#user_phone').val(),$('#user_mobile').val(), $('#user_metier').val(), $('#user_comment').val(), specific_users_ids, $('#ressource').val());" />
			</div>
		</div>
	</div>
</div>