<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário já está autenticado, redirecionar se estiver
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

// Verificar se foi submetido o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluir o arquivo de conexão com o banco de dados
    include("conexao.php");

    // Limpar e validar os dados de entrada
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Consulta SQL para verificar as credenciais do administrador
    $sql = "SELECT * FROM usuarios WHERE email = '$username' AND senha = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Credenciais corretas, iniciar a sessão
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;

        // Redirecionar para a página de administração
        header("Location: admin.php");
        exit;
    } else {
        // Credenciais incorretas, exibir mensagem de erro
        $error_message = "Usuário ou senha incorretos.";
    }

    // Fechar conexão com o banco de dados
    $conn->close();
}
?>