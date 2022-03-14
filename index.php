<?php
$con = new PDO("mysql:host=$server;dbname=$basePath", $user, $password);
echo "Connecting to $server";
session_start();
?>
<!DOCTYPE html>
<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <title>Forum</title>
  <link rel="stylesheet" href="main.css">
</head>

<body>
  <div id="content">
    
  </div>

  <form method="post">
    <input type="hidden" name="logged" value="<?php echo $_SESSION['logged']; ?>">
    <input type="submit" value="Wyloguj">
    <?php
    if (isset($_POST['logged'])) {
      unset($_SESSION['logged']);
      header('refresh:0');
    }
    ?>
  </form>

</body>

</html>
<?php
catch(PDOException $e){
  echo "Error connecting to $server: ".$e->getMessage();
}
?>