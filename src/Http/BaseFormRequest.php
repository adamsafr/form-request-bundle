<?php

namespace Adamsafr\FormRequestBundle\Http;

use Adamsafr\FormRequestBundle\Helper\Json;
use Adamsafr\FormRequestBundle\Helper\Str;
use Symfony\Component\HttpFoundation\ParameterBag;

class BaseFormRequest extends HttpRequestWrapper
{
    /**
     * @var null|ParameterBag
     */
    private $json;


    public function isJson(): bool
    {
        return Str::contains($this->headers()->get('CONTENT_TYPE'), ['/json', '+json']);
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

    public function json(): ParameterBag
    {
        if (!isset($this->json)) {
            $this->json = new ParameterBag((array) Json::decode($this->getContent()));
        }

        return $this->json;
    }

    public function all(): array
    {
        $input = $this->getInputSource()->all() + $this->query()->all();

        return array_replace_recursive($input, $this->files()->all());
    }

    protected function getInputSource(): ParameterBag
    {
        if ($this->isJson()) {
            return $this->json();
        }

        return $this->getRealMethod() === 'GET' ? $this->query() : $this->request();
    }
}
