<?php
class UserAccountMenu {
      protected $_avatar;
      protected $_nome;
      protected $_nome_complemento;
      protected $_membro_desde;
      protected $_link_dominio;
      
      
   	  public function getUserAccountMenu() {
   	  	
   	  	  //echo "<script>alert('Linkk-Dominio: {$this->_link_dominio}');</script>";
   	  	  
	      return "<!-- User Account Menu -->
	              <li class='dropdown user user-menu'>
	                <!-- Menu Toggle Button -->
	                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
	                  <!-- The user image in the navbar-->
	                  <img src='{$this->_avatar}' class='user-image' alt='User Image'>
	                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
	                  <span class='hidden-xs'>{$this->_nome}</span>
	                </a>
	                <ul class='dropdown-menu'>
	                  <!-- The user image in the menu -->
	                  <li class='user-header'>
	                    <img src='{$this->_avatar}' class='img-circle' alt='User Image'>
	                    <p>
	                      {$this->_nome}                      
	                      <small>{$this->_nome_complemento}</small>
	                      <small>Membro desde, {$this->_membro_desde}</small>
	                    </p>
	                  </li>
	                  <!-- Menu Body -->
	                  <li class='user-body'>
	                    <div class='col-xs-6 text-center'>
	                      <a href='{$this->_link_dominio}mensagens/index/index'><i class='fa fa-envelope-o fa-fw'>&nbsp;</i>Mensagens</a>
	                    </div>
	                    <div class='col-xs-6 text-center'>
	                      <a href='{$this->_link_dominio}notificacoes/index/index'><i class='fa fa-newspaper-o fa-fw'>&nbsp;</i>Notifica&#231;&#245;es</a>
	                    </div>                   
	                  </li>
	                  <!-- Menu Footer-->
	                  <li class='user-footer'>
	                    <div class='pull-left'>
	                      <a href='{$this->_link_dominio}profile/index/index' class='btn btn-default btn-flat'><i class='fa fa-user fa-fw'>&nbsp;</i>Profile</a>
	                    </div>
	                    <div class='pull-right'>
	                      <a href='{$this->_link_dominio}admin/logout/auth' class='btn btn-default btn-flat'><i class='fa fa-sign-out fa-fw'>&nbsp;</i>Sair</a>
	                    </div>
	                  </li>
	                </ul>
	              </li>";
   	}
   	  
	public function getAvatar() {
		return $this->_avatar;
	}
	public function setAvatar($_avatar) {
		$this->_avatar = $_avatar;
	}
	public function getNome() {
		return $this->_nome;
	}
	public function setNome($_nome) {
		$this->_nome = $_nome;
	}
	public function getNomeComplemento() {
		return $this->_complemento_nome;
	}
	public function setNomeComplemento($_nome_complemento) {
		$this->_nome_complemento = $_nome_complemento;
	}
	public function getMembroDesde() {
		return $this->_membro_desde;
	}
	public function setMembroDesde($_membro_desde) {
		$this->_membro_desde = $_membro_desde;
	}
	public function getLinkDominio() {
		return $this->_link_dominio;
	}
	public function setLinkDominio($_link_dominio) {
		$this->_link_dominio = $_link_dominio;
	}
	
}
?>