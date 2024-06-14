<?php 
session_start();


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.html");
    exit;
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Solicitações de Passaporte</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Menu</h2>
            <a href="#">Ver Solicitações</a>
            <a href="#">Sair</a>
        </div>

        <div class="content">
            <h2>Lista de Solicitações de Passaporte</h2>
            <div class="search">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Buscar por ID, Nome ou Email">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Incluir o arquivo de conexão
                    include("conexao.php");

                    // Inicializar variáveis de pesquisa
                    $search = "";
                    $sql = "SELECT * FROM solicitacoes";

                    // Verificar se foi submetido um formulário de pesquisa
                    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
                        // Limpar e validar o parâmetro de pesquisa
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                        // Construir a consulta SQL com base no parâmetro de pesquisa
                        $sql = "SELECT * FROM solicitacoes 
                                WHERE id LIKE '%$search%' 
                                   OR nome LIKE '%$search%' 
                                   OR email LIKE '%$search%'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['nome'] . "</td>";
                            echo "<td>" . $row['data_nascimento'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['telefone'] . "</td>";
                            echo "<td>" . $row['endereco'] . "</td>";
                            echo "<td>
                                    <form method='POST' action='admin.php' onsubmit='return confirm(\"Tem certeza que deseja eliminar esta solicitação?\")'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                                        <button type='submit' name='delete'>Eliminar</button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Nenhuma solicitação encontrada.</td></tr>";
                    }

                    // Processar a eliminação se o formulário de eliminação foi enviado
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                        // Limpar e validar o parâmetro de ID
                        $id = mysqli_real_escape_string($conn, $_POST['id']);

                        // Construir a consulta SQL para eliminar a solicitação
                        $delete_sql = "DELETE FROM solicitacoes WHERE id = '$id'";

                        if ($conn->query($delete_sql) === TRUE) {
                            echo "<script>alert('Solicitação eliminada com sucesso!');</script>";
                            // Atualizar a página após a eliminação (evita reenvio do formulário)
                            echo "<script>window.location = 'admin.php';</script>";
                        } else {
                            echo "<script>alert('Erro ao eliminar a solicitação: " . $conn->error . "');</script>";
                        }
                    }

                    // Fechar conexão com o banco de dados
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
