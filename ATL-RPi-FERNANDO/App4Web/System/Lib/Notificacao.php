<?php
class Notificacao extends Model{
      public $_entidade;
      private $_notificacao;
      private $_notificacao_header;
      private $_notificacao_active;
      private $_as_texto;
      private $_notificacao_link;  // Com dominio
      private $_notificacao_link_portal; // Ate o erp4web
      
      public function loadNotificacao($_notificacao_header, $_notificacao_active, $_entidade) {
      	
      	$this->_notificacao_header = $_notificacao_header;
      	
      	$this->_notificacao_active = $_notificacao_active;
      	
      	$this->_entidade = $_entidade;
      	
      	$_sql = "SELECT tms.*, msg.*, ntf.* ". 
                "  FROM entidade_notificacao ntf LEFT JOIN mensagem msg ON msg.msg_id = ntf.msg_id, ".
                "       tipo_mensagem tms ".
                "  WHERE ntf.tms_id = tms.tms_id ".     
                "    AND ntf.ent_id = ". $_entidade .
                "    AND ntf.ent_id_de = 0 ".
                "    AND ntf.ntf_perc_concluido = 0 ".
                "    AND ntf.ntf_dtmler = '0000-00-00 00:00:00' ". // Apenas as nao lidas
                "  ORDER BY tms.tms_ordem, ntf.ntf_id";   
      	     	
      	$_notificacao = $this->freeHandSQL($_sql);
      	
      	$_txt = "<br>";
      	     	
      	$_ntf_ant = "NONE";
      	$_ntf_ind = 0;
      	$_ntf_ctd = 0;
      	$_ntf_open = false;

      	// Inicializando notificadores e controles auxiliares
      	$_ntf = "";
      	$_ntf_part_1 = "";
      	$_ntf_part_2 = "";
      	$_ntf_part_3 = "";
      	$_ntf_anterior = null;
      	
      	for ($ind=0; $ind < count($_notificacao) ; $ind++) {
      		
      		$_txt .= "<br>Pos: {$ind}, Valor: {$_notificacao[$ind]['tms_descr']}";
      		
      		$_ntf_atual = $_notificacao[$ind]['tms_cod']; // Tipo de Mensagem
      		
      		if ( $_ntf_ant != $_ntf_atual ) {
      		   if ($_ntf_ind > 0 ) {
      		   	 $_ntf_label = $_ntf_anterior['tms_csstit'];
      		   	 if ( empty($_ntf_label) ) {
      		   	 	$_ntf_label = "NONE";
      		   	 }
      		   	 if ( $_ntf_label == "NONE" ) {
      		   	 	$_ntf_label = "info"; // Default para notificacao eh info
      		   	 }
      		     // Fechar o menu anterior
      		   	 $_ntf_part_2 = "<span class='label label-{$_ntf_label}'>{$_ntf_ctd}</span>".
      		       			    "</a>".
      		   			        "<ul class='dropdown-menu'>".
      		   			        "<li class='header'>Voce tem {$_ntf_ctd} notificacoes</li>".
      		   			        "<li>".
      		   			        "<!-- Inner Menu: contains the notifications -->".
      		   			        "<ul class='menu'>";
      		   	 $_ntf_part_4 = "</ul>".
      		   	 		        "</li>";
      		   	 if ($_ntf_ctd > 5 ) {
      		   	    $_ntf_part_4 .= "<li class='footer'><a href='#'>Visualizar todas</a></li>";
      		   	                    
      		   	 }
      		     $_ntf_part_4 .= "</ul>".
      		                     "</li>";
      		     
      		     // Finalizando um icone de Notificacao por determinado tipo
      		     $_ntf .= $_ntf_part_1 .
      		              $_ntf_part_2 .
      		              $_ntf_part_3 .
      		              $_ntf_part_4 ;
      		     
      		     $_ntf_open = false; // Fechei uma opcao de menu
      		     $_ntf_ctd  = 0;
      		     $_ntf_part_1 = "";
      		     $_ntf_part_2 = "";
      		     $_ntf_part_3 = "";
      		     $_ntf_part_4 = "";
      		   }
      		   // Guardando a posicao anterior para pegar dados antes de mudar de painel
      		   $_ntf_anterior = $_notificacao[$ind];
      		   $_ntf_csstexto = $_notificacao[$ind]['tms_cssico'];
      		   $_ntf_part_1  .= "<!-- Notifications Menu -->".
                                "<li class='dropdown notifications-menu'>".
      		                    "<!-- Menu toggle button -->".
      		                    "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>".
      		                    "<i class='{$_ntf_csstexto}'></i>";
      		   $_ntf_ant = $_ntf_atual;
      		   $_ntf_open = true; // Abrimos uma nova opcao de mennu
      		   $_ntf_ind++;
      		}
      		// Colocando as opcoes no menu aberto
      		if ( $_ntf_open == true ) {
      			$_ntf_descr = $_notificacao[$ind]['ntf_descr'];
      			$_ntf_csstexto = $_notificacao[$ind]['msg_csstexto'];
      			if (empty($_ntf_csstexto)) {
      				$_ntf_csstexto = $_notificacao[$ind]['ntf_csstexto'];
      				if (empty($_ntf_csstexto)) {
      				   $_ntf_csstexto = "NONE";
      				}
      			}
    			
      			//$_ntf_link = $this->_opc_link . $_opc_link;
      			
      			// Exibe apenas as ultimas 5 nao lidas.
      			// Continua contado caso tenha mais nao lida para exibir contador
      			// Ajustar logica para exibir link para ver mais apenas quando tiver mais de 5
      			if ( $_ntf_ctd < 4 ) {
      			   $_ntf_part_3 .= "<li><!-- start notification -->".
      			                   "<a href='#'>";
      			   if ( $_ntf_csstexto != "NONE" ) {
      			   	  $_ntf_part_3 .= "<i class='{$_ntf_csstexto}'></i>";
      			   }
      			   $_ntf_part_3 .= $_ntf_descr .
      			                   "</a>".
      			                   "</li><!-- end notification -->";
      			}      			      			
      			$_ntf_ctd++;
      		}      		
      	}
      	
      	// tem um aberto que precisa fechar ainda :)
      	if ( $_ntf_open == true ) {
      	   // Fechar a Notificacao que esta aberta
      	   $_ntf_label = $_ntf_anterior['tms_csstit'];
      	   if ( empty($_ntf_label) ) {
      		   $_ntf_label = "NONE";
      	   }
      	   if ( $_ntf_label == "NONE" ) {
      		   $_ntf_label = "info"; // Default para notificacao eh info
      	   }      	   
      	   $_ntf_part_2 = "<span class='label label-{$_ntf_label}'>{$_ntf_ctd}</span>".
      		    	  	  "</a>".
      				      "<ul class='dropdown-menu'>".
      				      "<li class='header'>Voce tem {$_ntf_ctd} notificacoes</li>".
      				      "<li>".
      				      "<!-- Inner Menu: contains the notifications -->".
      				      "<ul class='menu'>";
      	   
      	   $_ntf_part_4 = "</ul>".
      	   		          "</li>";
      	   		          
      	   if ($_ntf_ctd > 4 ) {
      		   $_ntf_part_4 .= "<li class='footer'><a href='#'>Visualizar todas</a></li>";      			   		       
      	   }      		
      	   $_ntf_part_4 .= "</ul>".
      	                   "</li>";
      	   
      	   $_ntf .= $_ntf_part_1 .
      	            $_ntf_part_2 . 
      	            $_ntf_part_3 .
      	            $_ntf_part_4 ;
      	}
      	    	      	
      	$this->_as_texto = $_txt;
      	
      	$this->_notificacao = $_ntf;
      }
      
