<?php
class redirectorHelper{
  	  protected $parametros = array();
	  public $_system;
	  
	  public function init($_start) {
		  $this->_system = $_start;
	  }
	  
  	  public function go( $_valor ) {
  	  	
		if ( $this->_system->_dominio == "" || $this->_system->_dominio == null ) {
			$this->_system->_dominio = "Portal";			
		}
				
		$_location = "Location: ".$this->_system->_link . $this->_system->_dominio . $_valor;
				
		//echo "<script>alert('--------- Location: {$_location}');</script>";		
		//echo "<br> redirector->go: Valor: " . $_valor;
		//echo "<br> redirector->go: Location: " . $_location;
		
  	  	header($_location);
		
  	  	return $this;
		
  	  }
  	  	  
  	  public function setUrlParameter( $nome, $value) {
  	  	$this->parametros [$nome] = $value;
  	  	return $this;
  	  }
	  
	  public function loadToUrlParameter($_dados) {
		  
          // Carregando dados como parametros
		  foreach ( $_dados as $key => $val ) {
			$this->parametros [$key] = $val;
		 }		 		 
		 return $this;
	  }	 
  	  
  	  public function getUrlParameters() {
  	  	$parms = "";
  	  	foreach ( $this->parametros as $nome => $value) 
  	  		$parms .= $nome . "/" . $value . "/";
  	  	return $parms; 
  	  }
  	  
  	  public function getCurrentDominio() {
  	  	global $start;
  	  	
  	  	//echo "<BR>redirection->gtCurrentDominio: {$start->_dominio}";
  	  	
  	  	return $start->_dominio;
  	  }
  	  
  	  public function getCurrentController() {
        global $start;
        
        //echo "<BR>redirection->gtCurrentController: {$start->_controller}";
        
        return $start->_controller;
  	  }
  	  
  	  public function getCurrentTask() {
  	  	global $start;
  	  	
  	  	//echo "<BR>redirection->gtCurrentTask: {$start->_task}";
  	  	
  	  	return $start->_task;
  	  }
  	  
  	  public function getCurrentAction() {
  	  	global $start;
  	  	
  	  	//echo "<BR>redirection->gtCurrentAction: {$start->_action}";
  	  	
  	  	return $start->_action;
  	  }
 	  
  	  public function goToController( $controller) {
  	  	
  	  	$this->go($this->getCurrentDominio() . "/{$controller}" . "/index/index/".$this->getUrlParameters());
  	  	  	  	
  	  }

  	  public function goToTask( $task ) {
  	  	
  	  	$this->go($this->getCurrentDominio() ."/". $this->getCurrentController(). "/". $task . "/index" . $this->getUrlParameters());
  	  	
  	  }
  	  
  	  public function goToAction( $action ) {
  	  	  	  	
  	  	$this->go($this->getCurrentDominio() ."/". $this->getCurrentController(). "/". $this->getCurrentTask() . "/" . $action . $this->getUrlParameters());
  	  	
  	  }

  	  public function goToControllerTaskAction( $controller, $task, $action ) {
  	  	
  	  	//echo "<br>rdirector->goToControllerTaskAction: Controller: {$controller}";
  	  	//echo "<br>rdirector->goToControllerTaskAction: Task: {$task}";
  	  	//echo "<br>rdirector->goToControllerTaskAction: Action: {$action}";
  	  	  	  	
  	  	$this->go("/{$controller}/{$task}/{$action}/" .$this->getUrlParameters());
  	  }

  	  public function goToDominioControllerTaskAction( $dominio, $controller, $task, $action ) {
  	    	  	 	 
  	  	//echo "<br>rdirector->goToDominioControllerTaskAction: Dominio: {$dominio}";
  	  	//echo "<br>rdirector->goToDominioControllerTaskAction: Controller: {$controller}";
  	  	//echo "<br>rdirector->goToDominioControllerTaskAction: Task: {$task}";
  	  	//echo "<br>rdirector->goToDominioControllerTaskAction: Action: {$action}";
  	    	  
  	  	$this->go("{$dominio}/{$controller}/{$task}/{$action}/" .$this->getUrlParameters());
  	  }
  	  
  	  public function goToIndex() {
  	  	//$dominio = $this->getCurrentDominio();
  	  	//$this->goToController($dominio."/index/index/index");
  	  	$_controller = $this->getCurrentController();
  	  	$this->goToController($_controller);
  	  }

  	  public function goToUrl( $url ) {
  	  	header("Location : " . $url);
  	  }
}
?>