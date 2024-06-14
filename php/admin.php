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
            <h2>Administração</h2>
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
                    
                    include("conexao.php");

                   
                    $search = "";
                    $sql = "SELECT * FROM solicitacoes";

                
                    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
                        
                        $search = mysqli_real_escape_string($conn, $_GET['search']);
                   
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

            
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
                      
                        $id = mysqli_real_escape_string($conn, $_POST['id']);

                    
                        $delete_sql = "DELETE FROM solicitacoes WHERE id = '$id'";

                        if ($conn->query($delete_sql) === TRUE) {
                            echo "<script>alert('Solicitação eliminada com sucesso!');</script>";
                       
                            echo "<script>window.location = 'admin.php';</script>";
                        } else {
                            echo "<script>alert('Erro ao eliminar a solicitação: " . $conn->error . "');</script>";
                        }
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
