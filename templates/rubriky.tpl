<h1><a href="index.html" class="hlavicka">{$title}</a></h1>

<ul>
{foreach from=$rubriky item=rubrika key=htmlfile}
<li><a href="{$htmlfile}.html">{$rubrika.jmeno}</a></li>
{/foreach}
</ul>
