<?php

namespace Adamsafr\FormRequestBundle\Http;

use Adamsafr\FormRequestBundle\Helper\Json;
use Adamsafr\FormRequestBundle\Helper\Str;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class BaseFormRequest
{
    /**
     * @var null|Request
     */
    private $request;

    /**
     * @var null|ParameterBag
     */
    private $json;


    /**
     * Set request object.
     *
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Get request object.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Set the JSON payload for the request.
     *
     * @param ParameterBag $json
     * @return $this
     */
    public function setJson(ParameterBag $json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get the JSON payload for the request.
     *
     * @return ParameterBag
     */
    public function json(): ParameterBag
    {
        if (!isset($this->json)) {
            $this->json = new ParameterBag((array) Json::decode($this->request->getContent()));
        }

        return $this->json;
    }

    /**
     * Determine if the request is sending JSON.
     *
     * @return bool
     */
    public function isJson(): bool
    {
        return Str::contains($this->request->headers->get('CONTENT_TYPE'), ['/json', '+json']);
    }

    /**
     * Get all of the input and files for the request.
     *
     * @return array
     */
    public function all(): array
    {
        $input = $this->getInputSource()->all() + $this->request->query->all();

        return array_replace_recursive($input, $this->request->files->all());
    }

    /**
     * Gets a "parameter" value from any bag.
     * Order of precedence: PATH (routing placeholders or custom attributes), GET, BODY.
     *
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * Get the input source for the request.
     *
     * @return ParameterBag
     */
    protected function getInputSource(): ParameterBag
    {
        if ($this->isJson()) {
            return $this->json();
        }

        return $this->request->getRealMethod() === 'GET' ? $this->request->query : $this->request->request;
    }
}
