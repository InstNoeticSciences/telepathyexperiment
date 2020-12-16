<?php
#Copyright 2006 Svetlozar Petrov
#All Rights Reserved
#svetlozar@svetlozar.net
#http://svetlozar.net

#Script to import the names and emails from hotmail contact list

#Globals Section, $location and $cookiearr should be used in any script that uses
#                                     get_contacts function
$location = "";
$cookiearr = array();
$chget = null;
$chpost = null;
$mspauth = "";
$mspprof = "";

#function get_contacts, accepts as arguments $login (the username) and $password
#returns array of: array of the names and array of the emails if login successful
#otherwise returns 1 if login is invalid and 2 if username or password was not specified
function get_contacts($login, $passwd)
{
  global $location;
  global $cookiearr;
  global $chget;
  global $chpost;
  global $addatmsn;
  $names = array();
  $emails = array();

  $cookiearr['CkTst']= "G" . time() . "000";

  #check if username and password was given:
	if ((isset($login) && trim($login)=="") || (isset($passwd) && trim($passwd)==""))
	{
	  #return error code if they weren't
		return 2;
	}

  #hotmail requires to add @hotmail.com when you sign in:	
	if (!eregi("@", $login))
	{
	 // Don't rely on this, there are many other domains supported by hotmail, allow users to enter full email address including domain part
	  if (isset($addatmsn) && $addatmsn)
		  $login .= "@" . "msn.com";
		else
		  $login .= "@" . "hotmail.com";
	}

	#initialize the curl session
	$chget = curl_init();
	$chpost = curl_init();
	
  #get the login form:
	curl_setopt($chget, CURLOPT_URL,"http://login.live.com/login.srf?id=2");
	curl_setopt($chget, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($chget, CURLOPT_REFERER, "");
	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
	$html = curl_exec($chget);
	
	$matches = array();
	
	#parse the hidden fields (logon form):
	preg_match_all('/<input type\="hidden"[^>]*name\="([^"]+)"[^>]*value\="([^"]*)">/', $html, $matches);
	$values = $matches[2];
	$params = "";
	
	$i=0;
	foreach ($matches[1] as $name)
	{
	  $params .= "$name=" . urlencode($values[$i]);
	  ++$i;
	  if(isset($matches[$i]))
	  {
		$params .= "&";
	  }
	}
	
	$params = trim ($params, "&");
	
	curl_setopt($chpost, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($chpost, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($chpost, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($chpost, CURLOPT_POST, 1);
	curl_setopt($chpost, CURLOPT_HEADERFUNCTION, 'read_header');

  
  #parse the login form:
	$matches = array();
	preg_match('/<form [^>]+action\="([^"]+)"[^>]*>/', $html, $matches);
	$opturl = $matches[1];
	
	
	#parse the hidden fields:
	preg_match_all('/<input type="hidden"[^>]*name\="([^"]+)"[^>]*value\="([^"]*)"[^>]*>/', $html, $matches);
	$values = $matches[2];
	$params = "";

  		
	$i=0;
	foreach ($matches[1] as $name)
	{
	  $paramsin[$name]=$values[$i];
	  ++$i;
	}

  #some form specific javascript stuff before submission, this takes care of that: 
	$sPad="IfYouAreReadingThisYouHaveTooMuchFreeTime";
	$lPad=strlen($sPad)-strlen($passwd);
	$PwPad=substr($sPad, 0,($lPad<0)?0:$lPad);
	
	$paramsin['PwdPad']=urlencode($PwPad);
	foreach ($paramsin as $key=>$value)
	{
	  $params .= "$key=" . urlencode($value) . "&";
	}
	
	if (strlen($passwd) > 16)
	{
    $passwd = substr($passwd, 0, 16);
  }
  
	curl_setopt($chpost, CURLOPT_URL, $opturl);
	curl_setopt($chpost, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($chpost, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($chpost, CURLOPT_POST, 1);
	curl_setopt($chpost, CURLOPT_POSTFIELDS, $params . "login=" . urlencode($login) . "&passwd=" . urlencode($passwd) . "&LoginOptions=2");
	$html = curl_exec($chpost);
	

  #test for valid login:
  # Before:
  #	if((preg_match('/replace[^"]*"([^"]*)"/', $html, $matches)==0) && (preg_match("/url=([^\"]*)\"/si", $html, $matches)==0 || eregi("password is incorrect", $html)))
  # Now: use cookies to verify login
  if (!isset($cookiearr['MSPAuth']) || !isset($cookiearr['MSNPPAuth']))
	{
  	return 1;
	}

  if(preg_match('/replace[^"]*"([^"]*)"/', $html, $matches)==0) 
    preg_match("/url=([^\"]*)\"/si", $html, $matches); 	

 
  #curl_setopt($chget, CURLOPT_URL, $location);
	curl_setopt($chget, CURLOPT_URL,$matches[1]);
	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
	$html = curl_exec($chget);
	

	if (eregi("hotmail.msn.com/", $location))
	{
    #process the non-live interface
      #passed the login, you need to load this page to get some more cookies to complete the login
    	curl_setopt($chget, CURLOPT_URL,"http://cb1.msn.com/hm/header.armx?lid=1033&cbpage=login&lc=1033&x=3.200.4104.0");
    	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
    	$html = curl_exec($chget);
    
      #follow the javascript redirection url:
    	curl_setopt($chget, CURLOPT_POST, 0);
    	curl_setopt($chget, CURLOPT_URL,$matches[1]);
    	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 0);
    	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
    	
    	$html = curl_exec($chget);
    	
      #get the base url and build the url for the page with contacts:
    	preg_match("/(http:\/\/[^\/]*\/cgi-bin\/).*?curmbox=([^\& ]*).*?a=([^\& ]*)/i", $location, $baseurl);
    	$url = $baseurl[1] . "AddressPicker?a=$baseurl[3]&curmbox=$baseurl[2]&Context=InsertAddress&_HMaction=Edit&qF=to";	
    	
    	curl_setopt($chget, CURLOPT_URL,$url);
    	curl_setopt($chget, CURLOPT_USERAGENT, 0);
    	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
    	
    	$html = curl_exec($chget);
    
      #parse the emails and names:
    	preg_match_all('/<option.*?value="([^"]*)"[^>]*>(.*?)\&lt;/i', $html, $emailarr);
    	
    
      #get rid of duplicates:
    	$emailsunique = array_unique($emailarr[1]);
    
      $i = 0;
    	foreach ($emailsunique as $key => $value)
    	{
        $emails[$i] = $emailarr[1][$key];
        $names[$i++] = $emailarr[2][$key];
      }
    
      #return the result:
      return array($names, $emails);
  }

  global $mspauth;
  global $mspprof;
  #override cookies for live mail
  #this is a quick solution for right now  
  
  if (!eregi("@msn", $login))
  {
  $cookiearr["MSPAuth"] = $mspauth;
  $cookiearr["MSPProf"] = $mspprof;
    
    $cookie = "";
      foreach ($cookiearr as $key=>$value)
      {
          $cookie .= "$key=$value; ";
      }
      $cookie = trim ($cookie, "; ");
      
  curl_setopt($chget, CURLOPT_COOKIE, $cookie);
  curl_setopt($chpost, CURLOPT_COOKIE, $cookie);
  }
  

  if (preg_match('/location\.replace[^"]*"([^"]*)"/', $html, $matches))
  {
   	curl_setopt($chget, CURLOPT_URL,$matches[1]);
  	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
  	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
  	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
  	$html = curl_exec($chget);
	}

	

  #new live mail ####################################################################################
  if (strpos($html, "TodayLight") > 0)
  {
    $url = $location; //$matches[1];
    $url = explode("/", $url);
    $url[sizeof($url)-1] = "options.aspx?subsection=26";
    $url = implode("/", $url);
   	curl_setopt($chget, CURLOPT_URL,$url);
  	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
  	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
  	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
  	
  	$html = curl_exec($chget);
    
    #changed: get hidden fields as well values for the submit button
   	preg_match_all('/<input [^>]*name\="([^"]+)"[^>]*value\="([^"]*)"[^>]*>/si', $html, $matches);
  	$values = $matches[2];
  	$params = "";
  
    		
  	$i=0;
  	foreach ($matches[1] as $name)
  	{
  	  $paramsin[$name]=$values[$i];
  	  ++$i;
  	}

   	preg_match_all('/<input [^>]*value\="([^"]+)"[^>]*name\="([^"]*)"[^>]*>/si', $html, $matches);
  	$values = $matches[2];
    		
  	$i=0;
  	foreach ($matches[1] as $name)
  	{
  	  $paramsin[$name]=$values[$i];
  	  ++$i;
  	}

    $paramsin['mt'] = $cookiearr['mt'];
    # the button name/value already parsed above
    
  	foreach ($paramsin as $key=>$value)
  	{
  	  $params .= "$key=" . urlencode($value) . "&";
  	}

  	curl_setopt($chpost, CURLOPT_URL, $url);
  	curl_setopt($chpost, CURLOPT_RETURNTRANSFER,1);
  	curl_setopt($chpost, CURLOPT_FOLLOWLOCATION, 1);
  	curl_setopt($chpost, CURLOPT_POST, 1);
  	curl_setopt($chpost, CURLOPT_POSTFIELDS, $params);
  	$html = curl_exec($chpost);
    
    
  	#parse the csv file:
  	$table = explode("\n", $html);
    
    $separator = ",";
    if (count($table)>0)
    {
       $rowzero = array_shift($table);
       if (count(explode(",", $rowzero)) > 10 ) #10 doesn't mean a lot here, could be compared to 1
       {
        $separator = ",";
       }
       else
       {
        $separator = ";";
       }
    }
  	
  	$maxi = count($table);
  	
  	$names = array();
  	$emails = array();
  	
    #parse emails and names:
  	for($i=0; $i<$maxi; ++$i)
  	{
  	  $table[$i]=explode($separator,$table[$i]);
     
      if (count($table[$i])>46)
      {
      	  $names[$i]=trim($table[$i][1],'"') . " " . trim($table[$i][3],'"');
      	  $emails[$i]=trim($table[$i][46],'"');
      }
  	}

    #return the result:
  	return array($names, $emails);

  }
################################################# end new live mail - old live mail below 

  $url = $matches[1];
  $url = explode("/", $url);
  $url[count($url)-1] = "ApplicationMainReach.aspx?Control=EditMessage&_ec=1&FolderID=00000000-0000-0000-0000-000000000001";//$matches[1];
  $url = implode("/", $url);

	curl_setopt($chget, CURLOPT_URL,$url);
	curl_setopt($chget, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($chget, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($chget, CURLOPT_HEADERFUNCTION, 'read_header');
	
	$html = curl_exec($chget);


	preg_match_all('/<input.*?type\="?hidden"?.*?name\="([^"]*)".*?value\="([^"]*)"/si', $html, $matches);
  $postarr[$matches[1][0]]=($matches[2][0]);
  $postarr["query"]= "Find in Mail";
  $postarr["ToContact"]= "To:";
  $postarr["fTo"]= "";
  $postarr["fCC"]= "";
  $postarr["fBcc"]= "";
  $postarr["fSubject"]= "";
  $postarr["fMessageBody"]= "";
  
  curl_setopt($chpost, CURLOPT_URL, $url);
	curl_setopt($chpost, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($chpost, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($chpost, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($chpost, CURLOPT_POST, 1);
	curl_setopt($chpost, CURLOPT_POSTFIELDS, $postarr);
	curl_setopt($chpost, CURLOPT_HEADERFUNCTION, 'read_header');
	$html = curl_exec($chpost);

	preg_match_all('/<input type="checkbox" name="contactNameEmail" value="([^;]*);([^"]+)"/si', $html, $matches);
	
	$emails = array_map("arrurldecode", $matches[2]);
  $names = array_map("arrurldecode", $matches[1]);

  return array($names, $emails);
}

function arrurldecode($val)
{
  return urldecode ($val);
}

#read_header is essential as it processes all cookies and keeps track of the current location url
#leave unchanged, include it with get_contacts
function read_header($ch, $string)
{
    global $location;
    global $cookiearr;
    global $chget;
    global $chpost;
    global $mspauth;
    global $mspprof;
    
    $length = strlen($string);
    if(!strncmp($string, "Location:", 9))
    {

      $url = trim(substr($string, 9, -1));
      if (eregi("http:", $url))
      {
        $location = $url;
      }
      else
      {
        $matches = array();
        preg_match("#(https?\:\/\/[^\/]*)\/#si", $location, $matches);
        $location = $matches[0] . $url;
      }
    }
    if(!strncmp($string, "Set-Cookie:", 11))
    {
      $cookiestr = trim(substr($string, 11, -1));
      $cookie = explode(';', $cookiestr);
      $cookie = explode('=', $cookie[0]);
      $cookiename = trim(array_shift($cookie)); 

      if ($cookiename == "MSPAuth")
      {
        $mspauth = !empty($cookiearr['MSPAuth']) ? $cookiearr['MSPAuth'] : "";
      }

      if ($cookiename == "MSPProf")
      {
        $mspprof = !empty($cookiearr['MSPProf']) ? $cookiearr['MSPProf'] : "";
      }

      if ($cookiename!=="MSPOK" || !$cookiearr['MSPOK'])
      {
        $cookiearr[$cookiename] = trim(implode('=', $cookie));
      }
      
    }
    $cookie = "";
    if(trim($string) == "") 
    {
      foreach ($cookiearr as $key=>$value)
      {
          $cookie .= "$key=$value; ";
      }
      $cookie = trim ($cookie, "; ");
      
      curl_setopt($chget, CURLOPT_COOKIE, $cookie);
      curl_setopt($chpost, CURLOPT_COOKIE, $cookie);
    }

    return $length;
}

?>
