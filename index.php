<?php
require 'vendor/autoload.php';
require 'public/lib/functions.php';
require 'public/lib/config.php';
date_default_timezone_set("Europe/Amsterdam");
/**
 * App settings
 * @var [type]
 */
$app = new \Slim\Slim(array(
	'debug' => true,
	'view' => new \Slim\Views\Twig(),
	'templates.path' => 'views/',
	));
/**
 * Slim before hook
 */
$app->hook('slim.before', function () use ($app) {
	$baseUrl = getBaseUrl();
	$url = $_SERVER['REQUEST_URI'];
	$path = explode('/',$url);

	// append data to all views
	if( $url != "/cms/login-process" && $url != "/cms/installer" && $url != "/cms/installer-process-db"  ) {

		$dbh = getDb();
		$sth = $dbh->prepare('SELECT * from settings');
		$sth->execute();
		$sth->errorInfo();
		$settings = $sth->fetch(PDO::FETCH_ASSOC);

		$usr_nam = isset($_SESSION['$usr_nam']) ? strip_tags($_SESSION['$usr_nam']) : "";
		$app->view()->appendData(array('baseURL' => $baseUrl, 'url'=>$url, 'path'=> $path, 'settings'=>$settings, 'usr_nam'=>$usr_nam));
	}
});
session_start();

// --- front-end routing --- //

/**
* View home
*/
$app->get('/', function () use ($app) {
<<<<<<< HEAD
	echo "Ketner Olsen";
=======
>>>>>>> FETCH_HEAD
	$app->render('base.twig',array());
});


// --- Back-end routing---//

/**
* check if user is logged in.
*/
$checkUser = function() use ($app) {
	$baseUrl = getBaseUrl();
	if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) $app->redirect($baseUrl.'/cms/login');
};

/**
* user log in
*/
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

/**
* user log out
*/
$app->get('/cms/logout', function() use($app) {
	session_destroy();
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms');
});

/**
* user is redirected here if not logged in
*/
$app->get('/cms/login', function() use($app) {

	// check if installer has been run
	$dbh = getDb();
	$sth = $dbh->prepare('SELECT 1 FROM settings LIMIT 1;');
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

	if($rows) {
		$app->render('cms/login.html', array('loggedout' => "true"));
	} else {
		$baseUrl = getBaseUrl();
		$app->redirect($baseUrl.'/cms/installer');
	}

});


/**
* view installer
*/
$app->get('/cms/installer/', function () use ($app) {

	$app->render('cms/installer.html', array('installer' => "true"));

})->name('cms');

/**
 * procces installer
 */
$app->post('/cms/installer-process-db', function () use ($app) {

	// database setup (run .sql file)
	$hostname = $_POST['db-hostname'];
	$dbUser = $_POST['db-user'];
	$dbPassword = $_POST['db-password'];
	$database = $_POST['db-database'];

	// save database details in json file
	$fp = fopen('config.json', 'w');
	fwrite($fp, json_encode(array($hostname,$database,$dbUser,$dbPassword)));
	fclose($fp);

	$sql = file_get_contents('sql/slim-cms.sql');
	$dbh = getDb(); //$hostname,$database,$dbUser,$dbPassword // http://stackoverflow.com/questions/147821/loading-sql-files-from-within-php
	$qry = $dbh->exec($sql);


	// insert first user
	$user = $_POST['user'];
	$password = md5($_POST['password']);
	$sth = $dbh->prepare('INSERT INTO users SET id=1, nam=?, psw=?');
	print_r($sth->errorInfo());
	$sth->execute(array($user,$password));

	// insert site title/desc
	$title = $_POST['title'];
	$description = $_POST['desc'];
	$baseurl = $_POST['baseurl'];
	$sth = $dbh->prepare('INSERT INTO settings SET id=1, tit=?, txt=?, url=?');
	print_r($sth->errorInfo());
	$sth->execute(array($title,$description,$baseurl));

})->name('cms');

