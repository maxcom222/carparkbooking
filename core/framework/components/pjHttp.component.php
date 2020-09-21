<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
/**
 * PHP Framework
 *
 * @copyright Copyright 2016, StivaSoft, Ltd. (https://www.stivasoft.com)
 * @link      https://www.phpjabbers.com/
 * @package   framework.components
 * @version   1.5.2
 */
/**
 * HTTP class
 *
 * @package framework.components
 * @since 1.0.0
 */
class pjHttp
{
/**
 * The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
 *
 * @var integer
 * @access private
 */
	private $connectTimeout = 30;
/**
 * POST data
 *
 * @var array|string
 * @access private
 */
	private $data = NULL;
/**
 * Error code/text
 *
 * @var array
 * @access private
 */
	private $error = array();
/**
 * Request headers
 *
 * @var array
 * @access private
 */
	private $headers = array();
/**
 * Host
 *
 * @var string
 * @access private
 */
	private $host = "http://127.0.0.1:3000/api/v1";
/**
 * HTTP status code from last request
 *
 * @var string
 * @access private
 */
	private $httpCode;
/**
 * HTTP headers from last request
 *
 * @var string
 * @access private
 */
	private $httpInfo;
/**
 * HTTP request method. Accept 'GET', 'POST'
 *
 * @var string
 * @access private
 */
	private $method = 'GET';
/**
 * Password for basic authentification
 *
 * @var string
 * @access private
 */
	private $password;
/**
 * Response
 *
 * @var mixed
 * @access private
 */
	private $response = NULL;
/**
 * Response headers
 *
 * @var array
 * @access private
 */
	private $responseHeaders = array();
/**
 * Verify SSL Cert
 *
 * FALSE to stop cURL from verifying the peer's certificate. Alternate
 * certificates to verify against can be specified with the CURLOPT_CAINFO
 * option or a certificate directory can be specified with the CURLOPT_CAPATH
 * option.
 *
 * @var boolean
 * @access private
 */
	private $sslVerifyPeer = FALSE;
/**
 * The connection timeout, in seconds.
 *
 * @var integer
 * @access private
 */
	private $timeout = 30;
/**
 * URL of last API call
 *
 * @var string
 * @access private
 */
	private $url;
/**
 * Username for basic authentification
 *
 * @var string
 * @access private
 */
	private $username;
/**
 * The contents of the "User-Agent: " header to be used in a HTTP request.
 *
 * @var string
 * @access private
 */
	private $userAgent = "StivaSoft PHP Library";
  
	public function getError()
	{
		return $this->error;
	}
/**
 * Get the header info to store.
 *
 * @param mixed $ch
 * @param string $header
 * @return integer
 */
	public function pjActionGetHeader($ch, $header)
	{
		$i = strpos($header, ':');
		if (!empty($i))
		{
			$key = strtolower(substr($header, 0, $i));
			$value = trim(substr($header, $i + 2));
			$this->responseHeaders[$key] = $value;
		}
		return strlen($header);
	}
/**
 * Make a HTTP request (using cURL functions)
 *
 * @param string $url
 * @access public
 * @return self
 */
	public function curlRequest($url)
	{
		$this->httpInfo = array();
		$ch = curl_init();

	    curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerifyPeer);
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'pjActionGetHeader'));
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		if (!empty($this->username) && !empty($this->password))
		{
			curl_setopt($ch, CURLOPT_USERPWD, sprintf("%s:%s", $this->username, $this->password));
		}

		$post_fields = $this->getData();
		
		switch ($this->getMethod())
		{
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, TRUE);
				if (!empty($post_fields))
				{
					if (is_array($post_fields))
					{
						curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: multipart/form-data"));
					}
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
				}
				break;
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($post_fields))
				{
					$url = "{$url}?{$post_fields}";
				}
				break;
		}

		$headers = $this->getHeaders();
		if (!empty($headers))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
			
		curl_setopt($ch, CURLOPT_URL, $url);
		$this->response = curl_exec($ch);
		$this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->httpInfo = array_merge($this->httpInfo, curl_getinfo($ch));
		$this->url = $url;
		if (curl_errno($ch) == 28)
		{
			$this->error = array('code' => 109, 'text' => 'Timeout');
		}
		curl_close($ch);
		return $this;
	}
/**
 * Make a HTTP request (using Socket function: 'fsockopen')
 *
 * @param string $url
 * @access public
 * @return self
 */
	public function socketRequest($url)
	{
		$parts = parse_url($url);
		$port = $parts['scheme'] == 'https' ? 443 : 80;
		$fp = @fsockopen($parts['host'], $port, $errno, $errstr, $this->connectTimeout);
		if (!$fp)
		{
		    $this->error = array('text' => $errstr, 'code' => $errno);
		} else {
			$data = NULL;
			switch ($this->getMethod())
			{
				case 'GET':
					$out = "GET ".$parts['path'].(isset($parts['query']) ? "?".$parts['query'] : NULL)." HTTP/1.1\r\n";
					break;
				case 'POST';
					$out = "POST ".$parts['path'].(isset($parts['query']) ? "?".$parts['query'] : NULL)." HTTP/1.1\r\n";
					
					$data = $this->getData();
					$this->addHeader("Content-Type: application/x-www-form-urlencoded");
					$this->addHeader("Content-Length: " . strlen($data));
					break;
			}
			$out .= "Host: ".$parts['host']."\r\n";
			if (!empty($this->username) && !empty($this->password))
			{
				$this->addHeader("Authorization: Basic " . base64_encode($this->username .":". $this->password));
			}
			foreach ($this->getHeaders() as $header)
			{
				$out .= $header."\r\n";
			}
		    $out .= "Connection: Close\r\n\r\n";

		    fwrite($fp, $out);
		    if (!is_null($data))
		    {
		    	fwrite($fp, $data);
		    }
		    stream_set_timeout($fp, $this->timeout);
		    $response = '';
        	$header = "not yet";
		    while (!feof($fp))
		    {
				$line = fgets($fp, 128);
				$this->pjActionGetHeader(NULL, $line);
				
				if ($line == "\r\n" && $header == "not yet")
				{
					$header = "passed";
				}
				if ($header == "passed")
				{
					//$response .= preg_replace('/\n|\r\n/', '', $line);
					$response .= $line;
				}
				
				if (empty($this->error))
				{
					$info = stream_get_meta_data($fp);
			    	if ($info['timed_out'])
			    	{
						$this->error = array('code' => 109, 'text' => 'Timeout');
					}
				}
		    }
		    fclose($fp);
		    $this->response = $response;
		}
		$this->url = $url;
		return $this;
	}
