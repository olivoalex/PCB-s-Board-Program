<?php
ini_set('default_charset','UTF-8');

require_once("seguranca.php");
require_once("indexPhp.php");
session_start();

protegePagina();

function buscaNomeUsuario() {
    global $_SG;
    $usuario = $_SESSION['usuarioLogin'];
    $senha = $_SESSION['usuarioSenha'];
    $sql = "SELECT cpf_usuario, nome_usuario, sobrenome_usuario FROM usuario WHERE email_login = '$usuario' AND senha_login = '$senha' LIMIT 1";
    $query = mysqli_query($_SG['link'], $sql);
    $resultado = mysqli_fetch_assoc($query);
    // Verifica se encontrou algum registro
    if (empty($resultado)) {
        // Nenhum registro foi encontrado => o usuário é inválido
        return false;
    } else {
        echo $resultado['nome_usuario'];
        echo " ";
        echo $resultado['sobrenome_usuario'] ;

        $_SESSION['CPF'] = $resultado['cpf_usuario'];
    }
}

function buscaMAC() {
    global $_SG;
    $cpf = $_SESSION['CPF'];
    $bsql = "SELECT nome, mac FROM adm_cont_BP2R WHERE cpf='$cpf'";
    $bquery = mysqli_query($_SG['link'], $bsql);

    while ($bresultado = mysqli_fetch_array($bquery)) {

        echo '<tr>
                  <td style="text-align: center">'.$bresultado['nome'].'</td>
                  <td style="text-align: center">
                        <form action="indexPhp.php" method="post">
                              <button style="margin-right: 10px; margin-bottom: 5px" id="'.$bresultado['mac'].'" name="LigaBotaoA" value="'.$bresultado['mac'].'" type="submit" class="btn btn-success">Ligar</button>
                              <button style="margin-bottom: 5px" id="'.$bresultado['mac'].'" name="DesligaBotaoA" value="'.$bresultado['mac'].'" type="submit" class="btn btn-danger">Desligar</button>
                        </form>
                  </td>
                  <td style="text-align: center">
                        <form action="indexPhp.php" method="post">
                               <button style="margin-right: 10px; margin-bottom: 5px" id="'.$bresultado['mac'].'" name="LigaBotaoB" value="'.$bresultado['mac'].'" type="submit"  class="btn btn-success">Ligar</button>
                               <button style="margin-bottom: 5px" id="'.$bresultado['mac'].'" name="DesligaBotaoB" value="'.$bresultado['mac'].'" type="submit" class="btn btn-danger">Desligar</button>
                        </form>
                  </td>
              </tr>';

    }
}

