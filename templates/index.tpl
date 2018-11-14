<a href="about.html" class="hlavicka"><h1>{$title}</h1></a>

<ul>
{foreach from=$ptaci item=ptak key=htmlfile}
<li><a href="{$htmlfile}">{$ptak.jmeno}</a></li>
{/foreach}
</ul>
