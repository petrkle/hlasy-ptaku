<h1><a href="about.html" class="hlavicka">{$title}</a></h1>

<ul>
{foreach from=$ptaci item=ptak key=htmlfile}
<li><a href="{$htmlfile}">{$ptak.jmeno}</a></li>
{/foreach}
</ul>
