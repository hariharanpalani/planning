{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank">
	<input type="hidden" name="old_status_id" id="old_status_id" value="{$status.status_id}" />
	<div class="container-fluid">
	<div class="form-group">
		<label class="col-md-4 control-label">{#status_identifiant#} :</label>
		<div class="col-md-5">
			{if $status.status_id neq ''}
			<input name="status_id" id="status_id" type="hidden" value="{$status.status_id}" class="form-control"/>
			<p class="form-control-static">{$status.status_id}</p>
			{else}
			<input name="status_id" id="status_id" type="text" maxlength="10" class="form-control" value="{$status.status_id}"  onChange="xajax_checkStatusId(this.value, '{$status.status_id}');" /> 
			<span id="divStatutCheckStatusId"></span>
			{#winPeriode_status_identifiantCarMax#}
			{/if}
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#status_nom#} :</label>
		<div class="col-md-5">
			<input name="nom" id="nom" class="form-control" type="text" maxlength="50" value="{$status.nom}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#status_commentaire#} :</label>
		<div class="col-md-7">
			<textarea name="commentaire" id="commentaire" class="form-control" maxlength="255">{$status.commentaire}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#options_statusAffichage_affichage#} :</label>
		<div class="col-md-6">
			<select name='affichage' id='affichage' class="form-control">
				<option value="tp" {if $status.affichage == 'tp'}selected="selected"{/if}>{#options_statusAffichage_tachesprojets#}</option>
				<option value="p" {if $status.affichage == 'p'}selected="selected"{/if}>{#options_statusAffichage_projets#}</option>
				<option value="t" {if $status.affichage == 't'}selected="selected"{/if}>{#options_statusAffichage_taches#}</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#status_pourcentage#} :</label>
		<div class="col-md-5">
			<select name='pourcentage' id='pourcentage' class="form-control input-3-digit">
				<option value="0" {if $status.pourcentage == 0}selected="selected"{/if}>0</option>
				<option value="10" {if $status.pourcentage == 10}selected="selected"{/if}>10</option>
				<option value="20" {if $status.pourcentage == 20}selected="selected"{/if}>20</option>
				<option value="30" {if $status.pourcentage == 30}selected="selected"{/if}>30</option>
				<option value="40" {if $status.pourcentage == 40}selected="selected"{/if}>40</option>
				<option value="50" {if $status.pourcentage == 50}selected="selected"{/if}>50</option>
				<option value="60" {if $status.pourcentage == 60}selected="selected"{/if}>60</option>
				<option value="70" {if $status.pourcentage == 70}selected="selected"{/if}>70</option>
				<option value="80" {if $status.pourcentage == 80}selected="selected"{/if}>80</option>
				<option value="90" {if $status.pourcentage == 90}selected="selected"{/if}>90</option>
				<option value="100" {if $status.pourcentage == 100}selected="selected"{/if}>100</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#status_couleur#} :</label>
		<div class="col-md-6">
		{if $smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE neq ""}
				{if $smarty.session.isMobileOrTablet==1}
					<input class="form-control" name="couleur" id="couleur" maxlength="6" type="color" list="colors" value="#{if $status.couleur eq ''}{$couleurExStatus}{else}{$status.couleur}{/if}" />
						<datalist id="colors">
							{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
								<option>{$couleurTmp}</option>
							{/foreach}
						</datalist>
				{else}
				<select name="couleur" id="couleur" class="form-control" style="background-color:#{$status.couleur};color:{"#"|cat:$status.couleur|buttonFontColor}">
				    {if $status.couleur eq ""}<option value="">{#status_couleurchoix#}</option>{/if}
					{foreach from=","|explode:$smarty.const.CONFIG_PROJECT_COLORS_POSSIBLE item=couleurTmp}
						<option value="{$couleurTmp|replace:'#':''}" style="background-color:{$couleurTmp};color:{$couleurTmp|buttonFontColor}" {if $couleurTmp eq "#"|cat:$status.couleur}selected="selected"{/if}>{$couleurTmp|replace:'#':''}</option>
					{/foreach}
				</select>
				{/if}
			{else}
                {if $smarty.session.couleurExStatus neq ""}
                    {assign var=couleurExStatus value=$smarty.session.couleurExStatus}
                {else}
                    {assign var=couleurExStatus value="ffffff"}
                {/if}
				<input class="form-control" name="couleur" id="couleur" maxlength="6" {if $smarty.session.isMobileOrTablet==1}type="color"{else}type="text"{/if} value="#{if $status.couleur eq ''}{$couleurExStatus}{else}{$status.couleur}{/if}" />
			{/if}
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#status_priorite#} :</label>
		<div class="col-md-5">
			<select name='priorite' id='priorite' class="form-control input-2-digit">
				<option value="1" {if $status.priorite == 1}selected="selected"{/if}>1</option>
				<option value="2" {if $status.priorite == 2}selected="selected"{/if}>2</option>
				<option value="3" {if $status.priorite == 3}selected="selected"{/if}>3</option>
				<option value="4" {if $status.priorite == 4}selected="selected"{/if}>4</option>
				<option value="5" {if $status.priorite == 5}selected="selected"{/if}>5</option>
				<option value="6" {if $status.priorite == 6}selected="selected"{/if}>6</option>
				<option value="7" {if $status.priorite == 7}selected="selected"{/if}>7</option>
				<option value="8" {if $status.priorite == 8}selected="selected"{/if}>8</option>
				<option value="9" {if $status.priorite == 9}selected="selected"{/if}>9</option>
				<option value="10" {if $status.priorite == 10}selected="selected"{/if}>10</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4"></label>
		<div class="col-md-5">
			<input type="button" value="{#status_valider#|escape:"html"}" class="btn btn-primary" onClick="xajax_submitFormStatus('{$status.status_id}', $('#status_id').val(), $('#nom').val(), $('#commentaire').val(), $('#affichage option:selected').val(), $('#pourcentage option:selected').val(), $('#couleur').val(), $('#priorite option:selected').val())" />
		</div>
	</div>
</div>
</form>