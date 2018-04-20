{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/options.php" class="btn btn-sm btn-default" ><i class="fa fa-cogs fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuOptions#}</a>
					<a href="javascript:xajax_modifRessource();undefined;" class="btn btn-sm btn-default" ><i class="fa fa-plug fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerRessource#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box margin-top-10">
				{if $ressources|@count > 0}
					<table class="table table-striped table-hover" id="ressourceTab">
						<tr>
							<th class="w100">&nbsp;</th>
							<th>
								<b>{#ressource_nom#}</b>
							</th>
							<th class="ressourceTabColComment nowrap">
								<b>{#ressource_commentaire#}</b>
							</th>
							<th class="ressourceTabColExclusive">
								<b>{#exclusivite#}</b>
							</th>
							</tr>
						{foreach name=ressources item=ressource from=$ressources}
							<tr>
								<td class="w100 nowrap">
									<a href="javascript:xajax_modifRessource('{$ressource.ressource_id|urlencode}');undefined;"><i class="fa fa-pencil fa-fw" aria-hidden="true" style='color:#333'></i></a>
									<a href="javascript:xajax_supprimerRessource('{$ressource.ressource_id|urlencode}');undefined;" onClick="javascript:return confirm('{#confirm#|escape:"javascript"}')"><i class="fa fa-trash-o fa-fw" aria-hidden="true" style='color:#333'></i></a>
									<a href="{$BASE}/process/planning.php?filtreSurRessource={$ressource.ressource_id}" title="{#planning_filtre_sur_ressource#|escape}"><i class="fa fa-globe fa-fw" aria-hidden="true" style='color:#333'></i></a>
								</td>
								<td>
									{$ressource.nom}&nbsp;
								</td>
								<td class="ressourceTabColComment nowrap">
									{$ressource.commentaire}
								</td>
								<td class="ressourceTabColExclusive">
									{if $ressource.exclusif eq 1}{#oui#}{else}{#non#}{/if}
								</td>
							</tr>
						{/foreach}
					</table>
				{else}
					{#info_noRecord#}
				{/if}
			</div>
		</div>
	</div>
</div>

{include file="www_footer.tpl"}