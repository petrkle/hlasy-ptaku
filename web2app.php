<?php

require('config.php');
require('func.php');

$kategorie = get_categories();
$birds = array();
$rubriky = array();

if(!is_dir(WWW)){
	mkdir(WWW, 0755, true);
}

$VERSION = `git describe --tags --always --dirty`;

$lat = get_lat('templates/index.js');

foreach($kategorie as $url=>$nazev){
	$ptaci = get_members($url.'?mode=100');
	foreach($ptaci as $clanek=>$jmeno){
		$htmlfile = asciize($jmeno).'.html';
		savehtml(ROZHLAS.$clanek);
		$birds[$htmlfile] = array(
			'jmeno' => $jmeno,
			'htmlfile' => $htmlfile,
			'id' => asciize($jmeno),
			'lat' => $lat[asciize($jmeno)]['l'],
			'rubrika' => get_rubrika($clanek),
			'rubrikaid' => asciize(get_rubrika($clanek)),
			'info' => get_ptakinfo($clanek),
			'nahravky' => get_nahravkyinfo($clanek),
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

		if(isset($rubriky[$birds[$htmlfile]['rubrikaid']])){
			array_push($rubriky[$birds[$htmlfile]['rubrikaid']]['clenove'], $birds[$htmlfile]);
		}else{
			$rubriky[$birds[$htmlfile]['rubrikaid']] = array(
				'jmeno' => $birds[$htmlfile]['rubrika'],
				'clenove' => array($birds[$htmlfile]),
			);
		}

	}
}

uasort($birds, 'sort_by_jmeno');

uasort($rubriky, 'sort_by_jmeno');

$cislo = 0;
$seznamptaku = array();
foreach($birds as $htmlfile => $bird){
	$seznamptaku[$cislo] = array('file' => $htmlfile,
		'bird' =>$bird);
	$cislo++;
}

$cislo = 0;

foreach($birds as $htmlfile => $bird){
	$smarty->assign('title', $bird['jmeno']);
	$smarty->assign('title_ascii', asciize($bird['jmeno']));

	$bird['clanek'] = array();

	foreach($bird['info'] as $info){
		$bird['clanek'][$info['poradi']] = array('typ' => 'text', 'text' => $info['text']);
	}

	foreach($bird['img'] as $img){
		$bird['clanek'][$img['poradi']] = array('typ' => 'img', 'img' => $img);
		$smarty->assign('img', $img['id'].'.jpeg');
		$html = $smarty->fetch('img.tpl');
		file_put_contents(WWW.'/'.$img['id'].'.html', $html);
	}

	foreach($bird['mp3'] as $mp3){
		$bird['clanek'][$mp3['poradi']] = array('typ' => 'mp3', 'mp3' => $mp3);
	}

	ksort($bird['clanek']);

	$smarty->assign('ptak', $bird);
	if($cislo == 0){
		$smarty->assign('prev', $seznamptaku[count($seznamptaku)-1]);
	}else{
		$smarty->assign('prev', $seznamptaku[$cislo-1]);
	}

	if($cislo == count($seznamptaku)-1){
		$smarty->assign('next', $seznamptaku[0]);
	}else{
		$smarty->assign('next', $seznamptaku[$cislo+1]);
	}
	$html = $smarty->fetch('hlavicka.tpl');
	$html .= preg_replace('/\('.$bird['lat'].'\)/', '(<a href="lat.html#'.$bird['id'].'" class="lat">'.$bird['lat'].'</a>)', $smarty->fetch('ptak.tpl'));
	$html .= $smarty->fetch('paticka.tpl');
	file_put_contents(WWW.'/'.$htmlfile, $html);
	$cislo++;
}

foreach($rubriky as $htmlfile => $rubrika){

	uasort($rubrika['clenove'], 'sort_by_jmeno');

	$smarty->assign('title', $rubrika['jmeno']);
	$smarty->assign('rubrika', $rubrika);
	$html = $smarty->fetch('hlavicka.tpl');
	$html .= $smarty->fetch('rubrika.tpl');
	$html .= $smarty->fetch('paticka.tpl');
	file_put_contents(WWW."/$htmlfile.html", $html);
}

$smarty->assign('lat', $lat);

$smarty->assign('title', 'Latinská jména');
$smarty->assign('ptaci', $birds);
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('lat.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/lat.html', $html);

$smarty->assign('title', 'Hlasy ptáků');
$smarty->assign('ptaci', $birds);
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('index.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/index.html', $html);

$smarty->assign('title', 'Druhy');
$smarty->assign('rubriky', $rubriky);
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('rubriky.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/rubriky.html', $html);

$smarty->assign('title', 'Hlasy ptáků');
$smarty->assign('VERSION', $VERSION);
$smarty->assign('pocet', count($birds));
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('about.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/about.html', $html);

copy('templates/ptaci.css', WWW.'/ptaci.css');
copy('templates/roboto-regular.ttf', WWW.'/roboto-regular.ttf');
copy('img/ptak512.png', WWW.'/ptak512.png');

copyToDir('templates/*.js', WWW);
copyToDir('templates/*.svg', WWW);
copyToDir(TMP.'/*.jpeg', WWW);
copyToDir(TMP.'/*.mp3', WWW);
