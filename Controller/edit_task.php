<?php
include __DIR__ . "/../Model/connection.php";

$id_task = $_GET['id'] ?? null;

if (!isset($id_task) || !is_numeric($id_task) || (int)$id_task <= 0) {
    die("Unspecified task");
}

$id_task = (int)$id_task;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $is_done = isset($_POST['is_done']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, is_done=? WHERE id_task=?");
    $stmt->bind_param('ssii', $title, $description, $is_done, $id_task);
    $stmt->execute();

    header("Location: initialTab.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id_task=?");
$stmt->bind_param('i', $id_task);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
if (!$task) die("Task not found");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

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
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.6);
        }

        .form-wrapper h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #F2F2F2;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #CCCCCC;
            font-weight: 500;
        }


        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #333;
            background-color: #2A2A2A;
            color: #F2F2F2;
            font-size: 16px;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus {
            border-color: #2196F3;
            box-shadow: 0 0 6px #2196F3;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.4;
        }

        .form-group label.checkbox-label {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 18px;
            color: #F2F2F2;
            cursor: pointer;
            padding: 8px 12px;
            background-color: #2A2A2A;
            border-radius: 6px;
            border: 1px solid #333;
            transition: all 0.2s ease;
        }

        .form-group label.checkbox-label:hover {
            background-color: #333;
        }

        .form-group input[type="checkbox"] {
            width: 24px;
            height: 24px;
            accent-color: #2196F3;
            margin: 0;
        }


        button.submit-button,
        button.cancel-button {
            width: 100%;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        button.submit-button {
            background-color: #2196F3;
            color: #FFF;
        }

        button.submit-button:hover {
            background-color: #1976D2;
        }

        button.cancel-button {
            background-color: #E53935;
            color: #FFF;
        }

        button.cancel-button:hover {
            background-color: #C62828;
        }

        @media (max-width: 640px) {
            .form-wrapper {
                padding: 20px;
            }

            .form-wrapper h2 {
                font-size: 24px;
            }

            .form-group input[type="text"],
            .form-group textarea {
                font-size: 14px;
                padding: 10px;
            }

            button.submit-button,
            button.cancel-button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="form-wrapper">
        <h2>Edit Task</h2>
        <form method="POST">
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($task['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_done" <?= $task['is_done'] ? 'checked' : '' ?>>
                    Done
                </label>
            </div>
            <button type="submit" class="submit-button">Save Changes</button>
            <button type="button" class="cancel-button" onclick="window.location.href='initialTab.php'">Cancel</button>
        </form>
    </div>
</body>

</html>