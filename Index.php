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

function checkPassword($testedPassword) : bool
{
    try {
        $conn = DataBase::ConnectPDO();

        $results = $conn->prepare("SELECT password FROM `locker`");
        $results->execute();

        $results = $results->fetchAll();
        $password_validity = true;

        foreach ($results as $result) {
            if (password_verify($testedPassword, $result[0])) {
                echo "Password existing";
                $password_validity = false;
            }
        }
            return $password_validity;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}
