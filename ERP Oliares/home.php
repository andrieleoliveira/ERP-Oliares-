<?php
include("auth.php");
include('config.php');

// Execute uma consulta SQL para obter o saldo atual da conta corrente
$sqlSaldoContaCorrente = "SELECT saldoatual FROM contacorrente LIMIT 1";
$resultadoSaldoContaCorrente = mysqli_query($con, $sqlSaldoContaCorrente);

if ($rowSaldoContaCorrente = mysqli_fetch_assoc($resultadoSaldoContaCorrente)) {
    $saldoContaCorrente = (float) $rowSaldoContaCorrente['saldoatual'];
} else {
    $saldoContaCorrente = 0;
}

// Execute uma consulta SQL para obter o valor total das contas a pagar
$sqlTotalContasPagar = "SELECT SUM(valor) as total FROM contapagar";
$resultadoTotalContasPagar = mysqli_query($con, $sqlTotalContasPagar);

if ($rowTotalContasPagar = mysqli_fetch_assoc($resultadoTotalContasPagar)) {
    $totalContasPagar = (float) $rowTotalContasPagar['total'];
} else {
    $totalContasPagar = 0;
}

// Execute uma consulta SQL para obter o valor total das contas a receber
$sqlTotalContasReceber = "SELECT SUM(valor) as total FROM contareceber";
$resultadoTotalContasReceber = mysqli_query($con, $sqlTotalContasReceber);

if ($rowTotalContasReceber = mysqli_fetch_assoc($resultadoTotalContasReceber)) {
    $totalContasReceber = (float) $rowTotalContasReceber['total'];
} else {
    $totalContasReceber = 0;
}

// Execute uma consulta SQL para obter os dados do gráfico
$sql = "SELECT MONTH(vencimento) as mes, SUM(valor) as total FROM contapagar GROUP BY mes";
$resultado = mysqli_query($con, $sql);

// Formatando os dados para o gráfico
$dados_grafico = array();
$dados_grafico[] = ['Mês', 'Total'];

while ($row = mysqli_fetch_assoc($resultado)) {
    $mes = $row['mes'];
    $total = (float) $row['total'];
    $dados_grafico[] = [$mes, $total];
}

// Execute uma consulta SQL para obter os dados da tabela de pessoas
$sqlPessoas = "SELECT codigo, nome, cpf FROM pessoa";
$resultadoPessoas = mysqli_query($con, $sqlPessoas);

$dados_pessoa = array();

while ($rowPessoa = mysqli_fetch_assoc($resultadoPessoas)) {
    $dados_pessoa[] = [$rowPessoa['codigo'], $rowPessoa['nome'], $rowPessoa['cpf']];
}

// Feche a conexão com o banco de dados
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="Style/home.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load("current", {
            packages: ["corechart"]
        });

        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($dados_grafico); ?>);

            var options = {
                title: 'Contas a pagar',
                is3D: true,
            };

            var monthNames = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

            for (var i = 0; i < data.getNumberOfRows(); i++) {
                var monthNumber = data.getValue(i, 0);
                var monthName = monthNames[monthNumber];
                data.setFormattedValue(i, 0, monthName);
            }

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }

        function openMenu() {
            document.getElementById("menu").style.width = "250px";
            document.getElementById("main").classList.add("menu-opened");
            var cards = document.getElementsByClassName("card");
            for (var i = 0; i < cards.length; i++) {
                cards[i].classList.add("menu-opened");
            }
            var containers = document.querySelectorAll("#chart-container, #tabela-container");
            containers.forEach(function(container) {
                container.classList.add("menu-opened");
            });
        }

        function closeMenu() {
            document.getElementById("menu").style.width = "0";
            document.getElementById("main").classList.remove("menu-opened");
            var cards = document.getElementsByClassName("card");
            for (var i = 0; i < cards.length; i++) {
                cards[i].classList.remove("menu-opened");
            }
            var containers = document.querySelectorAll("#chart-container, #tabela-container");
            containers.forEach(function(container) {
                container.classList.remove("menu-opened");
            });
        }

        function toggleSubMenu(link) {
            var subMenu = link.nextElementSibling;
            if (subMenu.style.display === 'block') {
                subMenu.style.display = 'none';
            } else {
                subMenu.style.display = 'block';
            }
        }
    </script>
    
    <link rel="icon" href="img/oliares.png" type="image/x-icon">
