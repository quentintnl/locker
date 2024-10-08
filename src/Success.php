<?php
session_start();
error_reporting(E_ERROR|E_PARSE);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link href="./input.css" rel="stylesheet">
        <link href="./output.css" rel="stylesheet">
        <title>Locker Project</title>
    </head>
    <body>
        <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl lg:text-5xl dark:text-white">Locker Project</h1>

        <p class="text-sm font-medium ">Le locker <?php echo $_SESSION['name']; ?> s'est ouvert, vous pouvez récupéré votre colis !</p>

        <form method="post" action="./api.php">
            <input
                tabindex="1"
                type="submit"
                name="close"
                value="Fermer le locker"
                id="btn-close-locker"
                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
            />
        </form>
        <script src="script.js"></script>
    </body>
</html>


