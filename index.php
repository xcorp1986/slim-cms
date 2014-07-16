<?php
require 'vendor/autoload.php';
require 'public/lib/functions.php';
require 'public/lib/config.php';
date_default_timezone_set("Europe/Amsterdam");
// Setup custom Twig view
$twigView = new \Slim\Extras\Views\Twig();
$app = new \Slim\Slim(array(
	'debug' => true,
	'view' => $twigView,
	'templates.path' => 'views/',
	));
$app->hook('slim.before', function () use ($app) {
	$baseUrl = getBaseUrl();
	$dbh = getDb();
	$url = $_SERVER['REQUEST_URI'];
	$path = explode('/',$url);
	//$path = explode('/', mysql_real_escape_string($_SERVER['REQUEST_URI']));
	//var_dump($path);
	// settings
	$sth = $dbh->prepare('SELECT * from settings');
	$sth->execute();
	$stt = $sth->fetch();


	$usr_nam = isset($_SESSION['$usr_nam']) ? strip_tags($_SESSION['$usr_nam']) : "";
	$app->view()->appendData(array('baseURL' => $baseUrl, 'url'=>$url, 'path'=> $path, 'stt'=>$stt, 'usr_nam'=>$usr_nam));
});
session_start();
// --- front-end routing --- //
$app->get('/', function () use ($app) {
	echo "Slim Front-end";
});

// --- Back-end routing---//
// check if user is logged in.
$checkUser = function() use ($app) {
	$baseUrl = getBaseUrl();
	if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) $app->redirect($baseUrl.'/cms/login');
};
// user log in
$app->post('/cms/login-process', function() use ($app)
{
	$usr = $_POST['usr'];
	$psw = md5($_POST['psw']);
	$baseUrl = getBaseUrl();
	$dbh = getDb();
	$sth = $dbh->prepare('SELECT * from users WHERE nam=? AND psw=?');
	$sth->execute(array($usr,$psw));
	$rows = $sth->fetchAll();
	global $id_res;
	foreach($rows as $row)	{
		$id_res = $row["id"];
		$usr_res = $row["nam"];
		$psw_res = $row["psw"];
	}
	if($usr_res==$usr && $psw_res==$psw)
	{
		$_SESSION['logged_in'] = true;
		$_SESSION['$usr_id'] = $id_res;
		$_SESSION['$usr_nam'] = $usr_res;
		$app->redirect($baseUrl.'/cms');
	} else
	{
		echo "wrong usr: ".$usr." wrong psw: ".$psw;
		$app->redirect($baseUrl.'/cms/login/error');
	}
});
// user log out
$app->get('/cms/logout', function() use($app) {
	session_destroy();
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms');
});
// user is redirected here if not logged in
$app->get('/cms/login', function() use($app) {
	$app->render('cms/login.html', array('loggedout' => "true"));
});
// view index
// check if user is logged in using middleware
$app->get('/cms', $checkUser, function () use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from articles WHERE typ='nws' ORDER BY dat DESC");
	$sth->execute();
	$nws = $sth->fetchAll();
	$sth = $dbh->prepare("SELECT * from articles WHERE typ='pag' ORDER BY dat DESC");
	$sth->execute();
	$pag = $sth->fetchAll();
	$app->render('cms/cnt.html', array('nws' => $nws, 'pag'=>$pag ));
})->name('cms');
/**
 * Clients list view
 */
$app->get('/cms/articles', $checkUser, function () use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from site_art ORDER BY id DESC");
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->execute();
	$fct = $sth->fetchAll();
	$typ = "clt";
	$app->render('cms/articles.html', array('fct' => $fct, 'typ'=>$typ));
});

/**
 *  view Settings
 */
$app->get('/cms/settings', $checkUser, function () use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from settings ORDER BY id ASC");
	$sth->execute();
	$rows = $sth->fetch(PDO::FETCH_ASSOC);
	$app->render('cms/settings.html', array('stt' => $rows ));
});
// view profile
$app->get('/cms/profile(/:notf)', $checkUser, function ($notf=NULL) use ($app)
{
	if
		($notf=="changed")
	{
		$notf = "Het wachtwoord is gewijzigd";
	} else
	{
		$notf = "";
	}
	$dbh = getDb();
	$sth = $dbh->prepare('SELECT * from users WHERE id=?');
	$sth->execute(array($_SESSION['$usr_id']));
	$rows = $sth->fetchAll();
	foreach
		($rows as $row)
	{
		$usr_res = $row["nam"];
		$psw_res = $row["psw"];
		$usr[] = array('nam'=>$usr_res, 'psw'=>$psw_res);
	}

	$app->render('cms/profile.html', array('usr'=>$usr, 'notf'=>$notf ));
});
// change password
$app->post('/cms/psw', $checkUser, function () use ($app)
{
	$usr = strip_tags($_POST['usr']);
	$psw = md5(strip_tags($_POST['psw']));
	echo $usr.$psw;
	$dbh = getDb();
	$sth = $dbh->prepare("UPDATE users SET psw=? WHERE nam=?");
	$sth->execute(array($psw,$usr));
	$baseUrl = getBaseUrl();
	$rdr = $baseUrl.'/cms/profile/changed';
	$app->redirect($rdr);
});
// view stats
$app->get('/cms/stats', $checkUser, function () use ($app) {
	$app->render('cms/stats.html');
})->name('cms');

