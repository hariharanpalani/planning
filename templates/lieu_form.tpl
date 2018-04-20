{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank">
	<input type="hidden" name="old_lieu_id" id="old_lieu_id" value="{$lieu.lieu_id}" />
	<div class="container-fluid">
	<div class="form-group">
		<label class="col-md-4 control-label">{#lieu_identifiant#} :</label>
		<div class="col-md-5">
			{if $lieu.lieu_id neq ''}
			<input name="lieu_id" id="lieu_id" type="hidden" value="{$lieu.lieu_id}" class="form-control"/>
			<p class="form-control-static">{$lieu.lieu_id}</p>
			{else}
			<input name="lieu_id" id="lieu_id" type="text" maxlength="10" class="form-control" value="{$lieu.lieu_id}"  onChange="xajax_checkLieuId(this.value, '{$lieu.lieu_id}');" /> 
			<span id="divStatutCheckLieuId"></span>
			{#winPeriode_lieu_identifiantCarMax#}
			{/if}
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#lieu_nom#} :</label>
		<div class="col-md-5">
			<input name="nom" id="nom" class="form-control" type="text" maxlength="50" value="{$lieu.nom}" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#lieu_commentaire#} :</label>
		<div class="col-md-7">
			<textarea name="commentaire" id="commentaire" class="form-control" maxlength="255">{$lieu.commentaire}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#exclusivite#} :</label>
		<div class="col-md-5 checkbox-inline">
		&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"  name="lieu_exclusif" id="lieu_exclusif" {if $lieu.exclusif == 1}checked="checked"{/if}>{#lieu_exclusif#}
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4"></label>
		<div class="col-md-5">
			<input type="button" value="{#lieu_valider#|escape:"html"}" class="btn btn-primary" onClick="xajax_submitFormLieu('{$lieu.lieu_id}', $('#lieu_id').val(), $('#nom').val(), $('#commentaire').val(), $('#lieu_exclusif').is(':checked') )" />
		</div>
	</div>
</div>
</form>