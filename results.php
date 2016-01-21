<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Results</title>
    <?php include 'view/links.php'; ?>

  </head>
  <body>
		<?php
    include 'db.php';
    include 'view/header.php';
    session_start();
    $connection = mysqli_select_db($db_connection,$dbname);
    $query=mysqli_query($db_connection,"SELECT * FROM question");
    if (isset($_POST['action'])) {
      $result = 0;
      $trueNum = 0;
      $falseNum = 0;
      $emptyAns = 0;
      $stdnAnswer = [];
      $userName =  $_SESSION['name'];
      $userSurname = $_SESSION['surname'];
      $userTel = $_SESSION['tel'];
      echo '<table class="highlight result">';
      while ($row = mysqli_fetch_assoc($query)) {
        $trueAns = $row['answer'];
        $varValue = $row['value'];
        $qID = $row['id'];
        $truFalse = "-";

        if (isset($_POST["$qID"]) && $_POST["$qID"] == $trueAns) {
          $truFalse  = "+";
          $result = $result + $varValue;
          $trueNum++;
        }
        elseif (!isset($_POST["$qID"])) {
          $emptyAns++;
        }
        elseif (isset($_POST["$qID"]) && $_POST["$qID"] != $trueAns) {
          $truFalse = "-";
          $falseNum++;
        }
        echo "<tr><td>".$qID." . ";
        if (isset($_POST["$qID"])) {
          $stdnAnswers[] = $_POST["$qID"];
          echo $_POST["$qID"];
        }
        else {
          $_POST["$qID"] = null;
          $stdnAnswers[] = $_POST["$qID"];
        }
        echo " ".$truFalse."</td></tr>";
      }
      echo "</table>";
          echo '<br />'."Netice : ".$result." bal".'<br />';
          echo "Doğru cavabların sayı ".$trueNum.'<br />';
          echo "Yanliş cavabların sayı ".$falseNum.'<br />';
          echo "Cavablandırılmamış ".$emptyAns;
          $today = date("Y-m-d  H:i:s");
          $query=mysqli_query($db_connection,"INSERT INTO student(name,surname,tel,result,reg_date) VALUES('$userName','$userSurname','$userTel','$result','$today')");
          $query=mysqli_query($db_connection,"SELECT id FROM student WHERE name = '$userName'");
          while ($row = mysqli_fetch_assoc($query)) {
            $s_id =  $row['id'];
          }
          foreach ($stdnAnswers as $key => $value) {
            $key = $key + 1;
            $query=mysqli_query($db_connection,"INSERT INTO students_answer(s_id,question_id,s_answer) VALUES('$s_id','$key','$value')");
          }
    }



include 'view/footer.php';
		?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
