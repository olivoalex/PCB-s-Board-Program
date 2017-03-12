<?php
ini_set('default_charset','UTF-8');

require_once("seguranca.php");

unset($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login | Agrotechlink ®</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="Agrotechlink ®">
    <meta name="description" content="Agricultura e tecnologia conectadas a serviço do agricultor.">
    <meta name="keywords" content="agricultura, joinville, sensor, sensores, atuadores, motores, controle, tempo">
    <meta name="reply-to" content="todos@agrotechlink.com">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="shortcut icon" href="images/iconeLogo.ico">

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-body">

<div class="container">

    <form class="form-signin" action="valida.php" method="post">
        <h2 class="form-signin-heading">Faça seu login</h2>
        <div class="login-wrap">
            <div class="user-login-info">
                <input class="form-control " name="usuario" id="usuario" maxlength="40" placeholder="Seu e-mail" type="email" required autofocus/>
                <input type="password" name="senha" id="senha" maxlength="40" class="form-control" placeholder="Senha"  required autofocus>
            </div>
            <!--<span class="pull-right" style="margin-bottom: 10px">
                    <a data-toggle="modal" href="#myModal"> Esqueceu sua senha? Hummm</a>
            </span>-->
            <button class="btn btn-lg btn-login btn-block" name="submit" id="submit" type="submit">Entrar</button>

            <div class="registration">
                Não tem uma conta ainda?
                <a href="registrar.html">
                    Criar uma conta.
                </a>
            </div>

        </div>

        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Esqueceu sua senha?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Digite seu e-mail para recuperar sua senha.</p>
                        <input type="text" name="email" placeholder="Seu e-mail de recuperação" autocomplete="off" class="form-control placeholder-no-fix">

                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
                        <button class="btn btn-success" type="button">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal -->

    </form>

</div>



<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>

</body>
</html>

