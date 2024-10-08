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
    $pdf->Cell(0, 10, 'Estadisticas de Jugadas', 0, 1, 'C');
    $pdf->Ln(10); // Espacio vertical para separar el título del contenido

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'hora', 1);
    $pdf->Cell(30, 10, 'apuesta', 1);
    $pdf->Cell(30, 10, 'lanzamiento', 1);
    $pdf->Cell(40, 10, 'saldo_inicial', 1);
    $pdf->Cell(40, 10, 'saldo_final', 1);
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
        $pdf->Cell(40, 10, $jugada['hora'], 1);
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
