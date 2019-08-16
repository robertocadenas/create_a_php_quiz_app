<?php

// Generate random questions
// Loop for required number of questions

function shuffleQuestionInUse($arrayQuestions) {
    $numbers = range(0, 9);
    shuffle($numbers);
    $questionsInUse = array();

    foreach ($numbers as $number) {
        $questionsInUse[] = $arrayQuestions[$number];
    }

    return $questionsInUse;
}

// Generate random buttons

function shuffleButtons ($question) {
    $values = array();
    $values[] = $question['correctAnswer'];
    $values[] = $question['firstIncorrectAnswer'];
    $values[] = $question['secondIncorrectAnswer'];

    $numbers = range(0, 2);
    shuffle($numbers);

    //random buttons in a new array
    $buttonsInUse = array();
    foreach ($numbers as $number) {
        $buttonsInUse[] = $values[$number];
    }
    //mark the right answer for the snack, and add to the last element of the array: $buttonsInUse[3]
    $buttonsInUse[] = $question['correctAnswer'];

    return $buttonsInUse;
}

//Create a new array of questions.
//The params set the total number of questions and the min and max to the leftAdder and rightAdder.
function createNewQuestions($totalQuestions, $minNum, $maxNum) {
    $newQuestions = array();

    for($i=0; $i<$totalQuestions; $i++) {
        // Get random numbers to add
        // Make sure it is a unique answer
        $leftAdder = rand($minNum, $maxNum);
        $rightAdder = rand($minNum, $maxNum);

        // Calculate correct answer
        $correctAnswer = $leftAdder + $rightAdder;

        // Get incorrect answers within 10 numbers either way of correct answer
        $firstIncorrectAnswer = rand($correctAnswer-10, $correctAnswer+10);
        $secondIncorrectAnswer = rand($correctAnswer-10, $correctAnswer+10);

        // Add question and answer to questions array
        $newQuestions[] =
            [
                "leftAdder" => $leftAdder,
                "rightAdder" => $rightAdder,
                "correctAnswer" => $correctAnswer,
                "firstIncorrectAnswer" => $firstIncorrectAnswer,
                "secondIncorrectAnswer" => $secondIncorrectAnswer,
            ];
    }

    return $newQuestions;
}

function showToast($initialMessage) {
    $htmlString = $initialMessage;
    $htmlString .= " You have: ";
    $htmlString .= $_SESSION['numRightQuestions'];
    $htmlString .= " points";
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
