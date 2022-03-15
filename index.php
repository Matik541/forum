<?php
require_once("./server.php");
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
  <div class="content">
    <style>
      .form {
        padding: 0;
      }

      .group{
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 5px;
      }

      .form .group .select {
        width: 75%;
      }

      .form .group button {
        width: 25%;
      }
    </style>
    <form method="post" class="form">
      <input type="text" placeholder="title" name="title" required>
      <div class="group">
        <div class="select">
          <select name="category" id="category" value="0" required>
            <option disabled selected>category</option>
            <?php
            $que = $con->query("SELECT DISTINCT `category` FROM `posts`");
            while ($rec = $que->fetch()) {
              echo "<option value='$rec[0]'> - $rec[0]</option>";
            }
            ?>
            <option value="custom">Custom</option>
          </select>
          <input type="text" placeholder="add" name="custom" id="custom">
          <script>
            let select = document.getElementById("select");
            let category = document.getElementById("category");
            let custom = document.getElementById("custom");
            custom.style.display = "none";
            category.addEventListener("change", function(e) {
              if (this.value == "custom") {
                custom.style.display = "inline-block";
                custom.required = true;
                category.style.borderRadius = "5px 5px 0 0";
              } else {
                custom.style.display = "none";
                custom.required = false;
                category.style.borderRadius = "5px";
              }
            })
          </script>
        </div>
        <button type="submit">Post</button>
      </div>
    </form>
  </div>

  <?php
  if (isset($_SESSION['logged'])) {
    include('./logout.php');
  } else {
    echo "<a class='login' href='./login.php'><button>Zaloguj</button></a>";
  }
  ?>

</body>

</html>