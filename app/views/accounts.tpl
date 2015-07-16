{include file='layout/top.tpl' active='accounts'}



<table cellpadding="10"><tr><td valign="top">



<table border=0 cellpadding="2" cellspacing="3" bgcolor="#eeeeee">
	<tr bgcolor="#bbbbbb">
		<td>ID</td>
		<td>Name</td>
		<td>Pass</td>
		<td>status</td>
		<td bgcolor="#6666bb">Characters</td>
	</tr>
{section name=mysec loop=$accounts}
	{assign var="tmp" value=""}
	{if $accounts[mysec].id==$account.id}
		{assign var="tmp" value="bgcolor='#eeeeaa'"}
	{/if}
	<tr>
		<td>{$accounts[mysec].id}</td>
		<td>{$accounts[mysec].username}</td>
		<td>{$accounts[mysec].password}</td>
		<td>{$accounts[mysec].status}</td>
		<td bgcolor="#c5c5ee">{$accounts[mysec].char_sum}</td>
		<td {$tmp}><a href="/accounts/edit?id={$accounts[mysec].id}"><img src="/images/edit.png" border="0"></a></td>
		<td {$tmp}><a href="/accounts/delete?id={$accounts[mysec].id}"><img src="/images/drop.png" border="0"></a></td>
	</tr>
{/section}
	<tr bgcolor="#eeeeaa">
		<td colspan="5">The ID for the NPC controller is 0</td>
	</tr>
</table>



</td><td valign="top">



<form action="/accounts/do" method="post">
<input type="hidden" name="id" value="{$account.id}">
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
		<td>Name:</td>
		<td><input type="text" name="username" value="{$account.username}"></td>
	</tr>
	<tr>
		<td>Pass:</td>
		<td><input type="text" name="password" value="{$account.password}"></td>
	</tr>
	<tr>
		<td>Status:</td>
		<td><input type="text" name="status" value="{$account.status}"></td>
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