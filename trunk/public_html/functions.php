<?php

/**
 * Chronolabs REST Checksums/Hashes Selector API
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Chronolabs Cooperative http://labs.coop
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         checksums
 * @since           1.0.2
 * @author          Simon Roberts <meshy@labs.coop>
 * @version         $Id: functions.php 1000 2013-06-07 01:20:22Z mynamesnot $
 * @subpackage		api
 * @description		Checksums/Hashes API Service REST
 * @link			https://screening.labs.coop Screening API Service Operates from this URL
 * @category		functions
 * @filesource
 */




if (!function_exists("randGetReasons")) {

	/** function randGetReasons()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @param array $data This is the data to be saved!
	 * @param string $path This is root path where the data to be saved!
	 *
	 * @return string
	 */
	function randGetReasons($number = 15, $path = '')
	{
		$results = file($path . DIRECTORY_SEPARATOR . 'results.txt');
		array_unique($results);
		foreach($results as $key => $value) {
			if (empty($value))
				unset($results[$key]);
		}
		while(count($results)>$number) {
			shuffle($results);
			unset($results[mt_rand(0, count($results) - 1)]);
		}
		$data=array();
		$i=0;
		foreach($results as $key => $value) {
			if (!empty($value)) {
				$ret = explode('|', $value);
				$data[$i]['filename'] = $ret[0];
				$data[$i]['when'] = $ret[1];
				$data[$i]['letters'] = $ret[2];
				$data[$i]['name'] = $ret[3];
				$data[$i]['email'] = $ret[4];
				$data[$i]['subject'] = $ret[5];
				$i++;
			}
		}
		return $data;
	}
}

if (!function_exists("saveArrayAsXML")) {

	/** function saveArrayAsXML()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @param array $data This is the data to be saved!
	 * @param string $path This is root path where the data to be saved!
	 *
	 * @return string
	 */
	function saveArrayAsXML($data = array(), $path = '') 
	{

		$package = array();
		$package['question'] = "Why?";
		$package['answer']['subject'] = $data['subject'];
		$package['answer']['reason'] = $data['reason'];
		$package['answer']['length'] = strlen($data['subject'])+strlen($data['reason']);
		$package['seconds']['started'] = $data['started'];
		$package['seconds']['ended'] = time();
		$package['seconds']['took'] = time()-$data['started'];
		$package['person']['name'] = $data['name'];
		$package['person']['email'] = $data['email'];
		$package['person']['age'] = $data['age'];
		$package['person']['gender'] = $data['gender'];
		$package['person']['family']['sibblings'] = $data['sibblings'];
		$package['person']['family']['children'] = $data['children'];
		$package['person']['coupling']['method'] = $data['coupling'];
		$package['person']['coupling']['last-years'] = $data['coupling-years'];
		$package['person']['locations'] = $data['locations'];
		$package['person']['place']['key'] = $data['place'];
		$package['person']['place']['name'] = $data['locations'][$data['place']]['RegionName'];
		$package['person']['place']['country'] = $data['locations'][$data['place']]['country'];
		$package['person']['place']['area'] = $data['locations'][$data['place']];
		$package['ip'] = json_decode(getURL("https://lookups.labs.coop/v1/country/".$ip."/json.api"), true);
		$package['ip']['netbios'] = gethostbyaddr($ip);
		$package['source']['netbios'] = strtolower($_SERVER['HTTP_HOST']);
		$package['source']['useragent'] = strtolower($_SERVER['HTTP_USER_AGENT']);
		$package['source']['protocol'] = (isset($_SERVER['HTTPS'])?"https":"http");
		$package['storage']['filename'] = md5(microtime(true).($ip=getIP(true))).'.xml';

		$dom = new XmlDomConstruct('1.0', 'utf-8');
		$dom->fromMixed(array('why'=>$package));
		$xml = $dom->saveXML();
		
		$subpath = date('Y') . DIRECTORY_SEPARATOR . date('M') . DIRECTORY_SEPARATOR . date('d') . DIRECTORY_SEPARATOR . date('h');
		mkdir($path . DIRECTORY_SEPARATOR . $subpath, 0777, true);
		
		$f = fopen($path . DIRECTORY_SEPARATOR . $subpath . DIRECTORY_SEPARATOR . $package['storage']['filename'], 'w');
		fwrite($f, $xml, strlen($xml));
		fclose($f);

		$results = file($path . DIRECTORY_SEPARATOR . 'results.txt');
		$results[] = $subpath . DIRECTORY_SEPARATOR . $package['storage']['filename'] . '|' . time() . '|' . $package['answer']['length'] . '|' . str_replace("|", " - ", $data['name']) . '|' . $data['email']. '|' . str_replace("|", " - ", $data['subject']);
		$gdata = implode("\n", $results);
		
		unlink($path . DIRECTORY_SEPARATOR . 'results.txt');
		$f = fopen($path . DIRECTORY_SEPARATOR . 'results.txt', 'w');   
		fwrite($f, $gdata, $gdata);
		fclose($f);		
		
		return $path . DIRECTORY_SEPARATOR . $subpath . DIRECTORY_SEPARATOR . $package['storage']['filename'];
	}
}

