<?php

namespace System\Collections;

/**
 * The Dictionary object implements a collection, that takes key-value pairs, 
 * just like an array.
 *
 * You can access and add items to the collection using the "add", "itemAt", and "remove"
 * methods, or you can use this object like an array:
 * <ul>
 *    <li> $dictionary[$key] = $value </li>
 *    <li> unset($dictionary[$key]) </li>
 *    <li> if(isset($dictionary[$key])) </li>
 *    <li> $numItems = count($dictionary) </li>
 *    <li> foreach($dictionary as $item) </li>
 * </ul>
 */
class Dictionary implements \IteratorAggregate, \ArrayAccess, \Countable,  \Serializable
{
	/**
	 * Data Container.
	 * @var mixed[]
	 */
	private $data = array();
	
	/**
	 * The index count of the data container
	 * @var bint
	 */
	protected $size = 0;
	
	/**
	 * Represents whether this dictionary is read-only.
	 * @var bool
	 */
	protected $isReadOnly = false;
	
	/**
	 * Constructor
	 */
	public function __construct($readOnly = false)
	{
		$this->isReadOnly = $readOnly;
	}
	
	/**
	 * Adds an item to the dictionary
	 *
	 * @param string $key The item key
	 * @param mixed $value The value of the item key
	 * @return void
	 */
	public function add($key, $value)
	{
		if($this->isReadOnly)
			throw new Exception();
			
		if(!empty($key) || $key === 0)
			$this->data[$key] = $value;
		else
			$this->data[] = $value;
			
		++$this->size;
	}
	
	/**
	 * Determines whether the dictionary contains the specified key
	 *
	 * @param mixed $key The item key
	 * @return bool
	 */
	public function containsKey($key)
	{
		return array_key_exists($key, $this->data);
	}
	
	/**
	 * Determines whether the dictionary contains a value
	 *
	 * @param mixed $item The value to search for
	 * @return bool
	 */
	public function containsValue($item)
	{
		return (($index = array_search($item, $this->data, true)) !== false);
	}
	
	/**
	 * Returns All the dictionary keys
	 *
	 * @return string[]
	 */
	public function getKeys()
	{
		return array_keys($this->data);
	}
	
	/**
	 * Returns All the dictionary values
	 *
	 * @return mixed[]
	 */
	public function getValues()
	{
		return array_values($this->data);
	}
	
	/**
	 * Removes all items from the dictionary
	 *
	 * @return void
	 */
	public function clear()
	{
		if($this->isReadOnly)
			throw new Exception();
			
		$this->data = array();
		$this->size = 0;
	}
	
	/**
	 * Gets the value associated with the specified key
	 * 
	 * @param string $key The item's key
	 * @return mixed Returns the item of the specified index, or null if it doesnt exist
	 */
	public function itemAt($key)
	{
		return (isset($this->data[$key])) ? $this->data[$key] : null;
	}
	
	/**
	 * Removes the value with the specified key from the Dictionary
	 * 
	 * @param mixed $item The item value to search for
	 * @return int|bool The zero based index of the item was removed from, or false
	 */
	public function remove($key)
	{
		if($this->isReadOnly)
			throw new Exception();
			
		// Check that the item exists
		if($this->containsKey($key))
		{
			$value = $this->data[$key];
			unset($this->data[$key]);
			--$this->size;
			return $value;
		}
		
		return false;
	}
	
	/**
	 * Returns the list as an array
	 * @return mixed[]
	 */
	public function toArray()
	{
		return $this->data;
	}
	
	// === Interface / Abstract Methods === //
	
	/**
	 * Returns the number of items in the list
	 * This method is required by the interface Countable.
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->size;
	}
	
	/**
	 * Returns whether the specifed item key exists in the container
	 * This method is required by the interface ArrayAccess.
	 *
	 * @param string $key The item key to check for
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return $this->containsKey($key);
	}
	
	/**
	 * Returns the item value of the specified key.
	 * This method is required by the interface ArrayAccess.
	 *
	 * @param string $key The item key
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->itemAt($key);
	}
	
	/**
	 * Sets the item with the specified key.
	 * This method is required by the interface ArrayAccess.
	 *
	 * @param string $key The item key to set
	 * @param mixed $value The item value
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		$this->add($key, $value)
	}
	
	/**
	 * Removes the item with the specified key.
	 * This method is required by the interface ArrayAccess.
	 *
	 * @param string $key The item key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		$this->remove($key);
	}
	
	/**
	 * Serializes the data, and returns it.
	 * This method is required by the interface Serializable.
	 *
	 * @return string The serilized string
	 */
	public function serialize() 
	{
		return serialize($this->data);
	}
	
	/**
	 * Unserializes the data, and sets up the storage in this container
	 * This method is required by the interface Serializable.
	 *
	 * @return void
	 */
    public function unserialize($data) 
	{
        $this->data = unserialize($data);
    }
	
	/**
	 * Returns the ArrayIterator of this object
	 * This method is required by the interface IteratorAggregate.
	 *
	 * @return string The serilized string
	 */
	public function getIterator() 
	{
        return new \ArrayIterator($this);
    }
}