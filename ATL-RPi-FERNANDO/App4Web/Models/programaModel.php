<?php

  class programaModel extends Model {

     public function init() {

        //Identificando a tabela a ser manipulada

        $this->_tabela_nome = "programa";

        $this->load();

     }

     public function listaPrograma($qtd, $offset = null) {
        return $this->read(null, $qtd, $offset, '1 ASC'); 
     }

     public function totalPrograma($where = null) {
        return $this->count($where);
     }
  }
?>