/**
* Clients list view
*/
$app->get('/cms(/visie)', $checkUser, function () use ($app) {

	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from visie ORDER BY id DESC");
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->execute();

	$app->render('cms/visie.html', array('tb'=>'visie', 'articles' => $rows));

});

/**
* Projecten list view
*/
$app->get('/cms/projecten', $checkUser, function () use ($app) {

	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from projecten ORDER BY id DESC");
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->execute();

	$app->render('cms/projecten.html', array('tb'=>'projecten', 'articles' => $rows));

});

/**
* Nieuws list view
*/
$app->get('/cms/nieuws', $checkUser, function () use ($app) {

	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from nieuws ORDER BY id DESC");
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->execute();

	$app->render('cms/nieuws.html', array('tb'=>'nieuws', 'articles' => $rows));

});

/**
* Projecten list view
*/
$app->get('/cms/studio', $checkUser, function () use ($app) {

	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from studio ORDER BY id DESC");
	$sth->execute();
	$rows = $sth->fetchAll(PDO::FETCH_ASSOC);
	$sth->execute();

	$app->render('cms/studio.html', array('tb'=>'studio', 'articles' => $rows));

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

/**
* view profile
*/
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
	$rows = $sth->fetch(PDO::FETCH_ASSOC);


	$app->render('cms/profile.html', array('user'=>$rows, 'notf'=>$notf ));
});

/**
* Save profile
*/
$app->post('/cms/profile/save', $checkUser, function () use ($app)
{
	$usr = strip_tags($_POST['username']);
	$psw = md5(strip_tags($_POST['password']));

	if($psw != "") {
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE users SET psw=? WHERE nam=?");
		$sth->execute(array($psw,$usr));
	}

	//redirect
	$baseUrl = getBaseUrl();
	$rdr = $baseUrl.'/cms/profile/changed';
	$app->redirect($rdr);
});

/**
* get images (for page)
*/
$app->get('/cms/:tb/:id/imgs', $checkUser, function ($tb,$id) use ($app) {
	//echo "get imgs".$id;
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT * from $tb WHERE id=? ORDER BY seq DESC");
	$sth->execute(array($id));
	$row = $sth->fetch(PDO::FETCH_ASSOC);
<<<<<<< HEAD


	$id = $row['id'];
	$utt = $row['utt'];
//echo "id: ".$id;
	$images = imgs($id,$tb);

	//var_dump($images);

=======
	$id = $row['id'];
	$utt = $row['utt'];
	$images = imgs($id,$tb);
	var_dump($images);
>>>>>>> FETCH_HEAD
	$app->render('cms/imgs.html', array("id" => $row['id'], "utt"=>$row['utt'], "tb"=>$tb, "images" => $images));
});

/**
* upload images
*/
$app->post('/cms/upload', function () use ($app) {
	$allowed = array('png', 'jpg', 'gif','zip');
	if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
		$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
		if(!in_array(strtolower($extension), $allowed)){
			echo '{"status":"error"}';
			exit;
		}
		$tb = $_POST['tb'];

		$pid = $_POST['pid'];
		$fnm = $_FILES['upl']['name'];
		$path = "content/".$tb."/i_".anum($pid)."/";
		if (!is_dir($path."/")){
			mkdir("content/".$tb."/i_".anum($pid), 0755);
		}
		$dbh = getDb();
		$sth = $dbh->prepare('SELECT MAX(seq) as mSeq FROM images WHERE pid=?');
		$sth->execute(array($pid));
		$test = $sth->fetch(PDO::FETCH_ASSOC);
		$seq = $test['mSeq']+1;
		$sth = $dbh->prepare('INSERT INTO images SET pid=?, pub=1, tb=?, fnm=?, seq=?');
		$sth->execute(array($pid,$tb,$fnm,$seq));
		move_uploaded_file($_FILES["upl"]["tmp_name"],$path . $fnm);
		$thu = thu($path, $fnm);
		echo '{"status":"success"}';
		//$baseUrl = getBaseUrl();
			//$app->redirect($baseUrl.'/cms/'.$tb.'/'.$pid.'/imgs');

		exit;
	}
	echo '{"status":"error"}';
	exit;
});

