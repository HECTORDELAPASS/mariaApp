<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == "create") {
            $pais = $_POST['pais'];
            $sql_insert = "INSERT INTO Pais (pais, fechaCrea, estatus) VALUES (?, GETDATE(), 1)";
            $params_insert = array($pais);
            $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);
            if ($stmt_insert === false) {
                echo "Error al insertar el país: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "update") {
            $idPais = $_POST['idPais'];
            $pais = $_POST['pais'];
            $sql_update = "UPDATE Pais SET pais = ?, fechaModifica = GETDATE() WHERE idPais = ?";
            $params_update = array($pais, $idPais);
            $stmt_update = sqlsrv_query($conn, $sql_update, $params_update);
            if ($stmt_update === false) {
                echo "Error al actualizar el país: " . print_r(sqlsrv_errors(), true);
            }
        }

        if ($action == "delete") {
            $idPais = $_POST['idPais'];
            $sql_delete = "DELETE FROM Pais WHERE idPais = ?";
            $params_delete = array($idPais);
            $stmt_delete = sqlsrv_query($conn, $sql_delete, $params_delete);
            if ($stmt_delete === false) {
                echo "Error al eliminar el país: " . print_r(sqlsrv_errors(), true);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Países</title>
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
    <h2>Gestión de Países</h2>

    <form method="post">
        <h3>Agregar Nuevo País</h3>
        <label for="pais">Nombre del País:</label>
        <input type="text" id="pais" name="pais" required>
        <input type="hidden" name="action" value="create">
        <input type="submit" value="Agregar País">
    </form>

    <h3>Lista de Países</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre del País</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql_paises = "SELECT * FROM Pais";
        $result_paises = sqlsrv_query($conn, $sql_paises);

        if ($result_paises !== false) {
            while ($row_pais = sqlsrv_fetch_array($result_paises, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row_pais['idPais'] . "</td>";
                echo "<td>" . $row_pais['pais'] . "</td>";
                echo "<td>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='idPais' value='" . $row_pais['idPais'] . "'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este país?\");'>
                      </form>";
                echo "<form method='post' style='display:inline;'>
                        <input type='hidden' name='idPais' value='" . $row_pais['idPais'] . "'>
                        <input type='hidden' name='action' value='edit'>
                        <input type='submit' value='Editar'>
                      </form>";
                echo "</td>";
                echo "</tr>";
            }
            sqlsrv_free_stmt($result_paises);
        } else {
            echo "Error al obtener los países: " . print_r(sqlsrv_errors(), true);
        }
        ?>
    </table>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "edit") {
        $idPais = $_POST['idPais'];
        $sql_pais = "SELECT * FROM Pais WHERE idPais = ?";
        $params_pais = array($idPais);
        $stmt_pais = sqlsrv_query($conn, $sql_pais, $params_pais);

        if ($stmt_pais !== false) {
            $row_pais = sqlsrv_fetch_array($stmt_pais, SQLSRV_FETCH_ASSOC);
            if ($row_pais) {
                echo "<h3>Editar País</h3>
                      <form method='post'>
                          <label for='pais'>Nombre del País:</label>
                          <input type='text' id='pais' name='pais' value='" . $row_pais['pais'] . "' required>
                          <input type='hidden' name='idPais' value='" . $row_pais['idPais'] . "'>
                          <input type='hidden' name='action' value='update'>
                          <input type='submit' value='Actualizar País'>
                      </form>";
            }
            sqlsrv_free_stmt($stmt_pais);
        } else {
            echo "Error al obtener el país: " . print_r(sqlsrv_errors(), true);
        }
    }
    ?>
</body>
</html>
