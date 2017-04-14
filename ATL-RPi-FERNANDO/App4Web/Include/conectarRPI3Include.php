<?php
function ConectarRPI3(){
	global $start;

	// Pegando as credenciais do DOMINIO logado conforme config.php
	$_db_server = $start->_session->selectSession("RPI3_DB_SERVER");
	$_db_name   = $start->_session->selectSession("RPI3_DB_NAME");
	$_db_login  = $start->_session->selectSession("RPI3_DB_USER");
	$_db_passwd = $start->_session->selectSession("RPI3_DB_PASSWD");

	//echo "Model->DB: {$_db_name}";
	$_pdo_string = "mysql:host={$_db_server};dbname={$_db_name};charset=utf8";

	//echo "--------------------> Model->DB: {$_pdo_string}";

	if ( $_db_server == null or empty($_db_server) ) {
		echo "Verify DATABASE credentials before...";
		return null;
	} else {
		try{
			$opcoes = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
			$con = new PDO($_pdo_string,$_db_login,$_db_passwd,$opcoes);
			return $con;
		} catch (Exception $e){
			echo 'ConectarRPI3Include.php: Erro: '.$e->getMessage();
			return null;
		}
	}
}
?>