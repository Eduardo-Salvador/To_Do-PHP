<?php
// Inicia a sessão para acessar os dados do usuário logado
session_start();

// Importa o arquivo de conexão com o banco de dados
require __DIR__ . '/../Data Base/connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_user'])) {
  // Se não estiver logado, redireciona para a página de login
  header('Location: ../Login/login.html');
  exit();
}

// Pega o ID do usuário da sessão 
$id_user = (int)$_SESSION['id_user'];

// Verifica se a requisição foi via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Pega a ação enviada pelo formulário (adicionar, toggle, delete)
  $action = $_POST['action'] ?? '';

  // Adicionar nova tarefa 
  if ($action === 'add') {
    // Obtém o título e a descrição da tarefa
    $title = trim($_POST['title'] ?? '');
    $desc  = trim($_POST['description'] ?? ''); // Descrição é opcional

    // Só insere se o título não estiver vazio
    if ($title !== '') {
      // Comando SQL para inserir a tarefa
      $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
      $stmt->bind_param('iss', $id_user, $title, $desc);
      $stmt->execute();
      $stmt->close();
    }

  //Alternar o status da tarefa 
  } elseif ($action === 'toggle') {
    // Pega o ID da tarefa a ser alterada
    $id_task = (int)($_POST['id_task'] ?? 0);

    if ($id_task > 0) {
      // Altera o campo is_done 
      $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 - is_done WHERE id_task = ? AND user_id = ?");
      $stmt->bind_param('ii', $id_task, $id_user);
      $stmt->execute();
      $stmt->close();
    }

  //Excluir tarefa 
  } elseif ($action === 'delete') {
    // Pega o ID da tarefa a ser excluída
    $id_task = (int)($_POST['id_task'] ?? 0);

    if ($id_task > 0) {
      // Deleta a tarefa correspondente ao id_task e ao usuário atual
      $stmt = $conn->prepare("DELETE FROM tasks WHERE id_task = ? AND user_id = ?");
      $stmt->bind_param('ii', $id_task, $id_user);
      $stmt->execute();
      $stmt->close();
    }
  }

  // Redireciona de volta para a página após qualquer ação
  header('Location: initialTab.php'); 
  exit();
}

// Buscas tarefas do usuário de acordo com a ordem de criação
$res = $conn->prepare("SELECT id_task, title, description, is_done FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$res->bind_param('i', $id_user); // Coloca o ID do usuário à consulta
$res->execute();
$result = $res->get_result();


$tasks = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$res->close();
?>
