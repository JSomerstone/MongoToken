<?php
namespace JSomerstone\MongoToken\test\unit;

use JSomerstone\MongoToken\Model\DataContainer;
use JSomerstone\MongoToken\Model\Token;
use \DateTime;

class TokenModelTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $token = new Token(
            new DataContainer([]),
            new DateTime('2014-10-30 12:34:56'),
            'PSEUDORANDOMTOKEN'
        );

        $expected = array(
            'data' => serialize([]),
            'validThrough' => '2014-10-30 12:34:56',
            '__id' => 'PSEUDORANDOMTOKEN'
        );

        $actual = $token->toArray();
        $this->assertEquals($expected, $actual);
    }

    public function testDataPreservation()
    {
        $originalToken = new Token(
            new DataContainer(['foo' => 'bar']),
            new DateTime('now'),
            uniqid()
        );

        $clone = Token::fromArray($originalToken->toArray());

        $this->assertSame(
            $originalToken->toArray(),
            $clone->toArray()
        );
    }

    public function testGenerateToken()
    {

    }
}
