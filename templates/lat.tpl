<h1><a href="index.html" class="hlavicka">{$title}</a></h1>

<ul>
{foreach from=$lat item=ptak key=htmlfile}
<li><a href="{$htmlfile}.html" id="{$htmlfile}">{$ptak.l}</a></li>
{/foreach}
</ul>
