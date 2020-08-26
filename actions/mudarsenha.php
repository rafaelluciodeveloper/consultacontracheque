<?php

include_once '../conf/conexao.php';
header('Content-Type: application/json');

$cpf = trim($_POST['cpf']);
$senha_antiga = trim(md5($_POST['senhaantiga']));
$senha_nova = trim(md5($_POST['senhanova']));


$resultado_cpf = mysqli_query($conexao, "SELECT * FROM TrabalhadoresSenhas WHERE Cpf='$cpf'");
$resultado_senha = mysqli_query($conexao, "SELECT * FROM TrabalhadoresSenhas WHERE Cpf='$cpf' and Senha='$senha_antiga'");


if (mysqli_num_rows($resultado_cpf) <= 0) {
    echo json_encode("CPF Não Encontrado em Nossa Base de Dados.");
} else {
    if (mysqli_num_rows($resultado_senha) >= 1) {
        if (mysqli_query($conexao, "UPDATE TrabalhadoresSenhas SET Senha ='$senha_nova' WHERE Cpf='$cpf'")) {
            echo json_encode("Senha Alterada com Sucesso.");
        } else {
            echo json_encode("Não Foi Possivel Alterar Senha,Tente Novamente.");
        }
    } else {
        echo json_encode("Senha antiga informada divergente da cadastrada em nossa base de dados.");
    }
}