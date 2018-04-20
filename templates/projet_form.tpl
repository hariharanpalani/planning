{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank" id="projectForm">
	<input type="hidden" name="saved" id="saved" value="{$projet.saved}" />
	<input type="hidden" name="old_projet_id" id="old_projet_id" value="{$projet.projet_id}" />
	<input type="hidden" name="origine" id="origine" value="{$origine}" />
	<div class="container-fluid">
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_identifiant#} :</label>
		<div class="col-md-5">
			<input class="form-control input-middle" name="projet_id" id="projet_id" type="text" maxlength="10" value="{$projet.projet_id}" onChange="xajax_checkProjetId(this.value, '{$projet.projet_id}');" />
		</div>
		<div class="col-md-3 left-0">
		<span id="divStatutCheckProjetId"></span>
		{#winProjet_identifiantCarMax#}
		</div>

	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_nomProjet#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="nom" id="nom" type="text" maxlength="30" value="{$projet.nom}" />
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_groupe#} :</label>
		<div class="col-md-6">
			<select name="groupe_id" id="groupe_id" class="form-control select2">
				<option value="" {if $projet.groupe_id eq ""}selected="selected"{/if}></option>
				{foreach from=$groupes item=groupe}
					<option value="{$groupe.groupe_id}" {if $projet.groupe_id eq $groupe.groupe_id}selected="selected"{/if}>{$groupe.nom}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_statut#} :</label>
		<div class="col-md-6">
			<select class="form-control" name="statut" id="statut">
				{foreach from=$listeStatus item=status}
					<option value="{$status.status_id}" {if $projet.statut eq $status.status_id}selected="selected"{/if}>{$status.nom}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_charge#} :</label>
		<div class="col-md-2">
			<input type="number" class="form-control input-small" name="charge" id="charge" maxlength="5" value="{$projet.charge}" />
		</div>
		<div class="col-md-3 left-0">
		{#winProjet_chargeJours#}
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_livraison#} :</label>
		<div class="col-md-8">
		{if $smarty.session.isMobileOrTablet==1}
			<input type="date" class="form-control" name="livraison" id="livraison" value="{$projet.livraison|forceISODateFormat}" />
		{else}
			<input type="text" class="form-control datepicker" name="livraison" id="livraison" value="{$projet.livraison|sqldate2userdate}" />		
		{/if}
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_lien#} :</label>
		<div class="col-md-6">
			<input class="form-control" name="lien" id="lien" {if $smarty.session.isMobileOrTablet==1}type="url"{else}type="text"{/if} maxlength="255" value="{$projet.lien}" />
		</div>
		{if $projet.lien neq ""}
			<div class="col-md-2">
				<a class="btn btn-sm btn-default tooltipster" title="{#winProjet_gotoLien#|escape}"  href="{if $projet.lien|strpos:"http" !== 0 && $projet.lien|strpos:"\\" !== 0}http://{/if}{$projet.lien}" target="_blank"><i class="glyphicon glyphicon-share"></i></a>
			</div>
		{/if}
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_couleur#} :</label>
		<div class="col-md-6">
			{if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}
				{if $smarty.session.isMobileOrTablet==1}
					<input class="form-control" name="couleur" id="couleur" maxlength="6" type="color" list="colors" value="#{if $projet.couleur eq ''}{$couleurExProjet}{else}{$projet.couleur}{/if}" />
						<datalist id="colors">
							{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
								<option>{$couleurTmp}</option>
							{/foreach}
						</datalist>
				{else}
					<select name="couleur" id="couleur" class="form-control" style="background-color:#{$projet.couleur};color:{"#"|cat:$projet.couleur|buttonFontColor}">
						{if $projet.couleur eq ""}<option value="">{#winProjet_couleurchoix#}</option>{/if}
						{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
							<option value="{$couleurTmp|replace:'#':''}" style="background-color:{$couleurTmp};color:{$couleurTmp|buttonFontColor}" {if $couleurTmp eq "#"|cat:$projet.couleur}selected="selected"{/if}>{$couleurTmp|replace:'#':''}</option>
						{/foreach}
					</select>
				{/if}
			{else}
                {if $smarty.session.couleurExProjet neq ""}
                    {assign var=couleurExProjet value=$smarty.session.couleurExProjet}
                {else}
                    {assign var=couleurExProjet value="ffffff"}
                {/if}
				<input class="form-control" name="couleur" id="couleur" maxlength="6" {if $smarty.session.isMobileOrTablet==1}type="color"{else}type="text"{/if} value="#{if $projet.couleur eq ''}{$couleurExProjet}{else}{$projet.couleur}{/if}" />
			{/if}
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_createur#} :</label>
		<div class="col-md-6">
			{if in_array("projects_manage_all", $user.tabDroits)}
				<select name="createur_id" id="createur_id" class="form-control{if $smarty.session.isMobileOrTablet==0} select2{/if}">
					{foreach from=$usersOwner item=owner}
						<option value="{$owner.user_id}" {if $createur.user_id eq $owner.user_id || ($createur.user_id eq "" && $owner.user_id eq $user.user_id)}selected="selected"{/if}>{$owner.nom}</option>
					{/foreach}
				</select>
			{else}
				{$createur.nom}
				<input type="hidden" name="createur_id" id="createur_id" value="{$createur.user_id}">
			{/if}
		</div>
	</div>
	<div class="form-group col-md-12">
		<label class="col-md-4 control-label">{#winProjet_commentaires#} :</label>
		<div class="col-md-8">
			<textarea name="iteration" id="iteration" class="form-control" maxlength="255">{$projet.iteration}</textarea>
		</div>
	</div>
	<div class="form-group col-md-12">
	<div class="col-md-4 control-label"></div>
		<div class="col-md-8">
			<input type="button" value="{#winProjet_valider#|escape:"html"}" class="btn btn-primary" onClick="xajax_submitFormProjet('{$projet.projet_id}', $('#origine').val(), $('#projet_id').val(), $('#nom').val(), $('#groupe_id').val(), $('#statut').val(), $('#charge').val(), $('#livraison').val(), $('#lien').val(), $('#couleur').val(), $('#createur_id').val(), $('#iteration').val())" />
		</div>
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
	$('#livraison').datepicker({
		dateFormat: "{/literal}{$smarty.const.CONFIG_DATE_DATEPICKER}{literal}"
	});
</script>
