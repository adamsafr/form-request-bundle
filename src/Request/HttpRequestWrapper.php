<?php

namespace Adamsafr\FormRequestBundle\Request;

use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HttpRequestWrapper
{
    /**
     * @var null|Request
     */
    protected $httpRequest;


    public function setHttpRequest(Request $httpRequest): void
    {
        $this->httpRequest = $httpRequest;
    }

    public function attributes(): ParameterBag
    {
        return $this->httpRequest->attributes;
    }

    public function request(): ParameterBag
    {
        return $this->httpRequest->request;
    }

    public function query(): ParameterBag
    {
        return $this->httpRequest->query;
    }

    public function server(): ServerBag
    {
        return $this->httpRequest->server;
    }

    public function files(): FileBag
    {
        return $this->httpRequest->files;
    }

    public function cookies(): ParameterBag
    {
        return $this->httpRequest->cookies;
    }

    public function headers(): HeaderBag
    {
        return $this->httpRequest->headers;
    }

    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->httpRequest->get($key, $default);
    }

    public function getSession(): ?SessionInterface
    {
        return $this->httpRequest->getSession();
    }

    public function hasPreviousSession(): bool
    {
        return $this->httpRequest->hasPreviousSession();
    }

    public function hasSession(): bool
    {
        return $this->httpRequest->hasSession();
    }

    public function setSession(SessionInterface $session): void
    {
        $this->httpRequest->setSession($session);
    }

    public function getClientIps(): array
    {
        return $this->httpRequest->getClientIps();
    }

    public function getClientIp(): ?string
    {
        return $this->httpRequest->getClientIp();
    }

    public function getScriptName(): string
    {
        return $this->httpRequest->getScriptName();
    }

    public function getPathInfo(): string
    {
        return $this->httpRequest->getPathInfo();
    }

    public function getBasePath(): string
    {
        return $this->httpRequest->getBasePath();
    }

    public function getBaseUrl(): string
    {
        return $this->httpRequest->getBaseUrl();
    }

    public function getScheme(): string
    {
        return $this->httpRequest->getScheme();
    }

    /**
     * @return int|string
     */
    public function getPort()
    {
        return $this->httpRequest->getPort();
    }

    public function getUser(): ?string
    {
        return $this->httpRequest->getUser();
    }

    public function getPassword(): ?string
    {
        return $this->httpRequest->getPassword();
    }

    public function getUserInfo(): string
    {
        return $this->httpRequest->getUserInfo();
    }

    public function getHttpHost(): string
    {
        return $this->httpRequest->getHttpHost();
    }

    public function getRequestUri(): string
    {
        return $this->httpRequest->getRequestUri();
    }

    public function getSchemeAndHttpHost(): string
    {
        return $this->httpRequest->getSchemeAndHttpHost();
    }

    public function getUri(): string
    {
        return $this->httpRequest->getUri();
    }

    public function getUriForPath(string $path): string
    {
        return $this->httpRequest->getUriForPath($path);
    }

    public function getRelativeUriForPath(string $path): string
    {
        return $this->httpRequest->getRelativeUriForPath($path);
    }

    public function getQueryString(): ?string
    {
        return $this->httpRequest->getQueryString();
    }

    public function isSecure(): bool
    {
        return $this->httpRequest->isSecure();
    }

    public function getHost(): string
    {
        return $this->httpRequest->getHost();
    }

    public function setMethod(string $method): void
    {
        $this->httpRequest->setMethod($method);
    }

    public function getMethod(): string
    {
        return $this->httpRequest->getMethod();
    }

    public function getRealMethod(): string
    {
        return $this->httpRequest->getRealMethod();
    }

    public function getMimeType(string $format): ?string
    {
        return $this->httpRequest->getMimeType($format);
    }

    public function getFormat(string $mimeType): ?string
    {
        return $this->httpRequest->getFormat($mimeType);
    }

    /**
     * @param string $format
     * @param string|array $mimeTypes
     */
    public function setFormat(string $format, $mimeTypes): void
    {
        $this->httpRequest->setFormat($format, $mimeTypes);
    }

    public function getRequestFormat(?string $default = 'html'): string
    {
        return $this->httpRequest->getRequestFormat($default);
    }

    public function setRequestFormat(string $format): void
    {
        $this->httpRequest->setRequestFormat($format);
    }

    public function getContentType(): ?string
    {
        return $this->httpRequest->getContentType();
    }

    public function setDefaultLocale(string $locale): void
    {
        $this->httpRequest->setDefaultLocale($locale);
    }

    public function getDefaultLocale(): string
    {
        return $this->httpRequest->getDefaultLocale();
    }

    public function setLocale(string $locale): void
    {
        $this->httpRequest->setLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->httpRequest->getLocale();
    }

    public function isMethod(string $method): bool
    {
        return $this->httpRequest->isMethod($method);
    }

    public function isMethodSafe(): bool
    {
        return $this->httpRequest->isMethodSafe();
    }

    public function isMethodIdempotent(): bool
    {
        return $this->httpRequest->isMethodIdempotent();
    }

    public function isMethodCacheable(): bool
    {
        return $this->httpRequest->isMethodCacheable();
    }

    public function getProtocolVersion(): string
    {
        return $this->httpRequest->getProtocolVersion();
    }

    /**
     * Returns the request body content.
     *
     * @param bool $asResource If true, a resource will be returned
     * @return string|resource The request body content or a resource to read the body stream
     * @throws \LogicException
     */
    public function getContent($asResource = false)
    {
        return $this->httpRequest->getContent($asResource);
    }

    public function getETags(): array
    {
        return $this->httpRequest->getETags();
    }

    public function isNoCache(): bool
    {
        return $this->httpRequest->isNoCache();
    }

    public function getPreferredLanguage(?array $locales = null): ?string
    {
        return $this->httpRequest->getPreferredLanguage($locales);
    }

    public function getLanguages(): array
    {
        return $this->httpRequest->getLanguages();
    }

    public function getCharsets(): array
    {
        return $this->httpRequest->getCharsets();
    }

    public function getEncodings(): array
    {
        return $this->httpRequest->getEncodings();
    }

    public function getAcceptableContentTypes(): array
    {
        return $this->httpRequest->getAcceptableContentTypes();
    }

    public function isXmlHttpRequest(): bool
    {
        return $this->httpRequest->isXmlHttpRequest();
    }

    public function isFromTrustedProxy(): bool
    {
        return $this->httpRequest->isFromTrustedProxy();
    }
}
