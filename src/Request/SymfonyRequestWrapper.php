<?php

namespace Adamsafr\FormRequestBundle\Request;

use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SymfonyRequestWrapper
{
    /**
     * @var null|Request
     */
    private $symfonyRequest;


    public function setSymfonyRequest(Request $request): void
    {
        $this->symfonyRequest = $request;
    }

    public function attributes(): ParameterBag
    {
        return $this->symfonyRequest->attributes;
    }

    public function request(): ParameterBag
    {
        return $this->symfonyRequest->request;
    }

    public function query(): ParameterBag
    {
        return $this->symfonyRequest->query;
    }

    public function server(): ServerBag
    {
        return $this->symfonyRequest->server;
    }

    public function files(): FileBag
    {
        return $this->symfonyRequest->files;
    }

    public function cookies(): ParameterBag
    {
        return $this->symfonyRequest->cookies;
    }

    public function headers(): HeaderBag
    {
        return $this->symfonyRequest->headers;
    }

    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->symfonyRequest->get($key, $default);
    }

    public function getSession(): ?SessionInterface
    {
        return $this->symfonyRequest->getSession();
    }

    public function hasPreviousSession(): bool
    {
        return $this->symfonyRequest->hasPreviousSession();
    }

    public function hasSession(): bool
    {
        return $this->symfonyRequest->hasSession();
    }

    public function setSession(SessionInterface $session): void
    {
        $this->symfonyRequest->setSession($session);
    }

    public function getClientIps(): array
    {
        return $this->symfonyRequest->getClientIps();
    }

    public function getClientIp(): ?string
    {
        return $this->symfonyRequest->getClientIp();
    }

    public function getScriptName(): string
    {
        return $this->symfonyRequest->getScriptName();
    }

    public function getPathInfo(): string
    {
        return $this->symfonyRequest->getPathInfo();
    }

    public function getBasePath(): string
    {
        return $this->symfonyRequest->getBasePath();
    }

    public function getBaseUrl(): string
    {
        return $this->symfonyRequest->getBaseUrl();
    }

    public function getScheme(): string
    {
        return $this->symfonyRequest->getScheme();
    }

    /**
     * @return int|string
     */
    public function getPort()
    {
        return $this->symfonyRequest->getPort();
    }

    public function getUser(): ?string
    {
        return $this->symfonyRequest->getUser();
    }

    public function getPassword(): ?string
    {
        return $this->symfonyRequest->getPassword();
    }

    public function getUserInfo(): string
    {
        return $this->symfonyRequest->getUserInfo();
    }

    public function getHttpHost(): string
    {
        return $this->symfonyRequest->getHttpHost();
    }

    public function getRequestUri(): string
    {
        return $this->symfonyRequest->getRequestUri();
    }

    public function getSchemeAndHttpHost(): string
    {
        return $this->symfonyRequest->getSchemeAndHttpHost();
    }

    public function getUri(): string
    {
        return $this->symfonyRequest->getUri();
    }

    public function getUriForPath(string $path): string
    {
        return $this->symfonyRequest->getUriForPath($path);
    }

    public function getRelativeUriForPath(string $path): string
    {
        return $this->symfonyRequest->getRelativeUriForPath($path);
    }

    public function getQueryString(): ?string
    {
        return $this->symfonyRequest->getQueryString();
    }

    public function isSecure(): bool
    {
        return $this->symfonyRequest->isSecure();
    }

    public function getHost(): string
    {
        return $this->symfonyRequest->getHost();
    }

    public function setMethod(string $method): void
    {
        $this->symfonyRequest->setMethod($method);
    }

    public function getMethod(): string
    {
        return $this->symfonyRequest->getMethod();
    }

    public function getRealMethod(): string
    {
        return $this->symfonyRequest->getRealMethod();
    }

    public function getMimeType(string $format): ?string
    {
        return $this->symfonyRequest->getMimeType($format);
    }

    public function getFormat(string $mimeType): ?string
    {
        return $this->symfonyRequest->getFormat($mimeType);
    }

    /**
     * @param string $format
     * @param string|array $mimeTypes
     */
    public function setFormat(string $format, $mimeTypes): void
    {
        $this->symfonyRequest->setFormat($format, $mimeTypes);
    }

    public function getRequestFormat(?string $default = 'html'): string
    {
        return $this->symfonyRequest->getRequestFormat($default);
    }

    public function setRequestFormat(string $format): void
    {
        $this->symfonyRequest->setRequestFormat($format);
    }

    public function getContentType(): ?string
    {
        return $this->symfonyRequest->getContentType();
    }

    public function setDefaultLocale(string $locale): void
    {
        $this->symfonyRequest->setDefaultLocale($locale);
    }

    public function getDefaultLocale(): string
    {
        return $this->symfonyRequest->getDefaultLocale();
    }

    public function setLocale(string $locale): void
    {
        $this->symfonyRequest->setLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->symfonyRequest->getLocale();
    }

    public function isMethod(string $method): bool
    {
        return $this->symfonyRequest->isMethod($method);
    }

    public function isMethodSafe(): bool
    {
        return $this->symfonyRequest->isMethodSafe();
    }

    public function isMethodIdempotent(): bool
    {
        return $this->symfonyRequest->isMethodIdempotent();
    }

    public function isMethodCacheable(): bool
    {
        return $this->symfonyRequest->isMethodCacheable();
    }

    public function getProtocolVersion(): string
    {
        return $this->symfonyRequest->getProtocolVersion();
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
        return $this->symfonyRequest->getContent($asResource);
    }

    public function getETags(): array
    {
        return $this->symfonyRequest->getETags();
    }

    public function isNoCache(): bool
    {
        return $this->symfonyRequest->isNoCache();
    }

    public function getPreferredLanguage(?array $locales = null): ?string
    {
        return $this->symfonyRequest->getPreferredLanguage($locales);
    }

    public function getLanguages(): array
    {
        return $this->symfonyRequest->getLanguages();
    }

    public function getCharsets(): array
    {
        return $this->symfonyRequest->getCharsets();
    }

    public function getEncodings(): array
    {
        return $this->symfonyRequest->getEncodings();
    }

    public function getAcceptableContentTypes(): array
    {
        return $this->symfonyRequest->getAcceptableContentTypes();
    }

    public function isXmlHttpRequest(): bool
    {
        return $this->symfonyRequest->isXmlHttpRequest();
    }

    public function isFromTrustedProxy(): bool
    {
        return $this->symfonyRequest->isFromTrustedProxy();
    }
}
