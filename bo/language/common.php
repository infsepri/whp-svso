<?php
/**
*
* @package    Common
* @package    Language
* @copyright  2020 SEPRI
*
*/
if(!isset($_SESSION))
{
    session_start();
}

if(isSet($_GET['lang']))
{
	$lang = $_GET['lang'];

	// register the session and set the cookie
	$_SESSION['lang'] = $lang;

	setcookie('lang', $lang, time() + (3600 * 24 * 30));
}
else if(isSet($_SESSION['lang']))
{
	$lang = $_SESSION['lang'];
}
else if(isSet($_COOKIE['lang']))
{
	$lang = $_COOKIE['lang'];
}
else
{
	$lang = 'pt';
}
$lang_abbr = $lang;
switch ($lang) {


  case 'pt':
  $lang_file = 'lang.pt.php';
  break;

  default:
  $lang_file = 'lang.pt.php';

}

if(!isset($_GET['lang_to_string'])){
  if(file_exists('bo/language/'.$lang_file)){
    require 'bo/language/'.$lang_file;
  }else{
    require 'language/'.$lang_file;
  }

}else{
  
  $rl = require 'language/'.$lang_file;
  return $rl;
}

?>
