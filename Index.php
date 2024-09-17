<?php
include ("ConnectBDD.php");

 function getPin()
 {
     try {
         $conn = ConnectBDD();

         if (isset($_GET['password'])) {
             $passwordLocker = htmlentities($_GET['password']);
             $result = $conn->query("SELECT password, status, name FROM `locker` WHERE `password`='$passwordLocker'");
             $result = $result->fetch_assoc();
             if(is_null($result)) {
             die( "Pas le bon code frerot");
             }

             echo json_encode($result);
         }

     } catch (PDOException $e) {
         echo $e->getMessage();
     }
 }

 getPin();