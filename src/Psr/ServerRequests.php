<?php
namespace LazarusPhp\Requests\Psr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;


class ServerRequests implements ServerRequestInterface
{
    private array $query;
    private array $body;
    private string $method;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->query  = $_GET;
        $this->body   = $_POST;

        if (empty($this->body)) {
            $raw = file_get_contents('php://input');
            if ($raw && str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
                $this->body = json_decode($raw, true) ?? [];
            }
        }
    }

    /* -------- Core methods you actually use -------- */

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getQueryParams(): array
    {
        return $this->query;
    }

    public function getParsedBody(): array
    {
        return $this->body;
    }

    public function getServerParams(): array
    {
        return $_SERVER;
    }

    public function getCookieParams(): array
    {
        return $_COOKIE;
    }

    public function getUploadedFiles(): array
    {
        return [];
    }

    public function getAttributes(): array
    {
        return [];
    }

    public function getAttribute($name, $default = null)
    {
        return $default;
    }

    /* -------- Immutable with* methods -------- */

    public function withMethod($method): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->body = is_array($data) ? $data : [];
        return $clone;
    }

    public function withQueryParams(array $params): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->query = $params;
        return $clone;
    }

    public function withAttribute($name, $value): ServerRequestInterface
    {
        return clone $this;
    }

    public function withoutAttribute($name): ServerRequestInterface
    {
        return clone $this;
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        return clone $this;
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        return clone $this;
    }

    /* -------- MessageInterface stubs -------- */

    public function getProtocolVersion(): string
    {
        return '1.1';
    }

    public function withProtocolVersion($version): ServerRequestInterface
    {
        return clone $this;
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function hasHeader($name): bool
    {
        return false;
    }

    public function getHeader($name): array
    {
        return [];
    }

    public function getHeaderLine($name): string
    {
        return '';
    }

    public function withHeader($name, $value): ServerRequestInterface
    {
        return clone $this;
    }

    public function withAddedHeader($name, $value): ServerRequestInterface
    {
        return clone $this;
    }

    public function withoutHeader($name): ServerRequestInterface
    {
        return clone $this;
    }

    public function getBody(): StreamInterface
    {
        throw new \BadMethodCallException('Body not implemented');
    }

    public function withBody(StreamInterface $body): ServerRequestInterface
    {
        return clone $this;
    }

    public function getRequestTarget(): string
    {
        return '/';
    }

    public function withRequestTarget($requestTarget): ServerRequestInterface
    {
        return clone $this;
    }

    public function getUri(): UriInterface
    {
        throw new \BadMethodCallException('URI not implemented');
    }

    public function withUri(UriInterface $uri, $preserveHost = false): ServerRequestInterface
    {
        return clone $this;
    }
}