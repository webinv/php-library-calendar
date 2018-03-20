<?php

namespace Webinv\Calendar;

/**
 * Class EventCollection
 * @package Calendar
 */
class EventCollection implements \Iterator, \JsonSerializable
{
    /**
     * @var Event[]
     */
    private $collection = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var \Closure
     */
    private $jsonSerializer;

    /**
     * @param \Closure $jsonSerializer
     */
    public function setJsonSerializer(\Closure $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param Event $event
     */
    public function add(Event $event)
    {
        $this->collection[] = $event;
    }

    /**
     * @return Event[]
     */
    public function toArray()
    {
        return $this->collection;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->collection[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->collection[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->collection);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        if (null !== $this->jsonSerializer) {
            return call_user_func($this->jsonSerializer, $this);
        }
        return $this->collection;
    }
}
