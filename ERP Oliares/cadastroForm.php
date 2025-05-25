<?php
include('config.php');

// Verificar se foi enviado o formulário de exclusão
if (isset($_POST['acao']) && $_POST['acao'] == 'excluir') {
    $id_to_delete = $_POST['id_to_delete'];
    $query_delete = "DELETE FROM usuarios WHERE ID = '$id_to_delete'";
    $result_delete = mysqli_query($con, $query_delete);

    // Verificar se a exclusão foi bem-sucedida
    if ($result_delete) {
        echo '<script>alert("Usuário excluído com sucesso.");</script>';
    } else {
        echo '<script>alert("Erro ao excluir o usuário. ' . mysqli_error($con) . '");</script>';
    }
}

// Lógica para pesquisa ou consulta padrão
if ($_POST['acao'] == 'pesquisar') {
    $search_term = $_POST['search_term'];
    $query = "SELECT * FROM usuarios WHERE ID LIKE '%$search_term%' OR login LIKE '%$search_term%'";
} else {
    $query = "SELECT * FROM usuarios";
}

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="Style/style.css">
    <meta charset="UTF-8">
    <link rel="icon" href="img/oliares.png" type="image/x-icon">
    <style>
        .erro {
            color: #f44336;
            margin-top: 5px;
        }
        #guia-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            padding: 10px;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        #guia-icon:hover {
            background-color: #0056b3;
        }
       
    </style>
</head>
<body>
    <div id="box">
        <div id='voltar'>
            <a href="home.php"><button type="submit" id="search-button" style="height: 40px;">Voltar</button></a>
        </div>
        <form method="POST" action="cadastro.php" onsubmit="return validarFormulario()">
            <h1>Usuários</h1><br><br>
            <label>Login:</label>
            <input type="text" name="login" id="login" required><br>
            
            <label>Senha:</label>
            <input type="password" name="senha" id="senha" required>
            <button type="button" class="password-toggle" onclick="togglePassword('senha')">Mostrar Senha</button>
            <br>
            <label>Confirme a Senha:</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" required>
            <button type="button" class="password-toggle" onclick="togglePassword('confirmar_senha')">Mostrar Senha</button><br>

            <span class="erro" id="erroSenha"></span><br>

            <input type="submit" value="Cadastrar" id="cadastrar" name="cadastrar">
        </form>

        <div class="container">
            <div id="buttons-container">
                <form id="search-form" method="post" action="">
                    <input type="text" id="search-input" name="search_term" style="height:30px;" placeholder="Digite o código ou Login para pesquisar">
                    <button type="submit" id="search-button" name="acao" value="pesquisar" style="height: 40px;">Pesquisar</button>
                </form>
            </div>

            <table>
                <tr>
                    <th>Código</th>
                    <th>Login</th>
                    <th>Ações</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['login']}</td>
                            <td>
                                <form method='post' action=''>
                                    <input type='hidden' name='id_to_delete' value='{$row['ID']}'>
                                    <input type='hidden' name='acao' value='excluir'>
                                    <button type='submit' onclick='return confirmarExclusao()' class='botao-excluir'>Excluir</button>
                                </form>
                            </td>
                        </tr>";
                }
                ?>
            </table>

            <script>
            function confirmarExclusao() {
                return confirm("Deseja realmente excluir?");
            }

            function togglePassword(inputId) {
                var input = document.getElementById(inputId);
                if (input.type === "password") {
                    input.type = "text";
                } else {
                    input.type = "password";
                }
            }

            function validarFormulario() {
                var senha = document.getElementById("senha").value;
                var confirmarSenha = document.getElementById("confirmar_senha").value;
                var erroSenha = document.getElementById("erroSenha");

                if (senha !== confirmarSenha) {
                    erroSenha.innerHTML = "As senhas não coincidem.";
                    return false;
                } else {
                    erroSenha.innerHTML = "";
                    return true;
                }
            }
            </script>

        </div>
    </div>
       <!-- Ícone de guia -->
       <a id="guia-icon" href="ajudaUsuario.html">&#9432;</a>
    </div>
</body>
</html>






