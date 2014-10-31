<?php
namespace JSomerstone\MongoToken\Model;

class DataContainer
{
    /**
     * @var array
     */
    private $properties = array();

    /**
     * @param array $properties
     */
    public function __construct(array $properties = array())
    {
        $this->properties = $properties;
    }

    /**
     * @param string|int $index
     * @param mixed $value
     * @return DataContainer $this
     */
    public function set($index, $value)
    {
        $this->properties[$index] = $value;
        return $this;
    }

    /**
     * @param string|int $index
     * @return mixed|null
     */
    public function get($index)
    {
        if ( ! isset($this->properties[$index]))
        {
            return null;
        }
        return $this->properties[$index];
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->properties;
    }

    /**
     * Serialized the data inside container
     * @return string
     */
    public function serialize()
    {
        return serialize($this->properties);
    }

    /**
     * Return new DataContainer with given data unserialized
     * @param string $serializedData
     * @return DataContainer
     */
    public static function unserialize($serializedData)
    {
        return new DataContainer(unserialize($serializedData));
    }
}
