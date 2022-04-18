<?php
    session_start();
    if ($_GET['id']) {
        // PDO CONNECT
        try {
            $driver = 'mysql';
            $host = 'localhost';
            $db_name = 'marlin-project';
            $db_user = 'root';
            $db_password = '';
            $charset = 'utf8';
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            $dsn = "$driver:host=$host;dbname=$db_name;charset=$charset";
            $pdo = new PDO($dsn, $db_user, $db_password, $options);
        }
        catch(PDOException $e) {  
            echo $e->getMessage();  
        }

        $id = $_GET['id'];
        // удаление файла аватарки
        $sql = "SELECT avatar FROM users WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $avatarName = $statement->fetch(PDO::FETCH_ASSOC);

        //
        // ПРОВЕРКА ПРАВ НА ИЗМЕНЕНИЕ ДАННЫХ
        //
        if ($_SESSION['role'] == 'admin' || $_SESSION['id'] == $_GET['id']) {
            if (!empty($avatarName['avatar'])) {
            unlink ('my_img/avatars/'.$avatarName['avatar']);
            }

            //удаление данных из БД
            $sql = "DELETE FROM users WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute(['id' => $id]);

            //удаление сессии(тольбко для пользователя, не админа) и редирект на главную
            if ($_SESSION['role'] !== 'admin') {
                session_unset();             
            }
            header('Location: index.php');
        } else {
            $_SESSION['error_permission'] = "У вас недостаточно прав для действия";
            header('Location: index.php');
        }
    }
?>