<?php
include('auth.php');
include('config.php');

$mensagem = ""; // Variável para armazenar mensagens
$classe_mensagem = ""; // Variável para armazenar a classe da mensagem

// Verificar se o formulário foi submetido para gravação
if (isset($_POST['gravar'])) {
    // Receber as variáveis do formulário HTML
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];

    // Inserir dados na tabela "grupo"
    $sql = "INSERT INTO grupo (codigo, descricao) VALUES ('$codigo', '$descricao')";
    $resultado = mysqli_query($con, $sql);

    // Exibir mensagem com base no resultado da operação
    if ($resultado) {
        $mensagem = "Dados gravados com sucesso.";
        $classe_mensagem = "mensagem-sucesso";
    } else {
        $mensagem = "Erro ao gravar os dados: " . mysqli_error($con);
        $classe_mensagem = "mensagem-erro";
    }
}

// Verificar se o formulário foi submetido para alteração
if (isset($_POST['alterar'])) {
    // Receber as variáveis do formulário HTML
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];

    // Atualizar dados na tabela "grupo"
    $sql = "UPDATE grupo SET descricao = '$descricao' WHERE codigo = '$codigo'";
    $resultado = mysqli_query($con, $sql);

    // Exibir mensagem com base no resultado da operação
    if ($resultado) {
        $mensagem = "Dados alterados com sucesso.";
        $classe_mensagem = "mensagem-sucesso";
    } else {
        $mensagem = "Erro ao alterar os dados: " . mysqli_error($con);
        $classe_mensagem = "mensagem-erro";
    }
}

// Verificar se o formulário foi submetido para exclusão
if (isset($_POST['excluir'])) {
    // Receber as variáveis do formulário HTML
    $codigo = $_POST['codigo'];

    // Excluir dados da tabela "grupo"
    $sql = "DELETE FROM grupo WHERE codigo = '$codigo'";
    $resultado = mysqli_query($con, $sql);

    // Exibir mensagem com base no resultado da operação
    if ($resultado) {
        $mensagem = "Dados excluídos com sucesso.";
        $classe_mensagem = "mensagem-sucesso";
    } else {
        $mensagem = "Erro ao excluir os dados: " . mysqli_error($con);
        $classe_mensagem = "mensagem-erro";
    }
}
// Verificar se a ação é de pesquisa
elseif ($_POST['acao'] == 'pesquisar') {
    // Lógica para pesquisa
    $search_term = $_POST['search_term'];
    $query = "SELECT * FROM grupo WHERE codigo LIKE '%$search_term%' OR descricao LIKE '%$search_term%'";
    $result = mysqli_query($con, $query);
} else {
    // Lógica padrão para obter e exibir todos os registros da tabela "grupo"
    $query = "SELECT * FROM grupo";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/style.css">
    <meta charset="UTF-8">
    <title>Cadastro de grupo</title>
    <link rel="icon" href="img/oliares.png" type="image/x-icon">
    <style>
        .mensagem {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
        }

        .mensagem-sucesso {
            background-color: #4CAF50; /* Verde */
            color: white;
        }

        .mensagem-erro {
            background-color: #f44336; /* Vermelho */
            color: white;
        }
        .botao-excluir {
            background-color: #f44336; /* Vermelho */
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
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
        <form name="formulario" method="post" action="grupos.php">
            <h1>Cadastro grupo</h1><br><br>
            Código:
            <input type="text" name="codigo" id="codigo" size=50 required>
            <br>
            Descrição:
            <input type="text" name="descricao" id="descricao" size=50 required>
            <br><br>
            <input type="submit" name="gravar"    id="gravar"    value="Gravar">
            <input type="submit" name="alterar"   id="alterar"   value="Alterar">
         
            
        </form>

        <!-- Exibição da mensagem -->
        <?php
        if (!empty($mensagem)) {
            echo "<p class='mensagem $classe_mensagem'>$mensagem</p>";
        }
        ?>
        <div class="container">
            <div id="buttons-container">
                <BR>
                <form id="search-form" method="post" action="">
                    <input type="text" id="search-input" name="search_term" style= "height:30px;" placeholder="Digite o código para pesquisar">
                    <button type="submit" id="search-button" name="acao" value="pesquisar" style="height: 40px;">Pesquisar</button>
                </form>
            </div>
            <table>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Ação</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['codigo']}</td>
                            <td>{$row['descricao']}</td>
                            <td>
                            <form method='post' action='grupos.php' onsubmit='return confirm(\"Deseja realmente excluir?\")'>
                                <input type='hidden' name='codigo' value='{$row['codigo']}'>
                                <input type='submit' name='excluir' value='Excluir'  class='botao-excluir'>
                            </form>
                </td>
                        </tr>";
                }
                ?>
            </table>
        </div>
    </div>
       <!-- Ícone de guia -->
       <a id="guia-icon" href="ajuda.html">&#9432;</a>
    </div>
</body>
</html>
