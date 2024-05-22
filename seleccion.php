<?php
include 'conexion.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Películas</title>
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
    <h2>Consulta de Películas</h2>
    <form method="post">
        <label for="pais">País:</label>
        <select name="pais" id="pais">
            <option value="">Seleccionar País</option>
            <?php
            $sql_paises = "SELECT * FROM Pais";
            $result_paises = sqlsrv_query($conn, $sql_paises);
            if ($result_paises !== false) {
                while ($row_pais = sqlsrv_fetch_array($result_paises, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='" . $row_pais['pais'] . "'>" . $row_pais['pais'] . "</option>";
                }
                sqlsrv_free_stmt($result_paises);
            }
            ?>
        </select>

        <label for="genero">Género:</label>
        <select name="genero" id="genero">
            <option value="">Seleccionar Género</option>
            <?php
            $sql_generos = "SELECT * FROM Genero";
            $result_generos = sqlsrv_query($conn, $sql_generos);
            if ($result_generos !== false) {
                while ($row_genero = sqlsrv_fetch_array($result_generos, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='" . $row_genero['nombre'] . "'>" . $row_genero['nombre'] . "</option>";
                }
                sqlsrv_free_stmt($result_generos);
            }
            ?>
        </select>

        <input type="submit" name="submit" value="Filtrar">
    </form>

    <form action="AgregarPelicula.php">
        <input type="submit" value="Agregar Película">
    </form>
    <form action="home.php" style="display: inline;">
        <input type="submit" value="HOME">
    </form>

    <h2>Películas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Lanzamiento</th>
            <th>Duración</th>
            <th>País</th>
            <th>Género</th>
        </tr>
        <?php
        if(isset($_POST['submit'])) {
            $pais_nombre = $_POST['pais'];
            $genero_nombre = $_POST['genero'];

            $sql_peliculas = "SELECT p.idPelicula, p.titulo, p.lanzamientoPelicula, p.duracionPelicula, pa.pais AS nombre_pais, p.genero
                              FROM Pelicula p
                              INNER JOIN Pais pa ON p.Pais = pa.pais
                              WHERE pa.pais = ? AND p.genero = ?";
            $params_peliculas = array($pais_nombre, $genero_nombre);

            $result_peliculas = sqlsrv_query($conn, $sql_peliculas, $params_peliculas);
            if ($result_peliculas !== false) {
                while ($row_pelicula = sqlsrv_fetch_array($result_peliculas, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row_pelicula['idPelicula'] . "</td>";
                    echo "<td>" . $row_pelicula['titulo'] . "</td>";
                    echo "<td>" . $row_pelicula['lanzamientoPelicula'] . "</td>";
                    echo "<td>" . $row_pelicula['duracionPelicula'] . "</td>";
                    echo "<td>" . $row_pelicula['nombre_pais'] . "</td>";
                    echo "<td>" . $row_pelicula['genero'] . "</td>";
                    echo "</tr>";
                }
                sqlsrv_free_stmt($result_peliculas);
            } else {
                echo "Error al ejecutar la consulta de películas: " . print_r(sqlsrv_errors(), true);
            }
        }
        ?>
    </table>
</body>
</html>
