<?php
ini_set('default_charset','UTF-8');

require_once("seguranca.php");

protegePagina();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $_SG;

    $macID_LigaA = (isset($_POST['LigaBotaoA'])) ? $_POST['LigaBotaoA'] : '';
    $macID_DesligaA = (isset($_POST['DesligaBotaoA'])) ? $_POST['DesligaBotaoA'] : '';

    $macID_LigaB = (isset($_POST['LigaBotaoB'])) ? $_POST['LigaBotaoB'] : '';
    $macID_DesligaB = (isset($_POST['DesligaBotaoB'])) ? $_POST['DesligaBotaoB'] : '';

    if ($macID_LigaA != null) {
        $sql1 = "UPDATE cont_BP2R SET  r_A =  '1' WHERE  mac =  '$macID_LigaA'";
        $query1 = mysqli_query($_SG['link'], $sql1);
    }
    if ($macID_LigaB != null) {
        $sql2 = "UPDATE cont_BP2R SET  r_B =  '1' WHERE  mac =  '$macID_LigaB'";
        $query2 = mysqli_query($_SG['link'], $sql2);
    }
    //________________________//
    if ($macID_DesligaA != null) {
        $sql3 = "UPDATE cont_BP2R SET  r_A =  '0' WHERE  mac =  '$macID_DesligaA'";
        $query3 = mysqli_query($_SG['link'], $sql3);
    }
    if ($macID_DesligaB != null) {
        $sql4 = "UPDATE cont_BP2R SET  r_B =  '0' WHERE  mac =  '$macID_DesligaB'";
        $query4 = mysqli_query($_SG['link'], $sql4);
    }

    header("Location: http://1.2.3.4/indexPhp.php");
}
?>