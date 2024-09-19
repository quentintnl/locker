<?php
include ("ConnectBDD.php");

function getLocker() : void
{
    try {
        $conn = DataBase::ConnectPDO();

        if (isset($_POST['password'])) {
            $passwordLocker = htmlentities($_POST['password']);
            $result = $conn->prepare('SELECT name, password, close_or_open, pin, ip FROM Locker INNER JOIN Raspberry ON Locker.ip_id = Raspberry.id');
            $result->execute();

            $result = $result->fetchAll();


            foreach ($result as $row) {
                if (password_verify($passwordLocker, $row["password"])) {
                    header('Location: ./success.html');

                    $pin = $row['pin'];
                    $ip = $row['ip'];
                    $closeOrOpen = $row['close_or_open'];

                    $url = "$ip/locker/?command=$closeOrOpen&port=$pin";
                    var_dump($url);
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($curl);
                    curl_close($curl);
                    print_r($response);

                    exit();
                }
            }
            header('Location: ./error.html');
            exit();

            // echo json_encode($result)


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

function updateCloseOrOpen($pin, $ip)
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

getLocker();