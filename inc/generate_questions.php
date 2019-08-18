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

        // Don't repeat the incorrect answers.
        while ($firstIncorrectAnswer == $secondIncorrectAnswer) {
            $secondIncorrectAnswer = rand($correctAnswer-10, $correctAnswer+10);
        }

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
