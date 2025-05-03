<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <input type="text" name="input">
    </form>
    <?php
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST['input'];
            echo password_hash($input, PASSWORD_BCRYPT); 
        }
    ?>
</body>
</html>