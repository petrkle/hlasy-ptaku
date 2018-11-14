<?php

require('config.php');
require('func.php');

$kategorie = get_categories();
$birds = array();

if(!is_dir(WWW)){
	mkdir(WWW, 0755, true);
}

foreach($kategorie as $url=>$nazev){
	$ptaci = get_members($url.'?mode=100');
	foreach($ptaci as $clanek=>$jmeno){
		$htmlfile = asciize($jmeno).'.html';
		savehtml(ROZHLAS.$clanek);
		$birds[$htmlfile] = array(
			'jmeno' => $jmeno,
			'info' => get_ptakinfo($clanek),
			'img' => array(),
			'mp3' => array(),
		);
		foreach(get_img(ROZHLAS.$clanek) as $id=>$img){
			$filename = TMP.'/'.$img['id'].'.jpeg';
			if(!is_file($filename)){
				savefile($img['orig'], $filename);
			}
			array_push($birds[$htmlfile]['img'], $img);
		}

		foreach(get_mp3(ROZHLAS.$clanek) as $mp3){
			$filename=TMP.'/'.$mp3['id'].'.mp3';
			if(!is_file($filename)){
				savefile($mp3['url'], $filename);
			}
			array_push($birds[$htmlfile]['mp3'], $mp3);
		}
	}
}

foreach($birds as $htmlfile => $bird){
	$smarty->assign('title', $bird['jmeno']);
	$smarty->assign('ptak', $bird);
	$html = $smarty->fetch('hlavicka.tpl');
	$html .= $smarty->fetch('ptak.tpl');
	$html .= $smarty->fetch('paticka.tpl');
	file_put_contents(WWW.'/'.$htmlfile, $html);
}

uasort($birds, 'sort_by_jmeno');

$smarty->assign('title', 'Hlasy ptáků');
$smarty->assign('ptaci', $birds);
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('index.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/index.html', $html);

$smarty->assign('title', 'Hlasy ptáků');
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('about.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/about.html', $html);

copy('templates/ptaci.css', WWW.'/ptaci.css');
copy('templates/roboto-regular.ttf', WWW.'/roboto-regular.ttf');

copyToDir(TMP.'/*.jpeg', WWW);
copyToDir(TMP.'/*.mp3', WWW);
