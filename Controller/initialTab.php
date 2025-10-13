<?php
session_start();
include __DIR__ . "/../Model/connection.php";

if (!isset($_SESSION['id_user'])) {

  header('Location: ../Controller/Login/login.html');
  exit();
}

$id_user = (int)$_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'add') {
    $title = trim($_POST['task-title'] ?? '');
    $desc  = trim($_POST['task-info'] ?? '');
    if ($title !== '') {
      $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
      $stmt->bind_param('iss', $id_user, $title, $desc);
      $stmt->execute();
      $stmt->close();
    }
  } elseif ($action === 'toggle') {
    $id_task = (int)($_POST['id_task'] ?? 0);
    if ($id_task > 0) {
      $stmt = $conn->prepare("UPDATE tasks SET is_done = 1 - is_done WHERE id_task = ? AND user_id = ?");
      $stmt->bind_param('ii', $id_task, $id_user);
      $stmt->execute();
      $stmt->close();
    }
  } elseif ($action === 'delete') {
    $id_task = (int)($_POST['id_task'] ?? 0);
    if ($id_task > 0) {
      $stmt = $conn->prepare("DELETE FROM tasks WHERE id_task = ? AND user_id = ?");
      $stmt->bind_param('ii', $id_task, $id_user);
      $stmt->execute();
      $stmt->close();
    }
  } elseif ($action === "edit") {
    $id_task = (int)($_POST['id_task']);
    $title = trim($_POST['task-title-edit'] ?? '');
    $desc  = trim($_POST['task-info-edit'] ?? '');
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ? WHERE id_task = ? AND user_id = ?");
    $stmt->bind_param("ssii", $title, $desc, $id_task, $id_user);
    $stmt->execute();
    $stmt->close();
  }
  header('Location: initialTab.php');
  exit();
}

$res = $conn->prepare("SELECT id_task, title, description, is_done FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$res->bind_param('i', $id_user);
$res->execute();
$result = $res->get_result();
$tasks = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$res->close();
function e($s)
{
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>To Do | DSW1</title>
  <link rel="icon" type="image/png" href="../assets/img/icon-to-do-list.png" />
  <link rel="stylesheet" href="../assets/initialtab.css" />
</head>

<body>

  <div class="general-wrapper">
    <div class="welcome-text-wrapper">
      <div class="welcome-texts">
        <h1 class="h1text">Welcome back!</h1>
        <h2>What's your plan for today?</h2>
      </div>
      <div>
        <label>
          <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
        </label>
      </div>
    </div>
    <div>
    </div>
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
                required />
              <input
                class="small-input"
                type="text"
                name="task-info"
                placeholder="About task (optional)..." />
              <button type="submit">ADD</button>
            </label>
          </li>
        </ul>
      </div>
    </form>
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
                <form action="edit_task.php" method="GET" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $t['id_task'] ?>">
                  <button type="submit" class="edit-button" title="Editar">&#9998;</button>
                </form>
                <form action="<?= e($_SERVER['PHP_SELF']) ?>" method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id_task" value="<?= $id ?>">
                  <div class="edit-button-wrapper">
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