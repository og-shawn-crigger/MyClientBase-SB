{if $tasks}

<table style="width: 100%;">

	{* HEADER *}
    <tr>
    	{$class = "first"}

    	{if $show_task_selector} {* //TODO what about if $common_list ? *}
			{$class = ""}
			<th scope="col" class="first">&nbsp;</th>
		{/if}
		
		<th scope="col" class="{$class}">{t}Contact{/t}</th>
		
		<th scope="col">{t}Date{/t}</th>
		<th scope="col">{t}Due Date{/t}</th>
		<th scope="col">{t}Topic{/t}</th>
		<th scope="col" style="text-align: center">{t}Done{/t}</th>			
		<th scope="col" class="last">{t}Actions{/t}</th>
    </tr>
    
    {* BODY *}
	{foreach $tasks as $task}
	<tr class="hoverall">
		{if $show_task_selector}
			<td class="first"><input type="checkbox" class="task_id_check" name="task_id[]" value="{$task->task_id}"></td>
		{/if}
	
		{if isset($task->client_name)}
			<td><a href="/contact/details/{$task->client_id_key}/{$task->client_id}#tab_tasks">{$task->client_name}</a></td>
		{/if}
		
		{if !empty($task->start_date)}
			{$date = $task->start_date|date_format:"%Y-%m-%d"}
		{else}
			{$date = '--'}
		{/if}
		<td>{$date}</td>
		
		{if !empty($task->due_date)}
			{$date = $task->due_date|date_format:"%Y-%m-%d"}
		{else}
			{$date = '--'}
		{/if}
		<td>{$date}</td>		
			
		<td>{$task->title}</td>
	
		{if !empty($task->complete_date)}
			<td  style="text-align: center">{icon name='check' alt='done' ext='png'}</td>
		{else}
			<td style="text-align: center">--</td>
		{/if}
		
		<td>
			<a href="/tasks/form/task_id/{$task->task_id}">{icon name='edit' alt='edit' ext='png'}</a>
			{* onclick="javascript:if(!confirm({t}Delete{/t})) return false;" *}
			<a href="/tasks/delete/task_id/{$task->task_id}" title="delete">{icon name='delete' alt='delete' ext='png'}</a>
		</td>
	</tr>	
	{/foreach}
	
</table>
{/if}