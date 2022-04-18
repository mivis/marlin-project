<?php session_start(); ?>
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
                                Добавление пользователя
                            </h5>
                            <!-- вывод логина -->
                            <?php
                            if ($_SESSION['login']) {?>
                            <h2>
                                You're <b><?=$_SESSION['login']?></b>
                            </h2>
                            <?php 
                            }
                                if(isset($_SESSION['error'])) {
                                    echo '<div class="alert alert-info">'.$_SESSION['error'].'</div>';
                                    unset($_SESSION['error']);
                                }
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

                                    // выборка инофрмации из БД для заполнения полей формы
                                    if ($_GET['id']) {
                                        $id = $_GET['id'];
                                        $sql = "SELECT * FROM users WHERE id = :id";
                                        $statement = $pdo->prepare($sql);
                                        $statement->execute(array( 'id' => $id ));
                                        $user = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    }  

                                    //
                                    // ПРОВЕРКА ПРАВ НА ИЗМЕНЕНИЕ ДАННЫХ
                                    //
                                    if ($_SESSION['role'] == 'admin' || $_SESSION['id'] == $_GET['id']) {

                                        //проверка на наличие аватарки для отображения на странице
                                        if (empty($user[0]['avatar']) || !file_exists("my_img/avatars/".$user[0]['avatar'])) {
                                            $avatar = 'my_img/avatars/avatar-default.png';
                                        } else {
                                            $avatar = "my_img/avatars/".$user[0]['avatar'];
                                        }  
                                        ?>
                                        <div class="frame-wrap">
                                            <form method="POST" enctype="multipart/form-data" action="my_actions/edit-action.php?id=<?=$id?>">
                                                <div class="form-group">
                                                    <label class="form-label" for="simpleinput">Логин</label>
                                                    <input type="text" id="simpleinput" class="form-control" name="login" value="<?=$user[0]['login']?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="example-email-2">Email</label>
                                                    <input type="email" id="example-email-2" class="form-control" placeholder="Email" name="email" value="<?=$user[0]['email']?>">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="example-password">Password</label>
                                                    <input type="password" id="example-password" class="form-control" name="password" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="example-select">Роль</label>
                                                    <select class="form-control" id="example-select" name="role">
                                                        <option value="user" <?php if ($user[0]['role']=="user") echo "selected";?>>Обычный пользователь</option>
                                                        <option value="manager" <?php if ($user[0]['role']=="manager") echo "selected";?>>Контент-менеджер</option>
                                                        <option value="admin" <?php if ($user[0]['role']=="admin") echo "selected";?>>Администратор</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="example-fileinput">Аватар</label>
                                                    <input type="file" id="example-fileinput" class="form-control-file" name="avatar">
                                                    <img src="<?=$avatar?>" width="100">
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-warning">Изменить</button>
                                                </div>
                                            </form>
                                        </div>
                                    <?php
                                    } else { 
                                        echo "<h2>Недостаточно прав для редактирования</h2>";
                                    }
                                    ?>
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
