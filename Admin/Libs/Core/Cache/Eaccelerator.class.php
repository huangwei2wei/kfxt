<?php
class Cache_Eaccelerator {
	
	public function get($key){
		return eaccelerator_get($key);
	}
	
	public function clear(){
		
	}
	
	public function rm($key){
		 return eaccelerator_rm($key);
	}
	
	public function set($key,$value,$expire){
         eaccelerator_lock($key);
         return eaccelerator_put ($key, $value, $expire);
	}
}