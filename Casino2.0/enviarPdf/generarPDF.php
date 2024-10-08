<?php
require '../vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

// Incluir la librería FPDF para generar PDFs
require('../vendor/fpdf/fpdf/src/Fpdf/fpdf.php'); // Verifica que la ruta a FPDF sea correcta

// Incluir la conexión a la base de datos
include '../conexionBD.php'; // Verifica que la ruta a la conexión a la base de datos sea correcta

/**
 * Función para generar un PDF con las estadísticas de un jugador específico.
 * 
 * @param int $id_jugador - El ID del jugador cuyas estadísticas se generarán.
 * @param PDO $pdo - El objeto de conexión a la base de datos.
 * @return string - La ruta del archivo PDF generado.
 */
function generarPDF($id_jugador, $pdo)
{
    // Crear el objeto PDF 
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Título del PDF
    $pdf->Cell(0, 10, 'Datos del Jugador', 0, 1, 'C');
    $pdf->Ln(10); // Espacio vertical para separar el título del contenido

    // Consulta SQL para obtener los datos del jugador
    $sqlJugador = "SELECT apodo, nombre, apellidos, dni, direccion, saldo FROM jugador WHERE id_jugador = :id_jugador";
    $stmtJugador = $pdo->prepare($sqlJugador);
    $stmtJugador->bindParam(':id_jugador', $id_jugador);
    $stmtJugador->execute();
    $jugador = $stmtJugador->fetch(PDO::FETCH_ASSOC);

    // Verificamos que el jugador exista
    if ($jugador) {
        // Añadir información del jugador al PDF

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Apodo: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $jugador['apodo'], 0, 1); // Apodo

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Nombre y apellidos: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $jugador['nombre'] . ' ' . $jugador['apellidos'], 0, 1); // Nombre y Apellidos

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'DNI: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $jugador['dni'], 0, 1); // DNI


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Direccion: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $jugador['direccion'], 0, 1); // Dirección

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Saldo: ', 0, 0);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $jugador['saldo'] . ' Euros', 0, 1); // Saldo actual del jugador

        $pdf->Ln(10); // Espacio vertical antes de la tabla de jugadas
    } else {
        // Si no existe el jugador, se muestra un mensaje
        $pdf->Cell(0, 10, 'Jugador no encontrado.', 0, 1, 'C');
        return false; // Si el jugador no existe, se detiene la generación del PDF
    }

    // Título para las jugadas
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Estadisticas de Jugadas', 0, 1, 'C');
    $pdf->Ln(10); // Espacio vertical para separar el título del contenido

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, 'Hora', 1);
    $pdf->Cell(30, 10, 'Apuesta', 1);
    $pdf->Cell(30, 10, 'Lanzamiento', 1);
    $pdf->Cell(40, 10, 'Saldo Inicial', 1);
    $pdf->Cell(40, 10, 'Saldo Final', 1);
    $pdf->Ln(); // Nueva línea para empezar a rellenar la tabla

    // Consulta SQL para obtener las jugadas del jugador desde la base de datos
    $sqlJugadas = "SELECT * FROM jugada WHERE id_jugador = :id_jugador ORDER BY hora DESC";
    $stmtJugadas = $pdo->prepare($sqlJugadas);
    $stmtJugadas->bindParam(':id_jugador', $id_jugador);
    $stmtJugadas->execute();
    $jugadas = $stmtJugadas->fetchAll(PDO::FETCH_ASSOC);

    // Poblamos la tabla con los datos de las jugadas obtenidas de la base de datos
    $pdf->SetFont('Arial', '', 12); // Cambiamos a una fuente más pequeña para el contenido
    foreach ($jugadas as $jugada) {
        $pdf->Cell(50, 10, $jugada['hora'], 1);
        $pdf->Cell(30, 10, $jugada['apuesta'], 1);
        $pdf->Cell(30, 10, $jugada['lanzamiento'], 1);
        $pdf->Cell(40, 10, $jugada['saldo_inicial'], 1);
        $pdf->Cell(40, 10, $jugada['saldo_final'], 1);
        $pdf->Ln();
    }

    // Guardar el PDF en el servidor con un nombre único basado en el ID del jugador
    $pdfFilePath = 'estadisticas_jugador_' . $id_jugador . '.pdf';
    $pdf->Output('F', $pdfFilePath); // Guarda el archivo PDF en el servidor

    // Retornar la ruta del archivo PDF generado para que se use en el envío de correo
    return $pdfFilePath;
}
