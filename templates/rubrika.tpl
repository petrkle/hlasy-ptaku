<h1><a href="rubriky.html" class="hlavicka">{$title}</a></h1>

<ul>
{foreach from=$rubrika.clenove item=ptak key=htmlfile}
<li><a href="{$ptak.htmlfile}">{$ptak.jmeno}</a></li>
{/foreach}
</ul>
