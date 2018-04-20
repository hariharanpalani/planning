{* Smarty *}
{include file="www_header.tpl"}
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/projets.php" class="btn btn-sm btn-default" ><img src="assets/img/pictos/projets.png" class="picto" alt=""> {#menuListeProjets#}</a>
					<a href="{$BASE}/groupe_list.php" class="btn btn-sm btn-default"><img src="assets/img/pictos/groupes.png" class="picto" alt=""> {#menuListeGroupes#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box margin-top-10">
				{if isset($error_fields)}
					<div class="alert alert-danger">
						<h5>{#groupe_erreurChamps#} :</h5>
						<ul>
							{foreach from=$error_fields item=field}
								<li>{$field}</li>
							{/foreach}
						</ul>
					</div>
				{/if}
				<form action="{$BASE}/process/groupe_save.php" method="POST" class="form-horizontal">
					<input type="hidden" name="saved" value="{$groupe.saved}" />
					<input type="hidden" name="groupe_id" value="{$groupe.groupe_id}" />
					<div class="input-group">
						<label class="col-md-4 control-label" for="nom">{#groupe_nom#} :</label>
						<div class="col-md-8">
							<div class="input-group">
								<input name="nom" id="nom" type="text" class="form-control input-sm" value="{$groupe.nom|escape:"html"}" maxlength="100" />
								<span class="input-group-btn">
									<input type="submit" value="{#groupe_valider#|escape:"html"}" class="btn btn-sm btn-default" />
								</span>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
{include file="www_footer.tpl"}