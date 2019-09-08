<h2>Nastavení</h2>
<p class="c"><input type="button" value="Zobrazení" id="theme" class="knoflik"></p>
<script>
knoflik = document.getElementById('theme');

if (localStorage.getItem('dark') != null) {
	knoflik.value = 'Světlý režim';
	knoflik.onclick = function(event){
		localStorage.removeItem('dark');
		location.reload(true);
	}
}else{
	knoflik.value = 'Tmavý režim';
	knoflik.onclick = function(event){
		localStorage.setItem('dark', 1);
		location.reload(true);
	}
}
</script>
