{* Smarty *}
{include file="www_header.tpl"}

<div class="container">
	<form action="stats_projects.php" method="POST" class="form-inline" id="formGraphe">

	<input type="hidden" name="users" id="users" value="">
	<input type="hidden" name="projets" id="projets" value="">

	<div class="soplanning-box margin-top-10">
		<div class="titrePage" align="center">{#droits_stats_projects#}</div>
		<br>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="col-md-2 control-label">
					{#stats_date_debut#}:
				</label>
				<div class="col-md-4">
					<input name="date_debut" id="date_debut" style="width:80px;" type="text" value="{if isset($stats_projects.date_debut)}{$stats_projects.date_debut}{/if}" class="form-control datepicker" />
					<script>{literal}addEvent(window, 'load', function(){jQuery("#date_debut").datepicker({ dateFormat: "{/literal}{$smarty.const.CONFIG_DATE_DATEPICKER}{literal}"})});{/literal}</script>
				</div>

				<label class="col-md-2 control-label">
					{#stats_date_fin#}
				</label>
				<div class="col-md-4">
					<input name="date_fin" id="date_fin" style="width:80px;" type="text" value="{if isset($stats_projects.date_fin)}{$stats_projects.date_fin}{/if}" class="form-control datepicker" />
					<script>{literal}addEvent(window, 'load', function(){jQuery("#date_fin").datepicker({ dateFormat: "{/literal}{$smarty.const.CONFIG_DATE_DATEPICKER}{literal}"})});{/literal}</script>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="col-md-2 control-label">{#stats_users_liste#} :</label>
				</label>
				<div class="col-md-4">
					<select multiple="multiple" name="user_id" id="user_id" class="select2">
						{assign var=groupeTemp value=""}

						{foreach from=$listeUsers item=userCourant name=loopUsers}
							{if $userCourant.user_groupe_id neq $groupeTemp}
								<optgroup label="{$userCourant.groupe_nom}">
							{/if}
							<option value="{$userCourant.user_id}" {if in_array($userCourant.user_id, $stats_projects.users)}selected="selected"{/if}>{$userCourant.nom} ({$userCourant.user_id})</option>
							{if $userCourant.user_groupe_id neq $groupeTemp}
								</optgroup>
							{/if}
							{assign var=groupeTemp value=$userCourant.user_groupe_id}
						{/foreach}
					</select>
				</div>

				<label class="col-md-2 control-label">{#stats_projets_liste#} :</label>
				<div class="col-md-4">
					<select  multiple="multiple" name="projet_id" id="projet_id" class="input-large select2 form-control">
						{assign var=groupeTemp value=""}

						{foreach from=$listeProjets item=projetCourant name=loopProjets}
							{if $projetCourant.groupe_id neq $groupeTemp}
								<optgroup label="{$projetCourant.groupe_nom}">
							{/if}
							<option value="{$projetCourant.projet_id}" {if in_array($projetCourant.projet_id, $stats_projects.projets)}selected="selected"{/if}>{$projetCourant.nom} ({$projetCourant.projet_id})</option>
							{if $projetCourant.groupe_id neq $groupeTemp}
								</optgroup>
							{/if}
							{assign var=groupeTemp value=$projetCourant.groupe_id}
						{/foreach}
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-12">
				<label class="col-md-2 control-label">{#stats_abscisse_echelle#} :</label>
				</label>
				<div class="col-md-4">
					<input type="radio" name="abscisse_echelle" id="abscisse_echelle_jour" value="jour" style="margin-bottom:6px" {if $stats_projects.abscisse_echelle eq "jour"}checked="checked"{/if}>
					<label for="abscisse_echelle_jour">{#stats_abscisse_echelle_jour#}</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="abscisse_echelle" id="abscisse_echelle_semaine" value="semaine" style="margin-bottom:6px" {if $stats_projects.abscisse_echelle eq "semaine"}checked="checked"{/if}>
					<label for="abscisse_echelle_semaine">{#stats_abscisse_echelle_semaine#}</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="abscisse_echelle" id="abscisse_echelle_mois" value="mois" style="margin-bottom:6px" {if $stats_projects.abscisse_echelle eq "mois"}checked="checked"{/if}>
					<label for="abscisse_echelle_mois">{#stats_abscisse_echelle_mois#}</label>
				</div>
				<label class="col-md-2 control-label">{#stats_abscisse_valeur#} :</label>
				</label>
				<div class="col-md-4">
					<input type="radio" name="abscisse_echelle_valeur" id="abscisse_echelle_valeur_heures" value="heures" style="margin-bottom:6px" {if $stats_projects.abscisse_echelle_valeur eq "heures"}checked="checked"{/if}>
					<label for="abscisse_echelle_valeur_heures">{#stats_abscisse_valeur_heures#}</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="abscisse_echelle_valeur" id="abscisse_echelle_valeur_jours" value="jours" style="margin-bottom:6px" {if $stats_projects.abscisse_echelle_valeur eq "jours"}checked="checked"{/if}>
					<label for="abscisse_echelle_valeur_jours">{#stats_abscisse_valeur_jours#}</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="col-md-2 control-label">{#stats_graphe_width#} :</label>
				<div class="col-md-10">
					<div class="input inline" style="display:inline;">
						<input name="graphe_width" id="graphe_width" style="width:60px;" type="text" value="{$stats_projects.graphe_width}" class="form-control" />
					</div>
					<label class="inline" style="margin-left:30px">
						{#stats_graphe_height#} :
					</label>
					<div class="input inline" style="display:inline;">
						<input name="graphe_height" id="graphe_height" style="width:60px;" type="text" value="{$stats_projects.graphe_height}" class="form-control" />
					</div>
					<label class="inline" style="margin-left:30px">
						{#stats_ordonnee_min#}:
					</label>
					<div class="input inline" style="display:inline;">
						<input name="ordonnee_min" id="ordonnee_min" style="width:60px;" type="text" value="{$stats_projects.ordonnee_min}" class="form-control" />
					</div>
					<label class="inline" style="margin-left:30px">
						{#stats_ordonnee_max#}:
					</label>
					<div class="input inline" style="display:inline;">
						<input name="ordonnee_max" id="ordonnee_max" style="width:60px;" type="text" value="{$stats_projects.ordonnee_max}" class="form-control" />
					</div>

				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12">
				<label class="col-md-2 control-label">{#stats_graphe_grille#} :</label>
				<div class="col-md-4">
					<input type="radio" name="grille" id="grille_h" value="grille_h" style="margin-bottom:6px" {if $stats_projects.grille eq "grille_h"}checked="checked"{/if}>
					<label for="grille_h">{#stats_graphe_grille_h#}</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="grille" id="grille_v" value="grille_v" style="margin-bottom:6px" {if $stats_projects.grille eq "grille_v"}checked="checked"{/if}>
					<label for="grille_v">{#stats_graphe_grille_v#}</label>
					&nbsp;&nbsp;&nbsp;
					<input type="radio" name="grille" id="grille_hv" value="grille_hv" style="margin-bottom:6px" {if $stats_projects.grille eq "grille_hv"}checked="checked"{/if}>
					<label for="grille_hv">{#stats_graphe_grille_hv#}</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group col-md-12">
				<div class="col-md-12">
					<button onClick="jQuery('#users').val(getSelectValue('user_id')); jQuery('#projets').val(getSelectValue('projet_id')); jQuery('#formGraphe').submit();" class="btn btn-small btn-primary" type="button">{#submit#}</button>
				</div>
			</div>
		</div>
	</div>
	</form>


	<div class="soplanning-box margin-top-10">
		<div class="row">
			<div class="col-md-12">
				<img id="imgLoading" src="assets/img/pictos/loading16.gif" />
				<img id="imgGraphe" src="graphe_projects.php" onload="jQuery('#imgLoading').hide();jQuery('#imgGraphe').show();" style="display:none;" />
			</div>
		</div>
	</div>

</div>

<script language="javascript">
	initselect2('{$lang}','{#stats_tous_selectionnes#}');					
</script>


{include file="www_footer.tpl"}