// get images (for page)
$app->get('/cms/:typ/:id/imgs', $checkUser, function ($typ,$id) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('SELECT * from articles WHERE id=? ORDER BY seq DESC');
	$sth->execute(array($id));
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	foreach($rows as $row) {
		$id = $row["id"];
		$img = imgs($id,$typ);
		$img = array('img'=>$img);
		if($data[] = array('txt'=>$row, 'img'=>$img));
	}
	$app->render('cms/imgs.html', array('cnt' => $data, 'typ'=>$typ ));
});
// upload images
$app->post('/cms/upload', function () use ($app) {
	$allowed = array('png', 'jpg', 'gif','zip');
	if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
		$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
		if(!in_array(strtolower($extension), $allowed)){
			echo '{"status":"error"}';
			exit;
		}
		$pid = $_POST['pid'];
		$fnm = $_FILES['upl']['name'];
		$path = "content/i_".anum($pid)."/";
		if (!is_dir($path."/")){
			mkdir("content/"."i_".anum($pid), 0755);
		}
		$dbh = getDb();
		$sth = $dbh->prepare('SELECT MAX(seq) as mSeq FROM images WHERE pid=?');
		$sth->execute(array($pid));
		$test = $sth->fetch(PDO::FETCH_ASSOC);
		$seq = $test['mSeq']+1;
		$sth = $dbh->prepare('INSERT INTO images SET pid=?, pub=1, typ="cnt", fnm=?, seq=?');
		$sth->execute(array($pid,$fnm,$seq));
		move_uploaded_file($_FILES["upl"]["tmp_name"],$path . $fnm);
		$thu = thu($path, $fnm);
		echo '{"status":"success"}';
		exit;
	}
	echo '{"status":"error"}';
	exit;
});
// delete images
$app->get('/cms/:typ/:id/:utt/img/delete/:fnm', $checkUser, function ($typ,$id,$utt="",$fnm) use ($app) {
	$pid = $id;
	$dbh = getDb();
	$sth = $dbh->prepare('DELETE from images WHERE fnm=?');
	$sth->execute(array($fnm));
	$path = "../content/i_".anum($pid)."/";
	if ($fnm && file_exists("$path/$fnm")){
		while (!unlink("$path/$fnm"));
	}
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});
// sort images
$app->get('/cms/:utt/img/sort/:order+', $checkUser, function ($utt,$order) use ($app) {
	$order = array_shift( $order );
  echo $order; // 'Something'.
  $order =  explode(",", $order);
  echo "order:".$order."<br>";
	//$order = explode("," , $order);
  var_dump($order);
  foreach ($order as $position => $item) {
  	echo "<br>position: ".$position;
  	echo "<br>item: ".$item."<br>";
  	$dbh = getDb();
  	$sth = $dbh->prepare("UPDATE images SET seq=? WHERE id=?");
  	$sth->execute(array($position,$item));
  	print_r($sth->errorInfo());
  }
	//print_r ($sth);
	//$app->redirect('/v7/public/cms/'.$utt);
});
// delete page thumb
$app->get('/cms/:utt/:id/img/delete/', $checkUser, function ($utt,$id) use ($app) {
	$pid = $id;
	$fnm = "thumb.jpg";
	$dbh = getDb();
	$sth = $dbh->prepare('DELETE from images WHERE fnm=?');
	$sth->execute(array($fnm));
	$path = "../content/i_".anum($pid)."/";
	if ($fnm && file_exists("$path/$fnm")){
		while (!unlink("$path/$fnm"));
	}
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$utt);
});
// get thumb (for page)
$app->get('/cms/:utt/thumb/', $checkUser, function ($utt) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('SELECT * from articles WHERE utt=? ORDER BY dat DESC LIMIT 1');
	$sth->execute(array($utt));
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	foreach($rows as $row) {
		$id = $row["id"];
		$img = cms_getthumb($id);
		$img = array('img'=>$img);
		$data[] = array('txt'=>$row, 'img'=>$img, 'thumbs'=>$thumbs);
	}
	$app->render('cms/imgs.html', array('cnt' => $data));
	//$app->redirect('/v7/public/cms/'.$utt);
});

// delete article
$app->get('/cms/:tb/:id/delete', $checkUser, function ($tb,$id) use ($app) {
	echo "delete ".$tb.$id;
	$dbh = getDb();
	$sth = $dbh->prepare("DELETE from $tb WHERE id=?");
	$sth->execute(array($id));
	$baseUrl = getBaseUrl();
	if($tb=="site_fct") {
		echo "rdr_fct";
		$rdr = $baseUrl.'/cms/invoices';
	} elseif($tb=="site_clt") {
		echo "rdr_clt";
		$rdr = $baseUrl.'/cms/clients';
	} else {
		$rdr = $baseUrl.'/cms';
	}
	$app->redirect($rdr);
});


