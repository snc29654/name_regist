<?php

require_once('config.php');

//POSTのvalidate
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}
//DB内でPOSTされたメールアドレスを検索
try {
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $stmt = $pdo->prepare('select * from userDeta2nd where email = ?');
  $stmt->execute([$_POST['email']]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
  echo $e->getMessage() . PHP_EOL;  
}
//emailがDB内に存在しているか確認
if (!isset($row['email'])) {
  echo 'メールアドレス又はパスワードが間違っています。';
  return false;
}
//パスワード確認後sessionにメールアドレスを渡す
if (password_verify($_POST['password'], $row['password'])) {
  $_SESSION['EMAIL'] = $row['email'];
  echo '名前＝[';
  echo $row['content'];
  echo ']';

  echo "<a href=\"../ToDo/todo.php\">ToDo</a><br/>";


} else {
  echo 'メールアドレス又はパスワードが間違っています。';
  return false;
}
