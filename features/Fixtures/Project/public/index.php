<?php

use KunicMarko\SonataAnnotationBundle\Features\Fixtures\Project\Kernel;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'../../../../../vendor/autoload.php';

umask(0000);

$kernel = new Kernel('test', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
