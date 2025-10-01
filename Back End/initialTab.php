<?php 
    session_start();
    // Conexão com o banco.
    include __DIR__ . "/../Data Base/connection.php";

    // Faz a verificação para ver se realmente o usuario esta logado, caso não esteja ele redireciona para o login.
    if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
    }

    // Pega o id e o nome do usuario na sessão
    $uid = (int)$_SESSION['id_user'];
    $name = $_SESSION['username'];

    // Verifica se o metodo que recebeu é do tipo POST
    if($server['Request Method'] == 'POST'){
        $acao = $_POST['acao'];
        // Verifica se a ação é de ADD
            if($acao === 'adicionar'){
                $title = trim($_POST['titulo']);
                if (!empty($title)){
                    $stmt = $conn->prepare('INSERT INTO tasks (user_id, title, is_done) VALUES (:uid, :title, 0)');
                    $stmt->execute([':uid'=>$uid, ':title'=>$title]);
                }
        // verifica se a ação é do tipo DELETE         
        }elseif ($acao === 'delete'){
            $task_id = (int)$_SESSION['tasks_id'];
        }

        header('Location: index.php');
        exit;    
    }

?>    