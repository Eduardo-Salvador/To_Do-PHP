<?php
// Inicia a sessão para acessar os dados do usuário logado
session_start();

// Importa o arquivo de conexão com o banco de dados
  include __DIR__ . "/../Model/connection.php";

// Verifica se o usuário está logado
if (!isset($_SESSION['id_user'])) {
  // Se não estiver logado, redireciona para a página de login
  header('Location: ../Controller/Login/login.html');
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
    $title = trim($_POST['task-title'] ?? '');
    $desc  = trim($_POST['task-info'] ?? ''); // Descrição é opcional

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
  } elseif ($action === "edit"){
    $id_task = (int)($_POST['id_task']);
    $title = trim($_POST['task-title-edit'] ?? ''); //Pega do formulario de edição
    $desc  = trim($_POST['task-info-edit'] ?? ''); 
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id_task = ? AND user_id = ?");
    $stmt->bind_param("ssii", $title, $desc, $id_task, $id_user);
    $stmt->execute();
    $stmt->close();
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

function e($s) { 
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); 
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>To Do | DSW1</title>
  <link rel="icon" type="image/png" href="../assets/img/icon-to-do-list.png" />
  <link rel="stylesheet" href="../assets/initialTab.css"/>
</head>
<body>
  <div class="general-wrapper">
    <div class="welcome-text-wrapper">
      <h1>Welcome back!</h1>
      <h2>What's your plan for today?</h2>
    </div>

    <!-- 7) Form de ADD -->
    <form class="add-row" action="initialTab.php" method="POST" autocomplete="off">
    <input type="hidden" name="action" value="add">
    <div class="item-wrapper">
      <ul>
        <li>
          <label class="add-button-container">
            <input
              class="small-input"
              type="text"
              name="task-title"
              placeholder="Title..."
              required
            />
            <input
              class="small-input"
              type="text"
              name="task-info"
              placeholder="About task (optional)..."
            />
            <button type="submit">ADD</button>
          </label>
        </li>
      </ul>
    </div>
  </form>


    <!-- 8) Lista dinâmica -->
    <div class="item-wrapper">
      <ul>
        <?php if (empty($tasks)): ?>
          <li><span style="color:#ccc; font-style:italic;">Let's go! No tasks registered yet.</span></li>
        <?php else: ?>
          <?php foreach ($tasks as $t): 
            $id   = (int)$t['id_task'];
            $done = (int)$t['is_done'] === 1;
            $title = e($t['title']);
            $desc  = e($t['description']);
          ?>
            <li>
              <div class="custom-checkbox-container <?= $done ? 'done' : '' ?>">
                <!-- Form TOGGLE -->
                <form action="<?= e($_SERVER['PHP_SELF']) ?>" method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="toggle">
                  <input type="hidden" name="id_task" value="<?= $id ?>">
                  <input type="checkbox" <?= $done ? 'checked' : '' ?> onchange="this.form.submit()">
                </form>

                <span class="item-text">
                  <?= $title ?>
                  <?php if ($desc !== ''): ?>
                    <small> — <?= $desc ?></small>
                  <?php endif; ?>
                </span>

                <!-- Form DELETE -->
                <form action="<?= e($_SERVER['PHP_SELF']) ?>" method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id_task" value="<?= $id ?>">
                  <div class="edit-button-wrapper">
                    <button class="edit-button" value="edit-button" type="submit" title="Editar">E</button>
                    <button class="delete-button" type="submit" title="Excluir">X</button>
                  </div>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</body>
</html>