/**
* delete images
*/
$app->get('/cms/:tb/:pid/:utt/img/delete/:id', $checkUser, function ($tb,$pid,$utt,$id) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('SELECT fnm FROM images WHERE id=?');
	$sth->execute(array($id));
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	$fnm = $row['fnm'];

	var_dump($row['fnm']);
	echo $row['fnm'];
	$sth = $dbh->prepare('DELETE from images WHERE id=?');
	$sth->execute(array($id));
	$path = "../content/".$tb."/i_".anum($pid)."/";
	if ($fnm && file_exists("$path/$fnm")){
		while (!unlink("$path/$fnm"));
	}
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$tb.'/'.$pid.'/'.$utt);
});

/**
* sort images
*/
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
});

/**
* delete page thumb
*/
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

/**
* get thumb (for page)
*/
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
});

/**
* Delete article
*/
$app->get('/cms/:tb/:id/delete', $checkUser, function ($tb,$id) use ($app) {
	echo "delete ".$tb.$id;
	$dbh = getDb();
	$sth = $dbh->prepare("DELETE from $tb WHERE id=?");
	$sth->execute(array($id));
	$baseUrl = getBaseUrl();
	$rdr = $baseUrl.'/cms/'.$tb;
	$app->redirect($rdr);
});

/**
* create new article ( + redirect to 'view article')
*/
$app->get('/cms/new/:tb', $checkUser, function ($tb) use ($app) {

	$dat = date("Y-m-d");
	$dbh = getDb();
	$sth = $dbh->prepare("SELECT max(id) as id FROM $tb ORDER by id asc");
	$sth->execute();
	$last_id = $sth->fetch();
	$id = $last_id[0]+1;
	$utt = "...";
	$sth = $dbh->prepare("INSERT INTO $tb SET id=?, pid=?, utt=?, dat=?,pub=1");
	$sth->execute(array($id,$id,$utt,$dat));
	//print_r($sth->errorInfo());
	$rdr = "/cms/".$tb."/".$id;
	$app->redirect($rdr);
})->name('cms');

/**
* View visie
*/
$app->get('/cms/visie/:id(/:utt)', $checkUser, function ($id,$utt="") use ($app) {
	$dbh = getDb();
	$tb = "visie";
	$sth = $dbh->prepare("SELECT * from visie WHERE id=? ORDER BY dat DESC LIMIT 1");
	$sth->execute(array($id));
	$article = $sth->fetch(PDO::FETCH_ASSOC);

	$app->render('cms/sin.html', array('article' => $article, 'tb'=> $tb));
})->name('cms');

/**
* View project
*/
$app->get('/cms/projecten/:id(/:utt)', $checkUser, function ($id,$utt="") use ($app) {
	$dbh = getDb();
	$tb = "projecten";
	$sth = $dbh->prepare("SELECT * from projecten WHERE id=? ORDER BY dat DESC LIMIT 1");
	$sth->execute(array($id));
	$article = $sth->fetch(PDO::FETCH_ASSOC);

	$app->render('cms/project.html', array('article' => $article, 'tb'=> $tb));
})->name('cms');

/**
* View Nieuws
*/
$app->get('/cms/nieuws/:id(/:utt)', $checkUser, function ($id,$utt="") use ($app) {
	$dbh = getDb();
	$tb = "nieuws";
	$sth = $dbh->prepare("SELECT * from nieuws WHERE id=? ORDER BY dat DESC LIMIT 1");
	$sth->execute(array($id));
	$article = $sth->fetch(PDO::FETCH_ASSOC);

	$app->render('cms/nieuws-sin.html', array('article' => $article, 'tb'=> $tb));
})->name('cms');

