		{* Smarty *}
		<div class="navbar navbar-fixed-bottom navbar-default footer">
			<div class="navbar-inner">
				<div class="container text-center">
					<p class="text-info">
						<a target="_blank" href="https://www.soplanning.org">www.soplanning.org</a>
						<span class="noprint">&nbsp;-&nbsp;</span>
						<a href="mailto:support@soplanning.org" class="noprint">{#soplanning_support#}</a>
						<span class="noprint">&nbsp;-&nbsp;</span>
						<a href="#divFormSupport" role="button" data-toggle="modal" class="noprint">{#formContact_titre#}</a>
					</p>
				</div>
			</div>
		</div>
		<div id="pwdReminderModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3>{#rappelPwdTitre#}</h3>
					</div>
					<div class="modal-body">
						<input type="text" id="rappel_pwd" placeholder="{#rappelPwdVotreEmail#}" class="form-control" />
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary" id="changePwd">{#submit#}</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="divFormSupport" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">{#formContact_titre#}</h3>
					</div>
					<div class="modal-body">
						<form class="form-horizontal">
							<input type="hidden" id="form_contact_version" value="{$infoVersion}" />
							<div class="form-group">
								<label for="form_contact_email" class="col-md-2 control-label">{#formContact_email#} :</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="form_contact_email" value="{if isset($user) && $user.email neq ''}{$user.email}{/if}" />
								</div>
							</div>
							<div class="form-group">
								<label for="form_contact_commentaire" class="col-md-2 control-label">{#formContact_commentaire#} :</label>
								<div class="col-md-6">
									<textarea class="form-control" id="form_contact_commentaire"></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2 control-label">&nbsp;</div>
								<div class="col-md-6">
									<label class="checkbox-inline">
										<input type="checkbox" id="form_contact_abo" value="1" checked="checked" />&nbsp;{#formContact_newsletter#}
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-2 control-label">&nbsp;</div>
								<div class="col-md-6">
									<input type="button" class="btn btn-primary" onClick="if(confirm('{#confirm#|escape:"javascript"}'))xajax_submitFormContact(document.getElementById('form_contact_version').value, document.getElementById('form_contact_email').value, document.getElementById('form_contact_commentaire').value, document.getElementById('form_contact_abo').checked);" value="{#formContact_envoyer#}" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="myModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">...</h3>
					</div>
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="alertModal" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="myBigModal" role="dialog">
			<div class="modal-dialog modalBig">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">...</h3>
					</div>
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<div class="modal modalMiddle" id="myMiddleModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title">...</h3>
					</div>
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			{* initialisation de toutes les fonctions importantes *}
			addEvent(window, 'load', initAll);
			{literal}
			jQuery(function() {
				jQuery( ".modal" ).draggable({
					cursor: "move",
					handle: "h3"
				});
			});
			{/literal}
		</script>
		<script type="text/javascript">
			{literal}
			jQuery(function() {
				$('.tooltipster').tooltipster({
				delay: 0,
				animationDuration: 0,
				arrow: false,
				contentAsHTML: true,
				contentCloning: true,
				multiple: true,
				theme: 'tooltipster-soplanning'
				});
			});
			{/literal}
		</script>
	</body>
</html>
