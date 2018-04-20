{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box">
				<div class="btn-group">
					<a href="{$BASE}/projets.php" class="btn btn-sm btn-default" ><i class="fa fa-book fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {#menuListeProjets#}</a>
					<a href="{$BASE}/groupe_form.php" class="btn btn-sm btn-default"><i class="fa fa-bookmark-o fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp; {#menuCreerGroupe#}</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box margin-top-10">
				<form method="GET" class="form-inline" id="filtreprojet">
				<div id="projectgroupStatusLabel">
					<label class="label-form">{#projet_liste_afficherGroupesProjets#} :</label>
				</div>
				<div id="projectgroupStatusCheckbox">
					<label class="checkbox-inline">
						<input type="checkbox" name="statut[]" id="a_faire" value="a_faire" onclick="javascript:$('#filtreprojet').submit();" {if in_array('a_faire', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutAfaire#}
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" name="statut[]" id="en_cours" value="en_cours" onclick="javascript:$('#filtreprojet').submit();" {if in_array('en_cours', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutEnCours#}
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" name="statut[]" id="fait" value="fait" onclick="javascript:$('#filtreprojet').submit();" {if in_array('fait', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutFait#}
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" name="statut[]" id="abandon" value="abandon" onclick="javascript:$('#filtreprojet').submit();" {if in_array('abandon', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutAbandon#}
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" name="statut[]" id="archive" value="archive" onclick="javascript:$('#filtreprojet').submit();" {if in_array('archive', $listeStatuts)}checked="checked"{/if}>{#projet_liste_statutArchive#}
					</label>
					<div class="btn-group">
						<div class="input-group">
							<input type="text" class="form-control input-sm" name="rechercheProjet" value="{$rechercheProjet|default:""}" />
							<span class="input-group-btn">
								<button type="submit" class="btn btn-sm {if $rechercheProjet != ""}btn-danger{else}btn-default{/if}"><i class="glyphicon glyphicon-search"></i></button>
							</span>
						</div>
					</div>
				  </div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box margin-top-10">
				{if $groupes|@count > 0}
					<table class="table table-striped table-hover">
						<tr>
							<th class="w70">&nbsp;</th>
							<th>
								{if $order eq "nom"}
									{if $by eq "asc"}
										<a href="{$BASE}/groupe_list.php?page=1&order=nom&by=desc">{#groupe_liste_nom#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/asc_order.png" alt="" />
									{else}
										<a href="{$BASE}/groupe_list.php?page=1&order=nom&by=asc">{#groupe_liste_nom#} ({$groupes|@count})</a>&nbsp;<img src="{$BASE}/assets/img/pictos/desc_order.png" alt="" />
									{/if}
								{else}
									<a href="{$BASE}/groupe_list.php?page=1&order=nom&by={$by}">{#groupe_liste_nom#} ({$groupes|@count})</a>
								{/if}
							</th>
							{assign var=totalProjets value=0}
							{foreach name=groupes item=groupe from=$groupes}
								{assign var=totalProjets value=$totalProjets+$groupe.totalProjets}
							{/foreach}
							<th>{#groupe_liste_nbProjets#} ({$totalProjets})</th>
						</tr>
						{foreach name=groupes item=groupe from=$groupes}
							{assign var=couleurLigne value="#ffffff"}
							<tr>
								<td>
									<a href="{$BASE}/groupe_form.php?groupe_id={$groupe.groupe_id}"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></a>
									<a href="{$BASE}/process/groupe_save.php?groupe_id={$groupe.groupe_id}&action=delete" onClick="javascript:return confirm('{#groupe_liste_confirmSuppr#|escape:"javascript"}')"><i class="fa fa-trash-o fa-fw" aria-hidden="true"></i></a>
								</td>
								<td>{$groupe.nom|xss_protect}&nbsp;</td>
								<td>{$groupe.totalProjets}&nbsp;</td>
							</tr>
						{/foreach}
						{if $nbPages > 1}
							<tr>
								<td colspan="7" align="right">
									{if $currentPage > 1}<a href="{$BASE}/groupe_list.php?page={$currentPage-1}">&lt;&lt; {#action_precedent#}</a>&nbsp;&nbsp;{/if}
									{section name=pagination loop=$nbPages}
										{if $smarty.section.pagination.iteration == $currentPage}<b>{else}<a href="{$BASE}/groupe_list.php?page={$smarty.section.pagination.iteration}">{/if}
										{$smarty.section.pagination.iteration}
										{if $smarty.section.pagination.iteration == $currentPage}</b>{else}</a>{/if}&nbsp;
									{/section}
									{if $currentPage < $nbPages}<a href="{$BASE}/groupe_list.php?page={$currentPage+1}">{#action_suivant#} &gt;&gt;</a>{/if}
								</td>
							</tr>
						{/if}
					</table>
				{else}
					{#info_noRecord#}
				{/if}
			</div>
		</div>
	</div>
</div>
{* CHARGEMENT SCROLL Y *}
<script type="text/javascript">
	{literal}
	var yscroll = getCookie('yposProjets');
	window.onscroll = function() {document.cookie='yposProjets=' + window.pageYOffset;};
	addEvent(window, 'load', chargerYScrollPos);
	{/literal}
</script>
{include file="www_footer.tpl"}