<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/stylesheets/main.css">
</head>
<body>

<h1>StarRaidNG manager</h1>

<ul id="tabs">
    <li id="tab1">
    	<a href="/">
            {if $active eq 'index'}<b>Overview</b>{else}Overview{/if}
		</a>
	</li>
    <li id="tab2">
    	<a href="/accounts">
    		{if $active eq 'accounts'}<b>Accounts</b>{else}Accounts{/if}
		</a>
	</li>
    <li id="tab3">
    	<a href="/characters">
    		{if $active eq 'characters'}<b>Characters</b>{else}Characters{/if}
		</a>
	</li>
    <li id="tab4">
    	<a href="objects.php">
    		{if $id eq 4}<b>Objects</b>{else}Objects{/if}
		</a>
	</li>
    <li id="tab5">
    	<a href="map.php">
    		{if $id eq 5}<b>Map</b>{else}Map{/if}
		</a>
	</li>
    <li id="tab6">
    	<a href="supplys.php">
    		{if $id eq 6}<b>Supplys</b>{else}Supplys{/if}
		</a>
	</li>
</ul>

<br />