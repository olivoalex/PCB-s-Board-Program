<?php
ini_set('default_charset','UTF-8');
/**
 * Sistema de segurança com acesso restrito
 *
 * Usado para restringir o acesso de certas páginas do site
 */
//  Configuracoes do Script
// ==============================
$_SG['conectaServidor'] = true;    // Abre uma conexão com o servidor MySQL?
$_SG['abreSessao'] = true;         // Inicia a sessão com um session_start()?
$_SG['validaSempre'] = true;       // Deseja validar o usuário e a senha a cada carregamento de página?
// Evita que, ao mudar os dados do usuário no banco de dado o mesmo contiue logado.
// const HOST ="localhost";
// const UNAME ="agrotech_u_intel";
// const PW ="OlvAgrotechlink1357";
// const DBNAME = "agrotech_intel";
//$_SG['servidor'] = 'www.agrotechlink.com';    // Servidor MySQL
//$_SG['servidor'] = '1.2.3.4';       // Servidor MySQL
$_SG['servidor'] = 'localhost';       // Servidor MySQL
$_SG['usuario'] = 'agrotech_u_intel';            // Usuario MySQL
$_SG['senha'] = 'OlvAgrotechlink1357';           // Senha MySQL
$_SG['banco'] = 'agrotech_intel';                // Banco de dados MySQL
$_SG['paginaLogin'] = 'login.php';                   // Página de login
$_SG['tabela'] = 'usuario';                      // Nome da tabela onde os usuários são salvos
// ==============================
// ======================================
// Verifica se precisa fazer a conexão com o MySQL
if ($_SG['conectaServidor'] == true) {
    $_SG['link'] = mysqli_connect($_SG['servidor'], $_SG['usuario'], $_SG['senha']) or die("MySQL: Não foi possível conectar-se ao servidor [".$_SG['servidor']."]." ); mysqli_connect_error();
    mysqli_set_charset($_SG['link'],"utf8");
    mysqli_select_db($_SG['link'], $_SG['banco']) or die("MySQL: Não foi possível conectar-se ao banco de dados [".$_SG['banco']."].");
}
// Verifica se precisa iniciar a sessão
if ($_SG['abreSessao'] == true)
    session_start();
/**
 * Função que valida um usuário e senha
 *
 * @param string $usuario - O usuário a ser validado
 * @param string $senha - A senha a ser validada
 *
 * @return bool - Se o usuário foi validado ou não (true/false)
 */
function validaUsuario($usuario, $senha) {
    global $_SG;
    // Usa a função addslashes para escapar as aspas
    $nusuario = addslashes($usuario);
    $nsenha = addslashes($senha);
    // Monta uma consulta SQL (query) para procurar um usuário
    $sql = "SELECT email_login, senha_login FROM usuario WHERE email_login='$nusuario' AND senha_login='$nsenha'";
    $query = mysqli_query($_SG['link'], $sql);
    $resultado = mysqli_fetch_assoc($query);
    // Verifica se encontrou algum registro
    if (empty($resultado)) {
        // Nenhum registro foi encontrado => o usuário é inválido
        return false;
    } else {
        // Definimos dois valores na sessão com os dados do usuário
        $_SESSION['usuarioLogin'] = $resultado['email_login']; // Pega o valor da coluna 'email' do registro encontrado no MySQL
        $_SESSION['usuarioSenha'] = $resultado['senha_login']; // Pega o valor da coluna 'senha' do registro encontrado no MySQL
        return true;
    }

}
/**
 * Função que cadastra usuario
 */
function cadastraUsuario($nome, $sobrenome, $cpf, $nascimento, $email, $celular, $sexo, $senha){
    global $_SG;
    $Cnome = addslashes($nome);
    $Csobrenome = addslashes($sobrenome);
    $Ccpf = addslashes($cpf);
    $Cnascimento = addslashes($nascimento);
    $Cemail = addslashes($email);
    $Ccelular = addslashes($celular);
    $Csexo = addslashes($sexo);
    $Csenha = addslashes($senha);

    date_default_timezone_set('America/Sao_Paulo');
    $CCadastro = date('Y-m-d H:i:s');

    $Csql = "INSERT INTO usuario (id_usuario, cpf_usuario, nome_usuario, sobrenome_usuario, nascimento, sexo, telefone_celular, email_login, 
            senha_login, data_cadastro) VALUES (NULL, '$Ccpf', '$Cnome', '$Csobrenome', '$Cnascimento', '$Csexo', '$Ccelular', 
            '$Cemail', '$Csenha', '$CCadastro')";
    $Cquerry = mysqli_query($_SG['link'], $Csql);

    if ($Cquerry) {
        return true;
    }
    else {
        expulsaVisitante();
    }

}
/**
 * Função que verifica cadastro existente!
 */
function verificaCadastro($CPF) {
    global $_SG;
    $CPF_Banco = addslashes($CPF);

    $Vsql = "SELECT id_usuario FROM usuario WHERE cpf_usuario = '$CPF_Banco'";
    $Vquery = mysqli_query($_SG['link'], $Vsql);
    $Vresultado = mysqli_fetch_assoc($Vquery);

    if (empty($Vresultado)) {
        return true;
    } else {
        return false;
    }
}

function protegePagina() {
    global $_SG;
    if (!isset($_SESSION['usuarioLogin']) OR !isset($_SESSION['usuarioSenha'])) {
        // Não há usuário logado, manda pra página de login
        expulsaVisitante();
    } else if (!isset($_SESSION['usuarioLogin']) OR !isset($_SESSION['usuarioSenha'])) {
        // Há usuário logado, verifica se precisa validar o login novamente
        if ($_SG['validaSempre'] == true) {
            // Verifica se os dados salvos na sessão batem com os dados do banco de dados
            if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'])) {
                // Os dados não batem, manda pra tela de login
                expulsaVisitante();
            }
        }
    }
}

/**
 * Função para expulsar um visitante
 */
function expulsaVisitante() {
    global $_SG;
    /**
     * Função que protege uma página
     */
    // Remove as variáveis da sessão (caso elas existam)
    unset($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']);
    // Manda pra tela de login
    header("Location: ".$_SG['paginaLogin']);
}