if (!function_exists("getURL")) {
	/**
	 *
	 * gets a URL and Data with cURL for the Profile Module
	 *
	 * @author Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @param string $url URI Path to retrieve
	 *
	 * @return string
	 */
	function getURL($url = '', $removecookie = true)
	{
		$cookies = XOOPS_UPLOAD_PATH . DIRECTORY_SEPARATOR . 'cURL_'.md5($url).'.cookie';
		try {
	
			if (function_exists('curl_init')) {
				// Intialises cURL
				$cc = curl_init($url);
		
				// Sets cURL options
				curl_setopt($cc, CURLOPT_CONNECTTIMEOUT, 120);
				curl_setopt($cc, CURLOPT_TIMEOUT, 120);
				curl_setopt($cc, CURLOPT_COOKIEJAR, $cookies);
				curl_setopt($cc, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($cc, CURLOPT_USERAGENT, XOOPS_VERSION . " :: date-creator.com - (".PHP_VERSION.")");
				curl_setopt($cc, CURLOPT_SSL_VERIFYPEER, false);
		
				// Executes and Closes cURL
				$data = curl_exec($cc);
				curl_close($cc);
			} else {
				$data = file_get_contents($url);
			}
		}
		catch (Exception $e) { trigger_error('Error with cURL: '.$e); return false; }
		if ($removecookie==true)
			unlink($cookies);
		return $data;

	}
}
 
if (!function_exists("getEmailBody")) {

	/** function getEmailBody()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @param array $data This is the data to be saved!
	 *
	 * @return string
	 */
	function getEmailBody($data = array())
	{
		return sprintf(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'email-template.txt'), $data['name'], $data['email'], $data['gender'], $data['age'], $data['locations'][$data['place']]['country'], $data['locations'][$data['place']]['RegionName'], $data['subject'], $data['reason']);
	}
}


if (!function_exists("getIP")) {

	/** function getIP()
	 *
	 * 	get the True IPv4/IPv6 address of the client using the API
	 * @author Simon Roberts (Chronolabs) simon@labs.coop
	 *
	 * @param boolean $asString Whether to return an address or network long integer
	 *
	 * @return mixed
	 */
	function getIP($asString = true){
		// Gets the proxy ip sent by the user
		$proxy_ip = '';
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_X_FORWARDED'];
		} else
		if (! empty($_SERVER['HTTP_FORWARDED_FOR'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else
		if (!empty($_SERVER['HTTP_FORWARDED'])) {
			$proxy_ip = $_SERVER['HTTP_FORWARDED'];
		} else
		if (!empty($_SERVER['HTTP_VIA'])) {
			$proxy_ip = $_SERVER['HTTP_VIA'];
		} else
		if (!empty($_SERVER['HTTP_X_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_X_COMING_FROM'];
		} else
		if (!empty($_SERVER['HTTP_COMING_FROM'])) {
			$proxy_ip = $_SERVER['HTTP_COMING_FROM'];
		}
		if (!empty($proxy_ip) && $is_ip = preg_match('/^([0-9]{1,3}.){3,3}[0-9]{1,3}/', $proxy_ip, $regs) && count($regs) > 0)  {
			$the_IP = $regs[0];
		} else {
			$the_IP = $_SERVER['REMOTE_ADDR'];
		}
			
		$the_IP = ($asString) ? $the_IP : ip2long($the_IP);
		return $the_IP;
	 
	}
}


if (!class_exists("XmlDomConstruct")) {
	/**
	 * class XmlDomConstruct
	 *
	 * 	Extends the DOMDocument to implement personal (utility) methods.
	 *
	 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
	 */
	class XmlDomConstruct extends DOMDocument {

		/**
		 * Constructs elements and texts from an array or string.
		 * The array can contain an element's name in the index part
		 * and an element's text in the value part.
		 *
		 * It can also creates an xml with the same element tagName on the same
		 * level.
		 *
		 * ex:
		 * <nodes>
		 *   <node>text</node>
		 *   <node>
		 *     <field>hello</field>
		 *     <field>world</field>
		 *   </node>
		 * </nodes>
		 *
		 * Array should then look like:
		 *
		 * Array (
		 *   "nodes" => Array (
		 *     "node" => Array (
		 *       0 => "text"
		 *       1 => Array (
		 *         "field" => Array (
		 *           0 => "hello"
		 *           1 => "world"
		 *         )
		 *       )
		 *     )
		 *   )
		 * )
		 *
		 * @param mixed $mixed An array or string.
		 *
		 * @param DOMElement[optional] $domElement Then element
		 * from where the array will be construct to.
		 *
		 * @author 		Simon Roberts (Chronolabs) simon@labs.coop
		 *
		 */
		public function fromMixed($mixed, DOMElement $domElement = null) {

			$domElement = is_null($domElement) ? $this : $domElement;

			if (is_array($mixed)) {
				foreach( $mixed as $index => $mixedElement ) {

					if ( is_int($index) ) {
						if ( $index == 0 ) {
							$node = $domElement;
						} else {
							$node = $this->createElement($domElement->tagName);
							$domElement->parentNode->appendChild($node);
						}
					}

					else {
						$node = $this->createElement($index);
						$domElement->appendChild($node);
					}

					$this->fromMixed($mixedElement, $node);

				}
			} else {
				$domElement->appendChild($this->createTextNode($mixed));
			}

		}
			
	}
}
?>
