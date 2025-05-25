<?php
include('auth.php');

// Obter dados do formulário
$login = $_POST['login'];
$senha = $_POST['senha'];

// Verificar se a senha atende aos critérios
if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[^A-Za-z0-9])\S{8,}$/', $senha)) {
    echo "<script language='javascript' type='text/javascript'>
    alert('A senha deve conter pelo menos um dígito, uma letra, um símbolo e ter no mínimo 8 caracteres.');window.location.href='cadastroForm.php';</script>";
    die();
}

// Hash da senha
$senha = md5($senha);

// Conectar ao banco de dados
$connect = mysqli_connect('localhost', 'root', 'rootroot', 'empresatcc');
if (!$connect) {
    die('Erro na conexão com o banco de dados: ' . mysqli_connect_error());
}

// Verificar se o login já existe
$query_select = "SELECT login FROM usuarios WHERE login = '$login'";
$result_select = mysqli_query($connect, $query_select);
if (!$result_select) {
    die('Erro na consulta ao banco de dados: ' . mysqli_error($connect));
}

$row_count = mysqli_num_rows($result_select);
if ($row_count > 0) {
    echo "<script language='javascript' type='text/javascript'>
    alert('Esse login já existe');window.location.href='cadastroForm.php';</script>";
    die();
}

// Inserir novo usuário no banco de dados
$query = "INSERT INTO usuarios (login, senha) VALUES ('$login', '$senha')";
$result_insert = mysqli_query($connect, $query);

// Exibir mensagens com base no resultado da operação
if ($result_insert) {
    echo "<script language='javascript' type='text/javascript'>
    alert('Usuário cadastrado com sucesso!');window.location.href='login.html'</script>";
} else {
    echo "<script language='javascript' type='text/javascript'>
    alert('Não foi possível cadastrar esse usuário');window.location.href='cadastro.html'</script>";
}

// Fechar a conexão com o banco de dados
mysqli_close($connect);
?>


