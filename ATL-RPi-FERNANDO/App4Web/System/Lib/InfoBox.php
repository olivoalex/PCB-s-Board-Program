<?php
   class InfoBox{
   	  public $_type;
      public $_header;
      public $_body;
      public $_trailer;
      public $_icon_csstexto;
      public $_color_csstexto;
      public $_progress_valor;
      private $_progress_color_csstexto;
      private $_infobox;
      
      public function setInfoBox($_type=null, $_head=null, $_body=null, $_trailer=null, $_progress_valor=0, $_icon_csstexto=null, $_color_csstexto=null) {
      	
      	// Determinando os defaults
      	$_type           = ($_type           == null || empty($_type)           || $_type           == " " ? "info-box"   : $_type);
      	$_icon_csstexto  = ($_icon_csstexto  == null || empty($_icon_csstexto)  || $_icon_csstexto  == " " ? "fa fa_home" : $_icon_csstexto);
      	$_color_csstexto = ($_color_csstexto == null || empty($_color_csstexto) || $_color_csstexto == " " ? "bg-aqua"    : $_color_csstexto);      	
      	$_progress_valor = ($_progress_valor == null || empty($_progress_valor) || $_progress_valor == " " ? 0            : $_progress_valor);
      	$this->_progress_color_csstexto = ($this->_progress_color_csstexto == null || empty($this->_progress_color_csstexto) || $this->_progress_color_csstexto == " " ? " " : $this->_progress_color_csstexto);
      	if ( $_color_csstexto == "bg-white" ) {
      		$this->_progress_color_csstexto = "bg-black";
      	}
      	
      	
      	$this->_type           = $_type;
      	$this->_header         = $_head;
      	$this->_body           = $_body;
      	$this->_trailer        = $_trailer;
      	$this->_icon_csstexto  = $_icon_csstexto;
      	$this->_color_csstexto = $_color_csstexto;
      	$this->_progress_valor = $_progress_valor;
      	
      	$_infobox = "<div class='{$this->_type} {$this->_color_csstexto}'>".
      	            "<span class='info-box-icon'><i class='{$this->_icon_csstexto}'></i></span>".
      	            "<div class='info-box-content'>".
      	            "<span class='info-box-text'>{$this->_header}</span>".
      	            "<span class='info-box-number'>{$this->_body}</span>".
      	            "<!-- The progress section is optional -->".
      	            "<div class='progress'>".
      	            "<div class='progress-bar {$this->_progress_color_csstexto}' style='width: {$this->_progress_valor}%'></div>".
      	            "</div>".
      	            "<span class='progress-description'>".
      	            $this->_trailer.
      	            "</span>".
      	            "</div><!-- /.info-box-content -->".
      	            "</div><!-- /.info-box -->";
      	
      	 $this->_infobox = $_infobox;
      }
      
      public function showInfoBox($_type=null, $_head=null, $_body=null, $_trailer=null, $_progress_valor=null, $_icon_csstexto=null, $_color_csstexto=null) {
      	
      	$this->setInfoBox($_type, $_head, $_body, $_trailer, $_progress_valor, $_icon_csstexto, $_color_csstexto);
      	
      	echo $this->_infobox;
      }
      
      public function getInfoBox() {
      	return $this->_infobox;
      }      
      
      public function setProgressColorCsstexto($_valor) {
      	$this->_progress_color_csstexto = $_valor;
      }
   }
?>