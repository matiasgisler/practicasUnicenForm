<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    require_once __DIR__ . "/conectar.php";
    if (!isset($conn)) {
        die("La conexión a la base de datos no está definida.");
    }

    // Recoger los datos del formulario
    $Carrera = $_POST['carrera'];
    $apellido_nombre = $_POST['apellido_nombre'];
    $DNI = $_POST['DNI'];
    $fecha_egreso = $_POST['fecha_egreso'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $ciudad = $_POST['ciudad'];
    $situacion_laboral = $_POST['situacion_laboral'];
    $empresa = $_POST['empresa'];
    $localidadempresa = $_POST['localidadempresa'];
    $cargo = $_POST['cargo'];
    $area = $_POST['area'];
    $mail = $_POST['mail'];
    $vinculacion = $_POST['vinculacion'];
    $Actividad = $_POST['Actividad'];
    $Docente = $_POST['Docente'];
    $cargo_docente = isset($_POST['cargo_docente']) ? $_POST['cargo_docente'] : NULL;
    $Departamento_docente = isset($_POST['Departamento_docente']) ? $_POST['Departamento_docente'] : NULL;
    $becario = isset($_POST['becario']) ? $_POST['becario'] : NULL;
    $no_docente = isset($_POST['no_docente']) ? $_POST['no_docente'] : NULL;
    $desocupado = isset($_POST['desocupado']) ? $_POST['desocupado'] : NULL;
    $capacitarse = $_POST['capacitarse'];
    $acompanar = $_POST['acompanar'];


    if ($Docente === "NO") {
        $Docente = "NO";
        $cargo_docente =  NULL;
        $Departamento_docente =  NULL;
    }

    if ($vinculacion === "NO" && $Docente === "SI") {
        $Docente = "NO";
        $cargo_docente =  NULL;
        $Departamento_docente =  NULL;
        $becario = NULL;
        $no_docente = NULL;
        $desocupado = NULL;
    }
    if ($vinculacion === "SI" && $Docente === "NO") {
        $Docente = "NO";
        $cargo_docente =  NULL;
        $Departamento_docente =  NULL;
        $becario = $_POST['becario'];
        $no_docente = $_POST['no_docente'];
        $desocupado = $_POST['desocupado'];
    }
    if ($Docente === "SI") {
        $Docente = "SI";
        $cargo_docente =  $_POST['cargo_docente'];
        $Departamento_docente =  $_POST['Departamento_docente'];
        $becario = $_POST['becario'];
        $no_docente = $_POST['no_docente'];
        $desocupado = $_POST['desocupado'];
    }

    // Verificar si el DNI ya existe en la base de datos
    $check_sql = "SELECT id FROM formulario_etapas WHERE DNI = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("s", $DNI);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Si existe, actualizar la columna estado a "inactivo"
        $update_sql = "UPDATE formulario_etapas SET estado = 'inactivo' WHERE DNI = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("s", $DNI);
        $stmt_update->execute();
        $stmt_update->close();
    }

    $stmt_check->close();

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO formulario_etapas (carrera, apellido_nombre, DNI, fecha_egreso, telefono, correo, ciudad, situacion_laboral, empresa, localidadempresa, cargo, area, mail, vinculacion, Actividad, Docente, cargo_docente, Departamento_docente, becario, no_docente, desocupado, capacitarse, acompanar, estado) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssssssssssssss", $Carrera, $apellido_nombre, $DNI, $fecha_egreso, $telefono, $correo, $ciudad, $situacion_laboral, $empresa, $localidadempresa, $cargo, $area, $mail, $vinculacion, $Actividad, $Docente, $cargo_docente, $Departamento_docente, $becario, $no_docente, $desocupado, $capacitarse, $acompanar);

    if ($stmt->execute()) {
        // Redirigir a la URL deseada después del envío exitoso
        echo '<div class="success-message">Formulario enviado correctamente.</div>';
        header("Refresh: 2; URL=https://www.fio.unicen.edu.ar/index.php?option=com_content&view=article&id=2141&Itemid=726");
        exit();
    } else {
        echo '<div class="error-message">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
    $conn->close();
    ?>
</body>

</html>