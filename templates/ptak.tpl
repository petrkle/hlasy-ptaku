<script src="jquery.js"></script>
<script src="ts.js"></script>
<a href="index.html" class="hlavicka"><h1>{$title}</h1></a>
{foreach from=$ptak.clanek item=info}

{if $info.typ == 'text'}
{if $info.text == 'Základní údaje'}
<h3>{$info.text}</h3>
{else}
<p>
{$info.text}
</p>
{/if}
{/if}

{if $info.typ == 'img'}
<a href="{$info.img.id}.jpeg"><img src="{$info.img.id}.jpeg" style="width:100%;max-width:45rem;" class="obr"></a>
{if $info.img.popis_ascii != $title_ascii}
<p>
{$info.img.popis}
</p>
{/if}
{/if}

{if $info.typ == 'mp3'}
<label for="{$info.mp3.id}"><h3>{$info.mp3.popis}</h3></label>

<audio
    id="{$info.mp3.id}"
    controls
    src="{$info.mp3.id}.mp3">
</audio>
{/if}

{/foreach}

{foreach from=$ptak.nahravky item=info}
{if $info == 'Jak vznikaly nahrávky'}
<h3>{$info}</h3>
{else}
<p>{$info}</p>
{/if}
{/foreach}
<script>
{literal}
$(document).ready(function () {
			$(".obr").swipe( {
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
{/literal}
					window.location = "{$next.file}";
{literal}
        },
        threshold: 100
      });
{/literal}
{literal}
			$(".obr").swipe( {
        swipeRight:function(event, direction, distance, duration, fingerCount) {
{/literal}
					window.location = "{$prev.file}";
{literal}
        },
        threshold: 100
      });
{/literal}

});
</script>
