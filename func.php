<?php

function savehtml($url){
	if(!is_file(TMP.'/'.url2fn($url))){
		savefile($url, TMP.'/'.url2fn($url));
	}
}

function savefile($url, $filename){
	if(!is_file($filename)){
		print "$url\n";
		if(!is_dir(dirname($filename))){
			mkdir(dirname($filename), 0755, true);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_REFERER, ROZHLAS);
		curl_setopt($ch, CURLOPT_USERAGENT, 'php '.phpversion());
		curl_setopt($ch,CURLOPT_ENCODING, '');
		file_put_contents($filename, curl_exec($ch));
		curl_close($ch);
	}
}

function asciize($str) {
    $str = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $str));
    $str = preg_replace('/[^a-z0-9.]/', ' ', $str);
    $str = preg_replace('/\s\s+/', ' ', $str);
    $str = str_replace(' ', '-', trim($str));
    return $str;
}

function url2fn($url){
	$url = str_replace(ROZHLAS, '', $url);
	return preg_replace('/[^a-z0-9]*/', '', $url).'.html';
}

function get_img($url){
	$linky = array();

	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
	$xpath = new DOMXPath($dom);
	$obrazky = $xpath->query("//div[@id='article']/div[@class='image']");

	
	foreach($obrazky as $obrazek) {
		$newdoc = new DOMDocument();
		$cloned = $obrazek->cloneNode(TRUE);
		$newdoc->appendChild($newdoc->importNode($cloned,TRUE));
		$chunk = new DOMXPath($newdoc);


		$l = $chunk->query('//a');
		$d = $chunk->query('//p[@class="description"]/span');
		if($d->item(0)){
			$popis = $d->item(0)->nodeValue;
		}
		if($l->item(0)){
			$link = $l->item(0)->getAttribute("href");
			$orig = preg_replace('/(.*_obrazek\/[0-9]+)\-\-.*/', '\1', $link).'.jpeg';
			$id = basename($orig, '.jpeg');
			$linky[$id] = array(
				'id' => $id,
				'orig' => $orig,
				'link' => $link,
				'popis' => $popis,
				'popis_ascii' => asciize($popis),
			);
		}
	}

	return $linky;

}

function get_mp3($url){
	$linky = array();
	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
	$xpath = new DOMXPath($dom);
	$audio = $xpath->query("//div[@class='audio']");

	foreach($audio as $mp3){

		$newdoc = new DOMDocument();
		$cloned = $mp3->cloneNode(TRUE);
		$newdoc->appendChild($newdoc->importNode($cloned,TRUE));
		$chunk = new DOMXPath($newdoc);

		$l = $chunk->query("//p[@class='embed']/a");

		$d = $chunk->query('//p[@class="description"]');
		if($d->item(0)){
			$popis = $d->item(0)->nodeValue;
		}

		if($l->item(0)){
			$link = $l->item(0)->getAttribute("href");
			$mp3 = preg_replace('/.*audio\/([0-9]+).*/', 'https://media.rozhlas.cz/_audio/\1.mp3', $link);
			array_push($linky, array(
			'url' => $mp3,
			'id' => basename($mp3, '.mp3'),
			'popis' => $popis,
			));
		}

	}
	return $linky;

}

function get_categories(){
	$index = '/hlas/portal/';
	savehtml(ROZHLAS.$index);
	$linky = array();
	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents(TMP.'/'.url2fn($index)));
	$xpath = new DOMXPath($dom);
	$odkazy = $xpath->query("//div[@id='box-categories-ptaci']/*/ul/li/h5/a");

	foreach($odkazy as $odkaz){
		$linky[$odkaz->getAttribute("href")] = $odkaz->nodeValue;
	}
	return $linky;
}

function get_members($url){
	savehtml(ROZHLAS.$url);
	$linky = array();
	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
	$xpath = new DOMXPath($dom);
	$odkazy = $xpath->query("//div[@class='content']/*/ul/li/h3/a");

	foreach($odkazy as $odkaz){
		$linky[$odkaz->getAttribute("href")] = preg_replace('/ \(VIDEO\)$/', '', $odkaz->nodeValue);
	}
	return $linky;
}

function get_ptakinfo($url){
	$navrat = array();
	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
	$xpath = new DOMXPath($dom);
	$odstavce = $xpath->query("//div[@id='article']/p");
	foreach($odstavce as $odstavec){
		$text = trim(preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $odstavec->nodeValue));
		if(strlen($text)>0 and !preg_match('/^Video:/', $text)){
			array_push($navrat, $text);
		}
	}
	return($navrat);
}

function get_nahravkyinfo($url){
	$navrat = array();
	$dom = new DOMDocument();
	$dom->loadHTML(file_get_contents(TMP.'/'.url2fn($url)));
	$xpath = new DOMXPath($dom);
	$odstavce = $xpath->query("//div[@id='article']/div[contains(@style,'color: grey')]/span");
	foreach($odstavce as $odstavec){
		$text = trim(preg_replace(['(\s+)u', '(^\s|\s$)u'], [' ', ''], $odstavec->nodeValue));
			array_push($navrat, $text);
	}
	return($navrat);
}

function copyToDir($pattern, $dir)
{
    foreach (glob($pattern) as $file) {
        $dest = realpath($dir) . DIRECTORY_SEPARATOR . basename($file);
        if(is_file($file)) {
            copy($file, $dest);
        }
    }
}

function sort_by_jmeno($a, $b)
{
	$coll = collator_create( 'cs_CZ.UTF-8' );
	return collator_compare($coll, $a['jmeno'], $b['jmeno']);
}
