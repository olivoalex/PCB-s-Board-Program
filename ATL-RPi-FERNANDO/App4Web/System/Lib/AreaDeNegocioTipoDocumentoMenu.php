<?php
class AreaDeNegocioTipoDocumentoMenu extends Model{
      public $_entidade;
      private $_menu;
      private $_menu_mnu_id; 
      private $_menu_opc_id; 
      private $_menu_prg_id; 
      private $_menu_nva_id;
      private $_menu_adn_id;
      private $_menu_ntd_id;
      private $_menu_tpd_id;
      private $_as_texto;
      private $_opc_link;
      
      public function loadMenuEntidade($_mnu_id, $_opc_id, $_prg_id, $_nva_id, $_adn_id, $_ntd_id, $_tpd_id, $_a_entidade) {
      	
      	$_menu_header = "Documento";
      	
      	// Se nao informada coloca ZERO
      	$this->_menu_mnu_id = empty($_mnu_id) ? 0 : $_mnu_id;
      	$this->_menu_opc_id = empty($_opc_id) ? 0 : $_opc_id;
      	$this->_menu_prg_id = empty($_prg_id) ? 0 : $_prg_id;
      	$this->_menu_nva_id = empty($_nva_id) ? 0 : $_nva_id;      	
      	$this->_menu_adn_id = empty($_adn_id) ? 0 : $_adn_id;
      	$this->_menu_ntd_id = empty($_ntd_id) ? 0 : $_ntd_id;
      	$this->_menu_tpd_id = empty($_tpd_id) ? 0 : $_tpd_id;
      	$this->_entidade    = empty($_a_entidade) ? 0 : $_a_entidade;
      	     
      	$_sql = "SELECT IFNULL(adn.adn_id,0) adn_id, adn.adn_descr, adn.adn_cod, adn.adn_ordem,"
      		  .       " IFNULL(ntd.ntd_id,0) ntd_id, ntd.ntd_descr, ntd.ntd_cod, ntd.ntd_ordem," 
              .       " IFNULL(tpd.tpd_id,0) tpd_id, tpd.tpd_descr, tpd.tpd_cod, tpd.tpd_ordem,"
              .       " IFNULL(prg.prg_id,0) prg_id, prg.prg_descr, prg.prg_cod, prg.prg_link, IFNULL(prg.apl_id,0) apl_id, "
              .		  " IFNULL(prg.mdl_id,0) mdl_id,  IFNULL(prg.nva_id,0) nva_id "
              . " FROM entidade_areadenegocio ead,"
              .      " area_de_negocio adn LEFT JOIN tipo_documento tpd on tpd.adn_id = adn.adn_id and tpd.tpd_ativo = 'S' "
              // NTP
              .                          " LEFT JOIN natureza_documento ntd ON ntd.ntd_id = tpd.ntd_id and ntd.ntd_ativo = 'S' "
              //. ( $_ntd_id != 0 ? " AND ntd.ntd_id = {$_ntd_id}" : "")
              // TPD
              .                          " LEFT JOIN programa prg ON prg.prg_id = tpd.prg_id and prg.prg_ativo = 'S' "
              //. ( $_tpd_id != 0 ? " AND tpd.tpd_id = {$_tpd_id}" : "")
              . " WHERE ead.adn_id = adn.adn_id "
              .   " AND ead.ent_id = ". $_a_entidade
              .   " AND adn.adn_ativo = 'S' "
              .   " AND ead.ead_ativo = 'S' ";
              // ADN              		
              //. ( $_adn_id != 0 ? " AND adn.adn_id = {$_adn_id}" : "");
      			
        $_sql .=" ORDER BY adn.adn_ordem, adn.adn_descr, ntd.ntd_ordem, ntd.ntd_descr, tpd.tpd_descr, prg.prg_descr";
      	     	
      	$_mnu = $this->freeHandSQL($_sql);

//echo "<br>SQL: {$_sql}";      	

      	$_txt = "<br>";
      	
      	$_menu = "<ul class='sidebar-menu'>".
      	         "<li class='header'>{$_menu_header}</li>".
      		     "<!-- Optionally, you can add icons to the links -->";
      	
      	// Area de negocio
      	$_adn_ant   = "NONE";
      	$_adn_ctd   = 0;
      	$_adn_atual = "NONE";
      	
      	// Natureza do tipo de Documento
      	$_ntd_ant   = "NONE";
      	$_ntd_ctd   = 0;
      	$_ntd_atual = "NONE";
      	
      	// Tipo Documento
      	$_tpd_ant   = "NONE";
      	$_tpd_ctd   = 0;
      	$_tpd_atual = "NONE";
      	
      	for ($ind =0; $ind < count($_mnu) ; $ind++) {
      		
      		$_txt .= "<br>Pos: {$ind}, Valor: {$_mnu[$ind]['adn_descr']}";

 // echo "<br>....................... Pos: {$ind}, Valor: {$_mnu[$ind]['adn_descr']}";

      		$_adn_atual = $_mnu[$ind]['adn_id'];
      		$_ntd_atual = $_mnu[$ind]['ntd_id'];
      		$_tpd_atual = $_mnu[$ind]['tpd_id'];
      		
      		// Arega de Negocio mudou e nao eh a 1a ?
      		if ( $_adn_ant != $_adn_atual ) {

      			// Natureza tipo Documento, se teve tipo de documento abaixo
      			if ($_tpd_ctd != 0 ) {
      				// Fecha o Anterior
      				if ($_tpd_ant != 0 ) {
      					$_menu .= "</ul>";
      				}
      				$_menu .= "</li>";
      				$_tpd_ctd = 0;
      		   }
      		
      		   // Area de Negocio se teve natuireza documento abaixo
      		   if ($_ntd_ctd != 0 ) {
      		   	// Fecha o Anterior
      		   	if ( $_ntd_ant != 0 ) {
      		   		$_menu .= "</ul>";
      		   	}
      		   	$_menu .= "</li>";
      		   	$_ntd_ctd = 0;
      		   }      		   
      		   
      		   // Sempre Abre uma Nova ?
      		   //if ( $_adn_ctd == 0 ) {      		   	 
      		   	 // Abre o Novo
      		   	 $_adn_descr = $_mnu[$ind]['adn_descr'];
      		   	 $_adn_csstexto = "";
      		   	 $_adn_active = "";
      		   	 if ( $_adn_atual == $_adn_id ) {
      		    		$_adn_active = " active ";
      		     }
      		     $_menu .= "<li class='treeview {$_adn_active} '>".
      		   			   "<a href='#'><i class='{$_adn_csstexto}'></i>".
      		   		  	   "<span>{$_adn_descr}</span><i class='fa fa-angle-left pull-right'></i></a>";
      		   			   
      		   //}
      		   
      		   $_adn_ctd++;
      		   
      		   if ( $_ntd_atual != 0 ) {
      		   	 $_menu .= "<ul class='treeview-menu'>";
      		   } else { 
      		   	 // Para otimizar se o NTD for ZERO indica que nao tem nada abaixo
      		   	 // forca um novo ADN
      		   	 continue;
      		   }
      		}
      		
      		// Natureza Tipo Documento Mudou e nao eh a 1a.
      		if ( $_ntd_ant != $_ntd_atual ) {
      			
      			// Sempre fecha a lista abaixo do agrupamento primeiro
      			// Indica que teve linhas abaixo dele, fechar
      			// Natureza Tipo Documento
      			if ($_tpd_ctd != 0 ) {
      				
      		       // Fecha o Anterior
      		       if ( $_ntd_ant != 0 ) {
      			      $_menu .= "</ul>";
      		       }
      			   $_menu .= "</li>";
      			   $_tpd_ctd = 0;      			   
      			}
      			
      			//if ($_ntd_ctd == 0 ) {
      			   // Abre o Novo
      			   $_ntd_descr = $_mnu[$ind]['ntd_descr'];
      			   $_ntd_csstexto = "";
      			   $_ntd_active = "";
      			   if ( $_ntd_atual == $_ntd_id ) {
      			  	  $_ntd_active = " active ";
      			   }
      			   $_menu .= "<li class='treeview {$_ntd_active} '>".
      					     "<a href='#'><i class='{$_ntd_csstexto}'></i>".
      					     "<span>{$_ntd_descr}</span><i class='fa fa-angle-left pull-right'></i></a>";
      			//}
      			
      			$_ntd_ctd++;
      			
      			if ( $_tpd_atual != 0 ) {
      				$_menu .= "<ul class='treeview-menu'>";
      			} else {
      				// Para otimizar se o NTD for ZERO indica que nao tem nada abaixo
      				// forca um novo ADN
      				continue;
      			}
      		}
      		
      		// Tipo de Documento eh no nivel que esta aberto :)
      		if  ( $_tpd_atual != 0 ) {
      			$_tpd_descr = $_mnu[$ind]['tpd_descr'];
      			$_tpd_csstexto = "";
      			$_prg_id = $_mnu[$ind]['prg_id'];
      			$_tpd_active = "";
      			 
      			// Montar link com o definido pelo programa e adicionar o ID do menu e da opcao/programa
      			// nao por na tabela de  programa o id no link vou fazer isso aqui :)
      			$_tpd_link = $_mnu[$ind]['prg_link'].
      			             "/mnu/".$_mnu_id.
      			             "/opc/".$_opc_id.
      			             "/mnv/0". // Nivel de Menu RAIZ=0 ou SUB/MENU=1
      			             "/idf/0".
      			             "/prg/".$_prg_id.
      			             "/nva/".$_nva_id.
      			             "/adn/".$_adn_atual.
      			             "/ntd/".$_ntd_atual.
      			             "/tpd/".$_tpd_atual;

      			if ( $_tpd_atual == $_tpd_id ) {
      				$_tpd_csstexto = "fa fa-arrow-right list_selected";
      				$_tpd_active = "";
      			}
      			
                $_tpd_link = $this->_opc_link . $_tpd_link;

                //echo "<br>----------------------------------------------Link: {$_tpd_link}";
                
      			$_menu .= "<li class='{$_tpd_active}'><a href='{$_tpd_link}'><i class='{$_tpd_csstexto}'></i><span>{$_tpd_descr}</span></a></li>";      			
      		    $_tpd_ctd++;
      		}
      		
      		// Agira mudando de linha :)
      		$_adn_ant = $_adn_atual;
      		$_ntd_ant = $_ntd_atual;
      		$_tpd_ant = $_tpd_atual;
        }
        
        // Se saiu e algum contador estiver diferente de ZERO eh pq precisa fechar
        // Natureza tipo DOcumento
        if ($_tpd_ctd != 0 ) {
        	// Fecha o Anterior
        	if ( $_tpd_ant != 0 ) {
        		$_menu .= "</ul>";
        	}
        	$_menu .= "</li>";
        }
        
        // Area deNegocio
        if ($_ntd_ctd != 0 ) {
        	// Fecha o Anterior
        	if ( $_ntd_ant != 0 ) {
        	   $_menu .= "</ul>";
        	}
        	$_menu .= "</li>";        
        }
              
      	// finalizando o SideBar
      	$_menu .= "</ul>"; 
      	    	      	
      	$this->_as_texto = $_txt;

      	$this->_menu = $_menu;
      }
      
      public function showMenu() {
      	echo $this->_menu;
      }
      
      public function setOpcaoLink($_valor) {
      	$this->_opc_link = $_valor;
      	//echo "<script>alert('Valor Link eh {$_valor}');</script>";
      }
      
      public function getMenu() {
      	return $this->_menu;
      }
      
      public function getAsText() {
      	return $this->_as_texto;
      }
      
      public function getOpcaoLink() {
      	return $this->_opc_link;
      }
}
?>