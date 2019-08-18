<?php
session_start();
// include('inc/questions.php');
include('inc/quiz.php');

//Load the user answer except the first time the page is loaded.
//In this case, we assign '99' that is a value with no meaning.
if(isset($_POST['answer'])) {
    $userInput = $_POST['answer'];
} else {
    $userInput = 99;
}

//The main function is whatState() in quiz.php
//It manage all the quiz and store the information in $quiz.
$quiz = whatState($quiz, $userInput);
$_SESSION['Quiz'] = $quiz;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Math Quiz: Addition</title>
    <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">

    <!--  change_color() in generate_questions() creates the new random style everytime the page is loaded -->
    <style><?php echo change_color(); ?> ></style>

    <!--  this script add the className to the div wih the message, letting the CSS show the message -->
    <script>
    function ShowSnackbar() {
      var x = document.getElementById("snackbar");
      x.className = "show";
      setTimeout(function(){ x.className = x.className.replace("show", ""); }, 4000);
    }
    </script>

</head>
<body onload="ShowSnackbar()">
    <div class="container">
        <div id="quiz-box">
            <!--
            We have 2 forms. 1) The default, the quiz is running. 2) When the quiz is completed.
            This behavior is controlled by the $quiz['Show Answer']
            -->

            <?php
            if (isset($quiz['Show Answer']) && $quiz['Show Answer'] == true) {
                echo "<p class='breadcrumbs'>Question " . ($quiz['controlQuestion'] + 1) . " of " . $quiz['totalQuestions'] . "</p>";
                echo "<p class='quiz'>What is " . $quiz['question']['leftAdder'] . " + " . $quiz['question']['rightAdder'] . "?</p>";
                echo '<form action="index.php" method="post">';
                    echo ' <input type="hidden" name="id" value="0" />';
                    echo '<input type="submit" class="btn" name="answer" value="' . $quiz['values'][0] . '"/>';
                    echo '<input type="submit" class="btn" name="answer" value="' . $quiz['values'][1] . '"/>';
                    echo '<input type="submit" class="btn" name="answer" value="' . $quiz['values'][2] . '"/>';
                echo '</form>';
            } else {
                echo '<form action="index.php" method="post">';
                    echo '<input type="submit" class="btn" name="tryAgain" value="Try Again"/>';
                echo '</form>';
            }
            ?>

        </div>
    </div>
    <div id="snackbar" ><?php echo showToast($quiz['Message'], $quiz['numRightQuestions'], $quiz['Show Answer']); ?></div>
</body>
</html>
