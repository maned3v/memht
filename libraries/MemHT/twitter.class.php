<?php

class Twitter {
	
	private $oauth_consumer_key = null;
	private $oauth_consumer_secret = null;
	private $oauth_access_token = null;
	private $oauth_access_token_secret = null;
	private $user_timeline_url = null;
	private $oauth_signature = null;
	private $oauth_signature_method = null;
	private $oauth_version = null;
	private $oauth_nonce = null;
	private $oauth_timestamp = null;
	
	private $twitid = null;
	private $count = 0;
	private $maxage = null;
	private $path = null;
	private $twitterdata = null;
	
	public function __construct() {
		$this->oauth_consumer_key = Utils::GetComOption("twitter","oauth_consumer_key",null);
		$this->oauth_consumer_secret = Utils::GetComOption("twitter","oauth_consumer_secret",null);
		$this->oauth_access_token = Utils::GetComOption("twitter","oauth_access_token",null);
		$this->oauth_access_token_secret = Utils::GetComOption("twitter","oauth_access_token_secret",null);
		$this->user_timeline_url = Utils::GetComOption("twitter","user_timeline_url","https://api.twitter.com/1.1/statuses/user_timeline.json");
		$this->oauth_signature_method = 'HMAC-SHA1';								
		$this->oauth_version = '1.0';
		$this->oauth_nonce = time();
		$this->oauth_timestamp = time();
		
		$this->twitid = Utils::GetComOption("twitter","twitid",null);
		$this->count = Utils::GetComOption("twitter","count",5);
		
		$this->maxage = 3600;
		$this->path = "assets/twitter/cache/".$this->twitid.".html";
	}
	
	private function buildBaseString($baseURI, $method, $params) {
		$r = array();
		ksort($params);
		foreach($params as $key=>$value) {
			$r[] = "$key=".rawurlencode($value);
		}
		return $method."&".rawurlencode($baseURI).'&'.rawurlencode(implode('&',$r));
	}
	
	private function buildAuthorizationHeader($oauth) {
		$r = 'Authorization: OAuth ';
		$values = array();
		foreach($oauth as $key=>$value) {
			$values[] = "$key=\"".rawurlencode($value)."\"";
		}
		$r .= implode(', ', $values);
		return $r;
	}
	
	private function getCache() {
		if (file_exists($this->path)) {
			$cacheage = @filemtime($this->path);
			if ((time()-$cacheage)<$this->maxage) {
				return true;
			}
		}
		return false;
	}
	
	private function writeCache() {
		$twitts = array();
		if (is_array($this->twitterdata) && sizeof($this->twitterdata)) {
			$i = 1;
			foreach ($this->twitterdata as $twit) {
				if ($i>$this->count) break;
				$i++;
				$created_at = strtotime($twit->created_at);
				$created_at = gmdate('D, j M Y, H:i e',$created_at);
				$twitts[] = "<li class='twititem'><div class='text'><a href='https://twitter.com/".$this->twitid."' rel='external nofollow'>".str_replace("'","&#039;",$twit->text)."</a></div><div class='created'>".$created_at."</div></li>\n";
			}
		}
		$content = implode("",$twitts);
		if (!empty($content)) {
			if ($handle = @fopen($this->path,"w")) {
				@fwrite($handle,$content);
				@fclose($handle);
			}
		}
		$this->twitterdata = $content;
	}
	
	public function MakeRequest() {
		if ($this->getCache()) {
			$this->twitterdata = file_get_contents($this->path);
		} else {
			if (!is_callable('curl_init')) return false;
			
			$oauth = array( 'oauth_consumer_key' => $this->oauth_consumer_key,
							'oauth_nonce' => $this->oauth_nonce,
							'oauth_signature_method' => $this->oauth_signature_method,
							'oauth_token' => $this->oauth_access_token,
							'oauth_timestamp' => $this->oauth_timestamp,
							'oauth_version' => $this->oauth_version);
			
			$base_info = $this->buildBaseString($this->user_timeline_url, 'GET', $oauth);
			$composite_key = rawurlencode($this->oauth_consumer_secret) . '&' . rawurlencode($this->oauth_access_token_secret);
			$oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
			$this->oauth_signature = $oauth['oauth_signature'];
			
			// Make Requests
			$header = array($this->buildAuthorizationHeader($oauth), 'Expect:');
			
			$options = array( CURLOPT_HTTPHEADER => $header,
							  CURLOPT_HEADER => false,
							  CURLOPT_URL => $this->user_timeline_url,
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_SSL_VERIFYPEER => false);
			
			$feed = curl_init();
			curl_setopt_array($feed, $options);
			$json = curl_exec($feed);
			curl_close($feed);
			
			$this->twitterdata = json_decode($json);
			
			$this->writeCache();
		}
		
		return $this->twitterdata;
	}
}

?>