      public function loadNotificacaoMessage($_notificacao_header, $_notificacao_active, $_entidade) {
      	 
      	$this->_notificacao_header = $_notificacao_header;
      	 
      	$this->_notificacao_active = $_notificacao_active;
      	 
      	$this->_entidade = $_entidade;
      	 
      	$_sql = "SELECT tms.*, msg.*, ntf.*, ".
      	        "       ent.ent_nome, ent.ent_nome_curto, ent.ent_apelido, ent.ent_dominio, ent.ent_foto_140x185,".
      	        "       tpe.tpe_descr, tpe.tpe_csstexto, tpe.tpe_foto, ".
      	        "       IF(DATE_FORMAT(ntf.ntf_dtmreg,'%Y-%m-%d') = DATE_FORMAT(SYSDATE(),'%Y-%m-%d'),true,false) ntf_hoje,".
      	        "       DATE_FORMAT(ntf.ntf_dtmreg,'%b, %d') ntf_data,".
      	        "       DATE_FORMAT(ntf.ntf_dtmreg,'%H:%i') ntf_hora".      	        
      			"  FROM entidade_notificacao ntf LEFT JOIN mensagem msg ON msg.msg_id = ntf.msg_id, ".
      			"       entidade ent, ".
      			"       tipo_entidade tpe, ". // Tipo Entidade Original
      			"       tipo_mensagem tms ".
      			"  WHERE ntf.tms_id = tms.tms_id ".
      			"    AND ntf.ent_id_de = ent.ent_id ".
      			"    AND ent.tpe_id = tpe.tpe_id ".
      			"    AND ntf.ent_id = ". $_entidade .      			
      			// Nesse caso indica q recebeu uma mensagem de outra entidade
      			"    AND ntf.ent_id_de != 0 ".
      			"    AND ntf.ntf_perc_concluido = 0 ".    
      			"    AND ntf.ntf_dtmler = '0000-00-00 00:00:00' ". // Apenas as nao lidas
      			"  ORDER BY tms.tms_ordem, ntf.ntf_dtmreg desc, ntf.ntf_id desc";
      		
      	$_notificacao = $this->freeHandSQL($_sql);
      	 
      	$_txt = "<br>";
      		
      	$_ntf_ant = "NONE";
      	$_ntf_ind = 0;
      	$_ntf_ctd = 0;
      	$_ntf_open = false;
      
      	// Inicializando notificadores e controles auxiliares
      	$_ntf = "";
      	$_ntf_part_1 = "";
      	$_ntf_part_2 = "";
      	$_ntf_part_3 = "";
      	$_ntf_anterior = null;
      	 
      	for ($ind=0; $ind < count($_notificacao) ; $ind++) {
      
      		$_txt .= "<br>Pos: {$ind}, Valor: {$_notificacao[$ind]['tms_descr']}";
      
      		$_ntf_atual = $_notificacao[$ind]['tms_cod']; // Tipo de Mensagem
      
      		if ( $_ntf_ant != $_ntf_atual ) {
      			if ($_ntf_ind > 0 ) {
      				$_ntf_label = $_ntf_anterior['tms_csstit'];
      				if ( empty($_ntf_label) ) {
      					$_ntf_label = "NONE";
      				}
      				if ( $_ntf_label == "NONE" ) {
      					$_ntf_label = "info"; // Default para notificacao eh info
      				}
      				// Fechar o menu anterior
      				$_ntf_part_2 = "<span class='label label-{$_ntf_label}'>{$_ntf_ctd}</span>".
      						"</a>".
      						"<ul class='dropdown-menu'>".
      						"<li class='header'>Voce tem {$_ntf_ctd} mensagens</li>".
      						"<li>".
      						"<!-- Inner Menu: contains the message -->".
      						"<ul class='menu'>";
      				$_ntf_part_4 = "</ul>".
      						"</li>";
      				if ($_ntf_ctd > 5 ) {
      					$_ntf_part_4 .= "<li class='footer'><a href='#'>Visualizar todas mensagens</a></li>";
      
      				}
      				$_ntf_part_4 .= "</ul>".
      						"</li>";
      					
      				// Finalizando um icone de Notificacao por determinado tipo
      				$_ntf .= $_ntf_part_1 .
      				$_ntf_part_2 .
      				$_ntf_part_3 .
      				$_ntf_part_4 ;
      					
      				$_ntf_open = false; // Fechei uma opcao de menu
      				$_ntf_ctd  = 0;
      				$_ntf_part_1 = "";
      				$_ntf_part_2 = "";
      				$_ntf_part_3 = "";
      				$_ntf_part_4 = "";
      			}
      			// Guardando a posicao anterior para pegar dados antes de mudar de painel
      			$_ntf_anterior = $_notificacao[$ind];
      			$_ntf_csstexto = $_notificacao[$ind]['tms_cssico'];
      			$_ntf_part_1  .= "<!-- Message Menu -->".
      					"<li class='dropdown messages-menu'>".
      					"<!-- Menu toggle button -->".
      					"<a href='#' class='dropdown-toggle' data-toggle='dropdown'>".
      					"<i class='{$_ntf_csstexto}'></i>";
      			$_ntf_ant = $_ntf_atual;
      			$_ntf_open = true; // Abrimos uma nova opcao de mennu
      			$_ntf_ind++;
      		}
      		// Colocando as opcoes no menu aberto
      		if ( $_ntf_open == true ) {
      			$_ntf_descr = $_notificacao[$ind]['ntf_descr'];
      			$_ntf_avatar = $_notificacao[$ind]['ent_foto_140x185'];
      			if ( empty($_ntf_avatar) ) {
      				$_ntf_avatar = "NONE";
      			}
      			if ( $_ntf_avatar == "NONE" ) {
      				// Obrigatorio ter um avatar para o tipo de entidade
      				// Kiko veja ai como fazer kkk :)
      				$_ntf_avatar = $_notificacao[$ind]['tpe_foto'];
      			}
      			$_ent_dominio = $_notificacao[$ind]['ent_dominio'];
      			$_ntf_avatar = $this->_notificacao_link_portal."Dominio/". $_ent_dominio."/Imagens/Profile/".$_ntf_avatar;
      			
      			//echo "<script>alert('{$_ntf_avatar}');</script>";

      			// Icone para exibir
      			$_ntf_csstexto = $_notificacao[$ind]['msg_csstexto'];
      			if (empty($_ntf_csstexto)) {
      				$_ntf_csstexto = $_notificacao[$ind]['ntf_csstexto'];
      				if (empty($_ntf_csstexto)) {
      					$_ntf_csstexto = $_notificacao[$ind]['tpe_csstexto'];
      					if (empty($_ntf_csstexto)) {
      					   $_ntf_csstexto = "NONE";
      					}
      				}
      			}     			 
      			// Exibe apenas as ultimas 4 nao lidas.
      			// Continua contado caso tenha mais nao lida para exibir contador
      			// Ajustar logica para exibir link para ver mais apenas quando tiver mais de 5
      			if ( $_ntf_ctd < 4 ) {
      				$_ntf_part_3 .= "<li><!-- Start Message -->".
      						        "<a href='#'>".
      						        "<div class='pull-left'>".
      						        "<!-- User Image -->";
      				if ( $_ntf_avatar != "NONE" ) {
      					$_ntf_part_3 .= "<img src='{$_ntf_avatar}' class='img-circle' alt='User Image'>";
      				}
      				// Descarregando dados da mensagem - Quem enviou
      				$_tpe_descr   = $_notificacao[$ind]['tpe_descr'];
      				$_ent_apelido = $_notificacao[$ind]['ent_nome_curto'];
      				$_ntf_data    = $_notificacao[$ind]['ntf_data'];
      				$_ntf_hora    = $_notificacao[$ind]['ntf_hora'];
      				$_ntf_part_3 .= "</div>".
        				            "<!-- Message title and timestamp -->".
        				            "<h4>".
        				            "{$_ent_apelido}";

      				//Identificando de quando foi a mensagem
      				$_ntf_hoje = $_notificacao[$ind]['ntf_hoje'];
      				$_ntf_horario = " as ".$_ntf_hora;
      				if ( $_ntf_hoje == false ) {
      					$_ntf_horario = " ".$_ntf_data." as ". $_ntf_hora;
      				}
      				$_ntf_part_3 .= "<small><i class='fa fa-clock-o text-blue'></i>{$_ntf_horario}</small>".
      				                "</h4>".
      				                "<!-- The message -->";     				
      	            //if ( $_ntf_csstexto != "NONE" ) {
      				   //	$_ntf_part_3 .= "<i class='{$_ntf_csstexto}'>&nbsp;</i>";
      				//}      					      				
      				$_ntf_part_3 .= "<p>{$_ntf_descr}</p>".
      						        "</a>".
      				                "</li><!-- end message -->";
      			}
      			$_ntf_ctd++;
      		}
      	}
      	 
      	// tem um aberto que precisa fechar ainda :)
      	if ( $_ntf_open == true ) {
      		// Fechar a Notificacao que esta aberta
      		$_ntf_label = $_ntf_anterior['tms_csstit'];
      		if ( empty($_ntf_label) ) {
      			$_ntf_label = "NONE";
      		}
      		if ( $_ntf_label == "NONE" ) {
      			$_ntf_label = "info"; // Default para notificacao eh info
      		}
      		$_ntf_part_2 = "<span class='label label-{$_ntf_label}'>{$_ntf_ctd}</span>".
      				"</a>".
      				"<ul class='dropdown-menu'>".
      				"<li class='header'>Voce tem {$_ntf_ctd} notificacoes</li>".
      				"<li>".
      				"<!-- Inner Menu: contains the message -->".
      				"<ul class='menu'>";
      
      		$_ntf_part_4 = "</ul>".
      				"</li>";
      
      		if ($_ntf_ctd > 4 ) {
      			$_ntf_part_4 .= "<li class='footer'><a href='#'>Visualizar todas mensagens</a></li>";
      		}
      		$_ntf_part_4 .= "</ul>".
      				"</li>";
      
      		$_ntf .= $_ntf_part_1 .
      		$_ntf_part_2 .
      		$_ntf_part_3 .
      		$_ntf_part_4 ;
      	}
      	 
      	$this->_as_texto = $_txt;
      	 
      	$this->_notificacao = $_ntf;
      }
      
