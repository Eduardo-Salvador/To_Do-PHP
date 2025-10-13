<?php
session_start();
include __DIR__ . "/../Model/connection.php";

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@admin.com') {
  header('Location: ../View/login.html?error=notadmin');
  exit();
}

$res = $conn->prepare("SELECT id_user, username, email, birthdate FROM user WHERE email <> ? ORDER BY username ASC");
$adminEmail = 'admin@admin.com';
$res->bind_param('s', $adminEmail);
$res->execute();
$result = $res->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/admin.css" />
  <title>Admin | To Do</title>
</head>

<body>
  <div class="general-wrapper">
    <div class="welcome-text-wrapper">
      <h1>Admin Area</h1>
      <h2>User Management</h2>
      <div class="top-buttons">
        <button onclick="window.location.href='add_user.php'" class="add-button">+ Add User</button>
        <button onclick="window.location.href='logout.php'" class="logout-button">Logout</button>
      </div>
    </div>

    <div class="table-container">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Birthdate</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['birthdate'] ? date('d/m/Y', strtotime($row['birthdate'])) : '-' ?></td>
              <td class="action-buttons">
                <form action="edit_user.php" method="GET" style="display:inline;">
                  <input type="hidden" name="id" value="<?= $row['id_user'] ?>">
                  <button class="edit-button" title="Editar">&#9998;</button>
                </form>
                <form action="delete_user.php" method="POST" style="display:inline;" onsubmit="return confirm('Deseja realmente excluir este usuário?');">
                  <input type="hidden" name="id" value="<?= $row['id_user'] ?>">
                  <button class="delete-button" title="Excluir">&#10006;</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>