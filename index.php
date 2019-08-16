<?php
session_start();

// include('inc/questions.php');
include('inc/generate_questions.php');

// initialMessage has the message for the Toast. showToast() will complete the message adapted to the answers and the result,
// and the javascript ShowSnackbar() will display it.
$initialMessage = " ";
//The number of quiz questions.
$totalQuestions = 10;
//We can use $questions from or generate_questions.php o create a new array with createNewQuestions().
$questions = createNewQuestions($totalQuestions, 0, 100);

//The first control we do is if the user click the right answer before the page load.
//To do that we compare the answer in the POST and the last right answer in the Session.
//Every time we obtain a new question, we load the rightanswer in the Session.
//If the answer was right we updated the counter of right questions and updated the message.

if(isset($_POST['answer']) && isset($_SESSION['theLastRightAnswer'])) {
    $lastAnswer = filter_input(INPUT_POST, 'answer', FILTER_SANITIZE_NUMBER_INT);
    if ($lastAnswer == $_SESSION['theLastRightAnswer']) {
        $_SESSION['numRightQuestions'] += 1;
        $initialMessage = "RIGHT! +1 point!";
    } else {
        $initialMessage = "WRONG!";
    }
}

//The second control is watching if we need a new array of questions. because the user is starting a new quiz o because it is finished.
//To obtain the new questions we call shuffleQuestionInUse() that it is in generate_questions.php
//The counters star from 0.
//The new array of questions is loaded in the Session.
//If we are in the middle of the quiz, we keep adding 1 to the counter

if (!isset($_SESSION['controlQuestion']) || $_SESSION['controlQuestion'] == ($totalQuestions-1)) {
    $questionsInUse = shuffleQuestionInUse($questions); // generate de array with the quizz
    $_SESSION['questionsInUse'] = $questionsInUse; // loading in the Session
    $_SESSION['controlQuestion'] = 0; // star the counter for questions
    $_SESSION['numRightQuestions'] = 0; // star the counter for questions
} else {
    $_SESSION['controlQuestion'] += 1;
    //$questionsInUse = $_SESSION['questionsInUse']; // recovering from the Session
}

//The third control is updated the final score every time the array of questions is completed.
if ($_SESSION['controlQuestion'] == ($totalQuestions-1)) {
    $_SESSION['finalScore'] = $_SESSION['numRightQuestions'];
}

//We show the next question using the session.
//To change the possition of the right and wrong answers we call the shuffleButtons() in generate_questions.php
//When the user click the button this page will need the last right answer to compare with the value of button clicked.
//We save this value in the Session.
$controlQuestion = $_SESSION['controlQuestion']; // recovering from the Session
$question = $_SESSION['questionsInUse'][$controlQuestion];
$values = shuffleButtons($question); // Shuflle buttons.
$_SESSION['theLastRightAnswer'] = $values[3];

//Now we have all data, so we can complete the first part of the message.
//It will be available when the page will be loaded and call showToast()
if(!isset($_POST['answer'])) {
    $initialMessage = "Star the quiz!";
} elseif ($controlQuestion == 0) {
    $initialMessage = "The last game you had: ";
    $initialMessage .= $_SESSION['finalScore'];
    $initialMessage .= ". Try again!<br />";
}

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
            <p class="breadcrumbs">Question <?php echo $controlQuestion + 1 . " of " .$totalQuestions; ?></p>
            <p class="quiz">What is <?php echo " " . $question['leftAdder'] . " + " .$question['rightAdder'] . "?"; ?></p>
            <form action="index.php" method="post">
                <input type="hidden" name="id" value="0" />
                <input type="submit" class="btn" name="answer" value=<?php echo "\"" . $values[0] . "\""; ?> />
                <input type="submit" class="btn" name="answer" value=<?php echo "\"" . $values[1] . "\""; ?> />
                <input type="submit" class="btn" name="answer" value=<?php echo "\"" . $values[2] . "\""; ?> />
            </form>
        </div>
    </div>
    <div id="snackbar" ><?php echo showToast($initialMessage); ?></div>
</body>
</html>
