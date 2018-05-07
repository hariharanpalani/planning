{* Smarty *}
{include file="www_header.tpl"}
<div class="container-fluid">
	<!--<div class="row noprint">
		<div class="col-md-12 sticky">
			<div class="soplanning-box form-inline" id="divPlanningDateSelector">
					{* DIV POUR CHOIX DATE *}
					<div class="btn-group" id="dropdownDateSelector">
						<form action="process/planning.php" method="GET" class="form-inline" id="formChoixDates">
						<button id="buttonDateSelector" class="btn dropdown-toggle btn-default bigFontSize" data-toggle="dropdown"><b>{$dateDebutTexte} - {$dateFinTexte}</b>&nbsp;&nbsp;&nbsp;<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li>
								<table class="planning-dateselector">
								<tr>
									<td class="padding-top-4">
										{#formDebut#} :&nbsp;
									</td>
									<td>
									{if $smarty.session.isMobileOrTablet==1}
										<input name="date_debut_affiche" id="date_debut_affiche" type="date" value="{$dateDebut|forceISODateFormat}" class="form-control w50" onChange="$('date_debut_custom').value= '----------------';" />
									{else}
										<input name="date_debut_affiche" id="date_debut_affiche" type="text" value="{$dateDebut}" class="form-control datepicker w50" onChange="$('date_debut_custom').value= '----------------';" />
										<script>{literal}addEvent(window, 'load', function(){jQuery("#date_debut_affiche").datepicker({ dateFormat: "{/literal}{$smarty.const.CONFIG_DATE_DATEPICKER}{literal}"});});{/literal}</script>
									{/if}
									<br>
										<select id="date_debut_custom" class="form-control" name="date_debut_custom" onChange="$('date_debut_affiche').value= '----------------';">
											<option value="">{#raccourci#}...</option>
											<option value="aujourdhui">{#raccourci_aujourdhui#}</option>
											<option value="semaine_derniere">{#raccourci_semaine_derniere#}</option>
											<option value="mois_dernier">{#raccourci_mois_dernier#}</option>
											<option value="debut_semaine">{#raccourci_debut_semaine#}</option>
											<option value="debut_mois">{#raccourci_debut_mois#}</option>
										</select>
									</td>
									<td class="padding-top-4">
										&nbsp;{#formFin#} :&nbsp;
									</td>
									<td>
									{if $smarty.session.isMobileOrTablet==1}
										<input name="date_fin_affiche" id="date_fin_affiche" type="date" value="{$dateFin|forceISODateFormat}" class="form-control"  onChange="$('date_fin_custom').value= '----------------';" />
									{else}
										<input name="date_fin_affiche" id="date_fin_affiche" type="text" value="{$dateFin}" class="form-control datepicker w50"   onChange="$('date_fin_custom').value= '----------------';" />
										<script>{literal}addEvent(window, 'load', function(){jQuery("#date_fin_affiche").datepicker({ dateFormat: "{/literal}{$smarty.const.CONFIG_DATE_DATEPICKER}{literal}"});});{/literal}</script>
									{/if}

										<br>
										<select id="date_fin_custom" name="date_fin_custom" class="form-control" onChange="$('date_fin_affiche').value= '----------------';">
											<option value="">{#raccourci#}...</option>
											<option value="1_semaine">{#raccourci_1_semaine#}</option>
											<option value="2_semaines">{#raccourci_2_semaines#}</option>
											<option value="3_semaines">{#raccourci_3_semaines#}</option>
											<option value="1_mois">{#raccourci_1_mois#}</option>
											<option value="2_mois">{#raccourci_2_mois#}</option>
											<option value="3_mois">{#raccourci_3_mois#}</option>
										</select>
									</td>
									<td>
										<button id="dateFilterButton" class="btn btn-sm btn-default margin-left-10 margin-right-10" onClick="$('formChoixDates').submit();"><i class="glyphicon glyphicon-search"></i></button>
									</td>
								</tr>
								</table>
							</li>
						</ul>
				</form>
				</div>
					<div class="btn-group margin-left-20" id="btnDateSelector">
						<a class="btn btn-sm btn-default" onClick="document.location='process/planning.php?raccourci_date=-{$nbJours}';" id="buttonDatePrevSelector"><i class="glyphicon glyphicon-chevron-left"></i> {$dateBoutonInferieur}</a>
						<a class="btn btn-sm btn-default" onClick="document.location='process/planning.php?raccourci_date=+{$nbJours}';" id="buttonDateNextSelector">{$dateBoutonSuperieur} <i class="glyphicon glyphicon-chevron-right"></i></a>
					</div>
					{if !in_array("tasks_readonly", $user.tabDroits)}
						<div class="btn-group" id="btnAddTask">
							<a class="btn btn-info btn-sm" href="javascript:Reloader.stopRefresh();xajax_ajoutPeriode();undefined;">
								{if !$smarty.server.HTTP_USER_AGENT|strstr:"MSIE 8.0"}
									<i class="fa fa-calendar-plus-o fa-lg fa-fw" aria-hidden="true"></i>
								{/if}
								<span id='label_addtask'>&nbsp;&nbsp;{#menuAjouterPeriode#}</span>
							</a>
						</div>
					{/if}
			</div>
		</div>
	</div> -->
	<!--{if $smarty.session.isMobileOrTablet==0}
	<script type="text/javascript">
	{literal}
	// hack pour empecher fermeture du layer au click sur les boutons du calendrier
	$('#date_fin_affiche').datepicker({
		onChangeMonthYear: function(year, month, inst) {return true;},
		dateFormat: "{/literal}{$smarty.const.CONFIG_DATE_DATEPICKER}{literal}"
	});
	$("#ui-datepicker-div").click( function(event) {
     event.stopPropagation();
	});
	jQuery('#dropdownDateSelector .dropdown-menu').on({
			"click":function(e){
			  e.stopPropagation();
			}
		});
	{/literal}
	</script>
	{/if}--> 

	<div class="row noprint">
		<div class="col-md-12">
			<div class="soplanning-box form-inline" id="divPlanningMainFilter">
					{* DIV POUR TRI AFFICHAGE *}
					<div class="btn-group" id="dropdownTri">
						<button class="btn dropdown-toggle btn-sm btn-default" data-toggle="dropdown"><i class="fa fa-sort-amount-desc fa-lg fa-fw" aria-hidden="true"></i><span id='label_tierpar'>&nbsp;&nbsp;{#formTrierPar#}</span>&nbsp;<span class="caret"></span></button>
						<ul class="dropdown-menu">
							{if $inverserUsersProjets}
								{foreach from=$triPlanningPossibleProjet item=triTemp}
									{assign var=chaineTmp value="triProjet_"|cat:$triTemp|replace:' ':'_'|replace:',':'_'}
									<li {if $triTemp eq $triPlanning}class="active"{/if}><a href="process/planning.php?triPlanning={$triTemp|urlencode}">{$smarty.config.$chaineTmp}</a></li>
								{/foreach}
							{else}
								{foreach from=$triPlanningPossibleUser item=triTemp}
									{assign var=chaineTmp value="triUser_"|cat:$triTemp|replace:' ':'_'|replace:',':'_'}
									<li {if $triTemp eq $triPlanning}class="active"{/if}><a href="process/planning.php?triPlanning={$triTemp|urlencode}">{$smarty.config.$chaineTmp}</a></li>
								{/foreach}
							{/if}
						</ul>
					</div>
					{* DIV POUR CHOIX EXPORT *}
					<div class="btn-group" id="dropdownExport">
						<button class="btn dropdown-toggle btn-sm btn-default" data-toggle="dropdown"><i class="fa fa-cloud-download fa-lg fa-fw" aria-hidden="true"></i><span id='label_export'>&nbsp;&nbsp;{#choix_export#}</span>&nbsp;<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="javascript:window.print();"><img src="assets/img/pictos/printButton.png" class="picto" alt='' /> {#printAll#|escape}</a></li>
							<li><a href="export_csv.php"><img src="assets/img/pictos/CSVIcon.gif" class="picto" alt='' /> {#CSVExport#|escape}</a></li>
							<li><a href="javascript:xajax_choixPDF();undefined;"><img src="assets/img/pictos/pdf.png" class="picto" alt='' /> {#PDFExport#|escape}</a></li>
							<li><a href="export_xls.php" target="_blank"><img src="assets/img/pictos/xls.png" class="picto" alt='' /> {#xlsExport#|escape}</a></li>
							<li><a href="export_gantt.php" target="_blank"><img src="assets/img/pictos/gantt.png" class="picto" alt='' /> {#ganttExport#|escape}</a></li>
							<li><a href="export_pdf_calendrier.php" target="_blank"><img src="assets/img/pictos/calendar.png" class="picto" alt='' /> {#calendarExport#|escape}</a></li>
							<li><a href="javascript:xajax_choixIcal();undefined;"><img src="assets/img/pictos/ical.png" class="picto" alt='' /> {#icalExport#|escape}</a></li>
						</ul>
					</div>
					{* DIV POUR CHOIX DIMENSION CASE ET AFFICHAGE LARGE REDUIT *}
					<div class="btn-group" id="dropdownLarge">
						{if $dimensionCase eq "reduit"}
							<a class="btn btn-sm btn-default" title="{#menuPlanningLarge#}" href="process/planning.php?dimensionCase=large"><i class="fa fa-search-plus fa-lg fa-fw" aria-hidden="true"></i></a>
						{else}
							<a class="btn btn-sm btn-default" title="{#menuPlanningReduit#}" href="process/planning.php?dimensionCase=reduit"><i class="fa fa-search-minus fa-lg fa-fw" aria-hidden="true"></i></a>
						{/if}
						<button class="btn dropdown-toggle btn-sm btn-default" data-toggle="dropdown"><i class="fa fa-sort fa-lg fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;<span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="{$BASE}/process/uplanning.php?scrolls=HV" {if $scrolls eq "HV"}class="btn-info"{/if}>{#scrolls_hv#|escape}</a></li>
							<li><a href="{$BASE}/process/uplanning.php?scrolls=H" {if $scrolls eq "H"}class="btn-info"{/if}>{#scrolls_h#|escape}</a></li>
							<li><a href="{$BASE}/process/uplanning.php?scrolls=NONE" {if $scrolls eq "NONE"}class="btn-info"{/if}>{#scrolls_none#|escape}</a></li>
						</ul>
					</div>

					{* DIV POUR RECHERCHE TEXTE *}
					<div class="btn-group" id="searchboxPlanning">
						<form action="process/planning.php" method="POST">
							<div class="input-group">
								<input type="text" class="form-control input-sm w70" name="filtreTexte" value="{$filtreTexte|escape:"html"}" maxlength="50" title="{#formFiltreTexte#|escape}" id="filtreTexte" />
								<span class="input-group-btn">
									<button type="submit" class="btn btn-sm {if $filtreTexte != ""}btn-danger{else}btn-default{/if}"><i class="glyphicon glyphicon-search"></i></button>
								{if $filtreTexte != ""}
									<div class="btn-group">
										<button class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">&nbsp;<span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="process/planning.php?desactiverFiltreTexte=1">{#formFiltreUserDesactiver#}</a></li>
										</ul>
									</div>
								{/if}
								</span>
							</div>
						</form>
					</div>
					{* DIV POUR CHOIX VUE JOUR MOIS *}
					<!--<div class="btn-group" id="btnDayView">
							{if $modeAffichage eq "mois"}
								<a class="btn btn-info btn-sm" href="planning_per_day.php"><i class="fa fa-clock-o fa-lg fa-fw" aria-hidden="true"></i><span id='label_vuejour'>&nbsp;&nbsp;{#menuPlanningJour#}</span></a>
							{else}
								<a class="btn btn-info btn-sm" href="planning.php"><i class="fa fa-calendar fa-lg fa-fw" aria-hidden="true"></i><span id='label_vuemois'>&nbsp;&nbsp;{#menuPlanningMois#}</span></a>
							{/if}
					</div>-->
			</div>
		</div>
	</div>
	{* le planning *}
	<div class="row">
		<div class="col-md-12">
			<div class="soplanning-box" id="divPlanning">
				<table id="tablePlanning">
					<tr>
						<td class="switchCell">
							<div id="divBoutonInverser" class="text-center">
							</div>
						</td>
						<td>
							<div id="divLigneEntete" style="{if $scrolls eq "HV"}width:990px; overflow-y:hidden;overflow-x:hidden{else}display:none{/if}">
								<table id="layerJours" class="planningContent scroll">
									<tbody id="bodyLayerJours">
									</tbody>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-right">
							<div id="divColonneEntente" {if $scrolls eq "HV"}style="height:600px; overflow-x:hidden;overflow-y:scroll" class="scroll"{/if}>
								<div class="h17"></div>
								<table id="divPeople">
									<tbody id="bodyPeople">
									</tbody>
								</table>
							</div>
						</td>
						<td>
							{if $scrolls eq "HV" || $scrolls eq "H"}
								<div id="divScrollHaut" class="scroll">
									<div id="divScrollHautInterne"></div>
								</div>
							{else}
								<div id="divScrollHautInterne"></div>
							{/if}
							<div id="divConteneurPlanning" class="scroll" style="{if $scrolls eq "HV" || $scrolls eq "H"}overflow-y:hidden{/if}{if $scrolls eq "HV"}width:700px; overflow-x:scroll{/if}" {if $modeAffichage eq "mois"}onscroll="document.cookie='xposMois=' + document.getElementById('divConteneurPlanning').scrollLeft;"{else}onscroll="document.cookie='xposJours=' + document.getElementById('divConteneurPlanning').scrollLeft;"{/if}>
								{$htmlTableau}
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="row noprint">
		<div class="col-md-12">
			<div class="soplanning-box" id="divPlanningSecondFilter">
					{* PAGINATION *}
					{if $nbPagesLignes > 1}
						<div class="btn-group" id="divPagination">
							<ul class="pagination pagination-sm">
							{section loop=$nbPagesLignes name=loopPages}
								{if $pageLignes eq $smarty.section.loopPages.iteration}
									<li class="active"><a href="#">{$smarty.section.loopPages.iteration}</a></li>
									{else}
									<li><a href="{$BASE}/process/uplanning.php?page_lignes={$smarty.section.loopPages.iteration}">{$smarty.section.loopPages.iteration}</a></li>
									{/if}
									{if !$smarty.section.loopPages.last}
									{/if}
								{/section}
							</ul>
						</div>
					{/if}
					<div class="btn-group" id="divNbrow">
						<a class="btn dropdown-toggle btn-default btn-sm" data-toggle="dropdown" href="#">{$nbLignes} {#planning_nbLignes#} <span class="caret"></span></a>
						{assign var=tabPages value=","|explode:$smarty.const.CONFIG_PLANNING_PAGES}
						<ul class="dropdown-menu">
							{foreach from=$tabPages item=valTemp}
							<li>
								<a onClick="top.location='{$BASE}/process/uplanning.php?nb_lignes=+{$valTemp}'">{$valTemp} {#planning_nbLignes#}</a>
							</li>
							{/foreach}
						</ul>
					</div>
					<!--<div class="btn-group" id="divHideRow">
						<a class="btn dropdown-toggle btn-sm {if $masquerLigneVide eq 1}btn-danger{else}btn-default{/if}" data-toggle="dropdown" href="#">{#planning_masquerLignesVides#} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							 {if $masquerLigneVide eq 1}
							<li>
								<a onClick="top.location='process/planning.php?masquerLigneVide=0'">{#planning_masquerLignesVides_non#}</a>
							</li>
							 {else}
							<li>
								<a onClick="top.location='process/planning.php?masquerLigneVide=1'">{#planning_masquerLignesVides_oui#}</a>
							</li>
							{/if}
						</ul>
					</div>
					<div class="btn-group" id="divShowRow">
						<a class="btn dropdown-toggle btn-sm {if $afficherLigneTotal eq 1}btn-danger{else}btn-default{/if}" data-toggle="dropdown" href="#">{#planning_afficherLigneTotal#} <span class="caret"></span></a>
						<ul class="dropdown-menu">
							 {if $afficherLigneTotal eq 1}
							<li>
								<a onClick="top.location='process/planning.php?afficherLigneTotal=0'">{#non#}</a>
							</li>
							 {else}
							<li>
								<a onClick="top.location='process/planning.php?afficherLigneTotal=1'">{#oui#}</a>
							</li>
							{/if}
						</ul>
					</div>
					<div class="btn-group" id="divShowTable">
						<a class="btn dropdown-toggle btn-default btn-sm" data-toggle="dropdown"  onclick="javascript:toggle2('divProjectTable');" >{#hide_show_table#}</a>
					</div>-->
					{* PAGINATION *}
			</div>
		</div>
	</div>	
</div>


{* GESTION DU DRAG N DROP *}
{literal}
<script type="text/javascript">
destinationsDrag = new Array();
var origineCaseX;
var origineCaseY;
function modifPeriode(obj, periode_id){
	if(origineCaseX != parseInt(obj.style.left) || origineCaseY != parseInt(obj.style.top)) {
		return false;
	}
	xajax_modifPeriode(periode_id);
}
{/literal}
{$js}
{literal}
</script>
{/literal}
{* FONCTION POUR COPIER LE TABLEAU DES PERSONNES *}
<script type="text/javascript">
{literal}
function copierTableauPersonnes () {
	// copy first cell (link to switch view)
	trTemp = document.createElement("tr");
	thTemp = document.createElement("th");
	thTemp.setAttribute('id', 'tdUserCopie_0');
	trTemp.appendChild(thTemp);
	document.getElementById('bodyPeople').appendChild(trTemp);
	document.getElementById('tdUserCopie_0').style.height = document.getElementById('tdUser_0').offsetHeight + 'px';
	//document.getElementById('tdUserCopie_0').innerHTML = '<div class="text-center"><a class="linkSwitchView" id="lienInverse" href="process/planning.php?inverserUsersProjets={/literal}{if $inverserUsersProjets eq 0}1{else}0{/if}{literal}"><i class="fa fa-exchange fa-3x fa-lg" aria-hidden="true" style="color:white;"></i></a></div>';

	var table = document.getElementById("tabContenuPlanning");
	numeroCellule = 1;
	for (var i = {/literal}{if $modeAffichage eq "mois"}4{else}2{/if}{literal}, row; row = table.rows[i]; i++) {
		for (var j = 0, col; col = row.cells[j]; j++) {
			if (j == 0) {
				thACopier = col.cloneNode(true);
				thACopier.setAttribute('id', 'tdUserCopie_' + numeroCellule);
				trTemp = document.createElement("tr");
				trTemp.appendChild(thACopier);
				document.getElementById('bodyPeople').appendChild(trTemp);
				document.getElementById('tdUserCopie_' + numeroCellule).style.height = col.offsetHeight + 'px';
				numeroCellule++;
				col.style.display = 'none';
			}
		}
	}
	document.getElementById("tdUser_0").style.display = 'none';

	{/literal}
	{if $scrolls eq "HV"}
	{literal}
		// days copy
		var table = document.getElementById("tabContenuPlanning");
		numeroCellule = 1;
		for (var i = 0, row; row = table.rows[i]; i++) {
			if (i > {/literal}{if $modeAffichage eq "mois"}3{else}1{/if}{literal}) {
				break;
			}
			trTemp = document.createElement("tr");
			document.getElementById('bodyLayerJours').appendChild(trTemp);
			for (var j = 0, col; col = row.cells[j]; j++) {
				if (j == 0 && i > 0 || j > 0) {
					thACopier = col.cloneNode(true);
					thACopier.setAttribute('id', 'tdJourCopie_' + numeroCellule);
					trTemp.appendChild(thACopier);
					document.getElementById('tdJourCopie_' + numeroCellule).style.width = col.offsetWidth + 'px';
					document.getElementById('tdJourCopie_' + numeroCellule).style.height = col.offsetHeight + 'px';
					numeroCellule++;
				}
			}
		}
		// we hide old cells here otherwise the cell width is corrupted
		for (var i = 0, row; row = table.rows[i]; i++) {
			if (i > {/literal}{if $modeAffichage eq "mois"}3{else}1{/if}{literal}) {
				break;
			}
			for (var j = 0, col; col = row.cells[j]; j++) {
				if (j == 0 && i > 0 || j > 0) {
					col.style.display = 'none';
				}
			}
		}

		document.getElementById('divBoutonInverser').innerHTML = document.getElementById('tdUserCopie_0').innerHTML;
		document.getElementById('divBoutonInverser').style.verticalAlign = 'middle';
		document.getElementById("tdUserCopie_0").style.display = 'none';
	{/literal}
	{/if}
	{literal}

}

{/literal}

</script>
{* FONCTION POUR COPIER LE TABLEAU DES PERSONNES *}
<script type="text/javascript">
{literal}
var displayMode = {/literal}{$modeAffichage|@json_encode}{literal};
var dateDebut = {/literal}{$dateDebut|@json_encode}{literal};
var dateFin = {/literal}{$dateFin|@json_encode}{literal};
var cookieDateDebut = getCookie('dateDebut');
var cookieDateFin = getCookie('dateFin');
if (dateDebut != cookieDateDebut || dateFin != cookieDateFin)  {
	document.cookie='dateDebut=' + dateDebut ;
	document.cookie='dateFin=' + dateFin ;
	document.cookie='xposMoisWin=0';
	document.cookie='xposMois=0';
	document.cookie='xposJoursWin=0';
	document.cookie='xposJours=0';
}

function writeCookie(displayMode){
	if (displayMode == 'mois'){
		document.cookie='yposMois=' + window.pageYOffset;
		document.cookie='xposMoisWin=' + window.pageXOffset;
	}else if (displayMode == 'jour'){
		document.cookie='yposJours=' + window.pageYOffset;
		document.cookie='xposJoursWin=' + window.pageXOffset;
	}
}

if (displayMode == 'mois'){
var xscroll = getCookie('xposMois');
var xscrollWin = getCookie('xposMoisWin');
var yscroll = getCookie('yposMois');
window.onscroll = function() {writeCookie(displayMode)};
}else if (displayMode == 'jour'){
var xscroll = getCookie('xposJours');
var xscrollWin = getCookie('xposJoursWin');
var yscroll = getCookie('yposJours');
window.onscroll = function() {writeCookie(displayMode)};
}
{/literal}


{* SCROLLBAR EN HAUT DU PLANNING *}

{literal}
function chargerScrolls(){
	{/literal}
	{if $scrolls eq "HV"}
	{literal}
		document.getElementById('divConteneurPlanning').style.width = document.body.offsetWidth - 100 - document.getElementById('divBoutonInverser').offsetWidth + 'px';
		document.getElementById('divLigneEntete').style.width = document.getElementById('divConteneurPlanning').offsetWidth + 'px';

		document.getElementById('divColonneEntente').style.height = window.innerHeight - getPosition(document.getElementById('divPlanning'), 'offsetTop') - 200 + 'px';
		document.getElementById('divConteneurPlanning').style.height = document.getElementById('divColonneEntente').style.height;

		document.getElementById('divScrollHaut').style.width = document.getElementById('divLigneEntete').offsetWidth + 'px';

		document.getElementById('divScrollHautInterne').style.width = document.getElementById('tabContenuPlanning').offsetWidth + 18 + 'px';

		jQuery("#divScrollHaut").scroll(function(){
			jQuery("#divConteneurPlanning").scrollLeft(jQuery("#divScrollHaut").scrollLeft());
			jQuery("#divLigneEntete").scrollLeft(jQuery("#divScrollHaut").scrollLeft());
		});
		jQuery("#divConteneurPlanning").scroll(function(){
			jQuery("#divScrollHaut").scrollLeft(jQuery("#divConteneurPlanning").scrollLeft());
		});
		document.getElementById('divScrollHaut').scrollLeft = xscroll;
		document.getElementById('divConteneurPlanning').scrollLeft = xscroll;

		// synch scrolls for left column and planning
		var isSyncingLeftScroll = false;
		var isSyncingRightScroll = false;
		var leftDiv = document.getElementById('divColonneEntente');
		var rightDiv = document.getElementById('divConteneurPlanning');

		leftDiv.onscroll = function() {
		  if (!isSyncingLeftScroll) {
			isSyncingRightScroll = true;
			rightDiv.scrollTop = this.scrollTop;
		  }
		  isSyncingLeftScroll = false;
		}
		rightDiv.onscroll = function() {
		  if (!isSyncingRightScroll) {
			isSyncingLeftScroll = true;
			leftDiv.scrollTop = this.scrollTop;
		  }
		  isSyncingRightScroll = false;
		}

	{/literal}
	{/if}

	{if $scrolls eq "H"}
	{literal}
		document.getElementById('divConteneurPlanning').style.width = document.body.offsetWidth - 10 - document.getElementById('divBoutonInverser').offsetWidth + 'px';
		document.getElementById('divLigneEntete').style.width = window.innerWidth - getPosition(document.getElementById('divLigneEntete'), 'offsetLeft') - 250 + 'px';
		var offset=document.getElementById('divPlanning').offsetWidth*0.03;
		document.getElementById('divScrollHaut').style.width = document.getElementById('divPlanning').offsetWidth - offset - document.getElementById('tdUserCopie_0').offsetWidth + 'px';
		document.getElementById('divConteneurPlanning').style.width = document.getElementById('divScrollHaut').style.width;
		document.getElementById('divScrollHautInterne').style.width = jQuery('#tabContenuPlanning').width() - 100 + 'px';

		jQuery("#divScrollHaut").scroll(function(){
		console.log(jQuery("#divScrollHaut").scrollLeft());
			jQuery("#divConteneurPlanning").scrollLeft(jQuery("#divScrollHaut").scrollLeft());
		});
		jQuery("#divConteneurPlanning").scroll(function(){
			jQuery("#divScrollHaut").scrollLeft(jQuery("#divConteneurPlanning").scrollLeft());
		});
		document.getElementById('divScrollHaut').scrollLeft = xscroll;
		document.getElementById('divConteneurPlanning').scrollLeft = xscroll;

	{/literal}
	{/if}
	{literal}
	window.scrollTo(xscrollWin,yscroll);
}

addEvent(window, 'load', copierTableauPersonnes);
addEvent(window, 'load', chargerScrolls);

addEvent(window, 'load', chargerScrolls);

Reloader.init({/literal}{$smarty.const.CONFIG_REFRESH_TIMER}{literal});
{/literal}

{* textes pour erreur dans fichier JS *}
var js_choisirProjet = '{#js_choisirProjet#|escape:"javascript"}';
var js_choisirUtilisateur = '{#js_choisirUtilisateur#|escape:"javascript"}';
var js_choisirDateDebut = '{#js_choisirDateDebut#|escape:"javascript"}';
var js_saisirFormatDate = '{#js_saisirFormatDate#|escape:"javascript"}';
var js_dateFinInferieure = '{#js_dateFinInferieure#|escape:"javascript"}';
var js_deposerCaseSurDate = '{#js_deposerCaseSurDate#|escape:"javascript"}';
var js_deplacementOk = '{#js_deplacementOk#|escape:"javascript"}';
var js_patienter = '{#js_patienter#|escape:"javascript"}';


{* initialisation de toutes les fonctions importantes *}
{literal}
jQuery(function() {
	jQuery( "div.cellProject" ).click(function( event ){
	event.stopPropagation();
	var id=this.id;
	idtab=id.split('_');
	var allocation_id = idtab[1];
	var user_id=idtab[2];
	xajax_planUserAvailability(user_id, allocation_id);
});

$(window).resize(function(e) {
	chargerScrolls();
});

{/literal}

{if isset($droitAjoutPeriode) and $droitAjoutPeriode== true}
	{literal}
	jQuery("td.week").on("click",(function(){
		var id=this.id;
		idtab=id.split('_');
		var user_id=idtab[1];
		var annee=idtab[2].substring(0, 4);
		var mois=idtab[2].substring(4, 6);
		var jour=idtab[2].substring(6, 8);
		var datedebut=annee+'-'+mois+'-'+jour;
		Reloader.stopRefresh();
		xajax_planUserAvailability(user_id, '', datedebut);
	}));
	{/literal}
{/if}

{literal}
});
{/literal}

</script>

{include file="www_footer.tpl"}
