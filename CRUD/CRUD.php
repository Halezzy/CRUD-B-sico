<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Alunos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adicionar Aluno
if(isset($_POST['adicionar_aluno'])){
    $nome = $_POST['nome'];
    $ra = $_POST['ra'];
    $cpf = $_POST['cpf'];
    $dt_nasc = $_POST['dt_nasc'];

    $sql = "INSERT INTO Aluno (Nome, RA, CPF, DT_Nasc) VALUES ('$nome', '$ra', '$cpf', '$dt_nasc')";

    if ($conn->query($sql) === TRUE) {
        echo "Aluno adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar aluno: " . $conn->error;
    }
}

// Consultar Alunos
$sql_alunos = "SELECT * FROM Aluno";
$result_alunos = $conn->query($sql_alunos);

// Deletar Aluno
if(isset($_GET['delete_aluno'])){
    $id = $_GET['delete_aluno'];

    $sql = "DELETE FROM Aluno WHERE ID = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Aluno deletado com sucesso!";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else {
        echo "Erro ao deletar aluno: " . $conn->error;
    }
}

// Atualizar Aluno
if(isset($_POST['atualizar_aluno'])){
    $ids = $_POST['aluno_id'];
    $nomes = $_POST['nome'];
    $ras = $_POST['ra'];
    $cpfs = $_POST['cpf'];
    $dt_nascs = $_POST['dt_nasc'];

    // Iterar sobre os arrays e atualizar os dados do aluno correspondente
    for ($i = 0; $i < count($ids); $i++) {
        $id = $ids[$i];
        $nome = $nomes[$i];
        $ra = $ras[$i];
        $cpf = $cpfs[$i];
        $dt_nasc = $dt_nascs[$i];

        $sql = "UPDATE Aluno SET Nome='$nome', RA='$ra', CPF='$cpf', DT_Nasc='$dt_nasc' WHERE ID=$id";

        if ($conn->query($sql) !== TRUE) {
            echo "Erro ao atualizar aluno ID $id: " . $conn->error . "<br>";
        } else {
            echo "Aluno ID $id atualizado com sucesso!<br>";
        }
    }

    // Recarregar a pesquisa
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Alunos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navigation-menu">
        <h2>Menu de Navegação</h2>
        <ul>
            <li><a href="#Aluno">Aluno</a></li>
            <li><a href="#Endereço">Endereço</a></li>
            <li><a href="#Contato">Contato</a></li>
        </ul>
    </div>

    <div class="content">
    <style>
        #TituloCRUD {
            width: 100%;
            text-align: center; /* Centraliza o texto */
            margin-top: 50px; /* Espaço superior para o título */
        }
    </style>
        <div id="TituloCRUD">
           <h2> CRUD Alunos</h2> 
        </div>
   
        <div class="session">
            <section id="aluno">
                <!-- Formulário para adicionar novo aluno -->
                <h2>Adicionar Aluno</h2>
                <form action="" method="post">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome"><br><br>
                    <label for="ra">RA:</label>
                    <input type="text" id="ra" name="ra"><br><br>
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf"><br><br>
                    <label for="dt_nasc">Data de Nascimento:</label>
                    <input type="date" id="dt_nasc" name="dt_nasc"><br><br>
                    <input type="submit" name="adicionar_aluno" value="Adicionar Aluno">
                </form><br>
            </section>
        </div>

        <div class="session">
            <section id="Aluno">
                <!-- Formulário e tabela do aluno -->
                <h2>Listar Aluno</h2>
                <form action="" method="post">
                    <table>
                        <tr>
                            <th>Nome:</th>
                            <th>RA:</th>
                            <th>CPF:</th>
                            <th>Data de Nascimento:</th>
                            <th>Ações</th>
                        </tr>
                        <?php
                        if ($result_alunos->num_rows > 0) {
                            while($row = $result_alunos->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><input type='text' name='nome[]' value='".$row["Nome"]."'></td>";
                                echo "<td><input type='text' name='ra[]' value='".$row["RA"]."'></td>";
                                echo "<td><input type='text' name='cpf[]' value='".$row["CPF"]."'></td>";
                                echo "<td><input type='date' name='dt_nasc[]' value='".$row["DT_Nasc"]."'></td>";
                                echo "<td><input type='hidden' name='aluno_id[]' value='".$row["ID"]."'>";
                                echo "<input type='submit' name='atualizar_aluno[]' value='Atualizar'>";
                                echo " | <a href='{$_SERVER['PHP_SELF']}?delete_aluno=".$row["ID"]."'>Deletar</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Nenhum aluno cadastrado</td></tr>";
                        }
                        ?>
                    </table>
                </form>
            </section>
        </div>

        <div class="session">
            <section id="Endereço">
                <h2>Listar Endereços</h2>
            </section>
        </div>

        <div class="session">
            <section id="Contato">
                <h2>Listar Contatos </h2>
            </section>
        </div>
    </div>
</body>
</html>
