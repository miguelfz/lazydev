<?php
namespace Lazydev\Controller;

use Lazydev\Core\Criteria;
use Lazydev\Model\Municipio;
use Lazydev\Model\Categoria;

class Inicio extends \Lazydev\Core\Controller{
    
    public function inicio() {
        $this->setTitle('Início');
        $c = new Criteria(['paginate' => [10, 'p']]);
        if($this->getParam('pesquisa')){
            $c->addCondition('nome','like', '%'.$this->getParam('pesquisa').'%');
        }
        $municipios = Municipio::getList($c);
        $this->set('municipios',$municipios);
        $this->set('p', $this->getParam('p'));   
        
        $nome = 'Pedro';
        echo "Meu nome é $nome!";
    }

    public function verImoveis() {
        $this->setTitle('ver Imóveis');
        $c = new Criteria();
        $municipio = new Municipio($this->getParam(0));
        $this->set('imoveis', $municipio->getImoveis());     
        $this->set('municipio', $municipio);     
        $this->set('p', $this->getParam(1));            
    }

    public function cadastrar(){
        $this->setTitle("Nova categoria");
    }

    public function post_cadastrar(){
        $categoria = new Categoria();
        $categoria->nome = filter_input(INPUT_POST, 'nome');
        $categoria->save();
    }

}