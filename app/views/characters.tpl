{include file='layout/top.tpl' active='characters'}



<table cellpadding="10"><tr><td valign="top">



<table border=0 cellpadding="2" cellspacing="3" bgcolor="#eeeeee">
	<tr bgcolor="#bbbbbb">
		<td bgcolor="#66bb66">Account</td>
		<td>ID</td>
		<td>Name</td>
		<td>prestige</td>
		<td bgcolor="#6666bb">Objects</td>
	</tr>
{section name=mysec loop=$characters}
	{assign var="tmp" value=""}
	{if $characters[mysec].id==$character.id}
		{assign var="tmp" value="bgcolor='#eeeeaa'"}
	{/if}
	<tr>
		<td><a href="/accounts/edit?id={$characters[mysec].login_id}">1</a></td>
		<td>{$characters[mysec].id}</td>
		<td>{$characters[mysec].name}</td>
		<td>{$characters[mysec].prestige}</td>
		<td bgcolor="#c5c5ee">{$characters[mysec].obj_sum}</td>
		<td {$tmp}><a href="/characters/edit?id={$characters[mysec].id}"><img src="/images/edit.png" border="0"></a></td>
		<td {$tmp}><a href="/characters/delete?id={$characters[mysec].id}"><img src="/images/drop.png" border="0"></a></td>
	</tr>
{/section}
</table>



</td><td valign="top">



<form action="/characters/do~FC" method="post">
<input type="hidden" name="id" value="{$character.id}">
<table border=0 cellpadding="2" cellspacing="3" bgcolor="#eeeeee">
	<tr bgcolor="#bbbbbb">
		<td colspan="2">
			{if $action == "index"}Add a new{/if}
			{if $action == "edit"}Edit{/if}
			{if $action == "delete"}Delete{/if}
			account
		</td>
	</tr>
	<tr>
		<td>Controller:</td>
		<td>{html_options name=login_id options=$loginList selected=$character.login_id}</td>
	</tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="name" value="{$character.name}"></td>
	</tr>
	<tr>
		<td>Prestige:</td>
		<td><input type="text" name="prestige" value="{$character.prestige}"></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			{if $action == "index"}<input type="submit" name="action" value="add">{/if}
			{if $action == "edit"}<input type="submit" name="action" value="save">{/if}
			{if $action == "delete"}<input type="submit" name="action" value="delete">{/if}
		</td>
	</tr>
</table>
</form>



</td></tr></table>

{include file='layout/bottom.tpl'}