/**
 * Make a HTTP request (using Filesystem function: 'file_get_contents')
 *
 * @param string $url
 * @access public
 * @return self
 */
	public function fileRequest($url)
	{
		$response = @file_get_contents($url);
		if (!$response)
		{
			$this->error = array('code' => 100, 'text' => 'An error occurs');
			return $this;
		}
		$this->response = $response;
		$this->url = $url;
		return $this;
	}
/**
 * Make a HTTP request (using Stream function: 'stream_get_contents')
 *
 * @param string $url
 * @access public
 * @return self
 */
	public function streamRequest($url)
	{
		$handle = @fopen($url, 'r');
		if (!$handle)
		{
			$this->error = array('code' => 100, 'text' => 'An error occurs');
			return $this;
		}
		$this->response = stream_get_contents($handle);
		$this->url = $url;
		fclose($handle);
		return $this;
	}
/**
 * Make a HTTP request
 *
 * @param string $url
 * @access public
 * @return self
 */
	public function request($url)
	{
		if (function_exists('curl_init'))
		{
			$this->curlRequest($url);
		} elseif (function_exists('file_get_contents')) {
			$this->fileRequest($url);
		} elseif (function_exists('fsockopen')) {
			$this->socketRequest($url);
		} elseif (function_exists('stream_get_contents')) {
			$this->streamRequest($url);
		}
		return $this;
	}
/**
 * Set username
 *
 * @param string $username
 * @access public
 * @return self
 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
/**
 * Set password
 *
 * @param string $password
 * @access public
 * @return self
 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}
/**
 * Set host
 *
 * @param string $host
 * @access public
 * @return self
 */
	public function setHost($host)
	{
		$this->host = $host;
		return $this;
	}
/**
 * Get data
 *
 * @access public
 * @return string
 */
	public function getData()
	{
		return $this->data;
	}
/**
 * Get host
 *
 * @access public
 * @return string
 */
	public function getHost()
	{
		return $this->host;
	}
/**
 * Get HTTP method, eg. GET, POST
 *
 * @access public
 * @return string
 */
	public function getMethod()
	{
		return $this->method;
	}
/**
 * Get response
 *
 * @access public
 * @return string
 */
	public function getResponse()
	{
		return $this->response;
	}
/**
 * Get response headers
 *
 * @access public
 * @return array
 */
	public function getResponseHeaders()
	{
		return $this->responseHeaders;
	}
/**
 * Set HTTP method. Accept 'GET', 'POST'
 *
 * @param string $method
 * @access public
 * @return self
 */
	public function setMethod($method)
	{
		$this->method = strtoupper($method);
		return $this;
	}
/**
 * Get all headers
 *
 * @access public
 * @return array
 */
	public function getHeaders()
	{
		return $this->headers;
	}
/**
 * Set data
 *
 * @param array|string $data
 * @param bool $encode_string
 * @access public
 * @return self
 */
	public function setData($data, $encode_string=TRUE)
	{
		if (is_array($data) && $encode_string)
		{
			$data = http_build_query($data);
		}
		$this->data = $data;
		return $this;
	}
/**
 * Set headers
 *
 * @param array $headers
 * @access public
 * @return self
 */
	public function setHeaders($headers)
	{
		$this->headers = array();

		foreach ($this->flattenHeaders($headers) as $header)
		{
			$this->addHeader($header);
		}
		
		return $this;
    }
/**
 * Add header
 *
 * @param string $header
 * @access public
 * @return self
 */
    public function addHeader($header)
	{
		if (0 === stripos(substr($header, -8), 'HTTP/1.') && 3 == count($parts = explode(' ', $header)))
		{
			list($method, $resource, $protocolVersion) = $parts;

			$this->setMethod($method);
			//$this->setResource($resource);
			//$this->setProtocolVersion((float) substr($protocolVersion, 5));
		} else {
			$this->headers[] = $header;
		}
		return $this;
    }
/**
 * Flatten headers
 *
 * @param array $headers
 * @access protected
 * @return array
 */
	protected function flattenHeaders(array $headers)
    {
        $flattened = array();
        foreach ($headers as $key => $header)
        {
			if (is_int($key))
			{
				$flattened[] = $header;
			} else {
				$flattened[] = $key.': '.$header;
			}
		}

		return $flattened;
    }
/**
 * Set connection timeout period
 *
 * @param int $value
 * @access public
 * @return self
 */
    public function setConnectTimeout($value)
    {
    	$this->connectTimeout = (int) $value;
    	
    	return $this;
    }
/**
 * Set timeout period
 *
 * @param int $value
 * @access public
 * @return self
 */
	public function setTimeout($value)
    {
    	$this->timeout = (int) $value;
    	
    	return $this;
    }
}
?>