<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \App\Action\HomeAction;
use \App\User;
use \App\Task;
use Slim\App;
use Tuupola\Middleware\HttpBasicAuthentication\AuthenticatorInterface;
use Tuupola\Middleware\HttpBasicAuthentication;
use Psr\Container\ContainerInterface;
use Doctrine\DBAL\Connection;

class AuthAppUser implements AuthenticatorInterface
{

    private Connection $connection;
    private $container;
    private $app;

    public function __construct(ContainerInterface $container, $app)
    {
        $this->container = $container;
        $this->app = $app;
        $this->connection = $container->get(Connection::class);
    }

    public function __invoke(array $arguments): bool
    {
        $ret = User::checkUserPass($arguments, $this->connection);
        if($ret) {
            //setar session global
            $_SESSION['user_id'] = $ret;
        }
        return $ret;
    }
}

class AuthAppAdmin implements AuthenticatorInterface
{

    private Connection $connection;
    private $container;
    private $app;

    public function __construct(ContainerInterface $container, $app)
    {
        $this->container = $container;
        $this->app = $app;
        $this->connection = $container->get(Connection::class);
    }

    public function __invoke(array $arguments): bool
    {
        $ret = User::checkUserPass($arguments, $this->connection, true);
        if($ret) {
            //setar session global
            $_SESSION['user_id'] = $ret;
        }
        return $ret;
    }
}

$app->add(new HttpBasicAuthentication([//todos usuarios
    "path" => ["/task"],
    "realm" => "Protected",
    "authenticator" => new AuthAppUser($app->getContainer(), $app)
]));
$app->add(new HttpBasicAuthentication([//admin
    "path" => ["/admin"],
    "realm" => "Protected",
    "authenticator" => new AuthAppAdmin($app->getContainer(), $app)
]));

return function (App $app) {//rotas

    //publica
    $app->post('/login', User::class . ':login');

    //admin
    $app->post('/admin/users/add', User::class . ':add');
    $app->get('/admin/users', User::class . ':list');
    $app->get('/admin/users/{id:[0-9]+}', User::class . ':view');
    $app->put('/admin/users/edit', User::class . ':edit');
    $app->delete('/admin/users/{id:[0-9]+}', User::class . ':delete');

    $app->get('/admin/task/{id:[0-9]+}', Task::class . ':listByUserId');
    $app->post('/admin/task/add', Task::class . ':add');
    $app->put('/admin/task/edit', Task::class . ':edit');
    $app->delete('/admin/task/{id:[0-9]+}', Task::class . ':delete');

    //usuario
    $app->get('/task', Task::class . ':list');
    $app->get('/task/concluir/{id:[0-9]+}', Task::class . ':concluir');
    
    
};