      public function loadNotificacaoTask($_notificacao_header, $_notificacao_active, $_entidade) {
      
      	$this->_notificacao_header = $_notificacao_header;
      
      	$this->_notificacao_active = $_notificacao_active;
      
      	$this->_entidade = $_entidade;
      
      	$_sql = "SELECT tms.*, msg.*, ntf.* ".
      			"  FROM entidade_notificacao ntf LEFT JOIN mensagem msg ON msg.msg_id = ntf.msg_id, ".
      			"       tipo_mensagem tms ".
      			"  WHERE ntf.tms_id = tms.tms_id ".
      			"    AND ntf.ent_id = ". $_entidade .
      			"    AND ntf.ent_id_de = 0 ".
      			// Colocar MENOS -1 qdo tiver esse controle e forçar ZERO para exibir se negativo
      	        "    AND ntf.ntf_perc_concluido != 0 ".
      	        "    AND ntf.ntf_dtmler = '0000-00-00 00:00:00' ".  // Apenas as nao lidas
      	"  ORDER BY tms.tms_ordem, ntf.ntf_id";
      
      	$_notificacao = $this->freeHandSQL($_sql);
      
      	$_txt = "<br>";
      
      	$_ntf_ant = "NONE";
      	$_ntf_ind = 0;
      	$_ntf_ctd = 0;
      	$_ntf_open = false;
      
      	// Inicializando notificadores e controles auxiliares
      	$_ntf = "";
      	$_ntf_part_1 = "";
      	$_ntf_part_2 = "";
      	$_ntf_part_3 = "";
      	$_ntf_anterior = null;
      
      	for ($ind=0; $ind < count($_notificacao) ; $ind++) {
      
      		$_txt .= "<br>Pos: {$ind}, Valor: {$_notificacao[$ind]['tms_descr']}";
      
      		$_ntf_atual = $_notificacao[$ind]['tms_cod']; // Tipo de Mensagem
      
      		if ( $_ntf_ant != $_ntf_atual ) {
      			if ($_ntf_ind > 0 ) {
      				$_ntf_label = $_ntf_anterior['tms_csstit'];
      				if ( empty($_ntf_label) ) {
      					$_ntf_label = "NONE";
      				}
      				if ( $_ntf_label == "NONE" ) {
      					$_ntf_label = "info"; // Default para notificacao eh info
      				}
      				// Fechar o menu anterior
      				$_ntf_part_2 = "<span class='label label-{$_ntf_label}'>{$_ntf_ctd}</span>".
      						"</a>".
      						"<ul class='dropdown-menu'>".
      						"<li class='header'>Voce tem {$_ntf_ctd} notificacoes</li>".
      						"<li>".
      						"<!-- Inner Menu: contains the notifications -->".
      						"<ul class='menu'>";
      				$_ntf_part_4 = "</ul>".
      						"</li>";
      				if ($_ntf_ctd > 5 ) {
      					$_ntf_part_4 .= "<li class='footer'><a href='#'>Visualizar todas</a></li>";
      
      				}
      				$_ntf_part_4 .= "</ul>".
      						"</li>";
      				 
      				// Finalizando um icone de Notificacao por determinado tipo
      				$_ntf .= $_ntf_part_1 .
      				$_ntf_part_2 .
      				$_ntf_part_3 .
      				$_ntf_part_4 ;
      				 
      				$_ntf_open = false; // Fechei uma opcao de menu
      				$_ntf_ctd  = 0;
      				$_ntf_part_1 = "";
      				$_ntf_part_2 = "";
      				$_ntf_part_3 = "";
      				$_ntf_part_4 = "";
      			}
      			// Guardando a posicao anterior para pegar dados antes de mudar de painel
      			$_ntf_anterior = $_notificacao[$ind];
      			$_ntf_csstexto = $_notificacao[$ind]['tms_cssico'];
      			$_ntf_part_1  .= "<!-- Task Menu -->".
      					"<li class='dropdown tasks-menu'>".
      					"<!-- Menu toggle button -->".
      					"<a href='#' class='dropdown-toggle' data-toggle='dropdown'>".
      					"<i class='{$_ntf_csstexto}'></i>";
      			$_ntf_ant = $_ntf_atual;
      			$_ntf_open = true; // Abrimos uma nova opcao de mennu
      			$_ntf_ind++;
      		}
      		// Colocando as opcoes no menu aberto
      		if ( $_ntf_open == true ) {
      			$_ntf_descr = $_notificacao[$ind]['ntf_descr'];
      			$_ntf_perc_concluido = $_notificacao[$ind]['ntf_perc_concluido'];
      			$_ntf_csstexto = $_notificacao[$ind]['msg_csstexto'];
      			if (empty($_ntf_csstexto)) {
      				$_ntf_csstexto = $_notificacao[$ind]['ntf_csstexto'];
      				if (empty($_ntf_csstexto)) {
      					$_ntf_csstexto = "NONE";
      				}
      			}
      
      			// Se ainda nao iniciou deve estar -1
      			if ( $_ntf_perc_concluido < 0 ) {
      				$_ntf_perc_concluido = 0;
      			} else {
      				// Arredondado para o inteiro abaixo
      				$_ntf_perc_concluido = floor($_ntf_perc_concluido);
      			}
      			 
      			// Cor da barra de progressao
      			$_ntf_progress = $_notificacao[$ind]['tms_cssbkg'];
      			if ( empty($_ntf_progress) ) {
      				$_ntf_progress = "NONE";
      			}
      			if ( $_ntf_progress == "NONE" ) {
      				$_ntf_progress = "aqua";
      			}
      
      			//$_ntf_link = $this->_opc_link . $_opc_link;
      
      			// Exibe apenas as ultimas 5 nao lidas.
      			// Continua contado caso tenha mais nao lida para exibir contador
      			// Ajustar logica para exibir link para ver mais apenas quando tiver mais de 5
      			if ( $_ntf_ctd < 4 ) {
      				$_ntf_part_3 .= "<li><!-- task item -->".
      						"<a href='#'>".
      						"<!-- Task title and progress text -->".
      						"<h3>";
      				if ( $_ntf_csstexto != "NONE" ) {
      					$_ntf_part_3 .= "<i class='{$_ntf_csstexto}'>&nbsp;</i>";
      				}
      				$_ntf_part_3 .= $_ntf_descr .
      				"<small class='pull-right'>{$_ntf_perc_concluido}%</small>".
      				"</h3>".
      				"<!-- The progress bar -->".
      				"<div class='progress xs'>".
      				"<!-- Change the css width attribute to simulate progress -->".
      				"<div class='progress-bar progress-bar-{$_ntf_progress}' ".
      				"style='width: {$_ntf_perc_concluido}%' role='progressbar' ".
      				"aria-valuenow='{$_ntf_perc_concluido}' aria-valuemin='0' ".
      				"aria-valuemax='100'>".
      				"<span class='sr-only'>{$_ntf_perc_concluido}% Complete</span>".
      				"</div>".
      				"</div>".
      				"</a>".
      				"</li><!-- end task item -->";
      			}
      			$_ntf_ctd++;
      		}
      	}
      
      	// tem um aberto que precisa fechar ainda :)
      	if ( $_ntf_open == true ) {
      		// Fechar a Notificacao que esta aberta
      		$_ntf_label = $_ntf_anterior['tms_csstit'];
      		if ( empty($_ntf_label) ) {
      			$_ntf_label = "NONE";
      		}
      		if ( $_ntf_label == "NONE" ) {
      			$_ntf_label = "info"; // Default para notificacao eh info
      		}
      		$_ntf_part_2 = "<span class='label label-{$_ntf_label}'>{$_ntf_ctd}</span>".
      				"</a>".
      				"<ul class='dropdown-menu'>".
      				"<li class='header'>Voce tem {$_ntf_ctd} notificacoes</li>".
      				"<li>".
      				"<!-- Inner Menu: contains the notifications -->".
      				"<ul class='menu'>";
      
      		$_ntf_part_4 = "</ul>".
      				"</li>";
      
      		if ($_ntf_ctd > 4 ) {
      			$_ntf_part_4 .= "<li class='footer'><a href='#'>Visualizar todas</a></li>";
      		}
      		$_ntf_part_4 .= "</ul>".
      				"</li>";
      
      		$_ntf .= $_ntf_part_1 .
      		$_ntf_part_2 .
      		$_ntf_part_3 .
      		$_ntf_part_4 ;
      	}
      
      	$this->_as_texto = $_txt;
      
      	$this->_notificacao = $_ntf;
      }
            
      public function showNotificacao() {
      	echo $this->_notificacao;
      }
      
      public function setNotificacaoLink($_valor) {
      	$this->_notificacao_link = $_valor;
      	//echo "<script>alert('Valor Link eh {$_valor}');</script>";
      }
      public function setNotificacaoLinkPortal($_valor) {
      	$this->_notificacao_link_portal = $_valor;
      	//echo "<script>alert('Valor Link Portal eh {$_valor}');</script>";
      }      
      public function getNotificacao() {
      	return $this->_notificacao;
      }
      
      public function getAsText() {
      	return $this->_as_texto;
      }
      
      public function getNotificacaoLink() {
      	return $this->_notificacao_link;
      }
      public function getNotificacaoLinkPortal() {
      	return $this->_notificacao_link_portal;
      }      
}
?>