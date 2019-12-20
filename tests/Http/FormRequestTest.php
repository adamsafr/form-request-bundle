<?php

namespace Adamsafr\FormRequestBundle\Tests\Http;

use Adamsafr\FormRequestBundle\Http\FormRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class FormRequestTest extends TestCase
{
    /**
     * @var null|TestRequest
     */
    private $formRequest;


    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new TestRequest();
    }

    public function contentTypeProvider(): array
    {
        return [
            ['application/x-collection+json', true],
            ['application/json', true],
            ['multipart/form-data', false],
        ];
    }

    /**
     * @dataProvider contentTypeProvider
     *
     * @param string $contentType
     * @param bool $expected
     */
    public function testIsJsonMethod($contentType, $expected)
    {
        $request = Request::create('/', 'POST', [], [], [], ['CONTENT_TYPE' => $contentType]);
        $this->formRequest->setRequest($request);

        $this->assertEquals($expected, $this->formRequest->isJson());
    }

    public function testJsonMethodWithJsonContent()
    {
        $json = '{"email": "lorem@ipsum.dolor", "name": "Lorem", "login": "lorem-ipsum"}';

        $request = Request::create('/', 'PUT', [], [], [], ['CONTENT_TYPE' => 'application/json'], $json);
        $this->formRequest->setRequest($request);

        $this->assertEquals(
            [
                'email' => 'lorem@ipsum.dolor',
                'name' => 'Lorem',
                'login' => 'lorem-ipsum',
            ],
            $this->formRequest->json()->all()
        );
    }

    public function testJsonMethodWithOtherContent()
    {
        $parameters = [
            'email' => 'lorem@ipsum.dolor',
            'name' => 'Lorem',
            'login' => 'lorem-ipsum',
        ];

        $request = Request::create('/', 'POST', $parameters, [], [], ['CONTENT_TYPE' => 'multipart/form-data']);
        $this->formRequest->setRequest($request);

        $this->assertEquals([], $this->formRequest->json()->all());
    }

    public function testAllMethodWithDifferentKeys()
    {
        $parameters = [
            'email' => 'lorem@ipsum.dolor',
            'name' => 'Lorem',
            'login' => 'lorem-ipsum',
        ];

        $files = [
            'avatar' => $this->createFile('avatar.png', 'image/png'),
        ];

        $request = Request::create('/', 'POST', $parameters, [], $files);
        $this->formRequest->setRequest($request);

        $this->assertEquals(array_merge($parameters, $files), $this->formRequest->all());
    }

    public function testAllMethodWhenFileKeyHasTheSameNameAsInputParameter()
    {
        $parameters = [
            'email' => 'lorem@ipsum.dolor',
            'avatar' => 'my_avatar_path',
        ];

        $avatar = $this->createFile('avatar.png', 'image/png');

        $request = Request::create('/', 'POST', $parameters, [], compact('avatar'));
        $this->formRequest->setRequest($request);

        $this->assertEquals(
            [
                'email' => 'lorem@ipsum.dolor',
                'avatar' => $avatar,
            ],
            $this->formRequest->all()
        );
    }

    public function testAllMethodWithJsonContent()
    {
        $json = '{"email": "lorem@ipsum.dolor", "name": "Lorem", "login": "lorem-ipsum"}';

        $request = Request::create('/', 'PUT', [], [], [], ['CONTENT_TYPE' => 'application/json'], $json);
        $this->formRequest->setRequest($request);

        $this->assertEquals(
            [
                'email' => 'lorem@ipsum.dolor',
                'name' => 'Lorem',
                'login' => 'lorem-ipsum',
            ],
            $this->formRequest->all()
        );
    }

    public function testAllMethodWithGetHttpMethod()
    {
        $parameters = [
            'page' => '2',
            'order' => 'name',
            'direction' => 'desc',
            'limit' => 10,
        ];

        $request = Request::create('/', 'GET', $parameters);
        $this->formRequest->setRequest($request);

        $this->assertEquals($parameters, $this->formRequest->all());
    }

    public function testAuthorizeMethod()
    {
        $this->assertTrue($this->formRequest->authorize());
    }

    public function testRulesMethod()
    {
        $this->assertEquals(null, $this->formRequest->rules());
    }

    public function testValidationGroups()
    {
        $this->assertEquals([], $this->formRequest->validationGroups());
    }

    private function createFile(string $name, string $mimeType): UploadedFile
    {
        return new UploadedFile(tempnam(sys_get_temp_dir(), 'upl'), $name, $mimeType);
    }
}

class TestRequest extends FormRequest
{
}
