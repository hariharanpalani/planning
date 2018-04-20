{* Smarty *}

{#usersBulkRights_help#}
<br><br>

<form class="form-horizontal" method="post" action="" onsubmit="return false;" name="usersBulkRightsForm" autocomplete="off">
<div class="container-fluid">
	<div class="form-group col-md-12">
		<div class="form-group col-md-12">
			<label class="control-label col-md-4">{#usersBulkRights_users#} :</label>
			<div class="col-md-8">
				<select name="bulk_user_id" multiple="multiple" id="bulk_user_id" class="hidden multiselect">
					{if $listeUsers|@count eq 0}
						<option>&nbsp;{#formFiltreUserAucunProjet#}</option>
					{else}
						<optgroup id="g0" value="1" label="{#cocheUserSansGroupe#}">
						{assign var=groupeTemp value=""}
						{foreach from=$listeUsers item=userCourant name=loopUsers}
							{if $userCourant.user_groupe_id neq $groupeTemp}
								</optgroup><optgroup id="g{$userCourant.user_groupe_id}" value="1" label="{$userCourant.groupe_nom}">
							{/if}
						<option value="{$userCourant.user_id}">{$userCourant.nom|escape} ({$userCourant.user_id|escape})</option> 								
						{assign var=groupeTemp value=$userCourant.user_groupe_id}
						{/foreach}
					{/if}
					</optgroup>
				</select>
				{literal}
				<script language="javascript">
					$("#bulk_user_id").multiselect({
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
					$("#bulk_user_id").show();
				</script>
				{/literal}
			</div>
		</div>

	</div>
	<hr width="90%">
	{#usersBulkRights_rights#} :
	<br>
	<div class="form-group col-md-12">
	<div class="tab-content">	
		<div class="tab-pane fade in active" id="droits">
			<div class="form-group">
				<label class="control-label col-md-2">{#droits_utilisateurs#} :</label>
				<div class="col-md-10">
					<label class="radio-inline tailleDroits">
						<input type="radio"name="users_manage" id="droit1" value="" checked="checked" >&nbsp;{#droits_aucundroitUser#}
					</label>
					<label class="radio-inline tailleDroits">
						<input type="radio"name="users_manage" id="users_manage_all" value="users_manage_all" >{#droits_gererTousUsers#}
					</label>
				</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_projets#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projects_manage" id="droit2" value="" checked="checked" >{#droits_aucunDroitProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projects_manage" id="projects_manage_all" value="projects_manage_all" >{#droits_gererTousProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projects_manage" id="projects_manage_own" value="projects_manage_own" >{#droits_uniquementProjProprio#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_groupesProjets#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projectgroups_manage" id="droit3" value="" checked="checked" >{#droits_groupesProjetsAucun#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="projectgroups_manage" id="projectgroups_manage_all" value="projectgroups_manage_all" >{#droits_gererTousGroupes#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_modifPlanning#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_readonly" value="tasks_readonly" checked="checked" >&nbsp;{#droits_planningLectureSeule#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_modify_all" value="tasks_modify_all" >{#droits_planningTousProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_modify_own_project" value="tasks_modify_own_project" >{#droits_planningProjetsProprio#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_modif" id="tasks_modify_own_task" value="tasks_modify_own_task" >{#droits_planningTachesAssignees#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_projets_visibles#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_all_projects" value="tasks_view_all_projects" checked="checked" >{#droits_vueTousProjets#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_team_projects" value="tasks_view_team_projects" >{#droits_vueProjetsEquipe#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_own_projects" value="tasks_view_own_projects" >{#droits_vueProjetsAssignes#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view" id="tasks_view_only_own" value="tasks_view_only_own" >{#droits_tasks_view_only_own#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_users_visibles#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view_users" id="tasks_view_all_users" value="tasks_view_all_users"  onChange="{literal}if(this.checked){document.getElementById('divSpecificUsers').style.display='none';}{/literal}" checked="checked">{#droits_tasks_view_all_users#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="planning_view_users" id="tasks_view_specific_users" value="tasks_view_specific_users"  onChange="{literal}if(this.checked){document.getElementById('divSpecificUsers').style.display='inline-block';}{/literal}">{#droits_tasks_view_specific_users#}
						</label>
						<div id="divSpecificUsers" style="width:350px;display:none">
							<select multiple="multiple" name="specific_user_id" id="specific_user_id" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" onfocus="blur();" style="width:100%">
								{assign var=groupeTemp value=""}
								{foreach from=$listeUsers item=userCourant name=loopUsers}
									{if $userCourant.user_groupe_id neq $groupeTemp}
										<optgroup label="{$userCourant.groupe_nom}">
									{/if}
									<option value="{$userCourant.user_id}" {if in_array($userCourant.user_id, $listUsersRights)}selected="selected"{/if}>{$userCourant.nom} - {$userCourant.user_id}</option>
									{if $userCourant.user_groupe_id neq $groupeTemp}
										</optgroup>
									{/if}
									{assign var=groupeTemp value=$userCourant.user_groupe_id}
								{/foreach}
							</select>
						</div>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_lieux#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="lieux" id="droit6" value="" checked="checked" >{#droits_aucunLieux#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="lieux" id="lieux_all" value="lieux_all" >{#droits_lieuxAcces#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_ressources#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="ressources" id="droit7" value="" checked="checked" >{#droits_aucunRessources#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="ressources" id="ressources_all" value="ressources_all" >{#droits_ressourcesAcces#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_parametres#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits">
							<input type="radio" name="parameters" id="droit5" value="" checked="checked" >{#droits_aucunParametres#}
						</label>
						<label class="radio-inline tailleDroits">
							<input type="radio" name="parameters" id="parameters_modify" value="parameters_all" >{#droits_parametresAcces#}
						</label>
					</div>
			</div>
			<div class="form-group">
					<label class="control-label col-md-2">{#droits_stats#} :</label>
					<div class="col-md-10">
						<label class="radio-inline tailleDroits" style="padding-left:0px">
							<input type="checkbox" id="stats_users" value="stats_users" >{#droits_stats_users#}
						</label>
						<label class="radio-inline tailleDroits" style="padding-left:0px">
							<input type="checkbox" id="stats_projects" value="stats_projects" >{#droits_stats_projects#}
						</label>
					</div>
			</div>
		</div>
	</div>
	<div class="form-group col-md-12">
		<div class="col-md-12">
			<div class="col-md-2"></div>
			<div class="col-md-6">
				<input type="button" class="btn btn-primary" value="{#submit#}" onClick="if(confirm('{#confirm#|escape:"javascript"}'))bulk_users_ids=getSelectValue('bulk_user_id'); specific_users_ids=getSelectValue('specific_user_id'); xajax_usersBulkRightsSubmit(bulk_users_ids, new Array(getRadioValue('users_manage'), getRadioValue('projects_manage'), getRadioValue('projectgroups_manage'), getRadioValue('planning_modif'), getRadioValue('planning_view'), getRadioValue('planning_view_users'), getRadioValue('lieux'), getRadioValue('ressources'), getRadioValue('parameters'), ($('#stats_users').is(':checked') ? $('#stats_users').val() : ''), ($('#stats_projects').is(':checked') ? $('#stats_projects').val() : '')), specific_users_ids);" />
			</div>
		</div>
	</div>
</div>