<?php

// turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();

require_once ('vendor/autoload.php');
require_once ('model/data-layer.php');

// instantiate Fat-Free
$f3 = Base::instance();

// define routes
$f3->route('GET /', function (){
    // instantiate a views object
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET|POST /survey', function ($f3){

    //Reinitialize session array
    $_SESSION = array();


    //Initialize variables to store user input
    $userName = "";
    $userAnswer = "";

    //if form is submitted take user to next page
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userName=$_POST['name'];
        $userAnswer=$_POST['answer'];


        //data validation
        if(strlen(trim($userName))>2){
            $_SESSION['name']=$userName;
        }
        else{
            $f3->set('errors["name"]','Please enter your name.');
        }

        //If answer is valid, store data
        if(!empty($userAnswer)) {
            $_SESSION['answers'] = $userAnswer;
        }
        //Otherwise, set an error variable in the hive
        else {
            $f3->set('errors["answer"]', 'Please check the valid answer.');
        }

        //If there are no errors, redirect to summary route
        if (empty($f3->get('errors'))) {
            header('location: summary');
        }

    }




    //get data from model
    $f3-> set('answers', getAnswers());

    //store user input in the hive
    $f3->set('userName', $userName);
    $f3->set('userAnswer', $userAnswer);


    // instantiate a views object
    $view = new Template();
    echo $view->render('views/survey.html');
});

$f3->route('GET /summary', function (){
    // instantiate a views object
    $view = new Template();
    echo $view->render('views/summary.html');
});

// run Fat-Free
$f3->run();