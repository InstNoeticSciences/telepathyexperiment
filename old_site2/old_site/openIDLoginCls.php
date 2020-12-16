<?php

/**
 * API Demonstration code for RPX.  Runs only on PHP 5.  Requires CURL
 * and DOM XML parsing support.
 *
 * Instantiate RPX with your API key and RPX API URL.  The public
 * interface returns DOMDocument objects to be parsed by your
 * application.  You can choose to request JSON data, which requires a
 * different parser not available on most PHP installations.
 *
 * The demonstration API throws APIException on HTTP or parsing error.
 *
 * $rpx = new RPX("1422262b85e296164f95913b9efc0d8316754b35",
 *                "https://EXAMPLE.rpxnow.com/");
 */

/*
 * NOTE: If your relying party uses more than one domain in your
 * token_url, you can use the following code to generate the URL of
 * the token URL handler in order to pass it to the auth_info call for
 * validation:
 *
 *     // Get the URL that is currently being served
 *     function current_url() {
 *         if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
 *              $proto = "https";
 *              $standard_port = '443';
 *          } else {
 *              $proto = 'http';
 *              $standard_port = '80';
 *          }
 *
 *          $authority = $_SERVER['HTTP_HOST'];
 *          if (strpos($authority, ':') === FALSE &&
 *              $_SERVER['SERVER_PORT'] != $standard_port) {
 *              $authority .= ':' . $_SERVER['SERVER_PORT'];
 *          }
 *
 *          if (isset($_SERVER['REQUEST_URI'])) {
 *              $request_uri = $_SERVER['REQUEST_URI'];
 *          } else {
 *              $request_uri = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
 *              $query = $_SERVER['QUERY_STRING'];
 *              if (isset($query)) {
 *                  $request_uri .= '?' . $query;
 *              }
 *          }
 *
 *          return $proto . '://' . $authority . $request_uri;
 *     }
 *
 * to use:
 *
 *     $response = $rpx_api->auth_info($token, current_url());
 */



class APIException extends Exception {}

class RPX {
    var $api_key = '';
    var $base_url = '';
    var $format = "xml";
    var $response_body = "";

    function RPX($api_key, $base_url) {
        while ($base_url[strlen($base_url) - 1] == "/") {
            $base_url = substr($base_url, 0, strlen($base_url) - 1);
        }

        $this->api_key = $api_key;
        $this->base_url = $base_url;
    }

    /*
     * Performs the 'auth_info' API call to retrieve information about
     * an OpenID authentication response.  You'll need to inspect the
     * resulting DOMDocument to get information about the response.
     * See the API documentation for details.
     *
     * https://rpxnow.com/docs
     */
    public function auth_info($token, $current_url=null) {
        $args = array("token" => $token);
        if ($current_url !== null) {
            $args['currentUrl'] = $current_url;
        }
        return $this->apiCall("auth_info", $args);
    }

    /*
     * Returns an array of identifier mappings for the specified
     * primary key.
     */
    public function mappings($primary_key) {
        $doc = $this->apiCall(
             "mappings", array("primaryKey" => $primary_key));

        $identifiers = array();

        $xpath = new DOMXPath($doc);
        $nodes = $xpath->query("/rsp/identifiers/identifier");

        foreach ($nodes as $identifier_node) {
          $identifiers[] = $identifier_node->textContent;
        }

        return $identifiers;
    }

    /*
     * Returns a hash of primary key -> array(identifier) of
     * all identifier mappings for this application.
     */
    public function all_mappings() {
        $doc = $this->apiCall("all_mappings", array());

        $mappings = array();

        $xpath = new DOMXPath($doc);
        $mapping_nodes = $xpath->query("/rsp/mappings/mapping");

        foreach ($mapping_nodes as $mapping_node) {
            // Get the primaryKey element
            $pk_node = $mapping_node->childNodes->item(0);

            // Get the identifier elements
            $identifier_nodes = $xpath->query("identifiers/identifier",
                                              $mapping_node);

            $mappings[$pk_node->textContent] = array();
            foreach ($identifier_nodes as $id_node) {
                $mappings[$pk_node->textContent][] = $id_node->textContent;
            }
        }

        return $mappings;
    }

    /*
     * Maps an identifier to a primary key from your application.
     * Returns null.
     */
    public function map($identifier, $primary_key) {
        $this->apiCall("map", array("primaryKey" => $primary_key,
                                    "identifier" => $identifier));
    }

    /*
     * Removes a mapping for an identifier and primary key.  Returns
     * null.
     */
    public function unmap($identifier, $primary_key) {
        $this->apiCall("unmap", array(
            "primaryKey" => $primary_key,
            "identifier" => $identifier));
    }

