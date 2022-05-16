<?php

declare(strict_types=1);

namespace Sapphirecat\Slim4HttpInteropAdapter;

use Slim\Factory\Psr17\Psr17Factory;

/**
 * Slim Framework (https://slimframework.com) v4.8.1.
 *
 * @license https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */
class GuzzlePsr7v1Factory extends Psr17Factory
{
    /** @var string */
    protected static $responseFactoryClass = 'Http\Factory\Guzzle\ResponseFactory';
    /** @var string */
    protected static $streamFactoryClass = 'Http\Factory\Guzzle\StreamFactory';
    /** @var string */
    protected static $serverRequestCreatorClass = 'GuzzleHttp\Psr7\ServerRequest';
    /** @var string */
    protected static $serverRequestCreatorMethod = 'fromGlobals';
}
