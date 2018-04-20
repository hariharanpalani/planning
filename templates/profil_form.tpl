{* Smarty *}
<form class="form-horizontal" method="post" action="" target="_blank" name="formUser"  onSubmit="return false;">
	<div class="container-fluid">
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_identifiant#} :</label>
		<div class="col-md-5">
			{if $user_form.saved eq 1}
				<input id="user_id" type="text" class="form-control" value="{$user_form.user_id|escape:"html"}" readonly="readonly"/>
			{else}
				<input id="user_id" type="text" class="form-control" value="{$user_form.user_id|escape:"html"}" maxlength="3" />
			{/if}
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_nom#} :</label>
		<div class="col-md-5">
			<input type="text" class="form-control" value="{$user_form.nom|escape:"html"}" readonly="readonly"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_email#} :</label>
		<div class="col-md-5">
			<input id="email_user" type="text" class="form-control" value="{$user_form.email|escape:"html"}" maxlength="255" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_login#} :</label>
		<div class="col-md-5">
			<input type="text" class="form-control" value="{$user_form.nom|escape:"html"}" readonly="readonly"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_password#} :</label>
		<div class="col-md-5">
			<input id="password_tmp" type="password" class="form-control" value="" maxlength="20" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_notifications#} :</label>
		<div class="col-md-5">
			<label class="radio-inline">
				<input type="radio" id="notificationsOui" name="notifications" value="oui" {if $user_form.notifications eq "oui"}checked="checked"{/if}>&nbsp;{#oui#}
			</label>
			<label class="radio-inline">
				<input type="radio" id="notificationsNon" name="notifications" value="non" {if $user_form.notifications eq "non"}checked="checked"{/if}>&nbsp;{#non#}
			</label>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_dateformat#} :</label>
		<div class="col-md-5">
			<select name="dateformat" id="dateformat" class="form-control">
				<option value="fr" {if $user_form.tabPreferences.dateformat eq "fr"}selected="selected"{/if}>{#user_dateformatfr#}</option>
				<option value="us" {if $user_form.tabPreferences.dateformat eq "us"}selected="selected"{/if}>{#user_dateformatus#}</option>
				<option value="jp" {if $user_form.tabPreferences.dateformat eq "jp"}selected="selected"{/if}>{#user_dateformatjp#}</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-md-4 control-label">{#user_prefs_vuedefaut#} :</label>
		<div class="col-md-6">
			<label class="radio-inline">
				<input type="radio" id="vueDefautPlanning" name="user_prefs_vueplanning" value="vuePlanning" {if $user_form.tabPreferences.vuePlanning eq "" or $user_form.tabPreferences.vuePlanning eq "vuePlanning"}checked="checked"{/if}>&nbsp;{#menuPlanningVuePlanning#}
			</label>
			<label class="radio-inline">
				<input type="radio" id="vueDefautPlanning" name="user_prefs_vueplanning" value="vueTaches" {if $user_form.tabPreferences.vuePlanning eq "vueTaches"}checked="checked"{/if}>&nbsp;{#menuPlanningVueTaches#}
			</label>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-4 control-label"></label>
		<div class="col-md-6">
			<label class="radio-inline">
				<input type="radio" id="vueDefautPersonne" name="user_prefs_vuedefaut" value="vuePersonne" {if $user_form.tabPreferences.vueDefaut eq "" or $user_form.tabPreferences.vueDefaut eq "vuePersonne"}checked="checked"{/if}>&nbsp;{#menuPlanningCompletPersonne#}
			</label>
			<label class="radio-inline">
				<input type="radio" id="vueDefautProjet" name="user_prefs_vuedefaut" value="vueProjet" {if $user_form.tabPreferences.vueDefaut eq "vueProjet"}checked="checked"{/if}>&nbsp;{#menuPlanningCompletProjet#}
			</label>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-4"></div>
		<div class="col-md-6">
			<label class="radio-inline">
				<input type="radio" id="vueDefautMois" name="user_prefs_vuedefaut_jourmois" value="vueMois" {if $user_form.tabPreferences.vueJourMois eq "" or $user_form.tabPreferences.vueJourMois eq "vueMois"}checked="checked"{/if}>&nbsp;{#menuPlanningMois#}
			</label>
			<label class="radio-inline">
				<input type="radio" id="vueDefautJour" name="user_prefs_vuedefaut_jourmois" value="vueJour" {if $user_form.tabPreferences.vueJourMois eq "vueJour"}checked="checked"{/if}>&nbsp;{#menuPlanningJour#}
			</label>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-4"></div>
		<div class="col-md-6">
			<label class="radio-inline">
				<input type="radio" id="vueDefautReduit" name="user_prefs_vuedefaut_largereduit" value="vueReduit" {if $user_form.tabPreferences.vueLargeReduit eq  "" || $user_form.tabPreferences.vueLargeReduit eq "vueReduit"}checked="checked"{/if}>&nbsp;{#menuPlanningReduit#}
			</label>
			<label class="radio-inline">
				<input type="radio" id="vueDefautLarge" name="user_prefs_vuedefaut_largereduit" value="vueLarge" {if $user_form.tabPreferences.vueLargeReduit eq "vueLarge"}checked="checked"{/if}>&nbsp;{#menuPlanningLarge#}
			</label>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-4"></div>
		<div class="col-md-4">
				<br /><input type="button" class="btn btn-primary" value="{#submit#}" onClick="xajax_submitFormProfil('{$user_form.user_id|escape}', $('#email_user').val(), $('#password_tmp').val(), $('#dateformat').val(), $('#notificationsOui').is(':checked'),$('#vueDefautPlanning').is(':checked'),$('#vueDefautPersonne').is(':checked'),$('#vueDefautMois').is(':checked'),$('#vueDefautLarge').is(':checked'));"/>
		</div>
	</div>
	</div>
</form>