# create_a_php_quiz_app

Functionality:

# Ask what is the state of the Quiz.
a) NEW. No previous session, no array of questions, no control variables.
    * Create the array of questions, control variables and message.
    * Use the session to use these elements.
    * Use the first question and message.
    * Use the first background color.
B) IN COURSE. There are session, array of questions and control variables.
    * Use the session to use these elements.
    * Progress this variables when it is needed.
    * Progress the questions, message and the background.
    * Change the color.
c) COMPLETED AND NEW.
    * Resume the results and show in the message.
    * Reset the variables in the session.
    * Create a new array of questions and a new message.
    * Use the session again.
    * Use the first question and message.
    * Change the color.


Main solution:


1. $quiz is an array with all the information we need between index.php, quiz.php and generate_questions.php
2. $quiz is stored in Session each time the form on index.php is submitted.
3. As the quiz is circular, it work with 3 main states: "Not Init", "Quiz running", "Finishing". The score, the message and the creation of new array of questions is manage with these states.
4. WhatState() updated $quiz every time the user submits the form on index.php
