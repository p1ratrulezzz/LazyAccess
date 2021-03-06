<?php
/**
 * P1ratRuleZZZ\Tools\LazyAccessTyped class
 */

namespace P1ratRuleZZZ\Tools;

/*
 * Lazy access to the elements in array
 * @todo Provide possibility of using nested objects as well as arrays
 * @todo Lazy setters?
 * @todo Implement setters
 * @todo Auto type conversions on getting values according to the $defaults array
 *
 * @deprecated
 */
class LazyAccess implements \Iterator, \ArrayAccess {
  protected $_values = array();
  protected $_defaults = array();
  protected $_current_key = NULL;
  protected $_empty = FALSE;

  public function __construct(&$values, $defaults = array()) {
    $this->_values = &$values;
    $this->_defaults = $defaults;

    $this->rewind();
    $this->__setKey();
    $this->setEmpty(FALSE);
  }

  /**
   *  Magic __isset() method
   */
  public function __isset($key) {
    return $this->isArray() ? isset($this->_values[$key]) : !$this->isEmpty();
  }

  /**
   *  Magic __get() method
   *  @return LazyAccess,mixed
   */
  public function __get($key) {
    if (!empty($this->_values) && !is_array($this->_values)) {
      return $this->normalizeValues($this->_values);
    }
    elseif (!isset($this->_values[$key]) && isset($this->_defaults[$key])) {
      $this->_values[$key] = $this->_defaults[$key];
    }
    elseif (!isset($this->_values[$key])) {
      //return NULL;
      $empty = array();
      return $this->normalizeValues($empty)
        ->setEmpty(TRUE); // Mark this as empty.
    }

    return $this->normalizeValues($this->_values[$key]);
  }

  public function raw() {
    return $this->_values;
  }

  public function value($default = NULL) {
    $ret = $this->getNormalValOrNull() or $ret = $default;
    return $ret;
  }

  public function getValue($default = NULL) {
    if (!isset($this->_values)) {
      return $default;
    }

    return $this->_values;
  }

  protected function normalizeValues(&$values) {
    if (!is_array($values)) { //} && !is_object($values)) {
      return $values;
    }

    return new static($values);
  }

  public function setEmpty($status) {
    $this->_empty = $status;
    return $this;
  }

  protected function __setKey() {
    $this->_current_key = $this->isArray() ? key($this->_values) : TRUE;
  }

  protected function isArray() {
    return is_array($this->_values);
  }

  public function __toString() {
    if (is_array($this->_values)) {
      return 'Array';
    }
    elseif (is_object($this->_values)) {
      return 'Object';
    }

    return (string) $this->_values;
  }

  public function __set($key, $value) {
    $this->_values[$key] = $value;
  }

  public function current() {
    return $this->{$this->_current_key};
  }

  public function key() {
    return $this->_current_key;
  }

  public function next() {
    next($this->_values);
    $this->__setKey();
  }

  public function rewind() {
    if ($this->isArray()) {
      reset($this->_values);
    }

    $this->__setKey();
  }

  public function valid() {
    return $this->_current_key !== NULL ? TRUE : FALSE;
  }

  /**
   * Implements ArrayAccess::offsetExists()
   * @param mixed $offset
   * @return bool
   */
  public function offsetExists($offset) {
    return $this->__isset($offset);
  }

  /**
   * Implements ArrayAccess::offsetGet()
   * @param mixed $offset
   * @return LazyAccess
   */
  public function offsetGet($offset) {
    return $this->{$offset};
  }

  /**
   * Implements ArrayAccess::offsetGet()
   * @param mixed $offset
   * @param mixed $value
   * @throws Exception
   * @todo Implement setters
   */
  public function offsetSet($offset, $value) {
    throw new Exception('Not implemented yet!');
  }

  public function offsetUnset($offset) {
    throw new Exception('Not implemented yet!');
  }


  protected function getNormalValOrNull($default = NULL, $skip_arrays = TRUE) {
    $ret = $this->normalizeValues($this->_values);

    if ($ret->isEmpty() || ($skip_arrays && (is_array($ret->_values) || is_object($ret->_values)))) {
      return $default;
    }

    return $ret->_values;
  }

  public function isEmpty() {
    return $this->_empty; // $this->_values === NULL;
  }
}
