<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>
    <form action="register.php" method="post">
    <input type="text" name="username" placeholder="Имя пользователя" required>
    <input type="email" name="email" placeholder="Адрес электронной почты" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <input type="password" name="confirm_password" placeholder="Подтверждение пароля" required>
    <button type="submit">Зарегистрироваться</button>
</form>


<?php
// Подключаем к базе данных
$db = new mysqli('localhost', 'username', 'password', 'database_name');

// Проверка соединения
if ($db->connect_error){
    die("Ошибка подключения: " . $db->connect_error);
}

// Получаем данные из формы
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

//Проверка заполнености полей
if(empty($username) || empty($email) || empty($password) || empty($confirm_password)){
    echo "Пожалуйста, заполните все поля.";
    exit;
}

// Совпадение пароля и подтверждения пароля
if ($password != $confirm_password){
    echo "Пароли не совпадают";
    exit;
}

// Существует ли пользователь с этим именем или постой
$query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
$result = $db->query($query);

if ($result->num_rows > 0){
    echo "Пользователь с таким именем пользователя или адресом электронной почты уже существует.";
    exit;
}

// Хеширование пароля 
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Добавление пользователя в базу данный
$insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
if ($db->query($insert_query) === TRUE){
    echo "Пользователь успешно зарегистрирован.";
} else {
    echo "Ошибка при регистрации пользователя: " . $db->error;
}

// Закрытие соединения с базой данных
$db->close();
?>
</body>
</html>