<?php
require '../../vendor/autoload.php';
require 'config.php';
require 'functions.php';
require 'fpdf.php';

date_default_timezone_set('Europe/Amsterdam');
$date = date("Y-m-d");


$vnm = strip_tags($_POST['vnm']);
$nam = strip_tags($_POST['nam']);
$utt= utt($vnm." ".$nam);
$mv = strip_tags($_POST['mv']);
$str = strip_tags($_POST['str']);
$hsn = strip_tags($_POST['hsn']);
$psc = strip_tags($_POST['psc']);
$pls = strip_tags($_POST['pls']);
$lnd = isset($_POST['lnd']) ? strip_tags($_POST['lnd']) : "";
$tel = strip_tags($_POST['tel']);
$eml = strip_tags($_POST['eml']);
$geb = strip_tags($_POST['geb']);
$vnm2 = strip_tags($_POST['vnm2']);
$nam2 = strip_tags($_POST['nam2']);
$mv2 = isset($_POST['mv2']) ? strip_tags($_POST['mv2']) : "";
$str2 = strip_tags($_POST['str2']);
$hsn2 = strip_tags($_POST['hsn2']);
$psc2 = strip_tags($_POST['psc2']);
$pls2 = strip_tags($_POST['pls2']);
$lnd2 = isset($_POST['lnd2']) ? strip_tags($_POST['lnd2']) : "";
$tel2 = strip_tags($_POST['tel2']);
$eml2 = strip_tags($_POST['eml2']);
$geb2 = strip_tags($_POST['geb2']);
$vnml = strip_tags($_POST['vnml']);
$naml = strip_tags($_POST['naml']);
$mvl = isset($_POST['mvl']) ? strip_tags($_POST['mvl']) : "";
$strl = strip_tags($_POST['strl']);
$hsnl = strip_tags($_POST['hsnl']);
$pscl = strip_tags($_POST['pscl']);
$plsl = strip_tags($_POST['plsl']);
$lndl = isset($_POST['lndl']) ? strip_tags($_POST['lndl']) : "";
$tell = strip_tags($_POST['tell']);
$emll = strip_tags($_POST['emll']);
$gebl = isset($_POST['gebl']) ? strip_tags($_POST['gebl']) : "";
$crs = strip_tags($_POST['crs']);
$als = strip_tags($_POST['als']);
$dls = strip_tags($_POST['dls']);
$bdr = strip_tags($_POST['bdr']);
$bdr = substr($bdr, 4);
$bzo = strip_tags($_POST['bzo']);


$dbh = getDb();
$sth = $dbh->prepare("INSERT INTO site_clt SET dat=?, vnm=?, nam=?, utt=?, mv=?, str=?, hsn=?, psc=?, pls=?, lnd=?, tel=?, eml=?, geb=?, vnml=?, naml=?, mvl=?, strl=?, hsnl=?, pscl=?, plsl=?, lndl=?, tell=?, emll=?, crs=?, als=?, dls=?, bdr=?, bzo=?");
$sth->execute(array($date,$vnm,$nam,$utt,$mv,$str,$hsn,$psc,$pls,$lnd,$tel,$eml,$geb,$vnml,$naml,$mvl,$strl,$hsnl,$pscl,$plsl,$lndl,$tell,$emll,$crs,$als,$dls,$bdr,$bzo));

	//	 print_r($sth->errorInfo());



