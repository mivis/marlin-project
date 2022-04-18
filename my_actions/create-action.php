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

                                        $login = $_POST['login'];
                                        $email = $_POST['email'];
                                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                        $role = $_POST['role'];

                                        // проверка на существование email
                                        $sql = "SELECT * FROM users WHERE email=:email";
                                        $statement = $pdo->prepare($sql);
                                        $statement->execute(['email' => $email]);
                                        $user = $statement->fetch(PDO::FETCH_ASSOC);
                                        if (!empty($user)) {
                                            $_SESSION['error'] = "Пользователь с таким email уже существует";
                                            header('Location: ../create.php'); 
                                            exit;
                                        }

                                        // проверка, загружен аватар или нет
                                        if (empty($_FILES['avatar']['name'])) {
                                            $avatar='';
                                        } else {
                                            $uniqid = uniqid();
                                            $avatar = $uniqid."-".$_FILES['avatar']['name'];                                              
                                            move_uploaded_file($_FILES['avatar']['tmp_name'], "../my_img/avatars/".$uniqid."-".$_FILES['avatar']['name']);                                     
                                        }

                                        $sql = "INSERT INTO users (login, email, password, role, avatar) VALUES (:login, :email, :password, :role, :avatar)";
                                        $statement = $pdo->prepare($sql);
                                        $statement->execute([
                                            'login' => $login,
                                            'email' => $email,
                                            'password' => $password,
                                            'role' => $role,
                                            'avatar' => $avatar
                                        ]);

                                        $userId = $pdo -> lastInsertId();

                                        //редирект на страницу пользователя
                                        header('Location: ../show.php?id='.$userId);                                    
                                    }
?>