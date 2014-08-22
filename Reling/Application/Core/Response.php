<?php
	namespace Reling\Application\Core;
	
	class Response {
	
		private $headers = array();
		private $httpContent;
	
		public function __construct() {
		
		}
		
		public function header($flag, $value) {
			$this->headers[$flag] = $value;
			return $this;
		}
		
		public function status($status = 200) {
			$this->headers('Status', $status);
			return $this;
		}
		
		public function json($content) {
			$this->headers('Content-Type','application/json');			
			return json_encode($content);
		}
		
		public function setContent($content) {
			$this->httpContent = $content;
			return $this;
		}
		
		public function send() {
			header(implode(': ', $this->headers));
			echo $this->httpContent;
		}	
	}
?>