/**
* View studio
*/
$app->get('/cms/studio/:id(/:utt)', $checkUser, function ($id,$utt="") use ($app) {
	$dbh = getDb();
	$tb = "studio";
	$sth = $dbh->prepare("SELECT * from studio WHERE id=? ORDER BY dat DESC LIMIT 1");
	$sth->execute(array($id));
	$article = $sth->fetch(PDO::FETCH_ASSOC);

	$app->render('cms/studio-sin.html', array('article' => $article, 'tb'=> $tb));
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

/**
* save article
*/
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
	$url = isset($_POST['url']) ? stripslashes($_POST['url']) : "";

	// make url title
	if(!$utt || $utt=="...") {
		$utt = utt($tit);
	}

	// change date format
	$dat = date('Y-m-d',strtotime($dat));

	// save settings
	if ($tb=="settings"){
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE settings SET tit=?, txt=?, url=?, adr=?, ctt=?, soc=? WHERE id=1");
		$sth->execute(array($tit,$txt,$url,$adr,$ctt,$soc));
		print_r($sth->errorInfo());
	}
	// save content
	elseif ($tb == "visie"){
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE visie SET dat=?, tit=?, utt=?, txt=?, typ=?, tag=?, ytb=? WHERE id=?");
		$sth->execute(array($dat,$tit,$utt,$txt,$typ,$tag,$ytb,$id));
		print_r($sth->errorInfo());
	}
	// save content
	elseif ($tb == "projecten"){
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE projecten SET dat=?, tit=?, utt=?, txt=?, typ=?, tag=?, ytb=? WHERE id=?");
		$sth->execute(array($dat,$tit,$utt,$txt,$typ,$tag,$ytb,$id));
		print_r($sth->errorInfo());
	}
	// save content
	elseif ($tb == "nieuws"){
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE nieuws SET dat=?, tit=?, utt=?, txt=?, typ=?, tag=?, ytb=? WHERE id=?");
		$sth->execute(array($dat,$tit,$utt,$txt,$typ,$tag,$ytb,$id));
		print_r($sth->errorInfo());
	}
	// save content
	elseif ($tb == "studio"){
		$dbh = getDb();
		$sth = $dbh->prepare("UPDATE studio SET dat=?, tit=?, utt=?, txt=?, typ=?, tag=?, ytb=? WHERE id=?");
		$sth->execute(array($dat,$tit,$utt,$txt,$typ,$tag,$ytb,$id));
		print_r($sth->errorInfo());
	}

	$baseUrl = getBaseUrl();

	echo "tb: ".$tb;
	if($tb=="settings") {
		$rdr = $baseUrl.'/cms/settings';
	}
	elseif($tb=="visie") {
		$rdr = $baseUrl.'/cms/visie/'.$id.'/'.$utt;
	}
	elseif($tb=="projecten") {
		$rdr = $baseUrl.'/cms/projecten/'.$id.'/'.$utt;
	}
	elseif($tb=="studio") {
		$rdr = $baseUrl.'/cms/studio/'.$id.'/'.$utt;
	}
	elseif($tb=="nieuws") {
		$rdr = $baseUrl.'/cms/nieuws/'.$id.'/'.$utt;
	}
	else {
		$rdr = $baseUrl.'/cms/articles/'.$id.'/'.$utt;
	}
	$app->redirect($rdr);
});

/**
* Set article to concept
*/
$app->get('/cms/:typ/:id/:utt/concept', $checkUser, function ($typ,$id,$utt) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('UPDATE articles SET pub=0 WHERE id=?');
	$sth->execute(array($id));
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});

/**
* Set article to public
*/
$app->get('/cms/:typ/:id/:utt/public', $checkUser, function ($typ,$id,$utt) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('UPDATE articles SET pub=1 WHERE id=?');
	$sth->execute(array($id));
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});

/**
* Show article on homepage
*/
$app->get('/cms/:utt/hp/:hp', $checkUser, function ($utt,$hp) use ($app) {
	$dbh = getDb();
	$sth = $dbh->prepare('UPDATE articles SET hp=? WHERE utt=?');
	$sth->execute(array($hp,$utt));
	$baseUrl = getBaseUrl();
	$app->redirect($baseUrl.'/cms/'.$typ.'/'.$id.'/'.$utt);
});
// --- RUN --- //
$app->run();
