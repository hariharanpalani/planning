{* Smarty *}
<form class="form-horizontal" method="POST" action="" target="_blank">
    <div class="container-fluid">
        <input type="hidden" name="resource_id" id="resource_id" value="{$resource_id}" />
        <input type="hidden" name="allocation_id" id="allocation_id" value="{$allocation_id}" />
        <div class="form-group">
		    <label class="col-md-4 control-label">{#ressource_nom#} :</label>
            <div class="col-md-5">
                <label class="control-label"><b>{$resourcename}</b></label>
            </div>
	    </div>
        <div class="form-group col-md-12">
            <label class="col-md-4 control-label">{#resourceUser#} :</label>
            <div class="col-md-6">
                <select name='user' id='user' class="form-control {if $smarty.session.isMobileOrTablet!=1}select2{/if}">
                    <option></option>
                    {foreach name=users item=user from=$users}
                        <option value="{$user.user_id}" {if $user.user_id eq $allocation.user_id} selected="selected" {/if}>{$user.nom}</option>
                    {/foreach}
                </select>
            </div>
	    </div>
        <div class="form-group">
            <label class="col-md-4"></label>
            <div class="col-md-5">
                <input type="button" value="{#ressource_valider#|escape:"html"}" class="btn btn-primary" onClick="xajax_saveResourceAllocationToUser($('#allocation_id').val(), $('#resource_id').val(), $('#user').val());"  />
            </div>
	    </div>
    </div>
</form>