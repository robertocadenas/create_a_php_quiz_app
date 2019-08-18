<?php
include('generate_questions.php');

/*
1. $quiz is an array with all the information we need between index.php, quiz.php and generate_questions.php
2. $quiz is stored in Session each time the form on index.php is submitted.
3. As the quiz is circular, it work with 3 main states: "Not Init", "Quiz running", "Finishing". The score, the message and the creation of new array of questions is manage with these states.
4. WhatState() updated $quiz every time the user submits the form on index.php
*/

if(!isset($_SESSION['Quiz'])) {
    $quiz = [
        'state' => "Not Init",
        //'Message' => "Start the quiz. Select the right answer.",
        'totalQuestions' => 10, //we decide 10.
        //'controlQuestion' => 0,
        //'numRightQuestions' => 0,
        //'Show Answer' => true,
    ];
} else {
    $quiz = $_SESSION['Quiz'];
}

function whatState($quiz, $userInput) {
    if ($quiz['state'] == "Not Init") {
        return initQuiz($quiz, $userInput);
    } elseif ($quiz['state'] == "Quiz running") {
        return checkQuiz($quiz, $userInput);
    } elseif ($quiz['state'] == "Finishing") {
        return endQuiz($quiz, $userInput);
    }
}

function initQuiz($quiz, $userInput) {
    //We can use $questions from or generate_questions.php o create a new array with createNewQuestions().
    $questions = createNewQuestions($quiz['totalQuestions'], 0, 100); // generate a array of questions
    $questionsInUse = shuffleQuestionInUse($questions); // random the questions in the array
    $question = $questionsInUse[0]; // select the first question to show on the page
    $values = shuffleButtons($question); // shuffle the options for the buttons.

    $quiz['questionsInUse'] = $questionsInUse;
    $quiz['question'] = $question;
    $quiz['controlQuestion'] = 0;
    $quiz['numRightQuestions'] = 0;
    $quiz['values'] = $values;
    $quiz['theLastRightAnswer'] = $values[3];
    $quiz['Message'] = "Start the quiz. Select the right answer.";
    $quiz['Show Answer'] = true;
    $quiz['state'] = "Quiz running";

    return $quiz;
}

function checkQuiz($quiz, $userInput) {
    //Check if the answer was right
    if ($userInput == $quiz['theLastRightAnswer']) {
        $quiz['Message'] = "RIGHT! +1 point!";
        $quiz['numRightQuestions'] += 1;
    } else {
        $quiz['Message'] = "WRONG!";
    }

    //Update the $quiz
    //First move one position
    $quiz['controlQuestion'] += 1;
    //Obtain the next question and shuffle the buttons.
    $num = $quiz['controlQuestion'];
    $question = $quiz['questionsInUse'][$num];
    $values = shuffleButtons($question);
    //Continue updating the $quiz
    $quiz['question'] = $question;
    $quiz['values'] = $values;
    $quiz['theLastRightAnswer'] = $values[3];

    //Update the state
    if ($quiz['controlQuestion'] < $quiz['totalQuestions']-1) {
        $quiz['state'] = "Quiz running";
    } else {
        $quiz['state'] = "Finishing";
    }

    return $quiz;
}

function endQuiz($quiz, $userInput) {
    //Create the last message
    if ($userInput == $quiz['theLastRightAnswer']) {
        $quiz['numRightQuestions'] += 1;
        $quiz['Message'] = "RIGHT! +1 point! <br > Your total points: " . $quiz['numRightQuestions'] . "<br />Try Again.";
    } else {
        $quiz['Message'] = "WRONG! <br > Your total points: " . $quiz['numRightQuestions'] . "<br />Try Again.";
    }

    //hidden the question on the index.php
    $quiz['Show Answer'] = false;

    //Update the state
    $quiz['state'] = "Not Init";

    return $quiz;
}

function showToast($message, $numRightQuestions, $showPoints) {
    $htmlString = $message;
    if($showPoints == true) {
        $htmlString .= " You have: ";
        $htmlString .= $numRightQuestions;
        $htmlString .= " points";
    }
    return $htmlString;
}

function selectColor() {
    // from https://www.canva.com/colors/color-wheel/
    // We use two arrays of colors to keep the contrast between backgrounds and letters.
    $colorsBackground = ['#da3225', '#e41b8d', '#a314eb', '#2225dd', '#699671', '#8e7c71'];
    $colorsLetters = ['#c2c7d3', '#d8f7fb', '#c2f7d9', '#D9C2F7', '#F7D9C2', '#FDE8FB'];

    $colorSelected = array();
    $colorSelected['colorsBackground_body'] = $colorsBackground[rand(0, count($colorsBackground)-1)];
    $colorSelected['colorsBackground_btn'] = $colorsBackground[rand(0, count($colorsBackground)-1)];
    $colorSelected['colorsLetters'] = $colorsLetters[rand(0, count($colorsLetters)-1)];

    // Different colors for background and button.
    while ($colorSelected['colorsBackground_body'] == $colorSelected['colorsBackground_btn']) {
        $colorSelected['colorsBackground_btn'] = $colorsBackground[rand(0, count($colorsBackground)-1)];
    }

    return $colorSelected;
}

//They content for the <style> in index.php
function change_color() {
    $colorSelected = selectColor();

    $htmlStyle = "body { background-color: ";
    $htmlStyle .= $colorSelected['colorsBackground_body'] . ';';
    $htmlStyle .= "}";
    $htmlStyle .= ".quiz { color: ";
    $htmlStyle .= $colorSelected['colorsLetters']. ';';
    $htmlStyle .= "}";
    $htmlStyle .= ".breadcrumbs { color: ";
    $htmlStyle .= $colorSelected['colorsLetters'] . ';';
    $htmlStyle .= "}";
    $htmlStyle .= ".btn { background-color: ";
    $htmlStyle .= $colorSelected['colorsBackground_btn'] . ';';
    $htmlStyle .= "color: ";
    $htmlStyle .= $colorSelected['colorsLetters'] . ';';
    $htmlStyle .= "}";
    $htmlStyle .= "#snackbar { background-color: ";
    $htmlStyle .= $colorSelected['colorsBackground_btn'] . ';';
    $htmlStyle .= "color: ";
    $htmlStyle .= $colorSelected['colorsLetters'] . ';';
    $htmlStyle .= "}";


    return $htmlStyle;
}


/*
 * PHP Techdegree Project 2: Build a Quiz App in PHP
 *
 * These comments are to help you get started.
 * You may split the file and move the comments around as needed.
 *
 * You will find examples of formating in the index.php script.
 * Make sure you update the index file to use this PHP script, and persist the users answers.
 *
 * For the questions, you may use:
 *  1. PHP array of questions
 *  2. json formated questions
 *  3. auto generate questions
 *
 */

// Include questions

// Keep track of which questions have been asked

// Show which question they are on
// Show random question
// Shuffle answer buttons


// Toast correct and incorrect answers
// Keep track of answers
// If all questions have been asked, give option to show score
// else give option to move to next question


// Show score
