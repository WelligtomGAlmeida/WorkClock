<?php
namespace Login\Model;

use Zend\Db\Sql\Sql;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

class Sessao {

    public $adapter = null;

    public function __construct($adapter){
        $this->adapter = $adapter;
    }

    public function login($dados){

      
     

            ## Serviço de autenticar e manter a sessão
            $auth = new AuthenticationService();

            ## Tabela de credenciais
            $authAdapter = new AuthAdapter($this->adapter);
            $authAdapter->setTableName('funcionarios')
                        ->setIdentityColumn('user_name')
                        ->setCredentialColumn('senha');

            ## Dados do formulário
            $authAdapter->setIdentity($dados["login"])
                        ->setCredential($dados["senha"]);

            $auth->setAdapter($authAdapter);

            ## Sessão de autenticação
            $sessionStorage = new SessionStorage('painel');
            $auth->setStorage($sessionStorage);
            
            try {
                $result = $auth->authenticate($authAdapter);
            } catch ( \Exception $ex ){
                var_dump($ex->getMessage());
                //return array("Erro ao realizar autenticação", $ex->getMessage(), array() , Mensagem::Danger);
            }
            

            if ( $result->isValid() ) {

                return array("Login realizado com sucesso!");
            
            } else {
            
                //
                return array("Usuário ou senha incorreto!", "Usuário ou senha incorreto!");
            
            }

    }

    public function logout(){

        $sessionStorage = new SessionStorage('painel');

        $auth = new AuthenticationService($sessionStorage);

        if ($auth->hasIdentity()) { 
            $auth->clearIdentity(); 
        } 

    }

}