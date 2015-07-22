<?php
/**
 * object主类,实现了ArrayAccess,方便对象数组化
 * @author php-朱磊
 */
class Object extends Base implements ArrayAccess {
	
	
	
	
	/**
	 * Defined by ArrayAccess interface
	 * Set a value given it's key e.g. $A['title'] = 'foo';
	 * @param mixed key (string or integer)
	 * @param mixed value
	 * @return void
	 */
	function offsetSet($key, $value) {
		$this->{$key} = $value;
	}
	
	/**
	 * Defined by ArrayAccess interface
	 * Return a value given it's key e.g. echo $A['title'];
	 * @param mixed key (string or integer)
	 * @return mixed value
	 */
	function offsetGet($key) {
		if (array_key_exists ( $key, get_object_vars ( $this ) )) {
			return $this->{$key};
		}
	}
	
	/**
	 * Defined by ArrayAccess interface
	 * Unset a value by it's key e.g. unset($A['title']);
	 * @param mixed key (string or integer)
	 * @return void
	 */
	function offsetUnset($key) {
		if (array_key_exists ( $key, get_object_vars ( $this ) )) {
			unset ( $this->{$key} );
		}
	}
	
	/**
	 * Defined by ArrayAccess interface
	 * Check value exists, given it's key e.g. isset($A['title'])
	 * @param mixed key (string or integer)
	 * @return boolean
	 */
	function offsetExists($offset) {
		return array_key_exists ( $offset, get_object_vars ( $this ) );
	}
}