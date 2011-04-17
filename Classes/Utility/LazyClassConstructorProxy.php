<?php
class Tx_T3orgFeedparser_Utility_LazyClassConstructorProxy {

	protected $className = null;
	protected $arguments = array();
	
	protected $object = null;
	
	public function __call($methodName, $arguments) {
		if(is_null($this->object)) {
			$this->initObject();
		}
		return call_user_method_array($methodName, $this->object, $arguments);
	}
	
	protected function initObject() {
		$this->object = t3lib_div::makeInstance($className, $arguments);
	}
	
}
?>