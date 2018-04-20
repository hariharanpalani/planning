{* Smarty *}
<form class="form-horizontal" id="formIcal">
	<div class="form-group">
		<label class="col-md-4 control-label">{#icalExport_users#} :</label>
		<div class="radio-inline">
			<input type="radio" name="ical_users" id="ical_users_moi" value="ical_users_moi" checked="checked" class="top-0" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getSelectValue('icalProjetsChoix'));">
			<label for="ical_users_moi">{#icalExport_users_moi#}</label>
			<br>
			<input type="radio" name="ical_users" id="ical_users_tous" value="ical_users_tous" class="top-0" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getSelectValue('icalProjetsChoix'));">
			<label for="ical_users_tous">{#icalExport_users_tous#}</label>
			<br><br>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#icalExport_projets#} :</label>
		<div class="col-md-8 radio-inline">
			<input type="radio" name="ical_projets" id="ical_projets_tous" value="ical_projets_tous" checked="checked" onClick="$('#divIcalProjets').addClass('hidden');" class="top-0" onClick="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getSelectValue('icalProjetsChoix'));">
			<label for="ical_projets_tous">{#icalExport_projets_tous#}</label>
			<br>
			<input type="radio" name="ical_projets" id="ical_projets_liste" value="ical_projets_liste" class="top-0" onClick="$('#divIcalProjets').removeClass('hidden');xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getSelectValue('icalProjetsChoix'));">
			<label for="ical_projets_liste">{#icalExport_projets_liste#}</label>
			<div id="divIcalProjets" class="hidden">
					<select multiple="multiple" name="icalProjetsChoix" id="icalProjetsChoix" class="form-control {if !$smarty.session.isMobileOrTablet}select2{/if}" onfocus="blur();" tabindex="1" style="width:100%" onchange="xajax_icalGenererLien(getRadioValue('ical_users'), getRadioValue('ical_projets'), getSelectValue('icalProjetsChoix'));">
					<option value="">- - - - - - - - - - -</option>
					{assign var="groupeCourant" value="-1"}
					{foreach from=$listeProjets item=projet}
						{if $groupeCourant != $projet.groupe_id}
							{assign var="groupeCourant" value=$projet.groupe_id}
							{if $projet.groupe_id == ""}
								{assign var="nomgroupe" value=#projet_liste_sansGroupes#}
							{else}
								{assign var="nomgroupe" value=$projet.nom_groupe}
							{/if}
							<optgroup label="{$nomgroupe}"></optgroup>
						{/if}
						<option value="{$projet.projet_id}">{$projet.nom} ({$projet.projet_id}) {if $projet.livraison neq ''} - S{$projet.livraison}{/if}</option>
					{/foreach}
				</select>
			</div>
			<br><br>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#icalExport_url#} :</label>
		<div class="col-md-5 left-0 form-inline">
			<input type="text" id="inputLienIcal" value="{$lienIcal}" class="form-control">
		    <span title="{#ical_instructions#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
			<br><br>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#icalExport_download#} :</label>
		<div class="col-md-6 left-0 form-inline">
			<a href="export_ical.php"><img src="assets/img/pictos/download.png" class="picto-large" /></a>
			&nbsp;<span title="{#ical_instructions2#}" class="glyphicon glyphicon-question-sign cursor-help small tooltipster"></span>
		</div>
	</div>
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