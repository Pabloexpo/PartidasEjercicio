<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Nueva partida</title>
        <style>
            html{
                background: lightgray;
            }
            body{
                width: 1200px;
                margin: 0 auto;
                display: flex;
                justify-content: center;
                background-color: white; 
                font-family: arial;
            }
            div{
                margin-bottom: 1em;

            }
        </style>
    </head>
    <body>
        <?php
        require_once 'conexion.php';

        $link = conectar();

        //seleccion del juego
        $query = "SELECT j.jue_id, j.jue_nombre FROM juegos j";
        $registros = mysqli_query($link, $query);

        echo '<form action="index.php" method="post">';
        echo '<div>';
        echo '<h4>Selecciona la partida y la plataforma</h4>';
        echo '<select name="seleccion">';
        while ($registro = mysqli_fetch_row($registros)) {
            echo '<option value="' . $registro[0] . '">' . $registro[1] . '</option>';
        }
        echo '</select>';

        $eleccion = isset($_POST['seleccion']) ? $_POST['seleccion'] : null;

        //seleccion de plataforma 
        $query = "SELECT p.pla_id, p.pla_nombre FROM plataformas p";
        $registros = mysqli_query($link, $query);

        echo '<select name="seleccionPla">';
        while ($registro = mysqli_fetch_row($registros)) {
            echo '<option value="' . $registro[0] . '">' . $registro[1] . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '<div>';
        echo '<input type="submit" name="aceptar" value="Aceptar">';
        echo '<input type="submit" value="Cancelar">';
        echo '</div>';

        $plataforma = isset($_POST['seleccionPla']) ? $_POST['seleccionPla'] : null;

        echo '</form>';

        mysqli_close($link);
        ?>
    </body>
</html>
