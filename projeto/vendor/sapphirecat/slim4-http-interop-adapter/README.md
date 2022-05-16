# sapphirecat/slim4-http-interop-adapter

Autoconfigures Slim 4.x to use `guzzlehttp/psr7` **version 1.x**.

## Rationale

Slim removed support for the 1.x line of `guzzlehttp/psr7` in version 4.9.0.
I was using `aws/aws-sdk-php`, which has a hard requirement on the 1.x line.

The PSR-7 and PSR-17 implementations are meant to be pluggable in Slim, so the
supported version is not expressed through Composer.  Therefore, a `composer
update` accepted the older `guzzlehttp/psr7` version and newer version of Slim
which did not support it, causing the app to fail at run-time.

## Installation and Usage

	composer require sapphirecat/slim4-http-interop-adapter

Dependencies and autoloading are both configured so that installation of the
package is the only necessary action.  Using the autoloader will register the
necessary support with Slim's `Psr17FactoryProvider`.

## License

MIT, copied verbatim from Slim 4.8.1.
