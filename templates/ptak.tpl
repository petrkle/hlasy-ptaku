<a href="index.html" class="hlavicka"><h1>{$title}</h1></a>
{if isset($ptak.img[0])}
<a href="{$ptak.img[0].id}.jpeg"><img src="{$ptak.img[0].id}.jpeg" style="width:100%;max-width:40em;"></a>
<p>
{$ptak.img[0].popis}
</p>
{/if}
{foreach from=$ptak.info item=info}
{if $info == 'Základní údaje'}
<h3>{$info}</h3>
{else}
<p>{$info}</p>
{/if}
{/foreach}

{foreach from=$ptak.mp3 item=mp3}

<label for="{$mp3.id}"><h3>{$mp3.popis}</h3></label>

<audio
    id="{$mp3.id}"
    controls
    src="{$mp3.id}.mp3">
</audio>

{/foreach}

{foreach from=$ptak.nahravky item=info}
{if $info == 'Jak vznikaly nahrávky'}
<h3>{$info}</h3>
{else}
<p>{$info}</p>
{/if}
{/foreach}
