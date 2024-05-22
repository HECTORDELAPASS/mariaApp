<?php
include 'conexion.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Película</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        function mostrarMensaje() {
            alert("La película se ha agregado correctamente.");
        }
    </script>
</head>
<body>
    <h2>Agregar Película</h2>
    <form method="post" action="ProcesarPelicula.php" onsubmit="mostrarMensaje()">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="nombre_original">Nombre Original:</label>
        <input type="text" id="nombre_original" name="nombre_original" required><br><br>

        <label for="duracion">Duración:</label>
        <input type="text" id="duracion" name="duracion" required><br><br>

        <label for="fecha_lanzamiento">Fecha de Lanzamiento:</label>
        <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" required><br><br>

        <label for="genero">Género:</label>
        <select name="genero" id="genero" required>
            <option value="">Seleccionar Género</option>
            <?php
            $sql_generos = "SELECT * FROM Genero";
            $result_generos = sqlsrv_query($conn, $sql_generos);

            if ($result_generos !== false) {
                while ($row_genero = sqlsrv_fetch_array($result_generos, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='" . $row_genero['nombre'] . "'>" . $row_genero['nombre'] . "</option>";
                }
                sqlsrv_free_stmt($result_generos);
            } else {
                echo "Error al obtener los géneros: " . print_r(sqlsrv_errors(), true);
            }
            ?>
        </select><br><br>

        <label for="idioma">Idioma:</label>
        <select name="idioma" id="idioma" required>
            <option value="">Seleccionar Idioma</option>
            <?php
            $sql_idiomas = "SELECT DISTINCT idioma FROM Pelicula";
            $result_idiomas = sqlsrv_query($conn, $sql_idiomas);

            if ($result_idiomas !== false) {
                while ($row_idioma = sqlsrv_fetch_array($result_idiomas, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='" . $row_idioma['idioma'] . "'>" . $row_idioma['idioma'] . "</option>";
                }
                sqlsrv_free_stmt($result_idiomas);
            } else {
                echo "Error al obtener los idiomas: " . print_r(sqlsrv_errors(), true);
            }
            ?>
        </select><br><br>

        <label for="compania">Compañía:</label>
        <select name="compania" id="compania" required>
            <option value="">Seleccionar Compañía</option>
            <?php
            $sql_companias = "SELECT * FROM Compania";
            $result_companias = sqlsrv_query($conn, $sql_companias);

            if ($result_companias !== false) {
                while ($row_compania = sqlsrv_fetch_array($result_companias, SQLSRV_FETCH_ASSOC)) {
                    echo "<option value='" . $row_compania['nombre'] . "'>" . $row_compania['nombre'] . "</option>";
                }
                sqlsrv_free_stmt($result_companias);
            } else {
                echo "Error al obtener las compañías: " . print_r(sqlsrv_errors(), true);
            }
            ?>
        </select><br><br>

        <input type="submit" name="submit" value="Agregar Película">
    </form>
</body>
</html>
