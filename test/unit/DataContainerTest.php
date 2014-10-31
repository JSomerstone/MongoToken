<?php
namespace JSomerstone\MongoToken\test\unit;

use JSomerstone\MongoToken\Model\DataContainer;
use \DateTime;

class DataContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DataContainer
     */
    protected $dataContainer;

    public function setUp()
    {
        $this->dataContainer = new DataContainer();
    }

    /**
     * @test
     * @dataProvider provideStorableValues
     */
    public function testSetterAndGetter($value)
    {
        $this->dataContainer->set('givenName', $value);

        $this->assertEquals(
            $value,
            $this->dataContainer->get('givenName')
        );
    }

    /**
     * @param $value
     * @dataProvider provideStorableValues
     */
    public function testSerialization($value)
    {
        $this->dataContainer->set('index', $value);
        $serializedData = $this->dataContainer->serialize();
        $newContainer = DataContainer::unserialize($serializedData);

        $this->assertEquals(
            $this->dataContainer,
            $newContainer
        );
    }

    public function provideStorableValues()
    {
        return array(
            [ null ],
            [ true ],
            [ false ],
            [ 1 ],
            [ PHP_INT_MAX ],
            [ 'A string' ],
            [ pi() ],
            [ new DateTime() ],
            [ new DataContainer(['one' => 1, 'two' => 2]) ]
        );
    }

    public function testSettingMultipleProperties()
    {
        $dataSet = $this->provideStorableValues();
        foreach($dataSet as $index => $value)
        {
            $this->dataContainer->set($index, $value);
        }

        $output = $this->dataContainer->getAll();

        $this->assertEquals(
            $dataSet,
            $output
        );
    }

    public function testHas()
    {
        $this->dataContainer->set('some-index', true);

        $this->assertTrue($this->dataContainer->has('some-index'));
        $this->assertFalse($this->dataContainer->has('none-existing'));
    }

    public function testHasAfterClearing()
    {
        $this->dataContainer->set('abba', 'A band');
        $this->assertTrue($this->dataContainer->has('abba'));
        $this->dataContainer->set('abba', null);
        $this->assertFalse($this->dataContainer->has('abba'));

    }
}
