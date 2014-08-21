<?php
	namespace Reling\Application\Core;
	
	class Application {
	
		private $config;
	
		public function __construct() {
			$this->loadConfig();
			$this->registerClassAliases();
		}
		
		private function loadConfig() {
			$this->config = require_once('../app/config/app.php');
			return $this;
		}
		
		private function registerClassAliases() {
			foreach($this->config['aliases'] as $alias => $original) {
				class_alias($original, $alias);
			}
		}
		
		public function debug() {
			return $this->config['debug'];
		}
		
		public function host() {
			return $this->config['host'];			
		}
		
		public function url() {
			return $this->config['url'];
		}
	}
?>