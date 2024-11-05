<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Selección de partida</title>
        <style>
            html{
                background: lightgray; 
            }
            body{
                width: 1200px;
                margin: 0 auto;
                display:flex;
                flex-direction: column;
                align-items: center;
                background-color: white; 
                font-family: arial;

            }
            #nueva{
                margin: 1em;
            }
        </style>
    </head>
    <body>
        <header>
            <h3>Elige una partida</h3>
        </header>
        <?php
        require_once 'conexion.php'; //llamamos al php que contiene la funcion para conectarnos

        $link = conectar();

        //REALIZAMOS EL INSERT SI HEMOS APLICADO NUEVA PARTIDA
        $juego = isset($_POST['seleccion']) ? $_POST['seleccion'] : null;
        $plataforma = isset($_POST['seleccionPla']) ? $_POST['seleccionPla'] : null;

        if (!empty($juego) && !empty($plataforma)) {
            $insert = "INSERT INTO partidas (par_fecha, par_hora, par_juego, par_plataforma) VALUES (DATE(NOW()), TIME(NOW()), $juego, $plataforma)";
            mysqli_query($link, $insert);
        }




        $query = 'SELECT p.par_id as ID, p.par_fecha as FECHA, p.par_hora as HORA, j.jue_nombre as NOMBRE, pl.pla_nombre as PLATAFORMA
                FROM partidas p 
                JOIN juegos j on p.par_juego = j.jue_id
                JOIN plataformas pl on p.par_plataforma = pl.pla_id
                order by p.par_id';

        $registros = mysqli_query($link, $query); //registramos la query
        //elaboramos las columnas

        echo '<table border="1">';

        echo '<tr>';
        while ($columnas = mysqli_fetch_field($registros)) {
            echo '<th>' . $columnas->name . '</th>';
        }
        echo '</tr>';

        //elaboramos las filas

        while ($fila = mysqli_fetch_row($registros)) {
            echo '<tr>';
            for ($i = 0; $i < count($fila); $i++) {
                echo '<td>' . $fila[$i] . '</td>';
            }
            //Elaboramos el formulario para recoger el valor
            echo '<form action="partida.php" method="post">';
            echo '<td>';
            echo '<input type="hidden" name="id" value="' . $fila[0] . '">'; //evitamos perder el id de la partida al cambiar de página
            echo '<input type="submit" value="Seleccionar">';
            echo '</form></td>';
            echo '</tr>';
        }
        echo '</table>';

        mysqli_close($link);
        ?>

        <form action="nueva.php" method="post">
            <input type="submit" name="nueva" id="nueva" value="Nueva partida">
        </form>
    </body>
</html>
