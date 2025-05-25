<?php
include('auth.php');
include('config.php');

$mensagem = ""; // Variável para armazenar mensagens
$classe_mensagem = ""; // Variável para armazenar a classe da mensagem


$sql_banco = "SELECT codigo, nome FROM banco";
$pesquisar_banco = mysqli_query($con, $sql_banco);

if (isset($_POST['gravar'])) {
    // Receber as variáveis do formulário HTML
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];
    $saldoinicial = $_POST['saldoinicial'];
    $saldoatual = $_POST['saldoatual'];
    $agencia = $_POST['agencia'];
    $digitoagencia = $_POST['digitoagencia'];
    $digito = $_POST['digito'];
    $codbanco = $_POST['codbanco'];

    // Inserir dados na tabela "contacorrente"
    $sql = "INSERT INTO contacorrente (codigo, descricao, saldoinicial, saldoatual, agencia, digitoagencia, digito, codbanco)
            VALUES ('$codigo', '$descricao', '$saldoinicial', '$saldoatual', '$agencia', '$digitoagencia', '$digito', '$codbanco')";

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

if (isset($_POST['alterar'])) {
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];
    $saldoinicial = $_POST['saldoinicial'];
    $saldoatual = $_POST['saldoatual'];
    $agencia = $_POST['agencia'];
    $digitoagencia = $_POST['digitoagencia'];
    $digito = $_POST['digito'];
    $codbanco = $_POST['codbanco'];

    // Atualizar dados na tabela "contacorrente"
    $sql = "UPDATE contacorrente SET descricao = '$descricao', saldoinicial = '$saldoinicial', 
            saldoatual = '$saldoatual', agencia = '$agencia', digitoagencia = '$digitoagencia', 
            digito = '$digito', codbanco = '$codbanco' WHERE codigo = '$codigo'";

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

if (isset($_POST['excluir'])) {
    $codigo = $_POST['codigo'];

    // Excluir dados da tabela "contacorrente"
    $sql = "DELETE FROM contacorrente WHERE codigo = '$codigo'";
    $resultado = mysqli_query($con, $sql);

    // Exibir mensagem com base no resultado da operação
    if ($resultado) {
        $mensagem = "Dados excluídos com sucesso.";
        $classe_mensagem = "mensagem-sucesso";
    } else {
        $mensagem = "Erro ao excluir os dados: " . mysqli_error($con);
        $classe_mensagem = "mensagem-erro";
    }
} elseif ($_POST['acao'] == 'pesquisar') {
    // Lógica para pesquisa
    $search_term = $_POST['search_term'];
    $query = "SELECT * FROM contacorrente WHERE codigo LIKE '%$search_term%' OR descricao LIKE '%$search_term%'";
    $result = mysqli_query($con, $query);
} else {
    // Lógica padrão para obter e exibir todos os registros da tabela "contacorrente"
    $query = "SELECT * FROM contacorrente";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/style.css">
    <link rel="icon" href="img/oliares.png" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Conta Corrente</title>
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
        <form name="formulario" method="post" action="contacorrente.php">
            <h1>Cadastro Corrente</h1><br><br>
            Código:
            <input type="text" name="codigo" id="codigo" size=50 required>
            <br>
            Descrição:
            <input type="text" name="descricao" id="descricao" size=50 required>
            <br>
            Saldo Inicial:
            <input type="number" name="saldoinicial" id="saldoinicial" size=50 required>
            <br>
            Saldo Atual:
            <input type="number" name="saldoatual" id="saldoatual" size=50 required>
            <br>
            Agência:
            <input type="text" name="agencia" id="agencia" size=50 required>
            <br>
            Digito Agência:
            <input type="text" name="digitoagencia" id="digitoagencia" size=50 required>
            <br>
            Digito:
            <input type="text" name="digito" id="digito" size=50 required>
            <br>
            Banco:
            <select name="codbanco" id="codbanco" required>
                <option value=0 selected="selected">Selecione o banco...</option>
                <?php
                if (mysqli_num_rows($pesquisar_banco) == 0) {
                    echo '<h1>Sua busca por banco não retornou resultados...</h1>';
                } else {
                    while ($resultado = mysqli_fetch_array($pesquisar_banco)) {
                        echo '<option value="' . $resultado['codigo'] . '">' . utf8_encode($resultado['nome']) . '</option>';
                    }
                }
                ?>
            </select>
            <br><br>
            <input type="submit" name="gravar" id="gravar" value="Gravar">
            <input type="submit" name="alterar" id="alterar" value="Alterar">
          
            
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
                    <input type="text" id="search-input" name="search_term" style="height:30px;" placeholder="Digite o código ou Banco para pesquisar">
                    <button type="submit" id="search-button" name="acao" value="pesquisar" style="height: 40px;">Pesquisar</button>
                </form>
            </div>
            <table>
                <tr>
                    <th>Código</th>
                    <th>Descrição</th>
                    <th>Saldo Inicial</th>
                    <th>Saldo Atual</th>
                    <th>Agência</th>
                    <th>Dígito Agência</th>
                    <th>Dígito</th>
                    <th>Banco</th>
                    <th>Ação</th>
                </tr>
                <?php
               
while ($row = mysqli_fetch_assoc($result)) {
    // Obtenha o código do banco da linha atual
    $codBanco = $row['codbanco'];

    // Consulta para obter o nome do banco com base no código
    $consultaBanco = "SELECT nome FROM banco WHERE codigo = $codBanco";
    $resultBanco = mysqli_query($con, $consultaBanco);

    // Verifique se a consulta foi bem-sucedida
    if ($resultBanco) {
        // Obtenha o nome do banco da consulta
        $rowBanco = mysqli_fetch_assoc($resultBanco);
        $nomeBanco = $rowBanco['nome'];

        // Exiba os dados na tabela
        echo "<tr>
        <td>{$row['codigo']}</td>
        <td>{$row['descricao']}</td>
        <td>R$ {$row['saldoinicial']}</td>
        <td>R$ {$row['saldoatual']}</td>
        <td>{$row['agencia']}</td>
        <td>{$row['digitoagencia']}</td>
        <td>{$row['digito']}</td>
        <td>{$nomeBanco}</td>
        <td>
                    <form method='post' action='contacorrente.php' onsubmit='return confirmarExclusao();'>
                        <input type='hidden' name='codigo' value='{$row['codigo']}'>
                        <input type='hidden' name='descricao' value='{$row['descricao']}'>
                        <input type='hidden' name='saldoinicial' value='{$row['saldoinicial']}'>
                        <input type='hidden' name='saldoatual' value='{$row['saldoatual']}'>
                        <input type='hidden' name='agencia' value='{$row['agencia']}'>
                        <input type='hidden' name='digitoagencia' value='{$row['digitoagencia']}'>
                        <input type='hidden' name='digito' value='{$row['digito']}'>
                        <input type='hidden' name='codbanco' value='{$nomeBanco}'>
                        <input type='submit' name='excluir' value='Excluir'  class='botao-excluir'>
                    </form>
                </td>
    </tr>";


    } else {
        // Trate o erro na consulta do banco
        echo "Erro na consulta do banco: " . mysqli_error($con);
    }
}

// Lembre-se de fechar o resultado da consulta do banco
mysqli_free_result($resultBanco);

// Feche a conexão com o banco de dados
mysqli_close($conn);
?>
            </table>
        </div>
    </div>
       <!-- Ícone de guia -->
       <a id="guia-icon" href="ajuda.html">&#9432;</a>
    </div>
</body>
</html>
