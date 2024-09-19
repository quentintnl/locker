<?php
include("ConnectBDD.php");

function getLocker(): void
{
    try {
        $conn = DataBase::ConnectPDO();

        if (isset($_POST['password'])) {
            $passwordLocker = htmlentities($_POST['password']);
            $result = $conn->prepare('SELECT Locker.id, name, password, close_or_open, pin, ip FROM Locker INNER JOIN Raspberry ON Locker.ip_id = Raspberry.id');
            $result->execute();

            $result = $result->fetchAll();


            foreach ($result as $row) {
                if (password_verify($passwordLocker, $row["password"])) {
                    header('Location: ./success.html');

                    $idLocker = $row['id'];
                    $pin = $row['pin'];
                    $ip = $row['ip'];
                    $closeOrOpen = $row['close_or_open'];

                    $url = "http://$ip/locker/?command=$closeOrOpen&port=$pin";
                    $response = json_decode(file_get_contents($url));
                    print_r($response[0]);

                    var_dump($response);

                    if ($response[0] == "success") {
                        echo "toto";

                        updateCloseOrOpen($idLocker, $closeOrOpen);
                    }

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
        $newPassword = '2345';
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

function updateCloseOrOpen($idLocker, $closeOrOpen)
{
    $closeOrOpen = $closeOrOpen == 1 ? 2 : 1;
    try {
        $conn = DataBase::ConnectPDO();

        if (isset($idLocker) && isset($closeOrOpen)) {

            $sql = "UPDATE locker SET close_or_open = :status WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['status' => $closeOrOpen, 'id' => $idLocker]);

            if ($closeOrOpen == 1) {
                $passwordUpdate = passwordUpdate();
                var_dump($passwordUpdate); // TODO
                $sql = "UPDATE locker SET password = :passwordUpdate WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['passwordUpdate' => $passwordUpdate, 'id' => $idLocker]);
            }
        } else {
            echo "Missing settings.";
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

getLocker();