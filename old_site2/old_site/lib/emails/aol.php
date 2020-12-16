<?php
#Copyright 2006 Svetlozar Petrov
#All Rights Reserved
#svetlozar@svetlozar.net
#http://svetlozar.net

#Script to import the names and emails from aol contact list

#Globals Section, $location and $cookiearr should be used in any script that uses
#                                     get_contacts function
$location = "";
$cookiearr = array();

#function get_contacts, accepts as arguments $login (the username) and $password
#returns array of: array of the names and array of the emails if login successful
#otherwise returns 1 if login is invalid and 2 if username or password was not specified
function get_contacts($login, $passwd)
{
  global $location;
  global $cookiearr;
  global $ch;
  
  $login = explode("@", $login);
  $login = $login[0];

  #check if username and password was given:
	if ((isset($login) && trim($login)=="") || (isset($passwd) && trim($passwd)==""))
	{
	  #return error code if they weren't
		return 2;
	}

	#initialize the curl session
	$ch = curl_init();
	
  #get the login form:

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_REFERER, "");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'read_header');
	
  curl_setopt($ch, CURLOPT_URL, "https://my.screenname.aol.com/_cqr/login/login.psp?mcState=initialized&seamless=novl&sitedomain=sns.webmail.aol.com&lang=en&locale=us&authLev=2&siteState=ver%3a2%7cac%3aWS%7cat%3aSNS%7cld%3awebmail.aol.com%7cuv%3aAOL%7clc%3aen-us");
	$html = curl_exec($ch);

  #parse the login form:
	preg_match('/<form name="AOLLoginForm".*?action="([^"]*).*?<\/form>/si', $html, $matches);
	#$opturl = "https://my.screenname.aol.com" .$matches[1];
	$opturl = "https://my.screenname.aol.com/_cqr/login/login.psp";
	
	#get the hidden fields:
	$hiddens = array();
	preg_match_all('/<input type="hidden" name="([^"]*)" value="([^"]*)".*?>/si', $matches[0], $hiddens);
	$hiddennames = $hiddens[1];
	$hiddenvalues = $hiddens[2];
	
	
	$hcount = count($hiddennames);
	$params = "";
	for($i=0; $i<$hcount; $i++)
	{
		$params .= $hiddennames[$i] . "=" . urlencode($hiddenvalues[$i]) . "&";
	}
	
	
  $login = urlencode($login);
	$passwd = urlencode($passwd);
	
  #attempt login:
	curl_setopt($ch, CURLOPT_URL, $opturl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params . "loginId=$login&password=$passwd");
	$html = curl_exec($ch);
	


  #check if login successful:
	if(!preg_match("/'loginForm', 'false', '([^']*)'/si", $html, $matches))
  {
    #return error if it's not
    return 1;
  }
  
  
  
	$opturl = $matches[1];
	curl_close ($ch);
	$ch = curl_init();
  foreach ($cookiearr as $key=>$value)
  {
    $cookie .= "$key=$value; ";
  }
  curl_setopt($ch, CURLOPT_COOKIE, $cookie);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_REFERER, $location);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADERFUNCTION, 'read_header');
	curl_setopt($ch, CURLOPT_URL, $opturl);
	$html = curl_exec($ch);

  if (preg_match('/gTargetHost = "([^"]*)".*?gSuccessPath = "([^"]*)"/si', $html, $matches) || preg_match('/gPreferredHost = "([^"]*)".*?gSuccessPath = "([^"]*)"/si', $html, $matches))
  {
    $opturl = $matches[1];
    $opturl .= $matches[2];
    $opturl = "http://" . $opturl;
  }
  else
  {
    if(preg_match("/'loginForm', 'false', '([^']*)'/si", $html, $matches))
    {
    	$opturl = $matches[1];
    	curl_setopt($ch, CURLOPT_URL, $opturl);
    	$html = curl_exec($ch);
      $opturl = $location;
  	}
  }
  

  $opturl = explode("/", $opturl);
  $opturl[count($opturl)-1]="AB";
  $opturl = implode("/", $opturl);

  preg_match('/\&uid:([^\&]*)\&/si', $cookiearr['Auth'], $matches);
  $usr = $matches[1];

  #get the address book:
	$opturl .= "/addresslist-print.aspx?command=all&undefined&sort=LastFirstNick&sortDir=Ascending&nameFormat=FirstLastNick&version=$cookiearr[Version]&user=$usr";
  
  curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_URL, $opturl);
	$html = curl_exec($ch);
	curl_close ($ch);


  #parse the emails and names:
	preg_match_all('/<span class="fullName">(.*?)<\/span>(.*?)<hr class="contactSeparator">/si', $html, $matches);
	$names = $matches[1];
	$emails = array_map("parse_emails", $matches[2]);


  #return the result:
  return array($names, $emails);
}

#parse_emails needs to be included to be able to get the emails
function parse_emails($str)
{
  $matches = array();
	preg_match('/<span>Email 1:<\/span> <span>([^<]*)<\/span>/si', $str, $matches) || preg_match('/<span>Primary Email:<\/span> <span>([^<]*)<\/span>/si', $str, $matches);
  return $matches[1];
}


#read_header is essential as it processes all cookies and keeps track of the current location url
#leave unchanged, include it with get_contacts
function read_header($ch, $string)
{
    global $location;
    global $cookiearr;
    global $ch;
    

    $length = strlen($string);
    if(!strncmp($string, "Location:", 9))
    {
      $location = trim(substr($string, 9, -1));
    }
    if(!strncmp($string, "Set-Cookie:", 11))
    {
      $cookiestr = trim(substr($string, 11, -1));
      $cookie = explode(';', $cookiestr);
      $cookie = explode('=', $cookie[0]);
      $cookiename = trim(array_shift($cookie)); 
      $cookiearr[$cookiename] = trim(implode('=', $cookie));
    }
    $cookie = "";
    if(trim($string) == "") 
    {
      foreach ($cookiearr as $key=>$value)
      {
        $cookie .= "$key=$value; ";
      }
      curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }

    return $length;
}

?>

