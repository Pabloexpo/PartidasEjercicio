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
        
        if (isset($_POST['op']) || isset($_POST['add'])) { //TENEMOS  FORMULARIOS
            $id = $_POST['idOculto'];
        } else {
            $id = $_POST['id'];
        }
//        elaboramos este ternario para comprobar si se ha pulsado el botón de
//        dar puntos, en el caso de que así sea, id será el valor oculto idOculto
//        de esta página para evitar perder su valor
//   
        
        //INSERTAMOS AL JUGADOR ANTES DE TODO SINO CUANDO AÑADAMOS, PRIMERO
//        MOSTRARÁ LA TABLA Y DESPUÉS AÑADIRÁ
        
        if (isset($_POST['seleccion'])) {
            //RECUPERAMOS AL JUGADOR

            $newJugador = $_POST['seleccion'];

            //le sacamos su id

            $query = "SELECT j.jug_id FROM jugadores j WHERE j.jug_nombre= '$newJugador'";

            $regNuevo = mysqli_query($link, $query);

            $newJugId = mysqli_fetch_row($regNuevo)[0];

            //AHORA LO INSERTAMOS EN LA PARTIDA CON PUNTUACION DE CERO

            $insert = "INSERT INTO puntuaciones (pun_partida, pun_jugador, pun_puntuacion)"
                    . "VALUES ($id, $newJugId,0)";

            mysqli_query($link, $insert);
        }

     
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
            <h3>Partida id: <?php echo $id; ?></h3>
            <h3>Fecha de la partida: <?php echo $fecha; ?></h3>
            <h3>Hora de la partida: <?php echo $hora; ?></h3>
            <h3>Nombre del videojuego: <?php echo $juego; ?></h3>
            <h3>Plataforma: <?php echo $consola; ?></h3>
        </header>


        <?php
        $valoroperar; //lo utilizamos para sumar o restar
//PROCEDIMIENTO PARA EL UPDATE

        if (isset($_POST['op'])) {

            //SELECCIONAMOS EL ID DEL JUGADOR
            $nombre = $_POST['nombreOculto'];

            $selectJugador = "SELECT ju.jug_id FROM jugadores ju "
                    . "join puntuaciones p on ju.jug_id=p.pun_jugador "
                    . "where ju.jug_nombre='$nombre' group by ju.jug_id";
            $regJug = mysqli_query($link, $selectJugador);
            $idJugador = mysqli_fetch_row($regJug)[0];

            //SELECCIONAMOS LA PUNTUACION Y RESTAMOS CON EL VALOR ANTES DEL UPDATE

            $selectPuntuacion = "SELECT p.pun_puntuacion FROM puntuaciones p "
                    . "WHERE p.pun_partida=$id and p.pun_jugador=$idJugador";
            $regPun = mysqli_query($link, $selectPuntuacion);

            $puntuacion = mysqli_fetch_row($regPun)[0];

            //OPERAMOS

            $operador = $_POST['op']; //RECOGEMOS EL VALOR


            $resultado = $puntuacion + $operador;

            if ($resultado <= 0) {
                $resultado = 0;
            }

            //HACEMOS EL UPDATE

            $update = "UPDATE puntuaciones p SET p.pun_puntuacion = $resultado "
                    . "WHERE p.pun_partida=$id AND p.pun_jugador=$idJugador";
            mysqli_query($link, $update);
        }


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
            echo '<form method="post">'; //FORMULARIO IMPORTANTE

            for ($i = 0; $i < count($fila); $i++) {
                echo '<td>' . $fila[$i] . '</td>';
            }
            //campo oculto de la fila actual
            echo '<input type="hidden" name="nombreOculto" value="' . $fila[0] . '"></td>';
            //BOTONES DE PUNTUACION 
            for ($j = 0; $j < 6; $j++) {
                $valor = -100;
                switch ($j) { //CAMBIAMOS DEPENDIENDO DE LA SITUACION DE j
                    case 0:
                        $valor = -100;
                        break;
                    case 1:
                        $valor = -10;
                        break;
                    case 2:
                        $valor = -1;
                        break;
                    case 3:
                        $valor = 1;
                        break;
                    case 4:
                        $valor = 10;
                        break;
                    case 5:
                        $valor = 100;
                }
                echo '<td><button type="submit" name="op" value="' . $valor . '">' . $valor . '</button></td>';
                ?>
                <!--EVITAMOS PERDER EL VALOR DE ID AL REINICIAR LA PAG-->
                <input type="hidden" name="idOculto" value = <?php echo $id; ?>> 
                <?php
            }
            echo '</form>';
            echo '</tr>';
        }
        echo '</table>';
        ?>
        <div class="add">
            <?php
            //REALIZAMOS LA LISTA DESPLEGABLE
            $select = "SELECT j.jug_nombre
            FROM jugadores j
            WHERE j.jug_id NOT IN (SELECT p.pun_jugador FROM puntuaciones p WHERE p.pun_partida=$id)";

            $registros = mysqli_query($link, $select);

            echo '<form method="post">';

            echo '<label for ="seleccion">Selecciona al nuevo jugador</label>';

            echo '<select name="seleccion">';

            while ($registro = mysqli_fetch_row($registros)) {
                echo '<option value="' . $registro[0] . '">' . $registro[0] . '</option>';
            }

            echo '</select>';
            ?>

            <!--DE NUEVO, EVITAMOS PERDER EL VALOR DE ID AL REINICIAR LA PAG-->
            <input type="hidden" name="idOculto" value = <?php echo $id; ?>> 
            <input type="submit" name="add" value="Añadir Jugador">

            <?php
            echo '</form>';


            mysqli_close($link);
            ?>
        </div>
        <form action="index.php" method="post">
            <input type="submit" name="reiniciar" value="Selección de partida">
        </form>
    </body>
</html>
