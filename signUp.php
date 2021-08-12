<?php
require_once('config.php');
//データベースへ接続、テーブルがない場合は作成
try {
  
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  echo "●1●";
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "●2●";
  $pdo->exec("create table if not exists userDeta2nd(
      id int not null auto_increment primary key,
      email varchar(255) unique,
      password varchar(255) ,
      content varchar(255) ,
      created timestamp not null default current_timestamp
    )");
  echo "●3●";
 
} catch (Exception $e) {
  echo $e->getMessage() . PHP_EOL;
  echo "●new error●";
}
echo "●new success●";
//POSTのValidate。
if (!$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  echo '入力された値が不正です。';
  return false;
}
//パスワードの正規表現
if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $_POST['password'])) {
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
} else {
  echo 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
  return false;
}
//登録処理
$content = $_POST['content'];
echo $content;

try {
  $stmt = $pdo->prepare("insert into userDeta2nd(email, password, content) value(?, ?, ?)");
  $stmt->execute([$email, $password, $content]);
  echo '登録完了';
} catch (\Exception $e) {
  echo '登録済みのメールアドレスです。';
}
