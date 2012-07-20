{if $activities}

<table style="width: 100%;">

	{* HEADER *}
    <tr>
    	{$class = "first"}

    	{if $show_task_selector} {* //TODO what about if $common_list ? *}
			{$class = ""}
			<th scope="col" class="first">&nbsp;</th>
		{/if}
		
		<th scope="col" class="{$class}" style="width: 140px;">{t}User{/t}</th>
		<th scope="col">{t}Activity{/t}</th>
		<th scope="col" style="width: 80px;">{t}Date{/t}</th>
		<th scope="col">{t}Duration{/t}</th>
		<th scope="col">{t}Mileage{/t}</th>
			
		<th scope="col" class="last">{t}Actions{/t}</th>
    </tr>
    
    {* BODY *}
	{foreach $activities as $activity}
	<tr class="hoverall">
		{if $show_task_selector}
			<td class="first"><input type="checkbox" class="activity_id_check" name="activity_id[]" value="{$activity->id}"></td>
		{/if}
	
		<td>{$activity->username}</td>
		
		<td>{$activity->description}</td>

		{$date = '--'}
		{if ! is_null($activity->date)}
			{$date = $activity->date|date_format:"%Y-%m-%d"}
		{/if}
		<td>{$date}</td>
					
		{if !empty($activity->duration)}
			{$duration = $activity->duration}
		{else}
			{$duration = '--'}
		{/if}
		<td>{$duration}</td>
		
		{if !empty($activity->mileage)}
			{$mileage = $activity->mileage}
		{else}
			{$mileage = '--'}
		{/if}
		<td>{$mileage}</td>		
		
		<td>
			<a href="/activities/form/activity_id/{$activity->id}">{icon name='edit' alt='edit' ext='png'}</a>
			{* onclick="javascript:if(!confirm({t}Delete{/t})) return false;" *}
			<a href="/activities/delete/activity_id/{$activity->id}" title="delete">{icon name='delete' alt='delete' ext='png'}</a>
		</td>
	</tr>	
	{/foreach}
	
</table>
{/if}