<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == "create") {
            $nombre = $_POST['nombre'];
            $sql_insert = "INSERT INTO Genero (nombre, fechaCrea, estatus) VALUES (?, GETDATE(), 1)";
            $params_insert = array($nombre);
            $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
            if ($stmt_insert === false) {
                echo "Error al insertar el género: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "update") {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $sql_update = "UPDATE Genero SET nombre = ?, fechaModifica = GETDATE() WHERE id = ?";
            $params_update = array($nombre, $id);
            $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);
            if ($stmt_update === false) {
                echo "Error al actualizar el género: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "delete") {
            $id = $_POST['id'];
            $sql_delete = "DELETE FROM Genero WHERE id = ?";
            $params_delete = array($id);
            $stmt_delete = sqlsrv_query($conn, $sql_delete, $params_delete);
            if ($stmt_delete === false) {
                echo "Error al eliminar el género: " . print_r(sqlsrv_errors(), true);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Géneros</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Gestión de Géneros</h2>

    <form method="post">
        <h3>Agregar Nuevo Género</h3>
        <label for="nombre">Nombre del Género:</label>
        <input type="text" id="nombre" name="nombre" required>
        <input type="hidden" name="action" value="create">
        <input type="submit" value="Agregar Género">
    </form>

    <h3>Lista de Géneros</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre del Género</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql_generos = "SELECT * FROM Genero";
        $result_generos = sqlsrv_query($conn, $sql_generos);

        if ($result_generos !== false) {
            while ($row_genero = sqlsrv_fetch_array($result_generos, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row_genero['id'] . "</td>";
                echo "<td>" . $row_genero['nombre'] . "</td>";
                echo "<td>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row_genero['id'] . "'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este género?\");'>
                      </form>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row_genero['id'] . "'>
                        <input type='hidden' name='action' value='edit'>
                        <input type='submit' value='Editar'>
                      </form>";
                echo "</td>";
                echo "</tr>";
            }
            sqlsrv_free_stmt($result_generos);
        } else {
            echo "Error al obtener los géneros: " . print_r(sqlsrv_errors(), true);
        }
        ?>
    </table>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "edit") {
        $id = $_POST['id'];
        $sql_genero = "SELECT * FROM Genero WHERE id = ?";
        $params_genero = array($id);
        $stmt_genero = sqlsrv_query($conn, $sql_genero, $params_genero);

        if ($stmt_genero !== false) {
            $row_genero = sqlsrv_fetch_array($stmt_genero, SQLSRV_FETCH_ASSOC);
            if ($row_genero) {
                echo "<h3>Editar Género</h3>
                      <form method='post'>
                          <label for='nombre'>Nombre del Género:</label>
                          <input type='text' id='nombre' name='nombre' value='" . $row_genero['nombre'] . "' required>
                          <input type='hidden' name='id' value='" . $row_genero['id'] . "'>
                          <input type='hidden' name='action' value='update'>
                          <input type='submit' value='Actualizar Género'>
                      </form>";
            }
            sqlsrv_free_stmt($stmt_genero);
        } else {
            echo "Error al obtener el género: " . print_r(sqlsrv_errors(), true);
        }
    }
    ?>

</body>
</html>
