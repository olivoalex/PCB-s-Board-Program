<?php
ini_set('default_charset','UTF-8');
/**
 * Função cadastra o usuario no MySQL
 */
require_once("seguranca.php");
// Verifica se um formulário foi enviado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = (isset($_POST['nome'])) ? $_POST['nome'] : '';
    $sobrenome = (isset($_POST['sobrenome'])) ? $_POST['sobrenome'] : '';
    $cpf = (isset($_POST['cpf'])) ? $_POST['cpf'] : '';
    $nascimento = (isset($_POST['nascimento'])) ? $_POST['nascimento'] : '';
    $email= (isset($_POST['email'])) ? $_POST['email'] : '';
    $celular = (isset($_POST['celular'])) ? $_POST['celular'] : '';
    $sexo = (isset($_POST['sexo'])) ? $_POST['sexo'] : '';

    if ($sexo == "Masc"){
        $sexo = "M";
    }
    else {
        $sexo = "F";
    }

    $senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
    $aceito = (isset($_POST['aceito'])) ? $_POST['aceito'] : '';

    if ($aceito == "1") {

        if (verificaCadastro($cpf) == true) {

            // Utiliza uma função criada no seguranca.php pra validar os dados digitados
            if (cadastraUsuario($nome, $sobrenome, $cpf, $nascimento, $email, $celular, $sexo, $senha) == true) {
                // O usuário e a senha digitados foram validados, manda pra página interna
                header("Location: http://1.2.3.4/login.php");
            } else {
                // O usuário e/ou a senha são inválidos, manda de volta pro form de login
                // Para alterar o endereço da página de login, verifique o arquivo seguranca.php
                expulsaVisitante();
            }
        }
        else {
            header("Location: http://1.2.3.4/registrar.php");
        }
    }
    else {

    }
}