function buscaEstacao() {
    global $_SG;
    $cpf = $_SESSION['CPF'];
    $sql = "SELECT mac, nome FROM adm_clima WHERE cpf='$cpf'";
    $query = mysqli_query($_SG['link'], $sql);
    $arraymac = array();
    $listaDados = array();

    while ($resultado = mysqli_fetch_array($query)) {

        echo '<tr><td style="text-align: center">'.$resultado['nome'].'</td>';

        $arraymac[] = $resultado['mac'];

        $sql2 = "SELECT d_T, d_U, b_P, hora, dia FROM dia_clima WHERE mac='".$resultado['mac']."' ORDER BY id DESC LIMIT 1";
        $query2 = mysqli_query($_SG['link'], $sql2);

        while ($resultado2 = mysqli_fetch_array($query2)) {

            echo '<td style="text-align: center"><a>'.$resultado2['d_T'].' °C &nbsp;</a><i class="fa fa-thermometer-3 fa-fw" aria-hidden="true" style="color: #2eb4ad;"></i></td>
                  <td style="text-align: center"><a>'.$resultado2['d_U'].' %UR &nbsp;</a><i class="fa fa-tint fa-fw" aria-hidden="true" style="color: #2eb4ad;"></i></td>    
                  <td style="text-align: center"><a>'.$resultado2['b_P'].' hPa &nbsp;</a><i class="fa fa-tachometer fa-fw" aria-hidden="true" style="color: #2eb4ad;"></i></td>
                  <td style="text-align: center"><a>'.$resultado2['hora'].' &nbsp;</a><i class="fa fa-clock-o fa-fw" aria-hidden="true" style="color: #2eb4ad;"></i></td>
                  <td style="text-align: center"><a>'.date_format( date_create($resultado2['dia']), 'd/m/Y' ).' &nbsp;</a><i class="fa fa-calendar-check-o fa-fw" aria-hidden="true" style="color: #2eb4ad;"></i></td>
                  </tr>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel | Agrotechlink ®</title>
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

    <link rel="stylesheet" href="css/bootstrap-switch.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-multi-select/css/multi-select.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-tags-input/jquery.tagsinput.css" />

    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://cdn.oesmith.co.uk/morris-0.4.1.min.js"></script>
    <![endif]-->
</head>

<body id="paginaInicial">

<section id="container" >
    <!--header start-->
    <header class="header fixed-top clearfix">
        <!--logo start-->
        <div class="brand">
            <a href="index.php" class="logo">
                <img src="images/logoindex.png" alt="">
            </a>
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars"></div>
            </div>
        </div>
        <!--logo end-->
        <div class="top-nav clearfix">
            <!--search & user info start-->
            <ul class="nav pull-right top-menu">
                <!-- user login dropdown start-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="">
                        <img alt="" src="images/iconeLogo.ico">
                        <span id="nomeUsuario" class="username"><?php buscaNomeUsuario(); ?></span>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu extended logout">
                        <!--<li><a href="#"><i class=" fa fa-suitcase"></i>Perfil</a></li>
                        <li><a href="#"><i class="fa fa-cog"></i> Configurações</a></li>-->
                        <li><a href="login.php"><i class="fa fa-key"></i>Sair</a></li>
                    </ul>
                </li>
                <!-- user login dropdown end
                <li>
                    <div class="toggle-right-box">
                        <div class="fa fa-bars"></div>
                    </div>
                </li>
            </ul>-->
                <!--search & user info end-->
        </div>
    </header>
    <!--header end-->
    <aside>
        <div id="sidebar" class="nav-collapse">
            <!-- sidebar menu start-->
            <div class="leftside-navigation">
                <ul class="sidebar-menu" id="nav-accordion">
                    <!--<li>
                        <a href="index.php">
                            <i class="fa fa-dashboard"></i>
                            <span>Página inicial</span>
                        </a>
                    </li>
                    <li>
                        <a href="blank.html">
                            <i class="fa fa-bar-chart-o"></i>
                            <span>Perfil</span>
                        </a>
                    </li>-->
                    <li>
                        <a href="login.php">
                            <i class="fa fa-arrow-left"></i>
                            <span>Sair</span>
                        </a>
                    </li>
                </ul></div>
            <!-- sidebar menu end-->
        </div>
    </aside>
</section>
<!--sidebar end-->

<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <!-- page start-->

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header STYLE="color: #2eb4ad; letter-spacing: 1px" class="panel-heading">
                        <b>LOCAL - ACIONADORES INTELIGENTES | MÉDIA POTÊNCIA</b>
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table">
                            <div class="space15"></div>
                            <table class="table table-condensed table-responsive table-striped table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th style="text-align: center">ID</th>
                                    <th style="text-align: center">SAÍDA A</th>
                                    <th style="text-align: center">SAÍDA B</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php buscaMAC() ?>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!--ESTAÇÃO CLIMÁTICA-->

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header style="color: #2eb4ad; letter-spacing: 1px" class="panel-heading">
                        <b>LOCAL - MICROCLIMA | INFORMAÇÕES ATUAIS</b>
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                        <div class="adv-table">
                            <div class="space15"></div>
                            <table class="table table-condensed table-responsive table-striped table-hover table-bordered">
                                <tbody id="estacaoClima">
                                <?php buscaEstacao(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- ÁREA DO GRÁFICO DO MICROCLIMA -->

    </section>
</section>
        <!-- page end -->
<!--main content end-->
<!--right sidebar start-->
<div class="right-sidebar">
<div class="search-row">
    <input type="text" placeholder="Search" class="form-control">
</div>
<div class="right-stat-bar">
<ul class="right-side-accordion">
<li class="widget-collapsible">
    <a href="#" class="head widget-head red-bg active clearfix">
        <span class="pull-left">work progress (5)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row side-mini-stat clearfix">
                <div class="side-graph-info">
                    <h4>Target sell</h4>
                    <p>
                        25%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="target-sell">
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info">
                    <h4>product delivery</h4>
                    <p>
                        55%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="p-delivery">
                        <div class="sparkline" data-type="bar" data-resize="true" data-height="30" data-width="90%" data-bar-color="#39b7ab" data-bar-width="5" data-data="[200,135,667,333,526,996,564,123,890,564,455]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info payment-info">
                    <h4>payment collection</h4>
                    <p>
                        25%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="p-collection">
						<span class="pc-epie-chart" data-percent="45">
						<span class="percent"></span>
						</span>
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info">
                    <h4>delivery pending</h4>
                    <p>
                        44%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="d-pending">
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="col-md-12">
                    <h4>total progress</h4>
                    <p>
                        50%, Deadline 12 june 13
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div style="width: 50%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-info">
                            <span class="sr-only">50% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head terques-bg active clearfix">
        <span class="pull-left">contact online (5)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1_small.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Jonathan Smith</a></h4>
                    <p>
                        Work for fun
                    </p>
                </div>
                <div class="user-status text-danger">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Anjelina Joe</a></h4>
                    <p>
                        Available
                    </p>
                </div>
                <div class="user-status text-success">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/chat-avatar2.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">John Doe</a></h4>
                    <p>
                        Away from Desk
                    </p>
                </div>
                <div class="user-status text-warning">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1_small.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Mark Henry</a></h4>
                    <p>
                        working
                    </p>
                </div>
                <div class="user-status text-info">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Shila Jones</a></h4>
                    <p>
                        Work for fun
                    </p>
                </div>
                <div class="user-status text-danger">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <p class="text-center">
                <a href="#" class="view-btn">View all Contacts</a>
            </p>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head purple-bg active">
        <span class="pull-left"> recent activity (3)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        just now
                    </p>
                    <p>
                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        2 min ago
                    </p>
                    <p>
                        <a href="#">Jane Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        1 day ago
                    </p>
                    <p>
                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head yellow-bg active">
        <span class="pull-left"> shipment status</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="col-md-12">
                <div class="prog-row">
                    <p>
                        Full sleeve baby wear (SL: 17665)
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                            <span class="sr-only">40% Complete</span>
                        </div>
                    </div>
                </div>
                <div class="prog-row">
                    <p>
                        Full sleeve baby wear (SL: 17665)
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                            <span class="sr-only">70% Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</li>
</ul>
</div>
</div>
<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<script src="js/bootstrap-switch.js"></script>

<script type="text/javascript" src="js/indexScript.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>

<script type="text/javascript" src="js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

<script src="js/jquery-tags-input/jquery.tagsinput.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>


<!--common script init for all pages-->
<script src="js/scripts.js"></script>

<script src="js/toggle-init.js"></script>

<script src="js/advanced-form.js"></script>


<!--Morris Chart-->
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="http://cdn.oesmith.co.uk/morris-0.4.1.min.js"></script>


</body>
</html>
