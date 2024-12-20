<?php
// Conectar ao banco de dados
$conn = new mysqli("localhost", "root", "", "academia");
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$result = $conn->query("SELECT alunos.*, planos.nome AS plano_nome FROM alunos LEFT JOIN planos ON alunos.plano_id = planos.id");

echo "<h2>Lista de Alunos</h2>";
echo "<table class='table table-striped'>";
echo "<thead><tr><th>ID</th><th>Nome</th><th>Plano</th><th>Ações</th></tr></thead><tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nome']}</td>
            <td>{$row['plano_nome']}</td>
            <td><a href='editar.php?id={$row['id']}'>Editar</a> | <a href='deletar.php?id={$row['id']}'>Deletar</a></td>
          </tr>";
}

echo "</tbody></table>";
$conn->close();
?>
