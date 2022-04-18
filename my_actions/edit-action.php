<?php
session_start();
// выборка инофрмации из БД
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
                                        $sql = "SELECT * FROM users WHERE id = :id";
                                        $statement = $pdo->prepare($sql);
                                        $statement->execute(array( 'id' => $id ));
                                        $user = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    }

                                    //
                                    // ПРОВЕРКА ПРАВ НА ИЗМЕНЕНИЕ ДАННЫХ
                                    //
                                    if ($_SESSION['role'] == 'admin' || $_SESSION['id'] == $_GET['id']) {   
                                        // функция изменения имейла                                 
                                        function update_email($emailPost, $emailBd, $id, $pdo) {
                                            $sql = "SELECT * FROM users WHERE email=:email";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute(['email' => $emailPost]);
                                            $user = $statement->fetch(PDO::FETCH_ASSOC);                                                
                                            // проверка на существование email в БД
                                            if (!empty($user)) {
                                                $_SESSION['error'] = "Пользователь с таким email уже существует";
                                                header('Location: ../edit.php?id='.$id); 
                                                 exit;
                                            }
                                            $sql = "UPDATE users SET email = :email WHERE id = :id";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute([
                                                'email' => $emailPost,
                                                'id' => $id
                                            ]);                                        
                                        }  
                                        
                                        // функция изменения логина
                                        function update_login($loginPost, $loginBd, $id, $pdo) {
                                            $sql = "UPDATE users SET login = :login WHERE id = :id";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute([
                                                'login' => $loginPost,
                                                'id' => $id
                                            ]);                                        
                                        }
                                        
                                        // функция изменения пароля
                                        function update_password($passwordPost, $id, $pdo) {
                                            $sql = "UPDATE users SET password = :password WHERE id = :id";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute([
                                                'password' => password_hash($passwordPost, PASSWORD_DEFAULT),
                                                'id' => $id
                                            ]);                                        
                                        }

                                        // функция изменения роли
                                        function update_role($rolePost, $roleBd, $id, $pdo) {
                                            $sql = "UPDATE users SET role = :role WHERE id = :id";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute([
                                                'role' => $rolePost,
                                                'id' => $id
                                            ]);                                        
                                        }

                                        // функция изменения аватарки
                                        function update_avatar($fileName, $fileTmpName, $id, $pdo) {
                                            //удаление старой аватарки при установке новой
                                            $sql = "SELECT avatar FROM users WHERE id=:id";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute(['id' => $id]);
                                            $user = $statement->fetch(PDO::FETCH_ASSOC);
                                            if (!empty($user['avatar'])) {
                                                unlink ('../my_img/avatars/'.$user['avatar']);
                                            }
                                            //добавление новой аватарки
                                            $uniqid = uniqid();
                                            $avatarName = $uniqid."-".$fileName;
                                            $avatarPath = "../my_img/avatars/".$uniqid."-".$fileName;          
                                            move_uploaded_file($fileTmpName, $avatarPath);
                                            $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
                                            $statement = $pdo->prepare($sql);
                                            $statement->execute([
                                                'avatar' => $avatarName,
                                                'id' => $id
                                            ]);
                                            
                                        }
                                        
                                        //условия для запуска функций
                                        if ($_POST) {
                                            if ($_POST['email'] !== $user[0]['email']) {                                            
                                                update_email($_POST['email'], $user[0]['email'], $id, $pdo);
                                            }
                                            if ($_POST['login'] !== $user[0]['login']) {
                                                update_login($_POST['login'], $user[0]['login'], $id, $pdo);
                                            }                                        
                                            if (!empty($_POST['password'])) {
                                                update_password($_POST['password'], $id, $pdo);
                                            }
                                            if ($_POST['role'] !== $user[0]['role']) {
                                                update_role($_POST['role'], $user[0]['role'], $id, $pdo);
                                            }
                                            if (!empty($_FILES['avatar']['name'])) {
                                                update_avatar($_FILES['avatar']['name'], $_FILES['avatar']['tmp_name'], $id, $pdo);
                                            }
                                        
                                        header('Location: ../edit.php?id='.$id);
                                        }
                                    }
                            ?>