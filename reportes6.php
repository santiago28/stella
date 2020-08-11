<?php

//Variables recibidas via GET
    include "conexion.php";
    $id_componente = '8';
    //Consulta  Visitas realizadas
    $registros=mysqli_query($mysqli, "
        SELECT nombre_componente, COUNT(id_sede) AS total FROM (SELECT c.nombre_componente, cs.id_contrato, p.nombre_prestador, m.nombre_modalidad, cs.id_sede, s.nombre_sede, a.numero_visita, a.id_interventor, COUNT(a.id_acta) AS total
FROM sede s
LEFT JOIN contrato_x_sede cs ON (s.id_sede=cs.id_sede)
LEFT JOIN acta a ON (a.id_sede=cs.id_sede AND a.id_contrato=cs.id_contrato)
LEFT JOIN modalidad m ON (cs.id_modalidad=m.id_modalidad)
LEFT JOIN componente c ON (a.id_componente=c.id_componente)
LEFT JOIN prestador p ON (cs.id_prestador=p.id_prestador)
WHERE cs.id_modalidad = 1 AND (a.id_componente = 8 OR a.id_componente IS NULL)
GROUP BY cs.id_sede, a.id_componente, cs.id_modalidad, cs.id_contrato
ORDER BY total ASC) as tabla GROUP BY nombre_componente ORDER BY total DESC ") OR die("Problemas en el select:".mysql_error());

    $sqlcomponentes = "SELECT id_componente, nombre_componente FROM componente WHERE estado = 1;";
    $rescomponentes = mysqli_query($mysqli, $sqlcomponentes);
?>
<!DOCTYPE html>
<html lang="es_CO">
 <head>
    <title>Listado de Sedes Visitadas y no Visitadas</title>

        <!-- JavaScript para los filtros de las tablas -->
    <script src="js/jquery-1.11.1.min.js"></script>

</head>
<body>
    <header>
        <select class="form_control" id="componente" name="componente" onchange="traemodalidad(this);">
            <option value="">Seleccione un Componente</option>
            <?php
            while ($componentes = mysqli_fetch_array($rescomponentes)) {
                echo '<option value="'.$componentes['id_componente'].'">'.$componentes['nombre_componente'].'</option>';
                } ?>
        </select>
        <select class="form_control" id="modalidad" name="modalidad" onchange="traetabla();">
            <option value="">Seleccione una Modalidad</option>
        </select>
    </header>
    <section>
        <table id="tabla2" name="tabla2" align='center' border='1'>
            <thead>
                <tr>
                    <th>Componente</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
        <?php while ($suma = mysqli_fetch_array($registros)) { ?>
            <tr>
                <td><?php echo $suma['nombre_componente']; ?></td>
                <td class="left"><?php echo $suma['total']; ?></td>
            </tr>
        <?php } ?>
            </tbody>

        </table>
    <hr>
    </section>
    <section>
        <table id="tabla" name="tabla1" align='center' border='1'>
        </table>
    </section>

    <script type="text/javascript">
    function traemodalidad(comp) {
                $("#componente option:selected").each(function () {
                    componente = $(this).val();
                    $.post("lib/newfunctions.php", { accion:"Modalidad", componente: componente }, function(data){
                        $("#modalidad").html(data);
                    });
                });
    }
    function traetabla() {

                    componente = $("#componente option:selected").val();
                    modalidad = $("#modalidad option:selected").val();
                    accion = "compXmodal";
                    $.post("lib/newfunctions.php", { accion:accion, componente: componente, modalidad:modalidad }, function(data){
                        $("#tabla").html(data);
                    });

    }
    </script>
 </body>
</html>