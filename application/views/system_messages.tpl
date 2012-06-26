{if $system_messages}
	{foreach $system_messages as $type => $message}
		<div class="{$type}">{t}{$message}{/t}</div>
	{/foreach}
{/if}