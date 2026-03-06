<?php
namespace LazarusPhp\Requests\Psr;

use LazarusPhp\Requests\Psr\HttpKernel;
use Psr\Http\Message\ServerRequestInterface;

final class PsrRequests
{
     private ServerRequestInterface $request;
    public function __construct(){
        $this->request = HttpKernel::getRequest();
    }

    public function method(): string
    {
        return $this->request->getMethod();
    }

    public function body(): array
    {
        return (array) $this->request->getParsedBody();
    }

    public function query(): array
    {
        return (array) $this->request->getQueryParams();
    }

    public function raw(): ServerRequestInterface
    {
        return $this->request;
    }
}