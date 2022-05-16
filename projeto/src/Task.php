<?php

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Doctrine\DBAL\Connection;

class Task
{

    private Connection $connection;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connection = $container->get(Connection::class);
    }

    public function add(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $ret = [
            "error" => true,
            "msg" => "Erro",
            "code" => 412
        ];

        $data = (array)$request->getParsedBody();

        $values = $data;

        if ($this->connection->insert('task', $values)) {
            $ret['error'] = false;
            $ret['msg'] = "Ok";
            $ret['code'] = 201;
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $ret = [
            "error" => true,
            "msg" => "Não foi possível salvar.",
            "code" => 412
        ];

        $data = (array)$request->getParsedBody();

        if (isset($data['id'])) {
            $id = $data['id'];
            $values = $data;

            if ($this->connection->update('task', $values, ["id" => $id])) {
                $ret['error'] = false;
                $ret['msg'] = "Ok";
                $ret['code'] = 200;
            }
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface { 
        $ret = [
            "error" => true,
            "msg" => "Erro",
            "code" => 412
        ];

        $id = $args['id'];
        if(!empty($id)) {
            if ($this->connection->delete('task', ["id" => $id])) {
                $ret['error'] = false;
                $ret['msg'] = "Ok";
                $ret['code'] = 200;
            }
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select('id, descricao, data_expiracao, data_conclusao, user_id')
            ->from('task')
            ->where("user_id = :user_id")
            ->orderBy("id", "DESC")
            ->setParameter("user_id", $_SESSION['user_id'])
            ->execute()
            ->fetchAll();

        $response->getBody()->write(json_encode($rows));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function listByUserId(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $query = $this->connection->createQueryBuilder();

        $rows = $query
            ->select('id, descricao, data_expiracao, data_conclusao, user_id')
            ->from('task')
            ->where("user_id = :user_id")
            ->setParameter("user_id", $args['id'])
            ->execute()
            ->fetchAll();

        $response->getBody()->write(json_encode($rows));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function view(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $ret = [
            "error" => true,
            "data" => [],
            "code" => 412
        ];

        $id = $args['id'];
        if(!empty($id)) {
            $query = $this->connection->createQueryBuilder();
    
            $rows = $query
            ->select('id, descricao, data_expiracao, data_conclusao, user_id')
                ->from('task')
                ->where('id = :id')
                ->setParameter("id", $id)
                ->execute()
                ->fetchAll();
            
            if(count((array) $rows)) {
                $ret = [
                    "error" => false,
                    "data" => $rows[0],
                    "code" => 200
                ];
            }
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }

    public function concluir(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $ret = [
            "error" => true,
            "msg" => "Erro.",
            "code" => 412
        ];

        $query = $this->connection->createQueryBuilder();
        $id = $args['id'];

        if(!empty($id)) {
            $rows = $query
                ->select('id')
                ->from('task')
                ->where("user_id = :user_id AND id = :id")
                ->setParameter("id", $id)
                ->setParameter("user_id", $_SESSION['user_id'])
                ->execute()
                ->fetchAll();

            if(count((array) $rows)) {
                $values = [
                    "data_conclusao" => date("Y-m-d H:i:s")
                ];

                if ($this->connection->update('task', $values, ["id" => $id])) {
                    $ret['error'] = false;
                    $ret['msg'] = "Ok";
                    $ret['code'] = 200;
                }
            }
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }
}
