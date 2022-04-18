<?php
session_start();
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
                            <?php }?>
                            <div class="frame-wrap">
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

                                    if ($_GET['id']) {
                                        $id = $_GET['id'];
                                        $sql = "SELECT * FROM users WHERE id = $id";
                                        $statement = $pdo->prepare($sql);
                                        $statement->execute();
                                        $user = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        //проверка на наличие аватарки
                                        if (empty($user[0]['avatar']) || file_exists("my_img/avatars/".$user[0]['avatar'])==false) {
                                            $avatar = 'my_img/avatars/avatar-default.png';
                                        } else {
                                             $avatar = "my_img/avatars/".$user[0]['avatar'];
                                        }                                       
                                    }
                                    
                                    //
                                    // ПРОВЕРКА НА ПРАВО ПРОСМОТРА EMAIL, ROLE
                                    //???????????????????????????????????????????????????????????????????????????????????
                                    if ($_SESSION['role'] == 'admin' || $_SESSION['id'] == $_GET['id']) {
                                        $safety = "yes";
                                    }
                                    ?>

                                    <tbody>
                                        <tr>
                                            <th scope="row">1</th>
                                            <td>
                                                <img src="<?=$avatar?>" width="75">
                                            </td>
                                            <td><?=$user[0]['login']?></td>
                                            <td>
                                                <?php
                                                if ($safety=='yes') {
                                                    echo $user[0]['role'];
                                                } else {
                                                    echo 'недостаточно прав';
                                                }                                            
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($safety=='yes') {
                                                    echo $user[0]['email'];
                                                } else {
                                                    echo 'недостаточно прав';
                                                }                                            
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($safety=='yes') {?>
                                                <a href="edit.php?id=<?=$user[0]['id']?>" class="btn btn-warning">Изменить</a>
                                                <a href="delete.php?id=<?=$user[0]['id']?>" class="btn btn-danger">Удалить</a>
                                                <?php
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                        </tr>
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
