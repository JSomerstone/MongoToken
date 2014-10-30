<?php
namespace JSomerstone\MongoToken\Model;

class Token
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTime
     */
    private $validThrough;

    /**
     * @var DataContainer
     */
    private $data;

    /**
     * @param DataContainer $data
     * @param \DateTime $validThrough
     * @param string|null $token
     */
    public function __construct(
        DataContainer $data,
        \DateTime $validThrough = null,
        $token = null)
    {
        $this->data = $data;
        $this->validThrough = $validThrough;
        $this->token = $token;
    }

    /**
     * @param DataContainer $container
     * @param \DateTime $valid
     * @return Token
     */
    public static function generateToken(DataContainer $container,  \DateTime $valid = null)
    {
        return new Token(
            $container,
            $valid,
            self::randomString()
        );
    }

    /**
     * @return string
     */
    private static function randomString()
    {
        return hash('sha256', openssl_random_pseudo_bytes(128));
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            '__id' => $this->token,
            'validThrough' => $this->validThrough->format('Y-m-d H:i:s'),
            'data' => $this->data->serialize(),
        );
    }

    /**
     * @param array $tokenArray
     * @return Token
     */
    public static function fromArray(array $tokenArray)
    {
        return new Token(
            DataContainer::unserialize($tokenArray['data']),
            new \DateTime($tokenArray['validThrough']),
            $tokenArray['__id']
        );
    }
} 