    /*
     * Performs an API call using the specified name and arguments
     * array.  Automatically adds your API key to the request and
     * requests an XML response.  Returns a DOMDocument or raises
     * APIException.
     */
    private function apiCall($method_name, $partial_query) {
        $partial_query["format"] = $this->format;
        $partial_query["apiKey"] = $this->api_key;

        $query_str = "";
        foreach ($partial_query as $k => $v) {
            if (strlen($query_str) > 0) {
                $query_str .= "&";
            }

            $query_str .= urlencode($k);
            $query_str .= "=";
            $query_str .= urlencode($v);
        }

        $url = $this->base_url . "/api/v2/" . $method_name;		
        $response_body = $this->_post($url, $query_str);
		
		$api_response = $this->_parseCustom($response_body);
		
		//print_r('<pre>');
		//print_r($api_response);
		//print_r($response_body);
		//print_r('<pre>');
		
		//$arrReturn['displayname']  = $displayName;
		//$arrReturn['identifier']   = $identifier;
		//$arrReturn['providername'] = $providerName;
		$status = $api_response['status'];
		
        //$api_response = $this->_parse($response_body);
        //$status = $this->_getMessageStatus($api_response);

        if ($status != 'ok') {
            throw new APIException(
              sprintf("API status was not 'ok', got '%s' instead", $status));
        }

        return $api_response;
    }

    private function _getMessageStatus($parsed_response) {
        $root = $parsed_response->childNodes->item(0);
        $node = $root->attributes->getNamedItem('stat');
        return $node->value;
    }

    private function _resetPostData() {
        $this->response_data = "";
    }

    private function _writeResponseData($curl_handle, $raw) {
        $this->response_data .= $raw;
        return strlen($raw);
    }

    private function _post($url, $post_data) {
        $this->_resetPostData();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_WRITEFUNCTION,
                    array(&$this, "_writeResponseData"));

        curl_exec($curl);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        /*if (!$code) {
            throw new APIException(
              sprintf("Error performing HTTP request: %s", curl_error($curl)));
        }*/

        $response_body = $this->response_data;
		//print_r('<pre>/');
		//print_r($response_body);
		//print_r('/</pre>');
        $this->_resetPostData();
        curl_close($curl);

        return $response_body;
    }

    private function _parse($raw) {
		echo ' == ';
		print_r($raw);
		echo ' == ';
	
        $doc = new DOMDocument;

        if (!$doc->loadXML($raw)) {
            throw new APIException("Error parsing XML response");
        }

        return $doc;
    }
	
	private function _parseCustom($raw) {		
		
		$xmlString = $raw;
		$xmlParser = xml_parser_create();
		xml_parse_into_struct($xmlParser, $xmlString, $values, $main);
		xml_parser_free($xmlParser);
		/*print_r('<pre>');
		echo "<b>Main array</b><br>";
		print_r($main);
		echo "<br>";
		echo "<b>Values array</b><br>";
		print_r($values);
		print_r('</pre>');*/
		
		$displayName = ''; 
		$identifier = ''; 
		$providerName = ''; 
		$status = '';
		
		foreach($values as $value) { 
			if( $value['tag']  == 'DISPLAYNAME' ) {
				$displayName = $value['value']; 
			}
			if( $value['tag']  == 'IDENTIFIER' ) {
				$identifier = $value['value']; 
			}
			if( $value['tag']  == 'PROVIDERNAME' ) {
				$providerName = $value['value']; 
			}
			/*if( is_array($value['tag']) ) {
				foreach($value['tag'] as $tkey => $tval) {
					if($tkey == 'STAT'){
						$status = $tval; 
					}
				}				
			}*/
			if( is_array($value['attributes']) ) {
				foreach($value['attributes'] as $tkey => $tval) {
					if($tkey == 'STAT'){
						$status = $tval; 
					}
				}				
			}
		}
		
		$arrReturn = array();
		$arrReturn['displayname']  = $displayName;
		$arrReturn['identifier']   = $identifier;
		$arrReturn['providername'] = $providerName;
		$arrReturn['status'] 	   = $status;
		
		return $arrReturn;
	}
	
}

/*
 * API methods that we can demonstrate from the command line.
 */
global $SUPPORTED_METHODS;
$SUPPORTED_METHODS = array("map", "unmap", "mappings", "all_mappings");

/*
 * Print out usage information for this demo code.
 */
function usage() {
  global $SUPPORTED_METHODS;
  global $argv;

  print sprintf(
     "Usage: %s <API key> <RPX service URL> <%s> [param ...]\n",
     $argv[0], implode("|", $SUPPORTED_METHODS));

  print "API methods:\n";
  print "  map <identifier> <primary key>\n";
  print "  unmap <identifier> <primary key>\n";
  print "  mappings <primary key>\n";
  print "  all_mappings\n";

  exit(1);
}