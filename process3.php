<?php
$host = "localhost";
$dbname = "escola_infantil";
$username = "root"; // Altere para o seu usuário do MySQL
$password = ""; // Altere para sua senha do MySQL

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM alunos WHERE id = $id");
    $aluno = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];

    // Atualizando no banco de dados
    $stmt = $conn->prepare("UPDATE alunos SET nome=?, data_nascimento=?, email=?, telefone=?, endereco=?, sexo=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nome, $data_nascimento, $email, $telefone, $endereco, $sexo, $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redireciona de volta
    } else {
        echo "Erro ao atualizar aluno.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJqKXw8iR+J6B1gkzT80aOfHWxL9k7lhjX3MEm3tkN2xVSbIFC4SHzL+xZCE" crossorigin="anonymous
