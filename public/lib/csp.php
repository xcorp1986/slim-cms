<?php
//define('FPDF_FONTPATH','./font/');
require '../../vendor/autoload.php';
require 'config.php';
require 'functions.php';
//require 'fpdf.php';
require('html2pdf.php');
date_default_timezone_set("Europe/Amsterdam");
$id = $_GET['id'];
$dbh = getDb();
$sth = $dbh->prepare("SELECT * from site_csp WHERE id=?");
$sth->execute(array($id));
$rows = $sth->fetchAll();
foreach($rows as $row) {
	$iid = $row["iid"];
	$id = $row["id"];
	$cid = $row["cid"];
	$tit = $row["tit"];
	$txt = $row["txt"];
	$dat = (string) $row["dat"];
	//$dat = $date_format($dat,'d m Y');
	$dat = date_create($dat);
	$dat =  date_format($dat, 'd m Y');
}
$sth = $dbh->prepare("SELECT * from site_clt WHERE id=?");
$sth->execute(array($cid));
$rows = $sth->fetchAll();
foreach($rows as $row) {
	$nam = $row['tit'];
	$str = $row['str'];
	$psc = $row['psc'];
	$pls = $row['pls'];
	$lnd = $row['lnd'];
}
$pdf = new PDF_HTML();
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
define('EURO',chr(128));
setlocale(LC_MONETARY, 'nl_NL.UTF-8');
$pdf->AddFont('Inconsolata', '', 'inconsolata.php');
$pdf->AddFont('Music-Studio', '', 'musicstudio-regular.php');
$pdf->AddFont('Music-Studio-Med', '', 'musicstudio-medium.php');
$pdf->AddFont('music-studio B', '', 'musicstudio-medium.php');
$pdf->SetTextColor(0);
$pdf->SetXY(210, 0);
$image1 = "../img/musicstudio-logo-1391714086599.png";
$pdf->Image($image1, 65, -5, 80);
$pdf->SetXY(10,40);
$pdf->SetFont('Music-Studio-Med','',18);
$pdf->Write( 40, $tit );
$pdf->Ln( 12 );
// $pdf->SetX(10);
// $pdf->SetFont('Music-Studio-Med','',11);
// $pdf->Write( 40, "Factuurnummer" );
// $pdf->Ln(5);
$pdf->SetFont('Music-Studio','',11);
$pdf->Write( 40, $iid );
$pdf->Ln(5);
$pdf->SetFont('Music-Studio-Med','',11);
$pdf->Write( 40, "Datum" );
$pdf->Ln(5);
$pdf->SetFont('Music-Studio','',11);
$pdf->Write( 40, $dat );
$pdf->Ln( 12 );
/**
 * Client adress
 */
$pdf->SetX(10);
$pdf->SetFont('Music-Studio','',11);
$pdf->Write( 40, iconv('utf-8', 'cp1252', $nam));
$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Write( 40, iconv('utf-8', 'cp1252', $str));
$pdf->Ln(5);
$pdf->SetX(10);
$pdf->Write( 40, iconv('utf-8', 'cp1252', $psc)." ".iconv('utf-8', 'cp1252', $pls) );
$pdf->Ln(5);
/**
 * Main text
 */
$pdf->Ln( 8 );
$pdf->SetY(120);
if(ini_get('magic_quotes_gpc')=='1')
	$txt=stripslashes($txt);
$pdf->WriteHTML($txt);
$pdf->Ln( 8 );
$dbh = getDb();
$sth = $dbh->prepare("SELECT * from site_csp WHERE pid=? ORDER BY id ASC");
$sth->execute(array($id));
$rows = $sth->fetchAll();
$pdf->Ln( 8 );
/**
 * Adress
 */
