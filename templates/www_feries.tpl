{* Smarty *}
{include file="www_header.tpl"}
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/options.php" class="btn btn-sm btn-default" ><i class="fa fa-cogs fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuOptions#}</a>
					<a href="javascript:xajax_modifFerie();undefined;" class="btn btn-sm btn-default" ><i class="fa fa-plane fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#menuCreerFerie#}</a>
					<div class="btn-group">
						<button class="btn btn-sm btn-default" data-toggle="dropdown"><i class="fa fa-upload fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;{#feries_import#}&nbsp;<span class="caret"></span></button>
						<ul class="dropdown-menu">
						{foreach from=$fichiers item=fichier}
							<li><a onClick="event.cancelBubble=true;" href="javascript:if(confirm('{#feries_confirmImport#}')){literal}{{/literal}document.location='process/feries.php?fichier={$fichier|basename}'{literal}}{/literal}">{$fichier|basename}</a></li>
						{/foreach}
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box margin-top-10">
				{if $feries|@count > 0}
					<table class="table table-striped table-hover">
						<tr>
							<th class="w70">&nbsp;</th>
							<th class="w100">
								<b>{#feries_date#}</b>
							</th>
							<th>
								<b>{#feries_libelle#}</b>
							</th>
						</tr>
						{foreach name=feries item=ferie from=$feries}
							<tr>
								<td>
									<a href="javascript:xajax_modifFerie('{$ferie.date_ferie|urlencode}');undefined;"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
									<a href="javascript:xajax_supprimerFerie('{$ferie.date_ferie|urlencode}');undefined;" onClick="javascript:return confirm('{#confirm#|escape:"javascript"}')"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
								</td>
								<td>
									{$ferie.date_ferie|sqldate2userdate}&nbsp;
								</td>
								<td>
									{$ferie.libelle}
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