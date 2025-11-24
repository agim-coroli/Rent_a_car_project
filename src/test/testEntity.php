<?php

use model\mapping\UserMapping;


$testComment = new UserMapping([
    ]);

$testComment1 = new UserMapping([
    'id' => 1,
    'full_name' => 'agim coroli',
    'pseudo' => "agim",
    'email' => "a@a.a",
    'phone' => "0477423505",
    'password' => "dazdazdazdazda",
    'date_birth' => "1993-03-11", 
    'gender' => "Feminin"

]);


var_dump($testComment,$testComment1);


