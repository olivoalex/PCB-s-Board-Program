<!--Author      : @fviana-->
<?php
  
   // Date in the past
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache");

   session_start();

   // Verifica se variaveis estao na sessão, se nao estiver tem que direcionar para uma pagina de erro
   if ( ! isset($_SESSION['LOGGED']) ) {
	  $_SESSION['LOGGED'] = false;   
   }
   
   echo "<script>alert('Session Logged ". $_SESSION["LOGGED"]."');</script>";
   
   // Verificando se LOGIN esta TRUE senao redireciona para pagina de LOGIN
   if ( $_SESSION['LOGGED'] != true ) {
	  // Redirecionar pagina
	  include('C:\Instalados\Vertrigo\www\erp4web\App\pages\login.php');
   } else {
      // trabalhando com parametros recebidos   
      $_emp_id  = (isset($_SESSION['emp_id']) ? $_SESSION['emp_id'] : 123) * 3;  
      $_usr_id  = (isset($_SESSION['usr_id']) ? $_SESSION['usr_id'] : 456) * 2;
   
      $_SESSION["emp_id"] = $_emp_id;
      $_SESSION["usr_id"] = $_usr_id;
?>
<div id="id-nav-tabs">
    <!--Nav tabs--> 
    <ul class="nav nav-tabs" role="tablist">
       <li class="active">
          <a href="#id-nav-tabs .home" role="tab" data-toggle="tab">
            <i class="fa fa-home text-cyan-dark"></i>
            KND
            <span class="badge badge-xs bg-red-light">12</span>
          </a>
       </li>
       <li>
          <a href="#id-nav-tabs .messages" role="tab" data-toggle="tab">
            <i class="fa fa-envelope text-cyan-dark"></i>
            PHP Information Setup
            <span class="badge badge-xs bg-orange-dark">6</span>
          </a>
       </li>
       <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-cog text-cyan-dark"></i>
            Atividades&nbsp;<span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
              <li><a href="#id-nav-tabs .profile" data-toggle="tab">Profile</a></li>
              <li><a href="#id-nav-tabs .settings" data-toggle="tab">Nascimento</a></li>
          </ul>
       </li>
    </ul>

    <!--Tab panes--> 
    <div class="tab-content bg-white padding-15">
       <div class="tab-pane home active" style="height:300px;">
<pre>
   Uma Filial de desenvolvimento web voltada a aplicacoes diversas
   Algo muito abrangente e aberto
pode não dizer nada mas representa tudo o que fazemos
as veses parece vago, mas acaba-se tornando muito complexo.
			
   Fernando
   Diretor Desenvolvimento e Aplicações
   +55 11 7899-9876  
   <i class='fa fa-envelope'></i>&nbsp;afvndo@gmail.com
</pre>
	        Empresa:<?php echo $_emp_id;?><br>
			Usuário:<?php echo $_usr_id;?>		
       </div>
       <div class="tab-pane messages" style="height:300px;">
           Sem mensagens no momento
       </div>
       <div class="tab-pane profile" style="height:300px;">
            Fernando, project leader from KND<br>
			:), eh isso ai<br>
			Falow Teh+
       </div>
       <div class="tab-pane settings" style="height:300px;">
            12 Setembro de 1971<br>
			Ficando velho<br>
			:)<br>
			<i class='fa fa-envelope'></i>&nbsp;afvndo@gmail.com
       </div>
    </div>
 </div>
<?php
   } // Fechando IF de controle de LOGIN :(
?>