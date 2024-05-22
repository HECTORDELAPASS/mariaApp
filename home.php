<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .button-container {
            margin: 20px;
        }
        .button-container form {
            display: inline-block;
            margin: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Bienvenido al Sistema de Gestión de Películas</h2>
    <div class="button-container">
        <form action="PaisABC.php">
            <input type="submit" value="País">
        </form>
        <form action="Genero.php">
            <input type="submit" value="Género">
        </form>
        <form action="Idioma.php">
            <input type="submit" value="Idioma">
        </form>
        <form action="CompañiaABC.php">
            <input type="submit" value="Compañía">
        </form>
    </div>
</body>
</html>
