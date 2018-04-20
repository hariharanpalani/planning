{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank" id="periodForm">
	<input type="hidden" id="periode_id" name="periode_id" value="{$periode.periode_id}" />
	<input type="hidden" id="saved" name="saved" value="{$periode.saved}" />
	<div class="container-fluid">
	<div class="form-group col-md-12">
		<label class="col-md-2 control-label">{#winPeriode_titre#} :</label>
		<div class="col-md-10">
			<input type="text" class="form-control" name="titre" id="titre" maxlength="2000" value="{$periode.titre|escape}" onFocus="xajax_autocompleteTitreTache($('#projet_id').val());"   data-provide="typeahead" class="input-xxlarge" tabindex="21" />
		</div>
	</div>
	<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_projet#} :</label>
			<div class="col-md-4">
				<select name="projet_id" id="projet_id" class="form-control {if !$smarty.session.isMobileOrTablet}select2{/if}" tabindex="1" style="width:100%">
					<option value="">- - - - - - - - - - -</option>
					{assign var="groupeCourant" value="-1"}
					{foreach from=$listeProjets item=projetTmp}
						{if $groupeCourant != $projetTmp.groupe_id}
							{assign var="groupeCourant" value=$projetTmp.groupe_id}
							{if $projetTmp.groupe_id == ""}
								{assign var="nomgroupe" value=#projet_liste_sansGroupes#}
							{else}
								{assign var="nomgroupe" value=$projetTmp.nom_groupe}
							{/if}
							<optgroup label="{$nomgroupe}"></optgroup>
						{/if}
						<option value="{$projetTmp.projet_id}" {if $periode.projet_id eq $projetTmp.projet_id}selected="selected"{/if} {if isset($projet_id_choisi) && $projet_id_choisi eq $projetTmp.projet_id}selected="selected"{/if}>{$projetTmp.nom} ({$projet.projet_id}) {if $projet.livraison neq ''} - S{$projet.livraison}{/if}</option>
					{/foreach}
				</select>
			</div>
			<label class="col-md-2 control-label">{#winPeriode_user#} :</label>
			<div class="col-md-4">
				<select multiple="multiple" name="user_id" id="user_id" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="2" style="width:100%">
					{assign var=groupeTemp value=""}
					{foreach from=$listeUsers item=userCourant name=loopUsers}
						{if $userCourant.user_groupe_id neq $groupeTemp}
							<optgroup label="{$userCourant.groupe_nom}">
						{/if}
						<option value="{$userCourant.user_id}" {if $periode.user_id eq $userCourant.user_id}selected="selected"{/if} {if isset($user_id_choisi) && $user_id_choisi eq $userCourant.user_id}selected="selected"{/if}>{$userCourant.nom} - {$userCourant.user_id}</option>
						{if $userCourant.user_groupe_id neq $groupeTemp}
							</optgroup>
						{/if}
						{assign var=groupeTemp value=$userCourant.user_groupe_id}
					{/foreach}
				</select>
			</div>
		</div>
		{if isset($estFilleOuParente)}
		<div class="form-group col-md-12">
			<label class="col-md-2 control-label"></label>
			<div class="col-md-4">
				<label class="checkbox-inline"><input type="checkbox" checked="checked" id="appliquerATous" value="1">	{#winPeriode_appliquerATous#}</label>
			</div>
		</div>
		{/if}
		<div class='col-md-12'><hr /></div>
		<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_debut#} :</label>
			<div class="col-md-4">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="form-control" name="date_debut" id="date_debut" maxlength="10" value="{$periode.date_debut|forceISODateFormat}" tabindex="4" />
				{else}
					<input type="text" class="form-control datepicker" name="date_debut" id="date_debut" maxlength="10" value="{$periode.date_debut|sqldate2userdate}" tabindex="4" />
				{/if}
			</div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_fin#} :</label>
			<div class="col-md-10">
				<label class="radio-inline">
					<input type="radio" name="radioChoixFin" id="radioChoixFinDate" value="" {if $periode.duree_details eq ""}checked="checked"{/if} onChange="$('#divFinChoixDate').removeClass('hidden');$('#divFinChoixDuree').addClass('hidden');" tabindex="5" />&nbsp;{#winPeriode_finChoixDate#}
				</label>
				<label class="radio-inline">
					<input type="radio" name="radioChoixFin" id="radioChoixFinDuree" value="" {if $periode.duree_details neq ""}checked="checked"{/if} onChange="$('#divFinChoixDuree').removeClass('hidden');$('#divFinChoixDate').addClass('hidden');" tabindex="6" />&nbsp;{#winPeriode_finChoixDuree#}
				</label>
			</div>
		</div>
		<div class="form-group col-md-12" id="divFinChoixDate">
			<label class="col-md-2 control-label emptylabel">&nbsp;</label>
			<div class="col-md-10">
				{if $smarty.session.isMobileOrTablet==1}
					<input type="date" class="form-control" name="date_fin" id="date_fin" maxlength="10" value="{$periode.date_fin|forceISODateFormat}" onFocus="remplirDateFinPeriode();videChampsFinTache(this.id);" onChange="videChampsFinTache(this.id);" tabindex="7" />
				{else}
					<input type="text" class="form-control datepicker" name="date_fin" id="date_fin" maxlength="10" value="{$periode.date_fin|sqldate2userdate}" onFocus="remplirDateFinPeriode();videChampsFinTache(this.id);" onChange="videChampsFinTache(this.id);" tabindex="7" />
				{/if}
				{#winPeriode_ouNBJours#} :
				<input type="number" class="form-control input-small" name="nb_jours" id="nb_jours" size="1" maxlength="2" onChange="videChampsFinTache(this.id);" tabindex="10" />
			{if $periode.periode_id neq 0 && $periode.date_fin neq ""}
				<label class="checkbox-inline" ><input type="checkbox" id="conserver_duree" name="conserver_duree" value="1" onClick="videChampsFinTache('');" tabindex="11" />{#winPeriode_conserverDuree#|sprintf:$nbJours}</label>
			{else}
				<input type="hidden" id="conserver_duree" value="" />
			{/if}
			</div>
		</div>
			<div class="form-group col-md-12 form-inline {if $periode.duree_details eq ''} hidden{/if}" id="divFinChoixDuree">
				<label class="col-md-2 control-label"></label>
				<div class="col-md-3">
					{#winPeriode_ouNBHeures#} <span title='{#winPeriode_FormatDuree#|escape:"quotes"}' class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span> :
					<input type="time" class="form-control input-middle" name="duree" id="duree" size="3" maxlength="5" value="{if $periode.duree_details eq 'duree'}{$periode.duree|sqltime2usertime}{/if}" onFocus="if(this.value == '')this.value='{$smarty.const.CONFIG_DURATION_DAY|usertime2sqltime:"short"}';" onChange="videChampsFinTache(this.id);" tabindex="12" />
				</div>
				<div class="col-md-7">
					{#winPeriode_heureDebut#} <span title='{#winPeriode_FormatDuree#|escape:"quotes"}' class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span> :
					<input type="time" class="form-control input-middle" id="heure_debut" id="heure_debut" size="3" maxlength="5" value="{if isset($periode.duree_details_heure_debut)}{$periode.duree_details_heure_debut|sqltime2usertime}{/if}" onChange="videChampsFinTache(this.id);" tabindex="13" />
					{#winPeriode_heureFin#} <span title='{#winPeriode_FormatDuree#|escape:"quotes"}' class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span> :
					<input type="time" class="form-control input-middle" id="heure_fin" size="3" maxlength="5" value="{if isset($periode.duree_details_heure_fin)}{$periode.duree_details_heure_fin|sqltime2usertime}{/if}" onChange="videChampsFinTache(this.id);" tabindex="14" />
					<br />
					<label class="checkbox-inline"><input type="checkbox" id="matin" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'AM'}checked="checked"{/if} tabindex="15">{#winPeriode_matin#} ({$smarty.const.CONFIG_DURATION_AM}{#tab_h#})</label>
					<label class="checkbox-inline"><input type="checkbox" id="apresmidi" onChange="videChampsFinTache(this.id);" {if $periode.duree_details eq 'PM'}checked="checked"{/if} tabindex="16">{#winPeriode_apresmidi#} ({$smarty.const.CONFIG_DURATION_PM}{#tab_h#})</label>
				</div>
			</div>
		<div class='col-md-12'><hr /></div>
		<div class="form-group col-md-12">
			{if !isset($estFilleOuParente)}
				<input type="hidden" id="appliquerATous" value="0">
				<label class="col-md-2 control-label">{#winPeriode_repeter#} :</label>
				<div class="col-md-4">
					<select name="repetition" id="repetition"
						onChange="{literal}
						if(this.value=='jour'){$('#divOptionsRepetitionJour').removeClass('hidden');$('#divExceptionRepetition').removeClass('hidden');}else{$('#divOptionsRepetitionJour').addClass('hidden');}	if(this.value=='semaine'){$('#divOptionsRepetitionSemaine').removeClass('hidden');$('#divExceptionRepetition').removeClass('hidden');}else{$('#divOptionsRepetitionSemaine').addClass('hidden');}
						if(this.value=='mois'){$('#divOptionsRepetitionMois').removeClass('hidden');$('#divExceptionRepetition').removeClass('hidden');}else{$('#divOptionsRepetitionMois').addClass('hidden');}
						if(this.value==''){$('#divOptionsRepetitionJour').addClass('hidden');$('#divOptionsRepetitionSemaine').addClass('hidden');$('#divOptionsRepetitionMois').addClass('hidden');$('#divExceptionRepetition').addClass('hidden');}
						{/literal}" class="form-control" tabindex="17">
							<option value="">{#winPeriode_repeter_pasderepetition#}</option>
							<option value="jour">{#winPeriode_repeter_jour#}</option>
							<option value="semaine">{#winPeriode_repeter_semaine#}</option>
							<option value="mois">{#winPeriode_repeter_mois#}</option>
					</select>
				</div>
				<div class="col-md-6 form-inline">
						<div id="divOptionsRepetitionJour" class="hidden" tabindex="18">{#winPeriode_repeter_tousles#}
							<select name='nbRepetitionJour' id='nbRepetitionJour' class="form-control input-2-digit">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
							{#winPeriode_jour#}&nbsp;{#winPeriode_repeter_jusque#}
							{if $smarty.session.isMobileOrTablet==1}
								<input type="date" class="form-control" id="dateFinRepetitionJour" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{else}
								<input type="text" class="form-control datepicker" id="dateFinRepetitionJour" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{/if}
						</div>
						<div id="divOptionsRepetitionSemaine" class="hidden" tabindex="19">
							{#winPeriode_repeter_tousles#}
							<select name='nbRepetitionSemaine' id='nbRepetitionSemaine' class="form-control">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
							&nbsp;{#winPeriode_semaine#}&nbsp;{#winPeriode_repeter_jusque#}
							{if $smarty.session.isMobileOrTablet==1}
								<input type="date" class="form-control" id="dateFinRepetitionSemaine" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{else}
								<input type="text" class="form-control datepicker" id="dateFinRepetitionSemaine" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{/if}
							<br />
							<label class="control-label">{#winPeriode_repeter_jourderepetition#} :</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="1" checked="checked" />{#initial_day_1#}</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="2" />{#initial_day_2#}</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="3" />{#initial_day_3#}</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="4" />{#initial_day_4#}</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="5" />{#initial_day_5#}</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="6" />{#initial_day_6#}</label>
							<label class="radio-inline"><input type="radio" name="jourSemaine" id="jourSemaine" value="0" />{#initial_day_0#}</label>
						</div>
						<div id="divOptionsRepetitionMois" class="hidden" tabindex="18">
							{#winPeriode_repeter_tousles#}
							<select name='nbRepetitionMois' id='nbRepetitionMois' class="form-control">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							</select>
							&nbsp;{#winPeriode_mois#}&nbsp;{#winPeriode_repeter_jusque#}
							{if $smarty.session.isMobileOrTablet==1}
								<input type="date" class="form-control" id="dateFinRepetitionMois" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{else}
								<input type="text" class="form-control datepicker" id="dateFinRepetitionMois" value="" size="11" maxlength="10" onFocus="remplirDateRepetition(this.id);" tabindex="18">
							{/if}
							<br />
							<label class="control-label">{#winPeriode_repeter_jourderepetition#} :</label>
							<label class="radio-inline"><input type="radio" name="radioChoixJourRepetition" id="radioChoixJourRepetition" value="0" checked="checked" />{#winPeriode_repeter_jourderepetition_jourmois#}</label>
						</div>
						<div id="divExceptionRepetition" class="form-group form-inline hidden" tabindex="19">
							<label class="control-label">{#winPeriode_repeter_exception_siferie#} :</label>
							<label class="radio-inline"><input type="radio" name="exceptionRepetition" id="exceptionRepetition" value="1" checked="checked" />{#winPeriode_repeter_exception_decaler#}</label>
							<label class="radio-inline"><input type="radio" name="exceptionRepetition" id="exceptionRepetition" value="2" />{#winPeriode_repeter_exception_pasajout#}</label>
							<label class="radio-inline"><input type="radio" name="exceptionRepetition" id="exceptionRepetition" value="3" />{#winPeriode_repeter_exception_ajout#}</label>
						</div>
					</div>
			{else}
					<label class="col-md-2 control-label">{#winPeriode_repeter#} :</label>
					<div class="col-md-10">
						<br /><b>{#winPeriode_recurrente#}{$prochaineOccurence|sqldate2userdate}</b>
					</div>
					<input type="hidden" name="repetition" id="repetition" value="" />
					<input type="hidden" name="dateFinRepetitionJour" id="dateFinRepetitionJour" value="" />
					<input type="hidden" name="dateFinRepetitionSemaine" id="dateFinRepetitionSemaine" value="" />
					<input type="hidden" name="dateFinRepetitionMois" id="dateFinRepetitionMois" value="" />
					<input type="hidden" name="nbRepetitionJour" id="nbRepetitionJour" value="" />
					<input type="hidden" name="nbRepetitionSemaine" id="nbRepetitionSemaine" value="" />
					<input type="hidden" name="nbRepetitionMois" id="nbRepetitionMois" value="" />
					<input type="hidden" name="jourSemaine" id="jourSemaine" value="" />
			{/if}
		</div>
		<div class='col-md-12'><hr /></div>
		<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_statut#} :</label>
			<div class="col-md-4">
				<select name="statut_tache" id="statut_tache" class="form-control" tabindex="19">
				{foreach from=$listeStatus item=status}
					<option value="{$status.status_id}" {if $periode.statut_tache eq $status.status_id}selected="selected"{/if}>{$status.nom}</option>
				{/foreach}
				</select>
			</div>
			<label class="col-md-2 control-label">{#winPeriode_livrable#} :</label>
			<div class="col-md-4" >
				<select name="livrable" id="livrable" class="form-control" tabindex="20">
					<option value="oui" {if $periode.livrable eq "oui"}selected="selected"{/if}>{#oui#}</option>
					<option value="non" {if $periode.livrable eq "non"}selected="selected"{/if}>{#non#}</option>
				</select>
			</div>
		</div>
		<div class="form-group col-md-12">
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX == 1 }
			<label class="col-md-2 control-label">{#winPeriode_lieu#} :</label>
				<div class="col-md-4">
					<select name="lieu" id="lieu" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="19" style="width:100%" >
						<option value=""></option>
						{foreach from=$listeLieux item=lieuTmp}
							<option value="{$lieuTmp.lieu_id}" {if $periode.lieu eq $lieuTmp.lieu_id} selected="selected" {/if}>{$lieuTmp.nom}</option>
						{/foreach}
					</select>
				</div>
		{/if}
		<!--{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 1 }
			<div class="col-md-2 control-label">{#winPeriode_ressource#} :</div>
				<div class="col-md-4">
					<select name="ressource" id="ressource" class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}" tabindex="20" style="width:100%" >
						<option value=""></option>
						{foreach from=$listeRessources item=ressourceTmp}
							<option value="{$ressourceTmp.ressource_id}" {if $periode.ressource eq $ressourceTmp.ressource_id} selected="selected" {/if}>{$ressourceTmp.nom}</option>
						{/foreach}
					</select>
				</div>
		{/if}-->
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_LIEUX == 0 }
		<input type="hidden" name="lieu" id="lieu" value="">
		{/if}
		{if $smarty.const.CONFIG_SOPLANNING_OPTION_RESSOURCES == 0 }
		<input type="hidden" name="ressource" id="ressource" value="">
		{/if}
		</div>
		<div class='col-md-12'><hr /></div>

		<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_lien#} :</label>
			<div class="col-md-10">
				<input {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} class="form-control float-left{if $periode.lien neq ""} input-withicon{/if}" name="lien" id="lien" maxlength="2000" value="{$periode.lien}" tabindex="22" />
				{if $periode.lien neq ""}
				<div title='{#winPeriode_gotoLien#|escape}' onclick="window.open('{if $periode.lien|strpos:"http" !== 0 && $periode.lien|strpos:"\\" !== 0}http://{/if}{$periode.lien}', '_blank')" target="_blank" class="glyphicon glyphicon-share tooltipster small margin-left-10"></div>
				{/if}
			</div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_commentaires#} :</label>
			<div class="col-md-10">
				<textarea class="form-control" id="notes" name="notes" tabindex="23" >{$periode.notes_xajax|escape:"html"}</textarea>
			</div>
		</div>
		<div class="form-group col-md-12">
			<label class="col-md-2 control-label">{#winPeriode_custom#} :</label>
			<div class="col-md-10">
				<input type="text" class="form-control float-left input-withicon" name="custom" id="custom" maxlength="255" value="{$periode.custom|escape:"html"}" tabindex="23" />
				<div title='{#winPeriode_custom_aide#|escape:"quotes"}' class="glyphicon glyphicon-question-sign cursor-help small tooltipster margin-left-10"></div>
			</div>
		</div>

		{if (in_array("projects_manage_all", $user.tabDroits) || (in_array("projects_manage_own", $user.tabDroits) && $user.user_id eq $projet.createur_id) || in_array("tasks_modify_all", $user.tabDroits) || (in_array("tasks_modify_own_task", $user.tabDroits) && $periode.user_id eq $user.user_id))}
			<div class="form-group col-md-12 text-right">
				{if $smarty.const.CONFIG_SMTP_HOST neq ''}
					<input type="checkbox" id="notif_email" checked="checked">
					<label for="notif_email" style="font-weight:normal" class="padding-right-25">{#winPeriode_notif_email#}</label>
				{else}
					<input type="hidden" id="notif_email" value="false">
				{/if}
				<div class="btn-group padding-right-7" role="group">
					<button type="button" id="butSubmitPeriode" class="btn btn-primary" tabindex="24" onClick="$('#divPatienter').removeClass('hidden');this.disabled=true;users_ids=getSelectValue('user_id');xajax_submitFormPeriode('{$periode.periode_id}', $('#projet_id').val(), users_ids, $('#date_debut').val(), $('#conserver_duree').is(':checked'), $('#date_fin').val(), $('#nb_jours').val(), $('#duree').val(), $('#heure_debut').val(), $('#heure_fin').val(), $('#matin').is(':checked'), $('#apresmidi').is(':checked'), $('#repetition option:selected').val(), $('#dateFinRepetitionJour').val(),$('#dateFinRepetitionSemaine').val(),$('#dateFinRepetitionMois').val(), $('#nbRepetitionJour option:selected').val(),$('#nbRepetitionSemaine option:selected').val(),$('#nbRepetitionMois option:selected').val(),getRadioValue('jourSemaine'),getRadioValue('exceptionRepetition'),$('#appliquerATous').is(':checked'), $('#statut_tache').val(),$('#lieu option:selected').val(), $('#ressource option:selected').val(), $('#livrable').val(), $('#titre').val(), $('#notes').val(), $('#lien').val(), $('#custom').val(), $('#notif_email').is(':checked'));">{#winPeriode_valider#|escape:'html'}</button>

					{if $periode.periode_id neq 0}
						<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_dupliquer#|escape:"javascript"} ?'))xajax_ajoutPeriode('', '', {$periode.periode_id});">{#winPeriode_dupliquer#}</button>
						<button type="button" class="btn btn-warning" onClick="if(confirm('{#winPeriode_confirmSuppr#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, false, $('#notif_email').is(':checked'));">{#winPeriode_supprimer#}</button>
						{if isset($estFilleOuParente)}
							<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, true, $('#notif_email').is(':checked'));">{#winPeriode_supprimer_repetition#}</button>
							<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, 'avant', $('#notif_email').is(':checked'));">{#winPeriode_supprimer_repetition_avant#}</button>
							<button type="button" class="btn btn-default" onClick="if(confirm('{#winPeriode_confirmSupprRepetition#|escape:"javascript"}'))xajax_supprimerPeriode({$periode.periode_id}, 'apres', $('#notif_email').is(':checked'));">{#winPeriode_supprimer_repetition_apres#}</button>
						{/if}
					{/if}
				</div>
				<div id="divPatienter" class="hidden pull-right form-group margin-top-5 margin-left-10 w20"><img src="assets/img/pictos/loading16.gif" class="picto" /></div>
			</div>
		{/if}
</form>
<script type="text/javascript">
	{literal}
	$('.tooltipster').tooltipster({
		delay: 0,
		arrow: false,
		contentAsHTML: true,
		contentCloning: true,
		multiple: true,
		theme: 'tooltipster-soplanning'
		});
	{/literal}
</script>
