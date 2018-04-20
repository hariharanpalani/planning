{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/options.php" class="btn btn-sm btn-default" ><i class="fa fa-cogs fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuOptions#}</a>
					<a href="javascript:xajax_modifStatus();undefined;" class="btn btn-sm btn-default" ><i class="fa fa-tags fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerStatus#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box margin-top-10">
				{if $status|@count > 0}
					<table class="table table-striped table-hover" id="locationTab">
						<tr>
							<th class="w100">&nbsp;</th>
							<th>
								<b>{#status_nom#}</b>
							</th>
							<th class="locationTabColComment nowrap">
								<b>{#status_commentaire#}</b>
							</th>
							<th class="locationTabColComment nowrap">
								<b>{#status_pourcentage#}</b>
							</th>
							<th class="locationTabColComment nowrap">
								<b>{#status_couleur#}</b>
							</th>
						</tr>
						{foreach name=status item=status from=$status}
							<tr>
								<td class="w100 nowrap">
									<a href="javascript:xajax_modifStatus('{$status.status_id|urlencode}');undefined;"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
									{if $status.status_id != 'a_faire' and $status.status_id != 'en_cours' and $status.status_id != 'fait' and $status.status_id != 'abandon'}
									<a href="javascript:xajax_supprimerStatus('{$status.status_id|urlencode}');undefined;" onClick="javascript:return confirm('{#confirm#|escape:"javascript"}')"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
									{/if}
									<a href="{$BASE}/process/planning.php?filtreSurStatus={$status.status_id}" title="{#planning_filtre_sur_status#|escape}"><i class="fa fa-globe fa-fw" aria-hidden="true"></i></a>
								</td>
								<td>
									{$status.nom}&nbsp;
								</td>
								<td class="locationTabColComment nowrap">
									{$status.commentaire}
								</td>
								<td class="locationTabColComment nowrap">
									{$status.pourcentage}
								</td>
								<td class="text-center" style="text-align:center">
									<div class="pastille-statut" style="background-color:#{$status.couleur}"></div>
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