</head>

<body>
<div id="topo">
        <span style="font-size: 40px; cursor: pointer; float: left; padding-left: 20px;" onclick="openMenu()">&#9776;</span>
       
    </div>
    <div id="menu">
        <a href="javascript:void(0)" class="closebtn" onclick="closeMenu()">&#9665; </a>
        <a href="#" onclick="toggleSubMenu(this)">Cadastros</a>
        <div class="sub-menu">
            <a href="pessoa.php">Cadastro de Pessoas</a>
            <a href="tamanhos.php">Cadastro de tamanhos</a>
            <a href="colecao.php">Cadastro de coleção </a>
            <a href="marca.php">Cadastro de marca </a>
            <a href="grupos.php">Cadastro de grupos </a>
            <a href="subgrupo.php">Cadastro de subgrupo </a>
            <a href="produtos.php">Cadastro de produtos </a>
            <a href="controledeestoque.php">Retirar Produto</a>
        </div>
        <a href="#" onclick="toggleSubMenu(this)">Administração</a>
        <div class="sub-menu">
            <!-- Adicione sub-opções financeiras aqui -->
            <a href="cadastroForm.php">Cadastro de Login</a>
        </div>
        <a href="#" onclick="toggleSubMenu(this)">Financeiro</a>
        <div class="sub-menu">
            <a href="contacorrente.php">Cadastro de contas correntes </a>
            <a href="banco.php">Cadastro de bancos </a>
            <a href="contaspagar.php">Contas a pagar </a>
            <a href="contasreceber.php">Conta a receber </a>
            <a href="movimento.php">Movimento conta a receber</a>
            <a href="movimentopagar.php">Movimento conta a pagar</a>
        </div>
        <a href="produtostamanho.php">Estoque</a>
    </div>
    <div id="main">
        
        <div class="container">
            <a href="contaspagar.php" class="card">
                <h2>Total de contas a pagar</h2>
                <p><span class='total-contas-pagar'>R$ <?php echo number_format($totalContasPagar, 2, ',', '.'); ?></span></p>
            </a>

            <a href="contasreceber.php" class="card">
                <h2>Total de contas a receber</h2>
                <p><span class='total-contas-receber'>R$ <?php echo number_format($totalContasReceber, 2, ',', '.'); ?></span></p>
            </a>

            <a href="contacorrente.php" class="card">
                <h2>Saldo da Conta Corrente</h2>
                <p><span class='saldo-conta-corrente'>R$ <?php echo number_format($saldoContaCorrente, 2, ',', '.'); ?></span></p>
            </a>
        </div>

        <div class="container">
            <div id="chart-container">
                <div id="piechart_3d" style="width: 100%; height: 100%;"></div>
            </div>

            <div id="tabela-container">
                <h2>Clientes</h2>
                <table>
    <tr>
        <th>Código</th>
        <th>Nome</th>
        <th>CPF</th>
    </tr>
    <?php
    foreach ($dados_pessoa as $row) {
        // Formatar o CPF para o formato padrão (###.###.###-##)
        $cpf_formatado = maskCpf($row[2]);

        echo "<tr><td>{$row[0]}</td><td>{$row[1]}</td><td>{$cpf_formatado}</td></tr>";
    }

    // Função para formatar CPF
    function maskCpf($cpf){
        $cpf = preg_replace("/\D/", '', $cpf);
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }
    ?>
</table>

            </div>
        </div>
    </div>
</body>

</html>
