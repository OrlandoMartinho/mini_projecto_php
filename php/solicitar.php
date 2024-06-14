<?php

include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $data_nascimento = mysqli_real_escape_string($conn, $_POST['data_nascimento']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefone = mysqli_real_escape_string($conn, $_POST['telefone']);
    $endereco = mysqli_real_escape_string($conn, $_POST['endereco']);

    $sql = "INSERT INTO solicitacoes (nome, data_nascimento, email, telefone, endereco)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);


    if ($stmt === false) {
        die("Erro na preparação da declaração SQL: " . $conn->error);
    } else {
      
        $stmt->bind_param("sssss", $nome, $data_nascimento, $email, $telefone, $endereco);

        if ($stmt->execute()) {
            echo "Solicitação de passaporte enviada com sucesso!";
        } else {
            echo "Erro ao enviar a solicitação: " . $stmt->error;
        }
    }

    // Fecha a declaração (statement)
    $stmt->close();
    
    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>
