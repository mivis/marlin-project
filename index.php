<?php
session_start();
if ($_GET['logout']=="1") {
    session_unset();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <title>
            Подготовительные задания к курсу
        </title>
        <meta name="description" content="Chartist.html">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
        <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
        <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
        <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
        <link rel="stylesheet" media="screen, print" href="css/statistics/chartist/chartist.css">
        <link rel="stylesheet" media="screen, print" href="css/miscellaneous/lightgallery/lightgallery.bundle.css">
        <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
        <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
        <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">
    </head>
    <body class="mod-bg-1 mod-nav-link ">
        <main id="js-page-content" role="main" class="page-content">
            <div class="col-md-8">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>
                            Задание
                        </h2>
                        <div class="panel-toolbar">
                            <button class="btn btn-panel waves-effect waves-themed" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                            <button class="btn btn-panel waves-effect waves-themed" data-action="panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></button>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <h5 class="frame-heading">
                                Обычная таблица
                            </h5>
                            <!-- вывод логина -->
                            <?php if ($_SESSION['login']) {?>
                            <h2>
                                You're <b><?=$_SESSION['login']?></b>
                            </h2>
                            <?php }

                            // вывод ошибке при отсутсивии прав не действие
                            if (!empty($_SESSION['error_permission'])) {
                                echo '<div class="alert alert-info">'.$_SESSION['error_permission'].'</div>';
                                unset($_SESSION['error_permission']);
                            }
                            ?>
                            <div class="frame-wrap">
                            	<div class="form-group">
                            		<a href="create.php" class="btn btn-success">Добавить пользователя</a>
                                    <a href="login.php" class="btn btn-info">Логин</a>
                                    <?php if (!empty($_SESSION)):?>
                                    <a href="index.php?logout=1" class="btn btn-danger">Выход</a>
                                    <?php endif;?>
                            	</div>
                                <div class="alert alert-secondary" role="alert">
                                    <b>Admin:</b> Права на изменение, удаление и просмотр данных всех пользователей</br>
                                    <b>User:</b> Права на изменение и удаление только своих данных. Просмотр email и роли других пользователей ограничен<br>
                                    "Добавить пользователя" оставил для теста. Можно также открыть только для админа.<br>
                                    <br>
                                    admin login: admin@admin.com<br>
                                    password: admin
                                    <br><br>
                                    user login: test@test.com<br>
                                    password: test
                                </div>
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Фото</th>
                                            <th>Логин</th>
                                            <th>Роль</th>
                                            <th>Email</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <?php

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
                                    
                                    $sql = "SELECT * FROM users";
                                    $statement = $pdo->prepare($sql);
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($result as $user) {
                                            // проверка на наличие аватарки в БД и существование файла аватарки
                                            if (empty($user['avatar']) || file_exists("my_img/avatars/".$user['avatar'])==false) {
                                                $user['avatar'] = 'my_img/avatar-default.png';
                                            } else {
                                                $user['avatar'] = "my_img/avatars/".$user['avatar'];
                                            }
                                        ?>
                                        <tr>
                                            <th scope="row"><?=$i;?></th>
                                            <td>
                                                <img src="<?=$user['avatar'];?>" width="75">
                                            </td>
                                            <td><?=$user['login'];?></td>
                                            <td><?=$user['role'];?></td>
                                            <td><?=$user['email'];?></td>
                                            <td>
                                                <a href="show.php?id=<?=$user['id']?>" class="btn btn-info">Посмотреть</a>
                                                <a href="edit.php?id=<?=$user['id']?>" class="btn btn-warning">Изменить</a>
                                                <a href="delete.php?id=<?=$user['id']?>" class="btn btn-danger">Удалить</a>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                        }
                                        ?>
                                    </tbody>                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        

        <script src="js/vendors.bundle.js"></script>
        <script src="js/app.bundle.js"></script>
        <script>
            // default list filter
            initApp.listFilter($('#js_default_list'), $('#js_default_list_filter'));
            // custom response message
            initApp.listFilter($('#js-list-msg'), $('#js-list-msg-filter'));
        </script>
    </body>
</html>
