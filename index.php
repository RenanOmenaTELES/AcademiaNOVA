<?php
$host = "localhost";
$dbname = "academia";
$username = "root"; // Altere para o seu usuário do MySQL
$password = ""; // Altere para sua senha do MySQL

// Conectando ao banco de dados
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultar planos disponíveis
$planos_result = $conn->query("SELECT * FROM planos");

// Processando o formulário de cadastro de aluno (Create)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];
    $plano = $_POST['plano'];

    // Validações
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("E-mail inválido.");
    }

    if (!preg_match('/^\d{10,11}$/', $telefone)) {
        die("Telefone inválido.");
    }

    // Verificando idade
    $data_atual = new DateTime();
    $data_nasc = new DateTime($data_nascimento);
    $idade = $data_atual->diff($data_nasc)->y;
    if ($idade < 18) {
        die("O aluno deve ser maior de idade.");
    }

    // Inserindo no banco de dados
    $stmt = $conn->prepare("INSERT INTO alunos (nome, data_nascimento, email, telefone, endereco, sexo, plano_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $nome, $data_nascimento, $email, $telefone, $endereco, $sexo, $plano);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Cadastro realizado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro ao cadastrar aluno.</div>";
    }

    $stmt->close();
}

// Atualizar aluno (Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $data_nascimento = $_POST['data_nascimento'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $sexo = $_POST['sexo'];
    $plano = $_POST['plano'];

    // Atualizando no banco de dados
    $stmt = $conn->prepare("UPDATE alunos SET nome=?, data_nascimento=?, email=?, telefone=?, endereco=?, sexo=?, plano_id=? WHERE id=?");
    $stmt->bind_param("ssssssii", $nome, $data_nascimento, $email, $telefone, $endereco, $sexo, $plano, $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Aluno atualizado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro ao atualizar aluno.</div>";
    }

    $stmt->close();
}

// Deletar aluno (Delete)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Deletando no banco de dados
    $stmt = $conn->prepare("DELETE FROM alunos WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Aluno excluído com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Erro ao excluir aluno.</div>";
    }

    $stmt->close();
}

// Consultar alunos cadastrados
$alunos_result = $conn->query("SELECT * FROM alunos");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Aluno</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('imagens/academia.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Cadastro de Aluno</h2>
    <form action="cadastro.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome Completo:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>

        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="telefone">Telefone:</label>
            <input type="text" class="form-control" id="telefone" name="telefone" required>
        </div>

        <div class="form-group">
            <label for="endereco">Endereço:</label>
            <textarea class="form-control" id="endereco" name="endereco"></textarea>
        </div>

        <div class="form-group">
            <label for="sexo">Sexo:</label>
            <select class="form-control" id="sexo" name="sexo" required>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
                <option value="outro">Outro</option>
            </select>
        </div>

        <div class="form-group">
            <label for="plano">Escolha o Plano:</label>
            <select class="form-control" id="plano" name="plano" required>
                <?php while ($plano = $planos_result->fetch_assoc()) { ?>
                    <option value="<?php echo $plano['id']; ?>"><?php echo $plano['nome']; ?> - R$ <?php echo $plano['preco']; ?></option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" name="cadastrar">Cadastrar</button>
    </form>
</div>

<div class="container mt-5">
    <h2 class="text-center">Lista de Alunos</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Plano</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($aluno = $alunos_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $aluno['id']; ?></td>
                    <td><?php echo $aluno['nome']; ?></td>
                    <td><?php echo $aluno['email']; ?></td>
                    <td><?php echo $aluno['telefone']; ?></td>
                    <td><?php echo $aluno['plano_id']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $aluno['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="?delete=<?php echo $aluno['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$conn->close();
?>



