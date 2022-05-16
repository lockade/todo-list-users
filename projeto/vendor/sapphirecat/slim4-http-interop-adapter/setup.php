<?php

declare(strict_types=1);

use Slim\Factory\Psr17\Psr17FactoryProvider;

Psr17FactoryProvider::addFactory('Sapphirecat\\Slim4HttpInteropAdapter\\GuzzlePsr7v1Factory');
