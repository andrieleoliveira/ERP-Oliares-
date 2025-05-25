<?php
include('auth.php');
include('config.php');

$mensagem = ""; // Variável para armazenar mensagens
$classe_mensagem = ""; // Variável para armazenar a classe da mensagem

$sql_produtos = "SELECT codigo, descricao FROM produtos ";
$pesquisar_produtos = mysqli_query($con, $sql_produtos);

$sql_tamanhos = "SELECT codigo, tamanhos FROM tamanhos ";
$pesquisar_tamanhos = mysqli_query($con, $sql_tamanhos);

if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $quantidade = $_POST['quantidade'];

    // Consulta para obter a quantidade atual do produto
    $query = "SELECT quantidadeatual FROM produtostamanho WHERE codigo = '$codigo'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $quantidadeatual = $row['quantidadeatual'];

        // Agora você pode continuar com o restante do código
        $novaQuantidade = $quantidadeatual - $quantidade;

        $updateQuery = "UPDATE produtostamanho SET quantidadeatual = $novaQuantidade WHERE codigo = '$codigo'";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($resultado) {
            $mensagem = "Dados alterados com sucesso.";
            $classe_mensagem = "mensagem-sucesso";
        } else {
            $mensagem = "Erro ao alterar os dados: " . mysqli_error($con);
            $classe_mensagem = "mensagem-erro";
        }
    } else {
        $mensagem = "Produto não encontrado ou erro na consulta da quantidade atual. " . mysqli_error($con);
        $classe_mensagem = "mensagem-erro";
    }
} elseif ($_POST['pesquisar']) {
    // Lógica para pesquisa
    $search_term = $_POST['search_term'];
    $query = "SELECT * FROM produtostamanho WHERE codigo LIKE '%$search_term%' OR codproduto LIKE '%$search_term%'";
    $result = mysqli_query($con, $query);
} else {
    // Lógica padrão para obter e exibir todos os registros da tabela "produtostamanho"
    $query = "SELECT * FROM produtostamanho";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Tela Pagamento</title>
    <link rel="stylesheet" href="Style/style.css">
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
    </style>
</head>

<body>
    <div id="box">
        <div id='voltar'>
            <a href="home.php"><button type="submit" id="search-button" style="height: 40px;">Voltar</button></a>
        </div>
        <form name="formulario" method="post" action="controledeestoque.php">
            <h1>Tela de Pagamento</h1><br><br>
            Codigo:<input type="text" name="codigo" id="codigo" required><br>
            Quantidade: <input type="number" name="quantidade" id="quantidade" required><br><br>
            <input type="submit" name="alterar" id="alterar" value="Alterar">
            <input type="submit" name="pesquisar" id="pesquisar" value="Pesquisar">
            
        </form>
        <?php
        if (!empty($mensagem)) {
            echo "<p class='mensagem $classe_mensagem'>$mensagem</p>";
        }
        ?>
        <div class="container">
            <div id="buttons-container">
                <br>
                <form id="search-form" method="post" action="">
                    <input type="text" id="search-input" name="search_term" style="height:30px;" placeholder="Digite o código para pesquisar">
                    <button type="submit" id="search-button" name="pesquisar" value="pesquisar" style="height: 40px;">Pesquisar</button>
                </form>
            </div>
            <table>
                <tr>
                    <th>Código</th>
                    <th>Quantidade</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['codigo']}</td>
                            <td>{$row['quantidadeatual']}</td>
                    </tr>";
                }
                ?>
            </table>

        </div>
    </div>
</body>

</html>

