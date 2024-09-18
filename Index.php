<?php
include ("ConnectBDD.php");

 function getPin()
 {
     try {
         $conn = DataBase::ConnectPDO();

         if (isset($_GET['password'])) {
            $passwordLocker = htmlentities($_GET['password']);
            $result = $conn->prepare('SELECT password, status, name FROM `locker` WHERE `password`= :pass');

            $result->bindParam('pass', $passwordLocker);

            $result->execute();
            $result = $result->fetchAll();
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