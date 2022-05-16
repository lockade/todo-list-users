<?php

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Doctrine\DBAL\Connection;

class User
{

    private Connection $connection;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connection = $container->get(Connection::class);
    }

    public static function checkUserPass($data, Connection $conn, $admin = false)
    {
        $ret = false;
        if (!empty($data['user']) && !empty($data['password'])) {
            //procurar se existe basic auth no banco.
            $query = $conn->createQueryBuilder();

            $query
                ->select('id')
                ->from('users')
                ->where("username = :username AND senha = :senha")
                ->setParameter("username", $data['user'])
                ->setParameter("senha", $data['password']);

            if ($admin) {
                $query
                    ->andWhere("admin = 1");
            }

            $rows = $query
                ->execute()
                ->fetchAll();

            if (count((array) $rows)) {
                $ret = $rows[0]['id'];
            }
        }

        return $ret;
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $ret = [
            "error" => true,
            "msg" => "Usuário/Senha Inválidos.",
            "code" => 412
        ];

        $data = (array)$request->getParsedBody();

        $valid = self::checkUserPass($data, $this->connection);
        if ($valid) {
            $ret = [
                "error" => false,
                "msg" => "Ok",
                "code" => 200,
                "admin" => $this->checkAdmin($valid)
            ];
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }

    private function checkAdmin($user_id) {
        $ret = false;
        if (!empty($user_id)) {
            //procurar se existe basic auth no banco.
            $query = $this->connection->createQueryBuilder();

            $query
                ->select('id')
                ->from('users')
                ->where("id = :id AND admin = 1")
                ->setParameter("id", $user_id);

            $rows = $query
                ->execute()
                ->fetchAll();

            if (count((array) $rows)) {
                $ret = true;
            }
        }

        return $ret;
    }

    public function add(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
        $ret = [
            "error" => true,
            "msg" => "Erro",
            "code" => 412
        ];

        $data = (array)$request->getParsedBody();

        $valida = $this->valida($data);
        if ($valida === true) {
            $values = [
                'username' => $data['username'],
                'nome' => $data['nome'],
                'senha' => $this->crypSenha($data['senha']),
                'telefone' => $data['telefone'],
                'genero' => $data['genero'],
                'email' => $data['email'],
            ];

            if ($this->connection->insert('users', $values)) {
                $ret['error'] = false;
                $ret['msg'] = "Ok";
                $ret['code'] = 201;
            }
        } else {
            $ret['msg'] = $valida['msg'];
            $ret['code'] = $valida['code'];
        }

        $response->getBody()->write(json_encode($ret));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($ret['code']);
    }

    public function edit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface {
        $ret = [
            "error" => true,
            "msg" => "Erro",
            "code" => 412
        ];

        $data = (array)$request->getParsedBody();
        // $data['id'] = $args['id'];

        $valida = $this->valida($data);

        if ($valida === true && isset($data['id'])) {
            $id = $data['id'];
            $values = $data;
            if(isset($values['senha'])) {
                $values['senha'] = $this->crypSenha($values['senha']);
            }

            if ($this->connection->update('users', $values, ["id" => $id])) {
                $ret['error'] = false;
                $ret['msg'] = "Ok";
                $ret['code'] = 200;
            }
        } else {
            $ret['msg'] = $valida['msg'];
            $ret['code'] = $valida['code'];
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
            if ($this->connection->delete('users', ["id" => $id])) {
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
            ->select('id, nome, email, telefone, genero, username, admin')
            ->from('users')
            ->orderBy("id", "DESC")
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
                ->select('id, nome, email, telefone, genero, username, admin')
                ->from('users')
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

    private function crypSenha(String $senha)
    {
        $ret = null;

        if (!empty($senha)) {
            $ret = sha1($senha);
        }

        return $ret;
    }

    private function valida(array $data)
    {
        $msg = null;
        $code = null;
        if (empty($data)) {
            $msg = "Sem dados.";
            $code = 412;
        } else if (empty($data['username'])) {
            $msg = "Usuário Inválido.";
            $code = 412;
        } else if ($this->verificaExist($data['username'], isset($data['id']) ? $data['id'] : null)) {
            $msg = "Usuario já existe";
            $code = 412;
        } else if (empty($data['nome'])) {
            $msg = "Usuario sem nome.";
            $code = 412;
        } else if (empty($data['senha']) && !isset($data['id'])) {
            $msg = "Usuario sem senha.";
            $code = 412;
        }

        if ($code != null)
            return ["msg" => $msg, "code" => $code];
        return true;
    }

    private function verificaExist($username, $id = null)
    {
        $query = $this->connection->createQueryBuilder();
    
        $query
            ->select('id')
            ->from('users')
            ->where("username = :username")
            ->setParameter("username", $username);
        
        if(!empty($id)) {
            $query
                ->andWhere("id != :id")
                ->setParameter("id", $id);
        }
        
        $rows = $query
            ->execute()
            ->fetchAll();

        if(count((array) $rows)) {
            return true;
        }
        return false;
    }
}
