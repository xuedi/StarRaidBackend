{include file='layout/top.tpl' active='objects'}



<table cellpadding="10"><tr><td valign="top">



<table border=0 cellpadding="2" cellspacing="3" bgcolor="#eeeeee">
	<tr bgcolor="#bbbbbb">
		<td bgcolor="#66bb66">Account</td>
		<td bgcolor="#66bb66">Character</td>
		<td>ID</td>
		<td>Name</td>
		<td>status</td>
		<td>x</td>
		<td>y</td>
		<td bgcolor="#6666bb">Type</td>
	</tr>
{foreach from=$objects item=obj}
	{assign var="tmp" value=""}
	{if $obj.id==$object.id}
		{assign var="tmp" value="bgcolor='#eeeeaa'"}
	{/if}
	<tr>
		<td bgcolor="#c5eec5"><a href="/accounts/edit?id={$obj.login_id}">{$obj.account.username}</a></td>
		<td bgcolor="#c5eec5"><a href="/characters/edit?id={$obj.character.id}">{$obj.character.name}</a></td>
		<td>{$obj.id}</td>
		<td>{$obj.name}</td>
		<td>{$obj.status}</td>
		<td><a href="map.php?mapW=500&mapH=500&mapX=-2000&mapY=-2000&mapZ=1&showlink=1">{$obj.x}</a></td>
		<td><a href="map.php?mapW=500&mapH=500&mapX=-2000&mapY=-2000&mapZ=1&showlink=1">{$obj.y}</a></td>
		<td bgcolor="#c5c5ee">{$obj.type.name}</td>
		<td {$tmp}><a href="/objects/edit?id={$obj.id}"><img src="/images/edit.png" border="0"></a></td>
		<td {$tmp}><a href="/objects/edit?id={$obj.id}"><img src="/images/drop.png" border="0"></a></td>
	</tr>
{/foreach}
	<tr bgcolor="#bbbbbb">
		<td colspan="2" bgcolor="#66bb66"></td>
		<td colspan="6"></td>
	</tr>
</table>



</td><td valign="top">



<form action="/objects/do" method="post">
<input type="hidden" name="id" value="{$object.id}">
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
		<td>Type:</td>
		<td>{html_options name=type_id options=$typeList selected=$object.type_id}</td>
	</tr>
	<tr>
		<td>Character:</td>
		<td>{html_options name=character_id options=$characterList selected=$object.character_id}</td>
	</tr>
	<tr>
		<td>Name:</td>
		<td><input type="text" name="name" value="{$object.name}"></td>
	</tr>
	<tr>
		<td>Status:</td>
		<td><input type="text" name="status" value="{$object.status}"></td>
	</tr>
	<tr>
		<td>X:</td>
		<td><input type="text" name="x" value="{$object.x}"></td>
	</tr>
	<tr>
		<td>Y:</td>
		<td><input type="text" name="y" value="{$object.y}"></td>
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