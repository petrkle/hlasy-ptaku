document.addEventListener('DOMContentLoaded',function() {
	if(document.querySelector('input[id="q"]')){
    document.querySelector('input[id="q"]').onkeyup=vyhledavani;
	}

	nadpis = document.querySelector('h1');
	var img = document.createElement("img");
	img.src = "s.svg";
	img.id = "lupa";
	img.onclick = function (){showsearchform()};
	nadpis.appendChild(img);

},false);

function showsearchform(){
	nadpis = document.querySelector('h1');
	nadpis.innerHTML = '<input type="search" placeholder="Vyhledávání" id="q" autocomplete="off" />';
  document.querySelector('input[id="q"]').onkeyup=vyhledavani;
	document.getElementById("q").focus();
	nadpis = document.querySelector('h1');
	var ul = document.createElement("ul");
	ul.id = "vysledky";
	nadpis.parentNode.insertBefore( ul, nadpis.nextSibling );
}

function vyhledavani(event){
	var query = event.target.value;
	var spojka = /_/gi;
	var limit = 100;
	var nalezeno = 0;
	var vysledky = [];
	var re = new RegExp('.*'+bezdiak(query.replace(/ /g, '.*'))+'.*', 'gi');

	for (const ptak in ptaci) {
			let foo = ptaci[ptak];

			var nazev = foo.c;
			var ptaksmezerou= ptak.replace(spojka, ' ');
			var ptaksmezeroupozpatku = ptaksmezerou.split(' ').reverse().join(' ');
			var dokument = ptak.replace(spojka, '-')+'.html';
			var vnazvu = ptaksmezerou.match(re);
			var vnazvupozpatku = ptaksmezeroupozpatku.match(re);
			if(vnazvu || vnazvupozpatku){
				if(nalezeno<limit){
					vysledky.push({nazev: nazev, dokument: dokument});
				}
				nalezeno++;
			}

			var nazevl = foo.l;
			var nazevlpozpatuku = nazevl.split(' ').reverse().join(' ');

			var vlnazvu = nazevl.match(re);
			var vlnazvupozpatku = nazevlpozpatuku.match(re);
			if(vlnazvu || vlnazvupozpatku){
				if(nalezeno<limit){
					vysledky.push({nazev: nazevl, dokument: dokument});
				}
				nalezeno++;
			}

	}

	var seznam = '';
	for(var foo=0; foo<vysledky.length; foo++){
		seznam = seznam + '<li><a href="' + vysledky[foo].dokument + '" class="vysledek">' + vysledky[foo].nazev + '</a></li>';
	}
	document.getElementById("vysledky").innerHTML = seznam;

}

function bezdiak(txt){
	var tx = '';
	var sdiak="ÁÂÄĄáâäąČčĆćÇçĈĉĎĐďđÉÉĚËĒĖĘéěëēėęĜĝĞğĠġĢģĤĥĦħÍÎíîĨĩĪīĬĭĮįİıĴĵĶķĸĹĺĻļĿŀŁłĹĽĺľŇŃŅŊŋņňńŉÓÖÔŐØŌōóöőôøŘřŔŕŖŗŠšŚśŜŝŞşŢţŤťŦŧŨũŪūŬŭŮůŰűÚÜúüűŲųŴŵÝYŶŷýyŽžŹźŻżß";
	var bdiak="AAAAaaaaCcCcCcCcDDddEEEEEEEeeeeeeGgGgGgGgHhHhIIiiIiIiIiIiIiJjKkkLlLlLlLlLLllNNNNnnnnnOOOOOOooooooRrRrRrSsSsSsSsTtTtTtUuUuUuUuUuUUuuuUuWwYYYyyyZzZzZzs";

for(p=0;p<txt.length;p++){

if (sdiak.indexOf(txt.charAt(p))!=-1){
	tx+=bdiak.charAt(sdiak.indexOf(txt.charAt(p)));
}
	else tx+=txt.charAt(p);
}

return tx;

}