$pdf->SetFont('Music-Studio-Med','',10);
$pdf->SetY(260);
$pdf->Cell(0, $height, "Music Studio", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->SetFont('Music-Studio','',10);
$pdf->Cell(0, $height, "06 43 978 662", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Cell(0, $height, "info@music-studio.nu", 0, 0, 'C');
$pdf->Ln( 4 );
$pdf->Cell(0, $height, "www.music-studio.nu", 0, 0, 'C');
$pdf->Ln( 12);
$pdf->SetFont('Music-Studio','',8);
$pdf->Cell(0, $height, "Business Studio F en F   Lange Veemarktstraat 4   8601 ET Sneek   KVK 58509860   BTW NL853069876B01   IBAN NL76 ABNA 0476 6706 75", 0, 0, 'C');
$pdf->Ln( 4 );
/**
 * Next page
 */

$len = count($rows);
if($len > 0) { // only add page if there are specifications

$pdf->AddPage();
$subtot = 0;
$btwtot = 0;
/**
 * Specification
 */
	foreach($rows as $row) {
		$pdf->SetFont('Music-Studio','',11);
		$txt = $row["txt"];
		$prc = $row["prc"];
		$btw = $row["btw"];
		$btwprc = ($btw*$prc)/100;
		$txt = iconv('utf-8', 'cp1252', $txt); // inserted martijnbac 070314 http://stackoverflow.com/questions/6334134/fpdf-utf-8-encoding-how-to
		$pdf->SetX(10);
		$pdf->Write( 80, $txt );
		$pdf->SetX(140);
		$pdf->SetFont('Inconsolata','',12);
		$pdf->Write( 80, EURO." ". money_format('%!(#5n', $prc) );
		$pdf->SetX(170);
		$pdf->SetFont('Music-Studio','',11);
		$pdf->Write( 80, " (".$btw."% btw)" );
		$pdf->SetX(140);
		$pdf->Ln(5);
		$subtot = $subtot + $prc;
		$btwtot = $btwtot + $btwprc;
		$tot = $subtot+$btwtot;
		$pdf->SetLineWidth(0.1);
		// totals
		$pdf->Line(10, 120, 135, 120);
		$pdf->SetLineWidth(0.1);
		$pdf->Line(140, 120, 200, 120);
		$pdf->SetFont('Music-Studio','',11);
		$pdf->SetXY(10,90);
		$pdf->Write( 80, "Subtotaal" );
		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Write( 80, "Btw" );
		$pdf->Ln(5);
		$pdf->SetX(10);
		$pdf->Write( 80, "Totaal" );
		$pdf->Ln(5);
		$pdf->SetXY(140,90);
		$pdf->SetFont('Inconsolata','',12);
		$subtot = money_format('%!(#5n', $subtot);
		$pdf->Write( 80, EURO." ".$subtot );
		$pdf->Ln(5);
		$pdf->SetX(140);
		$btwtot = money_format('%!(#5n', $btwtot);
		$pdf->Write( 80, EURO." ".$btwtot );
		$pdf->Ln(5);
		$pdf->SetX(140);
		$tot = money_format('%!(#5n', $tot);
		$pdf->Write( 80, EURO." ".$tot );
		$pdf->Ln(5);
	}
	/**
	 * Conditions
	 */
	$pdf->SetFont('Music-Studio','',9);
	$pdf->SetXY(10,220);
	$msg = "Op deze offerte zijn de algemene voorwaarden van toepassing.";
	$pdf->MultiCell(60, 5, $msg, 0,'L');
	$pdf->Ln(5);

	/**
	 * Adress
	 */
	$pdf->SetFont('Music-Studio-Med','',10);
	$pdf->SetY(260);
	$pdf->Cell(0, $height, "Music Studio", 0, 0, 'C');
	$pdf->Ln( 4 );
	$pdf->SetFont('Music-Studio','',10);
	$pdf->Cell(0, $height, "06 43 978 662", 0, 0, 'C');
	$pdf->Ln( 4 );
	$pdf->Cell(0, $height, "info@music-studio.nu", 0, 0, 'C');
	$pdf->Ln( 4 );
	$pdf->Cell(0, $height, "www.music-studio.nu", 0, 0, 'C');
	$pdf->Ln( 12);
	$pdf->SetFont('Music-Studio','',8);
	$pdf->Cell(0, $height, "Business Studio F en F   Lange Veemarktstraat 4   8601 ET Sneek   KVK 58509860   BTW NL853069876B01   IBAN NL76 ABNA 0476 6706 75", 0, 0, 'C');
	$pdf->Ln( 4 );
}
/**
 * Output the pdf
 */
$pdf->Output();
