<?php
namespace LazarusPhp\Requests\Psr;


use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

final class HttpKernel
{
    private static ?ServerRequestInterface $psrRequest = null;

    public static function boot(): void
    {
        if (self::$psrRequest === null) {
            self::$psrRequest = ServerRequestFactory::fromGlobals();
        }
    }

    public static function getRequest(): ServerRequestInterface
    {
        if (self::$psrRequest === null) {
            throw new \RuntimeException('HttpKernel must be booted first.');
        }

        return self::$psrRequest;
    }
}