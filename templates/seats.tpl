{* Smarty *}

{include file="www_header.tpl"}

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="soplanning-box form-inline">
                <b>Date:</b>
                <div class="btn-group">
					<div class="btn btn-default">
                        <b>{$todayDate}</b>
                    </div>
				</div>
            </div>
        </div>
    </div>

    {if $resources|@count > 0}
    
        <div class="row">
            <div class="col-md-12">
                <div class="soplanning-box margin-top-10">
                    <table class="table table-striped table-hover" id="resources">
                        <thead>
                            <tr>
                                <th>{#headerDesk#}</th>
                                <th>{#headerAssign#}</th>
                            </tr>
                        </thead>
                        <tbody>
                        {foreach name=resources item=resource from=$resources}
                            <tr>
                                <td>{$resource.resourcename | xss_protect}</td>
                                {if $resource.assigneduser != null}
                                    <td><a href="javascript:Reloader.stopRefresh();xajax_allocateResourceToUser('{$resource.resourcename}', '{$resource.resource_id}', '{$resource.allocation_id}');undefined;" class="badge badge-success">{$resource.assigneduser}</a></td>
                                {else}
                                    <td><a href="javascript:Reloader.stopRefresh();xajax_allocateResourceToUser('{$resource.resourcename}', '{$resource.resource_id}', '{$resource.allocation_id}');undefined;" class="badge badge-light">&nbsp;</a></td>
                                {/if}
                            </tr>
                        {/foreach}
                        </tbody>
                        <tfoot>
                        {if $nbPages > 1}
							<tr>
								<td colspan="9" class="text-right">
									{if $currentPage > 1}<a href="{$BASE}/seats.php?page={$currentPage-1}">&lt;&lt; {#action_precedent#}</a>&nbsp;&nbsp;{/if}
									{section name=pagination loop=$nbPages}
										{if $smarty.section.pagination.iteration == $currentPage}<b>{else}<a href="{$BASE}/seats.php?page={$smarty.section.pagination.iteration}">{/if}
										{$smarty.section.pagination.iteration}
										{if $smarty.section.pagination.iteration == $currentPage}</b>{else}</a>{/if}&nbsp;
									{/section}
									{if $currentPage < $nbPages}<a href="{$BASE}/seats.php?page={$currentPage+1}">{#action_suivant#} &gt;&gt;</a>{/if}
								</td>
							</tr>
						{/if}
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    {else}
	
		<div class="row">
			<div class="col-md-12">
				<div class="soplanning-box margin-top-10">
					{#info_noRecord#}
				</div>
			</div>
		</div>

	{/if}
</div>

<script language="javascript">
	initselect2('{$lang}','{#stats_tous_selectionnes#}');					
</script>

{include file="www_footer.tpl"}