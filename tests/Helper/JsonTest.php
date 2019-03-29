<?php

namespace Adamsafr\FormRequestBundle\Tests\Helper;

use Adamsafr\FormRequestBundle\Exception\JsonDecodeException;
use Adamsafr\FormRequestBundle\Helper\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testDecodeWithEmptyBody()
    {
        $this->assertEquals([], Json::decode(''));
        $this->assertEquals([], Json::decode('  '));
    }

    public function testDecodeWithValidData()
    {
        $json = '{
                   "menu": {
                      "id": "file",
                      "value": "File",
                      "popup": {
                         "menuitem": [{
                           "value": "New",
                           "onclick": "CreateNewDoc()"
                         }, {
                           "value": "Open",
                           "onclick": "OpenDoc()"
                         }, {
                           "value": "Close",
                           "onclick": "CloseDoc()"
                         }]
                      }
                   }
                }';

        $expected = [
            'menu' => [
                'id' => 'file',
                'value' => 'File',
                'popup' => [
                    'menuitem' => [
                        ['value' => 'New', 'onclick' => 'CreateNewDoc()'],
                        ['value' => 'Open', 'onclick' => 'OpenDoc()'],
                        ['value' => 'Close', 'onclick' => 'CloseDoc()'],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, Json::decode($json));
    }

    public function testDecodeWithInvalidData()
    {
        $this->expectException(JsonDecodeException::class);
        Json::decode('{"message": hello world!}');
    }

    public function testEncodeWithData()
    {
        $arr = [
            'menu' => [
                'id' => 'file',
                'value' => 'File',
                'popup' => [
                    'menuitem' => [
                        ['value' => 'New', 'onclick' => 'CreateNewDoc()'],
                        ['value' => 'Open', 'onclick' => 'OpenDoc()'],
                        ['value' => 'Close', 'onclick' => 'CloseDoc()'],
                    ],
                ],
            ],
        ];

        $expected = '{"menu":{"id":"file","value":"File","popup":{"menuitem":[{"value":"New",' .
            '"onclick":"CreateNewDoc()"},{"value":"Open","onclick":"OpenDoc()"},{"value":"Close",' .
            '"onclick":"CloseDoc()"}]}}}';

        $this->assertEquals($expected, Json::encode($arr));
    }

    /**
     * @dataProvider otherDataProvider
     *
     * @param mixed $input
     * @param string $output
     */
    public function testEncodeWithOtherData($input, $output)
    {
        $this->assertEquals($output, Json::encode($input));
    }

    public function otherDataProvider(): array
    {
        return [
            ['', '""'],
            ['  ', '"  "'],
            [[], '[]'],
            ['Hello world!', '"Hello world!"'],
        ];
    }
}
