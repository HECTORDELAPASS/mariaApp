<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == "create") {
            $idioma = $_POST['idioma'];
            $sql_insert = "INSERT INTO Idioma (idioma, fechaCrea, estatus) VALUES (?, GETDATE(), 1)";
            $params_insert = array($idioma);
            $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
            if ($stmt_insert === false) {
                echo "Error al insertar el idioma: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "update") {
            $id = $_POST['id'];
            $idioma = $_POST['idioma'];
            $sql_update = "UPDATE Idioma SET idioma = ?, fechaModifica = GETDATE() WHERE idIdioma = ?";
            $params_update = array($idioma, $id);
            $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);
            if ($stmt_update === false) {
                echo "Error al actualizar el idioma: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "delete") {
            $id = $_POST['id'];
            $sql_delete = "DELETE FROM Idioma WHERE idIdioma = ?";
            $params_delete = array($id);
            $stmt_delete = sqlsrv_query($conn, $sql_delete, $params_delete);
            if ($stmt_delete === false) {
                echo "Error al eliminar el idioma: " . print_r(sqlsrv_errors(), true);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Idiomas</title>
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
    <h2>Gestión de Idiomas</h2>

    <form method="post">
        <h3>Agregar Nuevo Idioma</h3>
        <label for="idioma">Nombre del Idioma:</label>
        <input type="text" id="idioma" name="idioma" required>
        <input type="hidden" name="action" value="create">
        <input type="submit" value="Agregar Idioma">
    </form>

    <h3>Lista de Idiomas</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre del Idioma</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql_idiomas = "SELECT * FROM Idioma";
        $result_idiomas = sqlsrv_query($conn, $sql_idiomas);

        if ($result_idiomas !== false) {
            while ($row_idioma = sqlsrv_fetch_array($result_idiomas, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row_idioma['idIdioma'] . "</td>";
                echo "<td>" . $row_idioma['idioma'] . "</td>";
                echo "<td>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row_idioma['idIdioma'] . "'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este idioma?\");'>
                      </form>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row_idioma['idIdioma'] . "'>
                        <input type='hidden' name='action' value='edit'>
                        <input type='submit' value='Editar'>
                      </form>";
                echo "</td>";
                echo "</tr>";
            }
            sqlsrv_free_stmt($result_idiomas);
        } else {
            echo "Error al obtener los idiomas: " . print_r(sqlsrv_errors(), true);
        }
        ?>
    </table>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "edit") {
        $id = $_POST['id'];
        $sql_idioma = "SELECT * FROM Idioma WHERE idIdioma = ?";
        $params_idioma = array($id);
        $stmt_idioma = sqlsrv_query($conn, $sql_idioma, $params_idioma);

        if ($stmt_idioma !== false) {
            $row_idioma = sqlsrv_fetch_array($stmt_idioma, SQLSRV_FETCH_ASSOC);
            if ($row_idioma) {
                echo "<h3>Editar Idioma</h3>
                      <form method='post'>
                          <label for='idioma'>Nombre del Idioma:</label>
                          <input type='text' id='idioma' name='idioma' value='" . $row_idioma['idioma'] . "' required>
                          <input type='hidden' name='id' value='" . $row_idioma['idIdioma'] . "'>
                          <input type='hidden' name='action' value='update'>
                          <input type='submit' value='Actualizar Idioma'>
                      </form>";
            }
            sqlsrv_free_stmt($stmt_idioma);
        } else {
            echo "Error al obtener el idioma: " . print_r(sqlsrv_errors(), true);
        }
    }
    ?>
</body>
</html>
