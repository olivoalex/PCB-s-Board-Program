<?php
class Menu extends Model{
      public $_entidade;
      private $_menu;
      private $_menu_header;
      private $_opcao_active;
      private $_menu_nivel;
      private $_as_texto;
      private $_opc_link;
      
      public function loadMenuEntidade($_action, $_menu_header, $_opcao_active, $_menu_nivel, $_entidade, $_mnu_sel, $_opc_sel, $_prg_sel) {
      	
      	$this->_menu_header = $_menu_header;
      	
      	$this->_opcao_active = $_opcao_active;
      	
      	// Determinando segundo nivel para o menu
      	// Fique atento pois o menu tem que pegar as opcoes para essa opcao como menu :) kkk te deixei confuso neh.
      	// mas eh isso mesmo kkkk
      	$_mnu_nivel = 0;
      	
      	//echo "<script>alert('****ANTES****  Nivel: {$_menu_nivel} e Opcao Ativa: {$_opcao_active} -> novo nivel: {$_mnu_nivel}');</script>";
      	
      	// Se tiver montando o Menu, desconsidera a opcao pois ela sera apenas marcada como ultima acionada
      	// Forca nao abria a opcao e sim apenas indicar q ela foi a ultima,,, repetido neh kkk
      	if ( $_action == "menu" ) {
      		$_opcao_active = 0;      		
      		$_menu_nivel = 0;
      	} else {
      		if ( $_menu_nivel == 0 ) {
      			// forca marcar a peimeira como selcionada
      			$_opc_sel = 0;
      		}
      	}
      	
      	if ( $_opcao_active != 0 ) {      		 	
      		if ( $_menu_nivel == 1 ) {
      			// Se ja for 1, coloca a opcao para o 3o nivel de menu dentro da opcao manter o anterior
      			// bagunçado neh mas eh isso mesmo kkk
      			$_mnu_nivel = $_opcao_active;
      		} else {
      			if ( $_menu_nivel == 0 ) {
      				$_mnu_nivel = $_opcao_active;;
      			} else {
      				$_mnu_nivel = $_menu_nivel;
      			}
      		}
      		$_menu_nivel = 1;
      	}      	       	

      	$this->_menu_nivel   = $_menu_nivel;
      	      	
      	$this->_entidade = $_entidade;
      	
      	//echo "<script>alert('Action: {$_action}, Nivel: {$_menu_nivel} e Opcao Ativa: {$_opcao_active} -> novo nivel: {$_mnu_nivel}');</script>";
      	     	
      	$_sql = " SELECT DISTINCT tpm.tpo_ordem tpm_ordem, mno.mno_id, mnu.opc_cod mnu_cod, tpm.tpo_cod tpm_cod, mnu.opc_descr mnu_descr, mnu.opc_csstexto mnu_csstexto,".
      	        "  opc.opc_cod , tpo.tpo_cod, opc.opc_descr, opc.opc_csstexto, mno.mnu_ordem, opc.prg_id, ".
      	        "  prg_descr, prg.mdl_id, prg.mdl_ordem, prg.prg_link, IFNULL(mno.mno_nivel,0) mnu_nivel, IFNULL(mno.nva_id,0) nva_id, ".
      	        "  IFNULL(mno.mnu_id,0) mnu_id, IFNULL(mno.opc_id,0) opc_id, IFNULL(opc.apl_id,0) apl_id, IFNULL(opc.mdl_id,0) mdl_id, IFNULL(opc.prg_id,0) prg_id ".
      	        " FROM menu mno, opcao mnu, tipo_opcao tpm, ".
      	        "  opcao opc RIGHT JOIN entidade_programa prg ON opc.prg_id = prg.prg_id AND prg.ent_id = ". $_entidade.", ".
      	        "  tipo_opcao tpo ". 
                " WHERE mno.mnu_id = mnu.opc_id ".
                "   AND mnu.tpo_id = tpm.tpo_id ".
                "   AND mno.opc_id = opc.opc_id ".
                "   AND opc.tpo_id = tpo.tpo_id ".
                "   AND mno.mno_nivel = ". $_menu_nivel;
      	
        if ( $_opcao_active != 0 ) {
           $_sql .= " AND mno.mnu_id = " . $_mnu_nivel;
        }                

        //$_sql .=" ORDER BY tpm_ordem, prg.mdl_ordem, mnu_ordem, prg.prg_descr ";
        $_sql .=" ORDER BY tpm_ordem, mnu_ordem, prg.prg_descr ";
      	     	
      	$_mnu = $this->freeHandSQL($_sql);
      	
      	$_txt = "<br>";
      	
      	$_menu = "<ul class='sidebar-menu'>".
      	         "<li class='header'>{$_menu_header}</li>".
      		     "<!-- Optionally, you can add icons to the links -->";
      	
      	$_mnu_ant = "NONE";
      	$_mnu_ctd = 0;
      	$_mnu_open = false;
      	
      	// Varre a lista selecionada pra ver se tem nivel 1 abaixo se tiver, traz aberto
      	// provavelmente deve ser um sub-menu dentro de um novo programa/opcao
      	$_mnu_tem_nivel_um_abaixo = false;
      	for ($ind =0; $ind < count($_mnu) ; $ind++) {
      	   if ( $_mnu[$ind]['mnu_nivel'] == 1 ) {
      	   	$_mnu_tem_nivel_um_abaixo = true;
      	   	break;
      	   }
      	}
      	
      	for ($ind =0; $ind < count($_mnu) ; $ind++) {
      		$_txt .= "<br>Pos: {$ind}, Valor: {$_mnu[$ind]['prg_descr']}";
      		
      		$_mnu_atual = $_mnu[$ind]['mnu_cod'];
      		if ( $_mnu_ant != $_mnu_atual ) {
      		   if ($_mnu_ctd > 0 ) {
      		     // Fechar o menu anterior
      		     $_menu .= "</ul>".
      		               "</li>";
      		     $_mnu_open = false; // Fechei uma opcao de menu
      		   }
      		   $_mnu_descr = $_mnu[$ind]['mnu_descr'];
      		   $_mnu_csstexto = $_mnu[$ind]['mnu_csstexto'];
      		   $_menu_active = "";
      		   if ( $_mnu_tem_nivel_um_abaixo == true ) {
      		   	$_menu_active = " active ";
      		   }

      		   // Se estiver zerado, abre o primeiro menu
      		   if ( $_mnu_sel == 0 ) {
      		   	 $_mnu_sel = $_mnu[$ind]['mnu_id'];
      		   }
      		   
      		   //echo "<br>------------------------- mnu -----> {$_mnu_sel} --------> ". $_mnu[$ind]['mnu_id'];
      		   
      		   // Eh o menu selecionado ?
      		   if ( $_mnu_sel != 0 && $_mnu_sel == $_mnu[$ind]['mnu_id']) {
      		   	$_menu_active = " active ";
      		   }
      		   
      		   $_menu .= "<li class='treeview {$_menu_active} '>".
      		             "<a href='#'><i class='{$_mnu_csstexto}'></i>".
      		             "<span>{$_mnu_descr}</span><i class='fa fa-angle-left pull-right'></i></a>".
      		             "<ul class='treeview-menu'>";
      		   //echo "<br>Menu: {$_mnu_descr}";
      		   $_mnu_ant = $_mnu_atual;
      		   $_mnu_open = true; // Abrimos uma nova opcao de mennu
      		   $_mnu_ctd++;
      		}
      		// Colocando as opcoes no menu aberto
      		if ( $_mnu_open == true ) {
      			$_opc_descr = $_mnu[$ind]['opc_descr'];
      			$_opc_csstexto = $_mnu[$ind]['opc_csstexto'];
      			
      			// Montar link com o definido pelo programa e adicionar o ID do menu e da opcao/programa
      			// nao por na tabela de  programa o id no link vou fazer isso aqui :)
      			$_opc_link = $_mnu[$ind]['prg_link'].
      			             "/mnu/".$_mnu[$ind]['mnu_id'].
      			             "/opc/".$_mnu[$ind]['opc_id'].
      			             "/mnv/".$_mnu_nivel.  // Nivel de Menu RAIZ=0 ou SUB/MENU=1
      			             "/idf/0".
      			             "/prg/".$_mnu[$ind]['prg_id'].
      			             "/nva/".$_mnu[$ind]['nva_id'];

      			//echo "<br>Opcao: {$_opc_descr}";
      			//echo "Link: ".$this->_opc_link;
      			//echo "<script>alert('Prg.Link: ".$_opc_link."');</script>";
      			
      			$_mnu_link = $this->_opc_link . $_opc_link;
      			
      			// Eh o menu selecionado ?
      			$_opcao_active = "";
      			$_opcao_csstexto = "";
      			
      			// Se estiver zerado, abre o primeiro menu
      			if ( $_opc_sel == 0 ) {
      				$_opc_sel = $_mnu[$ind]['opc_id'];
      			}
      			
      			//echo "<br>------------------------- mnu -----> {$_mnu_sel} --------> ". $_mnu[$ind]['mnu_id'];
      			//echo "<br>------------------------- opc -----> {$_opc_sel} --------> ". $_mnu[$ind]['opc_id'];
      			//echo "<br>------------------------- pog -----> {$_prg_sel}";
      					
      			if ( $_opc_sel != 0 && $_opc_sel == $_mnu[$ind]['opc_id']) {
      				$_opcao_active = "";
      				$_opcao_csstexto = "fa fa-arrow-right list_selected";
      				//echo "<br><br>------------------------- achou -----> {$_opc_sel} --------> ". $_mnu[$ind]['opc_id']."<br>";
      			}
      			
      			$_menu .= "<li><a href='{$_mnu_link}'><i class='{ $_opcao_csstexto}'></i><i class='{$_opc_csstexto}'></i><span>{$_opc_descr}</span></a></li>";
      		}      		
      	}
      	if ( $_mnu_open == true ) {
      	   // Fechar o menu que esta aberto
      	   $_menu .= "</ul>".
      	   		     "</li>";
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