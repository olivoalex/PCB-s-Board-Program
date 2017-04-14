<?php
class eMailHelper{
   	   protected $_de,
   	             $_de_nome,
   	             $_para,
   	             $_subject,
   	             $_SMTP_user,
   	             $_SMTP_passwd,
   	             $_hostname,
   	             $_body;
   	   
   	   private $_mail;

    public function __construct(){
    	
      //fazemos a chamada a classe phpmailer
      $this->_mail = new PHPMailer();
      
      //chamada par envio de email via smtp
      $this->_mail->Mailer = "smtp";
      
      //habilita o envio de email HTML
      $this->_mail->IsHTML(true);
      
      //coloque aqui o seu servidor de sa죡 de emails (SMTP)
      $this->_mail->Host = "11.12.13.30";
       
      //Habilita a autenticacao smtp
      //Habilitar a autenticacao email
      $this->_mail->SMTPAuth = "true"; 

      //usuario SMTP       
      $this->_mail->Username = "portal@clog.com.br";
      
      //senha do usuario SMTP
      $this->_mail->Password = "it#prtl2014";
      
    }
    
    public function setDe($_valor) {
   	    $this->_de = $_valor;
    }
    public function setDeNome($_valor) {
    	$this->_de_nome = $_valor;
    }    
    public function setPara($_valor){
    	$this->_para = $_valor;
    }
    public function setSubject($_valor) {
    	$this->_subject = $_valor;
    }
    public function setBody($_valor) {
    	$this->_body = $_valor;
    }
    public function setHostname($_valor) {
    	$this->_hostname = $_valor;
    	$this->_mail->Host = $this->_hostname;
    }
    public function setSMTPUser($_valor) {
    	$this->_SMTP_user = $_valor;
    	$this->_mail->Username = $this->_SMTP_user;
    }
    public function setSMTPPasswd($_valor) {
    	$this->_SMTP_passwd = $_valor;
    	$this->mail->Password = $this->_SMTP_passwd;
    }
    
    public function getDe() {
      return $this->_de;	
    }
    public function getDeNome() {
    	return $this->_de_nome;
    }    
    public function getPara() {
    	return $this->_para;
    }
    public function getSubject() {
    	return $this->_subject;
    }
    public function getBody() {
    	return $this->_body;
    }
    public function getHostname() {
    	return $this->_hostname;
    }
    public function getSMTPUser() {
    	return $this->_SMTP_user;
    }
    public function getSMTPPasswd() {
    	return $this->_SMTP_passwd;
    }
    
    public function sendMail(){

    	// Determinando variaveis de retorno
    	$_ret['msg'] = "NONE";
    	$_ret['ok'] = false;;
    	     	 
    	//Remetente do e-mail
    	$this->_mail->From = $this->_de;
    	
    	//nome do remetente do email
    	$this->_mail->FromName = $this->_de_nome;
    	
    	//endereco de destino do email 
    	$this->_mail->AddAddress($this->_para);
    	
    	//assunto do email    	
    	$this->_mail->Subject = $this->_subject;
    	
    	//texto da mensagem
    	$this->_mail->Body = $this->_body;
    	          
    	if(!$this->_mail->Send()){
    	   $_ret['msg'] .= "\nOcorreu um erro ao enviar o e-mail de {$this->_de} para {$this->_para}.";
   		   $_ret['ok' ] = 100;
   		} else {
    	   // enviado com sucesso
    	   $_ret['msg'] = "Email enviado.".
    	                  "<hr><br>De: {$this->_de}".
    	                  "<br>De-Nome: {$this->_de_nome}".
    	                  "<br>Para: {$this->_para}".
    	                  "<br>Subject: {$this->_subject}".
    	                  "<br>Body: {$this->_body}";
    	   $_ret['ok' ] = true;
        }
    	
    	return $_ret;
    }    
}
?>