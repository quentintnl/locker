<?php

include ("Index.php");

function generatePassword() : void
{
    $password_validity = false;
    while (!$password_validity) {
        $password = password_hash(rand(1000, 9999), PASSWORD_DEFAULT);
        $password_validity = checkPassword($password);
    }
}

generatePassword();
