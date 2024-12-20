<?php
$host = "localhost";
$dbname = "escola_infantil";
$username = "root"; // Altere para o seu usuário do MySQL
$password = ""; // Altere para sua senha do MySQL

// Conectando ao banco de dados
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Cadastrar aluno (Create)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];

    // Inserir no banco de dados
    $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, email, telefone, endereco, sexo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $data_nascimento, $email, $telefone, $endereco, $sexo);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirecionar para a página inicial
    } else {
        echo "Erro ao cadastrar aluno.";
    }

    $stmt->close();
}

// Excluir aluno (Delete)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Deletando no banco de dados
    $stmt = $conn->prepare("DELETE FROM alunos WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirecionar para a página inicial
    } else {
        echo "Erro ao excluir aluno.";
    }

    $stmt->close();
}

$conn->close();
?>

