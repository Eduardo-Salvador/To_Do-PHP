<?php
include __DIR__ . "/../Model/connection.php";

$id = $_GET['id'] ?? null;
if (!$id) die("Usuário não especificado.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $birthdate = $_POST['birthdate'] ?? null;

    $stmt = $conn->prepare("UPDATE user SET username=?, email=?, birthdate=? WHERE id_user=?");
    $stmt->bind_param('sssi', $username, $email, $birthdate, $id);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM user WHERE id_user=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

body {
    background-color: #121212;
    font-family: "Roboto", sans-serif;
    min-height: 100vh;
    padding: 40px 20px;
    color: #F2F2F2;
}

.form-wrapper {
    max-width: 500px;
    margin: 50px auto;
    background-color: #1E1E1E;
    border-radius: 8px;
    padding: 30px 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.5);
}

.form-wrapper h2 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 25px;
    color: #F2F2F2;
    text-align: center;
}

.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    color: #CCCCCC;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #333;
    background-color: #2A2A2A;
    color: #F2F2F2;
    font-size: 16px;
    transition: all 0.2s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #2196F3;
    box-shadow: 0 0 6px #2196F3;
}

button.submit-button {
    background-color: #2196F3;
    color: #FFF;
    font-size: 16px;
    font-weight: 500;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    transition: all 0.2s ease;
    margin-top: 10px;
}

button.submit-button:hover {
    background-color: #1976D2;
}

button.cancel-button {
    background-color: #E53935;
    color: #FFF;
    font-size: 16px;
    font-weight: 500;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    transition: all 0.2s ease;
}

button.cancel-button:hover {
    background-color: #C62828;
}

@media (max-width: 640px) {
    .form-wrapper {
        padding: 20px 15px;
    }
    
    .form-wrapper h2 {
        font-size: 24px;
    }

    .form-group input {
        font-size: 14px;
    }

    button.submit-button,
    button.cancel-button {
        font-size: 14px;
        padding: 8px 16px;
    }
}
</style>
</head>
<body>
<div class="form-wrapper">
  <h2>Edit User</h2>
  <form method="POST">
    <div class="form-group">
      <label>Username:</label>
      <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>
    <div class="form-group">
      <label>Email:</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <div class="form-group">
      <label>Birthdate:</label>
      <input type="date" name="birthdate" value="<?= $user['birthdate'] ?>">
    </div>
    <button type="submit" class="submit-button">Save Changes</button>
    <button type="button" class="cancel-button" onclick="window.location.href='admin.php'">Cancel</button>
  </form>
</div>
</body>
</html>