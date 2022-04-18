<?php
session_start();
    if ($_POST) {
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

        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = :email";
        $statement = $pdo->prepare($sql);
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // если email нет в БД
        if (empty($user)) {
            $_SESSION['error_noemail'] = "Пользователя с таким email не существует";            
            header('Location: ../login.php');
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            $_SESSION['error_password'] = "Пароль не совпадает";
            header('Location: ../login.php');
            exit;
        } else {
            $_SESSION['id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../index.php');
        }
    }

?>