<?php

/**
 * Url title
 * @param  string $str
 * @return string
 */
function utt($str) {
    $url = str_replace("'", '', $str);
    $url = str_replace('%20', ' ', $url);
   	$url = str_replace(' ', '-', $url);
    $url = trim($url, "-");
    $url = iconv("Windows-1252", "us-ascii//TRANSLIT", $url);//
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}

/**
 * Alphabetical number
 * @param  int $n
 * @return int
 */
function anum($n){
	return (($n<100)? (($n<10)?"00$n":"0$n"): $n);
}

/**
 * Shorthand typ to full typ for url
 * @param  [type] $typ [description]
 * @return [type]      [description]
 */
function getUrlTyp($typ) {
	switch($typ){
		case "articles":
		return 'articles';
		break;
		case "nws":
		return 'articles';
		break;
		case "cnt":
		return 'articles';
		break;
		case "kls":
		return 'articles';
		break;
		case "mre":
		return 'articles';
		break;
		case "shw":
		return 'articles';
		break;
		case "products":
		return 'products';
		break;
		case "categories":
		return 'categories';
		break;
		case "discounts":
		return 'discounts';
		break;
	}
}

/**
 * Thumbnails
 * @param  int $pid [description]
 * @param  [type] $typ [description]
 * @return [type]      [description]
 */
function thumbs($pid,$typ) {
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT typ, fnm, id from shop_img WHERE pid=? AND typ=? ORDER BY seq ASC");
	$sth->execute(array($pid,$typ));
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	foreach($rows as $row) {
		$typ = $row["typ"];
		$fnm = $row["fnm"];
		$fnm = preg_replace('/(.*)(\.[\w\d]{3})/', '$1_thu$2', $fnm);
		$id = $row["id"];
		$imgs[] = array('img'=>$img = "/content/cnt/i_".anum($pid)."/$fnm",'fnm'=>$fnm,'id'=>$id);
	}
	//var_dump($imgs);
	return $imgs;
}

<<<<<<< HEAD
=======
/**
 * Images
 * @param  [type] $pid [description]
 * @param  string $typ [description]
 * @return [type]      [description]
 */
>>>>>>> FETCH_HEAD
function imgs($pid,$tb="cnt") {
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT tb, fnm, id from images WHERE pid=? AND tb=? ORDER BY seq ASC");
	$sth->execute(array($pid,$tb));
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$baseUrl = getBaseUrl();
	$imgs = "";
	foreach($rows as $row) {
		$tb = $row["tb"];
		$fnm = $row["fnm"];
		$id = $row["id"];
<<<<<<< HEAD
		$imgs[] = array('path'=>$baseUrl."/content/".$tb."/i_".a_num($pid)."/$fnm",'fnm'=>$fnm,'id'=>$id);
=======
		$imgs[] = array('path'=>$baseUrl."/content/".$tb."/i_".anum($pid)."/$fnm",'fnm'=>$fnm,'id'=>$id);
>>>>>>> FETCH_HEAD
	}
	return $imgs;
}
if(isset($_GET['item'])) {
	foreach ($_GET['item'] as $position => $item)
	{
	    $sql[] = "UPDATE `table` SET `position` = $position WHERE `id` = $item";
	}
}

function add_nm($fnm, $add){
	$pp = pathinfo($fnm);
	return $pp['filename'].$add.".".$pp['extension'];
}

/**
 * Create thumbnail
 * @param  [type]  $path  [description]
 * @param  [type]  $fn    [description]
 * @param  integer $nw    [description]
 * @param  string  $props [description]
 * @param  string  $sfx   [description]
 * @return [type]         [description]
 */
function thu($path, $fn, $nw=900, $props="", $sfx="_thu"){
	$th_n = add_nm($fn, $sfx);
		if (!file_exists("$path/$fn")){
			return "Image gone";
		}
		$pp = pathinfo($fn);
		$xt = $pp['extension'];
		if ($xt == "jpg" || $xt == "gif" || $xt == "jpeg" || $xt == "png" || $xt == "ping"){
			list($w, $h, $value, $params) = getimagesize("$path/$fn");
			if ($w > 2400 || $h > 2400){
				return "Image is too big to create a thumbnail";
			}
			if($w>$nw){
				if ($w>$h){
					$prc = ($nw * 100) / $w;
					$nh = ($h * $prc) / 100;
				}else{
					$nh = $nw;
					$prc = ($nh * 100) / $h;
					$nw = ($w * $prc) / 100;
				}
			}else{
				$nw = $w;
				$nh = $h;
			}
			$newImage = imagecreatetruecolor($nw, $nh);
			switch($xt){
				case "jpg":
				case "jpeg":
					$cpyImage = imagecreatefromjpeg("$path/$fn");
					break;
				case "gif":
					$cpyImage = imagecreatefromgif("$path/$fn");
					break;
				case "png":
				case "ping":
					$cpyImage = imagecreatefrompng("$path/$fn");
					break;
			}
			imagecopyresampled($newImage, $cpyImage, 0, 0, 0, 0, $nw, $nh, $w, $h);
			imagepng($newImage,"$path/$th_n",0,PNG_NO_FILTER);
			chmod ("$path/$th_n", 0755);
			imagedestroy($newImage);
		}
}
