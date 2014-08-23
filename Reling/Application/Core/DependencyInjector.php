<?php
	namespace Reling\Application\Core;
	
	use Exception;
	use ReflectionClass;
	use ReflectionException;

	class DependencyInjector {
	
		private $dependencies = [];
		private $instances    = [];
		
		public function __construct($className) {
			$this->getDependencies($className);					
		}		
		
		public function resolve() {
			$dependencies = array_reverse($this->dependencies);
			
			foreach($dependencies as $className => $deps) {			
				
				$classInstances = [];
				foreach($deps as $name => $dep) {
					if(!isset($this->instances[$dep])) {
						$reflection            = new ReflectionClass($dep);
						$this->instances[$dep] = $reflection->newInstanceArgs();						
						$classInstances[]      = $this->instances[$dep];						
					} else {
						$classInstances[]      = $this->instances[$dep];
					}
				}
				
				if(!isset($this->instances[$className])) {
					$reflection                  = new ReflectionClass($className);					
					$this->instances[$className] = $reflection->newInstanceArgs($classInstances);
				}
			}

			$this->dependencies = [];
			
			return $this;
		}
		
		public function getInstance() {
			$instances = array_reverse($this->instances);			
			return reset($instances);
		}
		
		private function getDependencies($className) {
			$reflection  = new ReflectionClass($className);
			$constructor = $reflection->getConstructor();
			
			$parameters  = $constructor->getParameters();
			if(!empty($parameters)) {
				foreach($parameters as $parameter) {
					try {
						if($parameter->getClass() !== null) {
							// use the parameters typehint
							$dependencyValue = $parameter->getClass()->name;
						} else {
							// try using the parameters name as alias (as configured in app config)
							$dependencyValue = ucfirst($parameter->name);
						}
					} catch(ReflectionException $ex) {
						throw new Exception(sprintf('A constructor parameters type hinted class was not found in class "%s"!', $className), 0, $ex);
					}

					if(!class_exists($dependencyValue)) {
						throw new Exception(sprintf('No class found to inject in class "%s"`s constructor parameter "%s"!', $className, $parameter->name));
					}

					$this->dependencies[ucfirst($className)][ucfirst($parameter->name)] = $dependencyValue;
					
					// Be aware of recursion !
					$this->getDependencies($dependencyValue);
				}
			}

			$this->resolve();

			if($this->instances[$className]) {
				return $this->instances[$className];
			}

			return null;
		}
	}
?>