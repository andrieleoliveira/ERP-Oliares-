<?php
include('auth.php');
include('config.php');

$mensagem = ""; // Variável para armazenar mensagens
$classe_mensagem = ""; // Variável para armazenar a classe da mensagem

$sql_pessoa = "SELECT codigo, nome FROM pessoa";
$pesquisar_pessoa = mysqli_query($con, $sql_pessoa);

if (isset($_POST['gravar'])) {
    // Receber as variáveis do formulário HTML
    $codigo = $_POST['codigo'];
    $parcela = $_POST['parcela'];
    $valor = $_POST['valor'];
    $status = $_POST['status'];
    $vencimento = $_POST['vencimento'];
    $dataconta = $_POST['dataconta'];
    $codpessoa = $_POST['codpessoa'];

    // Inserir dados na tabela "contareceber"
    $sql = "INSERT INTO contareceber (codigo, parcela, valor, status, vencimento, dataconta, codpessoa)
            VALUES ('$codigo', '$parcela', '$valor', '$status', '$vencimento', '$dataconta', '$codpessoa')";

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
    $parcela = $_POST['parcela'];
    $valor = $_POST['valor'];
    $status = $_POST['status'];
    $vencimento = $_POST['vencimento'];
    $dataconta = $_POST['dataconta'];
    $codpessoa = $_POST['codpessoa'];

    // Atualizar dados na tabela "contareceber"
    $sql = "UPDATE contareceber SET parcela = '$parcela', valor = '$valor', status = '$status'
            WHERE codigo = '$codigo'";

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

    // Excluir dados da tabela "contareceber"
    $sql = "DELETE FROM contareceber WHERE codigo = '$codigo'";
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
    $query = "SELECT * FROM contareceber WHERE codigo LIKE '%$search_term%' OR codpessoa LIKE '%$search_term%'";
    $result = mysqli_query($con, $query);
} else {
    // Lógica padrão para obter e exibir todos os registros da tabela "contareceber"
    $query = "SELECT * FROM contareceber";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contas a Receber</title>
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
        <form name="formulario" method="post" action="contasreceber.php">
            <h1>Cadastro a Receber</h1><br><br>
            Código:
            <input type="text" name="codigo" id="codigo" size=50 required>
            <br>
            Parcela:
            <input type="number" name="parcela" id="parcela" size=50 required>
            <br>
            Valor:
            <input type="number" name="valor" id="valor" size=50 required>
            <br>
            Status:
            <select id="status" name="status" required>
                <option value="Aberta">Aberta</option>
                <option value="Paga">Paga</option>
                <option value="Vencida">Vencida</option>
            </select>
            <br>
            Vencimento:
            <input type="date" name="vencimento" id="vencimento" size=50 required>
            <br>
            Data da Conta:
            <input type="date" name="dataconta" id="dataconta" size=50 required>
            <br>
            Pessoa:
            <select name="codpessoa" id="codpessoa" required>
                <option value=0 selected="selected">Selecione a pessoa...</option>
                <?php
                if (mysqli_num_rows($pesquisar_pessoa) == 0) {
                    echo '<h1>Sua busca por pessoa não retornou resultados...</h1>';
                } else {
                    while ($resultado = mysqli_fetch_array($pesquisar_pessoa)) {
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
                    <input type="text" id="search-input" name="search_term" style="height:30px;" placeholder="Digite o código ou pessoa para pesquisar ">
                    <button type="submit" id="search-button" name="acao" value="pesquisar" style="height: 40px;">Pesquisar</button>
                </form>
            </div>
            <table>
                <tr>
                    <th>Código</th>
                    <th>Parcela</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Vencimento</th>
                    <th>Data da Conta</th>
                    <th>Pessoa</th>
                    <th>Ação</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    // Obtenha o código do pessoa da linha atual
                    $codPessoa = $row['codpessoa'];
                
                    // Consulta para obter o nome do banco com base no código
                    $consultaPessoa = "SELECT nome FROM pessoa WHERE codigo = $codPessoa";
                    $resultPessoa = mysqli_query($con, $consultaPessoa);
                
                    // Verifique se a consulta foi bem-sucedida
                    if ($resultPessoa) {
                        // Obtenha o nome do Pessoa da consulta
                        $rowPessoa = mysqli_fetch_assoc($resultPessoa);
                        $nomePessoa = $rowPessoa['nome'];
                
                        // Formate o valor como uma quantia de dinheiro
                        $valorFormatado = "R$ " . number_format($row['valor'], 2, ',', '.');
                
                        echo "<tr>
                                <td>{$row['codigo']}</td>
                                <td>{$row['parcela']}</td>
                                <td>{$valorFormatado}</td>
                                <td>{$row['status']}</td>
                                <td>{$row['vencimento']}</td>
                                <td>{$row['dataconta']}</td>
                                <td>{$nomePessoa}</td>
                                <td>
                                <form method='post' action='contasreceber.php' onsubmit='return confirmarExclusao();'>
                                    <input type='hidden' name='codigo' value='{$row['codigo']}'>
                                    <input type='hidden' name='parcela' value='{$row['parcela']}'>
                                    <input type='hidden' name='valor' value='{$valorFormatado}'>
                                    <input type='hidden' name='status' value='{$row['status']}'>
                                    <input type='hidden' name='vencimento' value='{$row['vencimento']}'>
                                    <input type='hidden' name='dataconta' value='{$row['dataconta']}'>
                                    <input type='hidden' name='codpessoa' value='{$nomePessoa}'>
                                    <input type='submit' name='excluir' value='Excluir'class='botao-excluir'>
                                </form>
                </td>
                            </tr>";
                    } else {
                        // Trate o erro na consulta do banco
                        echo "Erro na consulta do banco: " . mysqli_error($con);
                    }
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

