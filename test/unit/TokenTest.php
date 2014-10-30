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

        $this->assertEquals(
            $originalToken->toArray(),
            $clone->toArray()
        );
    }

    public function testDataPreservationWithoutExpiration()
    {
        $originalToken = new Token(
            new DataContainer(['foo' => 'bar'])
        );

        $clone = Token::fromArray($originalToken->toArray());

        $this->assertEquals(
            $originalToken->toArray(),
            $clone->toArray()
        );
    }

    public function testGenerateToken()
    {
        $tokenObj = Token::generateToken(new DataContainer([]));
        $this->assertNotEmpty($tokenObj->getToken());
        $this->assertTrue($tokenObj->isValid());
    }

    /**
     * @test
     * @dataProvider provideDateTimesAndValidities
     */
    public function testExpiration($dateTime, $expectedValidity)
    {
        $data = new DataContainer([]);
        $token = new Token($data, $dateTime);

        $this->assertEquals(
            $expectedValidity,
            $token->isValid()
        );
    }

    public function provideDateTimesAndValidities()
    {
        return array(
            [ null, true ],
            [ new DateTime('+1 second'), true ],
            [ new DateTime('+1 minute'), true ],
            [ new DateTime('+1 hour'), true ],
            [ new DateTime('+1 day'), true ],
            [ new DateTime('+1 year'), true ],
            [ new DateTime('-1 second'), false ],
            [ new DateTime('-1 minute'), false ],
            [ new DateTime('-1 hour'), false ],
            [ new DateTime('-1 day'), false ],
            [ new DateTime('-1 year'), false ],
        );
    }

    public function testDataGetter()
    {
        $data = new DataContainer(['BD' => new DateTime('2009-10-22')]);

        $tokenObj = new Token($data);

        $this->assertSame(
            $data,
            $tokenObj->getData()
        );
    }

    public function testGeneratedTokensAreUnique()
    {
        $generated = array();
        $emptyData = new DataContainer([]);
        $numberOfTokens = 1000;
        while ($numberOfTokens-- > 1)
        {
            $token = Token::generateToken($emptyData)->getToken();

            $this->assertFalse(isset($generated[$token]), "Non-unique token encountered");

            $generated[$token] = true;
        }
    }
}
