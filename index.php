<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'conexion.php'; //llamamos al php que contiene la funcion para conectarnos
        
        $link = conectar(); 
        
        $query = 'SELECT p.par_id as ID, p.par_fecha as FECHA, p.par_hora as HORA, j.jue_nombre as NOMBRE, pl.pla_nombre as PLATAFORMA
                FROM partidas p 
                JOIN juegos j on p.par_juego = j.jue_id
                JOIN plataformas pl on p.par_plataforma = pl.pla_id
                order by p.par_id'; 
        
        $registros = mysqli_query($link, $query); //registramos la query
        
        //elaboramos las columnas
        
        echo '<table border="1">'; 
        
        echo '<tr>'; 
        while ($columnas = mysqli_fetch_field($registros)){
            echo '<th>'.$columnas -> name.'</th>'; 
        }
        echo '</tr>'; 
        
        //elaboramos las filas
        
        while ($fila= mysqli_fetch_row($registros)){
            echo '<tr>'; 
            for ($i=0; $i<count($fila); $i++){
                echo '<td>'.$fila[$i].'</td>'; 
            }
            //Elaboramos el formulario para recoger el valor
            echo '<form action="partida.php" method="post">'; 
            echo '<td>'; 
            echo '<input type="hidden" name="id" value="'.$fila[0].'">'; //evitamos perder el id de la partida al cambiar de página
            echo '<input type="submit" value="Seleccionar">'; 
            echo '</form></td>'; 
            echo '</tr>'; 
        }  
        echo '</table>'; 
        
        mysqli_close($link); 
        ?>
    </body>
</html>
