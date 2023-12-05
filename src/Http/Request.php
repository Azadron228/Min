<?php

namespace Min\Http;


use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Session\Session;

class Request
{
    private SymfonyRequest $request;

    public function __construct()
    {
        $this->request = SymfonyRequest::createFromGlobals();
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function method(): string
    {
        return $this->request->getMethod();
    }

    public function json(): array
    {
        return $this->request->toArray();
    }

    public function content()
    {
        return $this->request->getContent();
    }

    public function cookie(string $key = null): mixed
    {
        if ($key) {
            return $this->request->cookies->get($key);
        }

        return $this->request->cookies->all();
    }

    public function session(): mixed
    {
        return new Session();
    }

    public function header(string $key = null): mixed
    {
        if ($key) {
            return $this->request->headers->get($key);
        }

        return $this->request->headers->all();
    }

    public function isMethod(string $method): bool
    {
        return $this->request->isMethod($method);
    }
}