// create new article ( + redirect to 'view article')
$app->get('/cms/new/:typ', $checkUser, function ($typ) use ($app) {

    $tb = getUrlTyp($typ);
    $dat = date("Y-m-d");
    $dbh = getDb();
    $sth = $dbh->prepare("SELECT max(id) as id FROM $tb ORDER by id asc");
    $sth->execute();
    $last_id = $sth->fetch();
    $id = $last_id[0]+1;
    $utt = "...";
    $sth = $dbh->prepare("INSERT INTO $tb SET id=?, pid=?, utt=?, dat=?, typ=?, pub=1");
    $sth->execute(array($id,$id,$utt,$dat,$typ));
    //print_r($sth->errorInfo());
    $rdr = "/cms/".$tb."/".$id;
    $app->redirect($rdr);
})->name('cms');



// view article
$app->get('/cms/:typ/:id(/:utt)', $checkUser, function ($typ,$id,$utt="") use ($app) {
	$dbh = getDb();
	$tb = getUrlTyp($typ);
	$sth = $dbh->prepare("SELECT * from articles WHERE id=? ORDER BY dat DESC LIMIT 1");
	$sth->execute(array($id));
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$data = "";
	foreach($rows as $row){
		$data[] = array('txt'=>$row,'tb'=>$tb);
	}
	$app->render('cms/sin.html', array('cnt' => $data));
})->name('cms');


function add_tag($tit) {
	$utt = utt($tit);
	$dbh = getDb();
	$sth = $dbh->prepare("INSERT INTO site_tag SET tit=?, utt=?");
	$sth->execute(array($tit,$utt));
	$tit = trim($tit);
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * FROM site_tag WHERE tit=?");
	$sth->execute(array($tit));
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	foreach($rows as $row) {
		$id = $row["id"];
		return $id;
	}
}
// save (or create) page
$app->post('/cms/save', $checkUser, function () use ($app) {
	// general
	$tb = strip_tags($_POST['tb']);
	$id = isset($_POST['id']) ? strip_tags($_POST['id']) : "";
	$dat = isset($_POST['dat']) ? strip_tags($_POST['dat']) : "";
	$tit = isset($_POST['tit']) ? strip_tags($_POST['tit']) : "";
	$utt = isset($_POST['utt']) ? strip_tags($_POST['utt']) : "";
	$txt = isset($_POST['txt']) ? stripslashes($_POST['txt']) : "";
	$tag = isset($_POST['tag']) ? stripslashes($_POST['tag']) : "";
	$typ = isset($_POST['typ']) ? stripslashes($_POST['typ']) : "";
	$ytb = isset($_POST['ytb']) ? stripslashes($_POST['ytb']) : "";

	// settings
	$adr = isset($_POST['adr']) ? stripslashes($_POST['adr']) : "";
	$ctt = isset($_POST['ctt']) ? stripslashes($_POST['ctt']) : "";
	$soc = isset($_POST['soc']) ? stripslashes($_POST['soc']) : "";

	// make url title
	if(!$utt || $utt=="...") {
		$utt = utt($tit);
	}

	// change date format
	$dat = date('Y-m-d',strtotime($dat));

	if ($tb=="settings"){
		echo "update settings";
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE settings SET tit=?, txt=?, adr=?, ctt=?, soc=? WHERE id=1");
		$sth->execute(array($tit,$txt,$adr,$ctt,$soc));
		print_r($sth->errorInfo());
	}
	elseif ($id){
		// update content
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE articles SET dat=?, tit=?, utt=?, txt=?, typ=?, tag=?, ytb=? WHERE id=?");
		$sth->execute(array($dat,$tit,$utt,$txt,$typ,$tag,$ytb,$id));
		print_r($sth->errorInfo());
	}
	$baseUrl = getBaseUrl();

	echo "tb: ".$tb;
	if($tb=="settings") {
		$rdr = $baseUrl.'/cms/settings';
	} elseif($tb=="site_fct") {
		$rdr = $baseUrl.'/cms/invoices/'.$id.'/'.$utt;
	}elseif($tb=="site_csp") {
		$rdr = $baseUrl.'/cms/correspondence/'.$id.'/'.$utt;
	} elseif($tb=="site_clt") {
		$rdr = $baseUrl.'/cms/clients/'.$id.'/'.$utt;
	} else {
		$rdr = $baseUrl.'/cms/articles/'.$id.'/'.$utt;
	}
	$app->redirect($rdr);
});

// set article to concept
$app->get('/cms/:typ/:id/:utt/concept', $checkUser, function ($typ,$id,$utt) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('UPDATE articles SET pub=0 WHERE id=?');
	$sth->execute(array($id));
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});
// set article to Public
$app->get('/cms/:typ/:id/:utt/public', $checkUser, function ($typ,$id,$utt) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('UPDATE articles SET pub=1 WHERE id=?');
	$sth->execute(array($id));
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});
// homepage
$app->get('/cms/:utt/hp/:hp', $checkUser, function ($utt,$hp) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('UPDATE articles SET hp=? WHERE utt=?');
	$sth->execute(array($hp,$utt));
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});
// --- RUN --- //
$app->run();
