<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == "create") {
            $nombre = $_POST['nombre'];
            $sql_insert = "INSERT INTO Compania (nombre, fechaCrea, estatus) VALUES (?, GETDATE(), 1)";
            $params_insert = array($nombre);
            $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
            if ($stmt_insert === false) {
                echo "Error al insertar la compañía: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "update") {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $sql_update = "UPDATE Compania SET nombre = ?, fechaModifica = GETDATE() WHERE idCompania = ?";
            $params_update = array($nombre, $id);
            $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);
            if ($stmt_update === false) {
                echo "Error al actualizar la compañía: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "delete") {
            $id = $_POST['id'];
            $sql_delete = "DELETE FROM Compania WHERE idCompania = ?";
            $params_delete = array($id);
            $stmt_delete = sqlsrv_query($conn, $sql_delete, $params_delete);
            if ($stmt_delete === false) {
                echo "Error al eliminar la compañía: " . print_r(sqlsrv_errors(), true);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Compañías</title>
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
    <h2>Gestión de Compañías</h2>

    <form method="post">
        <h3>Agregar Nueva Compañía</h3>
        <label for="nombre">Nombre de la Compañía:</label>
        <input type="text" id="nombre" name="nombre" required>
        <input type="hidden" name="action" value="create">
        <input type="submit" value="Agregar Compañía">
    </form>

    <h3>Lista de Compañías</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre de la Compañía</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql_companias = "SELECT * FROM Compania";
        $result_companias = sqlsrv_query($conn, $sql_companias);

        if ($result_companias !== false) {
            while ($row_compania = sqlsrv_fetch_array($result_companias, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row_compania['idCompania'] . "</td>";
                echo "<td>" . $row_compania['nombre'] . "</td>";
                echo "<td>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row_compania['idCompania'] . "'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta compañía?\");'>
                      </form>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row_compania['idCompania'] . "'>
                        <input type='hidden' name='action' value='edit'>
                        <input type='submit' value='Editar'>
                      </form>";
                echo "</td>";
                echo "</tr>";
            }
            sqlsrv_free_stmt($result_companias);
        } else {
            echo "Error al obtener las compañías: " . print_r(sqlsrv_errors(), true);
        }
        ?>
    </table>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "edit") {
        $id = $_POST['id'];
        $sql_compania = "SELECT * FROM Compania WHERE idCompania = ?";
        $params_compania = array($id);
        $stmt_compania = sqlsrv_query($conn, $sql_compania, $params_compania);

        if ($stmt_compania !== false) {
            $row_compania = sqlsrv_fetch_array($stmt_compania, SQLSRV_FETCH_ASSOC);
            if ($row_compania) {
                echo "<h3>Editar Compañía</h3>
                      <form method='post'>
                          <label for='nombre'>Nombre de la Compañía:</label>
                          <input type='text' id='nombre' name='nombre' value='" . $row_compania['nombre'] . "' required>
                          <input type='hidden' name='id' value='" . $row_compania['idCompania'] . "'>
                          <input type='hidden' name='action' value='update'>
                          <input type='submit' value='Actualizar Compañía'>
                      </form>";
            }
            sqlsrv_free_stmt($stmt_compania);
        } else {
            echo "Error al obtener la compañía: " . print_r(sqlsrv_errors(), true);
        }
    }
    ?>

</body>
</html>
