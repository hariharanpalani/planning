{* Smarty *}
<form class="form-horizontal" method="post" action="" target="_blank" onsubmit="return false;">
	<div class="container-fluid">
	<div class="form-group">
		<label class="col-md-2 control-label">{#feries_date#} :</label>
		<div class="col-md-6">
			{if $smarty.session.isMobileOrTablet==1}
				<input type="date" class="form-control" id="date_ferie" maxlength="10" value="{$ferie.date_ferie|forceISODateFormat}" />		
			{else}
				<input type="text" class="form-control datepicker" id="date_ferie" maxlength="10" value="{$ferie.date_ferie|sqldate2userdate}" />		
			{/if}
			
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-2 control-label">{#feries_libelle#} :</label>
		<div class="col-md-6">
			<input id="libelle" maxlength="50" type="text" value="{$ferie.libelle}" class="form-control" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-2"></div>
		<div class="col-md-6">
			<input type="button" class="btn btn-primary" value="{#submit#|escape:"html"}" onclick="xajax_submitFormFerie(document.getElementById('date_ferie').value, document.getElementById('libelle').value);"/>
		</div>
	</div>
</form>