<?php
include("ConnectBDD.php");
session_start();
error_reporting(E_ERROR|E_PARSE);
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
                    $_SESSION['id'] = $row["id"];
                    $_SESSION['close_or_open'] = $row["close_or_open"];
                    $_SESSION['pin'] = $row["pin"];
                    $_SESSION['ip'] = $row["ip"];
                    $_SESSION['name'] = $row["name"];
                    header('Location: ./Success.php');

                    $idLocker = $row['id'];
                    $pin = $row['pin'];
                    $ip = $row['ip'];
                    $closeOrOpen = $row['close_or_open'];

                    $url = "http://$ip/locker/?command=$closeOrOpen&port=$pin";
                    $response = json_decode(file_get_contents($url));

                    if ($response[0] == "success") {
                        updateCloseOrOpen($idLocker, $closeOrOpen);
                    }

                    $close = $_POST['close'];

                    exit();
                }
            }
            header('Location: ./Error.php');
            exit();

        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function passwordUpdate()
{
    try {
        $newPassword = rand(1000, 1005);
        $conn = DataBase::ConnectPDO();

        $results = $conn->prepare("SELECT password FROM `locker`");
        $results->execute();

        $results = $results->fetchAll();
        $password_validity = true;
        foreach ($results as $result) {
            if (password_verify($newPassword, $result["password"])) {
                $password_validity = false;
                passwordUpdate();
            }
        }
        if ($password_validity) {
            //echo file_put_contents("raedme.txt", $newPassword . '  ');
            return password_hash($newPassword, PASSWORD_DEFAULT);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function updateCloseOrOpen($idLocker, $closeOrOpen)
{
    $closeOrOpen = $closeOrOpen == 1 ? 2 : 1;
    $_SESSION['close_or_open'] = $closeOrOpen;
    try {
        $conn = DataBase::ConnectPDO();

        if (isset($idLocker) && isset($closeOrOpen)) {

            $sql = "UPDATE locker SET close_or_open = :status WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['status' => $closeOrOpen, 'id' => $idLocker]);

            if ($closeOrOpen == 1) {
                $pin = $_SESSION['pin'];
                $ip = $_SESSION['ip'];
                $passwordUpdate = passwordUpdate();
                $sql = "UPDATE locker SET password = :passwordUpdate WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->execute(['passwordUpdate' => $passwordUpdate, 'id' => $idLocker]);
                header('Location: ./Index.php');
                $url = "http://$ip/locker/?command=2&port=$pin";
                json_decode(file_get_contents($url));
            }
        } else {
            echo "Missing settings.";
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function index() {
    if (isset($_POST['close'])) {
        $id = $_SESSION['id'];
        $closeOrOpen = $_SESSION['close_or_open'];
        updateCloseOrOpen($id, $closeOrOpen);
    } else {
        getLocker();
    }
}

index();