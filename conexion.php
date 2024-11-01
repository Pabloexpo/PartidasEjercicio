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
            function conectar () {
                $link = mysqli_connect('localhost', 'super', 'alumno', 'videojuegos');
                if ($link) {
                    return $link;
                } else {
                    echo "No se ha encontrado la conexión";
                }
            }
        ?>
    </body>
</html>
