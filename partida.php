<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'conexion.php';
        $link = conectar();
        $id = isset($_POST['name']) ? $_POST['idOculto'] : $_POST['id'];
        
//        elaboramos este ternario para comprobar si se ha pulsado el botón de
//        dar puntos, en el caso de que así sea, id será el valor oculto idOculto
//        de esta página para evitar perder su valor
//        
        //CABECERO PARA MOSTRAR LOS DATOS - CON SUS RESPECTIVAS CONSULTAS
        
        //FECHA
        
        $queryFecha = "SELECT p.par_fecha FROM partidas p WHERE p.par_id = $id";
        $regFecha = mysqli_query($link, $queryFecha);
        $fecha = mysqli_fetch_row($regFecha)[0]; //recogemos el valor
        
        
        //HORA
        
        $queryHora = "SELECT p.par_hora FROM partidas p WHERE p.par_id = $id";
        $regHora = mysqli_query($link, $queryHora);
        $hora = mysqli_fetch_row($regHora)[0];
        
        //NOMBRE DEL VIDEOJUEGO
        
        $queryJuego = "SELECT ju.jue_nombre FROM partidas p JOIN "
                . "juegos ju on p.par_juego=ju.jue_id WHERE p.par_id = $id";
        $regJuego = mysqli_query($link, $queryJuego);
        $juego = mysqli_fetch_row($regJuego)[0]; 
        
        //NOMBRE DE LA PLATAFORMA
        
        $queryCon = "SELECT pl.pla_nombre FROM partidas p JOIN plataformas pl on "
                . "p.par_plataforma=pl.pla_id WHERE p.par_id = $id";
        $regCon = mysqli_query($link, $queryCon);
        $consola = mysqli_fetch_row($regCon)[0]; 
        
        ?>
<!--        MOSTRAMOS LOS DATOS DE LA CABECERA-->
        
        <header>
            <h3>Partida id: <?php echo $id;?></h3>
            <h3>Fecha de la partida: <?php echo $fecha;?></h3>
            <h3>Hora de la partida: <?php echo $hora;?></h3>
            <h3>Nombre del videojuego: <?php echo $juego;?></h3>
            <h3>Plataforma: <?php echo $consola;?></h3>
        </header>
        
        
        <?php

        //CONSULTA PARA VER LOS DATOS DE LA PARTIDA
        $consultaPartida = 'SELECT j.jug_nombre NOMBRE,pu.pun_puntuacion PUNTUACIÓN
                            FROM puntuaciones pu 
                            JOIN jugadores j on pu.pun_jugador=j.jug_id
                            WHERE pu.pun_partida=' . $id;

        $registros = mysqli_query($link, $consultaPartida);

        //columnas
        echo '<table border="1">';

        echo '<tr>';
        while ($columnas = mysqli_fetch_field($registros)) {
            echo '<th>' . $columnas->name . '</th>';
        }
        echo '</tr>';

        //filas

        while ($fila = mysqli_fetch_row($registros)) {
            echo '<tr>';
            for ($i = 0; $i < count($fila); $i++) {
                echo '<td>' . $fila[$i] . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        mysqli_close($link);
        ?>


        <form action="" method="post">
            <!--EVITAMOS PERDER EL VALOR DE ID AL REINICIAR LA PAG-->
            <input type="hidden" name="idOculto" value = <?php echo $id; ?>> 
            <input type="submit" name="name" value="Recarga de página">
        </form>
        <form action="index.php" method="post">
            <input type="submit" name="reiniciar" value="Reiniciar">
        </form>
    </body>
</html>
