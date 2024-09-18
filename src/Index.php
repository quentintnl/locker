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
            if(isset($result)) {
                die( "Pas le bon code frerot");
            }
            echo json_encode($result);

            // ULR serv python
         }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function passwordUpdate()
{
    try {
        $newPassword = rand(1, 2);
        $conn = DataBase::ConnectPDO();

        $results = $conn->prepare("SELECT password FROM `locker`");
        $results->execute();

        $results = $results->fetchAll();
        $password_validity = true;
        var_dump($results);
        foreach ($results as $result) {
            if (password_verify($newPassword, $result["password"])) {
                echo "Password existing";
                $password_validity = false;
                passwordUpdate();
            } else {
                echo "Password not existing";
            }
            var_dump($newPassword);
        }
        if ($password_validity) {
            return password_hash($newPassword, PASSWORD_DEFAULT);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function updateCloseOrOpen()
{
    try {
        $conn = DataBase::ConnectPDO();

        if (isset($_GET['closeOrOpen']) && isset($_GET['id'])) {
            $id = htmlentities($_GET['id']);
            $closeOrOpen = htmlentities($_GET['closeOrOpen']);
            $idExiste = "SELECT id FROM locker WHERE id = :id";
            $stmtId = $conn->prepare($idExiste);
            $stmtId->execute(['id' => $id]);

            if ($stmtId->rowCount() === 0) {
                echo "Pas d'id";
            } else {

                $sql = "UPDATE locker SET closeOrOpen = :status WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['status' => $closeOrOpen,'id' => $id]);

                if ($closeOrOpen == 0) {
                    $passwordUpdate = passwordUpdate();
                    var_dump($passwordUpdate);
                    $sql = "UPDATE locker SET password = :passwordUpdate WHERE id = :id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['passwordUpdate' => $passwordUpdate, 'id' => $id]);
                }
            }
        } else {
            echo "Missing settings.";
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

getPin();