$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAuthor('Music Studio');
$pdf->SetTitle('Inschrijven');
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
define('EURO',chr(128));
$pdf->AddFont('Music-Studio', '', 'musicstudio-regular.php');
$pdf->AddFont('Music-Studio-Med', '', 'musicstudio-medium.php');
$pdf->SetTextColor(0);
$pdf->SetXY(210, 0);
$image1 = "../img/musicstudio-logo-1391714086599.png";
$pdf->Image($image1, 65, -5, 80);
$pdf->SetFont('Music-Studio-Med','',8);
$pdf->SetXY(40,20);
$pdf->Write( 80, "Aanmeldformulier" );
$pdf->Ln( 12 );
$pdf->SetFont('Music-Studio','',8);
$pdf->SetX(40);
$pdf->Write( 80, "Naam" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Geslacht" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Adres" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Telefoon" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "E-mail" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Geboortedatum" );
$pdf->Ln( 12 );
// if($ant==2) {
// $pdf->SetX(40);
// $pdf->Write( 80, "Naam" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "Geslacht" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "Adres" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "Telefoon" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "E-mail" );
// $pdf->Ln( 4 );
// $pdf->SetX(40);
// $pdf->Write( 80, "Geboortedatum" );
// $pdf->Ln( 12 );
// }
if($naml != "") {
	$pdf->SetX(40);
	$pdf->Write( 80, "Naam" );
	$pdf->Ln( 4 );
	$pdf->SetX(40);
	$pdf->Write( 80, "Geslacht" );
	$pdf->Ln( 4 );
	$pdf->SetX(40);
	$pdf->Write( 80, "Adres" );
	$pdf->Ln( 4 );
	$pdf->SetX(40);
	$pdf->Write( 80, "" );
	$pdf->Ln( 4 );
	$pdf->SetX(40);
	$pdf->Write( 80, "" );
	$pdf->Ln( 4 );
	$pdf->SetX(40);
	$pdf->Write( 80, "Telefoon" );
	$pdf->Ln( 4 );
	$pdf->SetX(40);
	$pdf->Write( 80, "E-mail" );
	$pdf->Ln( 12 );
}
$pdf->SetX(40);
$pdf->Write( 80, "Cursus" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Aantal lessen" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Duur lessen" );
$pdf->Ln( 4 );
$pdf->SetX(40);
$pdf->Write( 80, "Totaalbedrag*" );
$pdf->Ln( 12 );
$pdf->SetX(40);
$pdf->SetXY(40,162);
$pdf->Write( 80, "Bijzonderheden" );
$pdf->Ln( 18 );
$pdf->SetXY(40,180);
$pdf->Write( 80, "Handtekening" );
$pdf->Ln(  );
$pdf->SetXY(100, 20);
$pdf->SetX(100);
$pdf->Write( 80, "" );
$pdf->Ln( 12 );
$pdf->SetX(100);
$pdf->Write( 80, $vnm." ".$nam );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $mv );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $str." ".$hsn );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $psc );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $pls );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $tel );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $eml );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $geb );
$pdf->Ln( 12 );

if($naml != "") {
	$pdf->SetX(100);
	$pdf->Write( 80, $vnml." ".$naml );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $mvl );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $strl." ".$hsnl );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $pscl );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $plsl );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $tell );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $emll );
	$pdf->Ln( 4 );
	$pdf->SetX(100);
	$pdf->Write( 80, $gebl );
	$pdf->Ln( 8 );
}
$pdf->SetX(100);
$pdf->Write( 80, $crs );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $als );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, $dls." minuten" );
$pdf->Ln( 4 );
$pdf->SetX(100);
$pdf->Write( 80, EURO." ".$bdr );
$pdf->Ln( 12 );
$pdf->SetXY(100,200);
$pdf->MultiCell(80, 4, $bzo);
$pdf->Ln( 12 );
if($bzo =="") {
	$pdf->SetLineWidth(0.1);
	$pdf->Line(101, 205, 180, 205);
}
$pdf->SetLineWidth(0.1);
$pdf->Line(101, 225, 180, 225);


// toelichting totaalbedrag
$pdf->SetXY(100,240);
$msg = "* Dit bedrag is een indicatie. Eventuele kortingen zijn nog niet meeberekend.";
$pdf->MultiCell(60, 5, $msg, 0,'L');
$pdf->Ln(5);





$height = 0;
// adres
$pdf->SetFont('Music-Studio-Med','',10);
$pdf->SetY(267);
$pdf->Cell(0, $height, "Music Studio", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->SetFont('Music-Studio','',10);
$pdf->Cell(0, $height, "Lange Veemarktstraat 3", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Cell(0, $height, "8601 ET Sneek", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Cell(0, $height, "06 43 978 662", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Cell(0, $height, "info@music-studio.nu", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Cell(0, $height, "www.music-studio.nu", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Output();

//** Send e-mail **//
//
// if 'lesgeldplichtige' exists, sent to that adress
$send_eml = isset($emll) ? $emll : $eml;
$to = $send_eml;
$cc = "info@music-studio.nu"; //info@music-studio.nu
// from
$from = "info@music-studio.nu";
$subject = "Inschrijfformulier Music Studio";
$message = "<p>Zie bijlage.</p>";
// a random hash will be necessary to send mixed content
$separator = md5(time());
// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;
// attachment name
$filename = "inschrijfformulier-".$utt."-".utt($date).".pdf";
// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output("", "S");
$attachment = chunk_split(base64_encode($pdfdoc));

// main header
$headers  = "From: ".$from.$eol;
$headers .= "Cc: ".$cc.$eol;
$headers .= "MIME-Version: 1.0".$eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

// no more headers after this, we start the body! //

$body = "--".$separator.$eol;
$body .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
//$body .= "This is a MIME encoded message.".$eol;

// message
$body .= "--".$separator.$eol;
$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
$body .= $message.$eol;

// attachment
$body .= "--".$separator.$eol;
$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
$body .= "Content-Transfer-Encoding: base64".$eol;
$body .= "Content-Disposition: attachment".$eol.$eol;
$body .= $attachment.$eol;
$body .= "--".$separator."--";

// send message
mail($to, $subject, $body, $headers);
?>
