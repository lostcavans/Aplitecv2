<?php
require 'vendor/autoload.php'; // Asegúrate de que la ruta sea correcta

use Mpdf\Tag\PageBreak;
use Spipu\Html2Pdf\Html2Pdf;

// Verifica que los datos hayan sido enviados
if (!isset($_POST['imgData1']) || !isset($_POST['imgData2']) || !isset($_POST['tableHTML'])) {
    die('Faltan datos para generar el PDF.');
}

// Obtener datos del POST
$imgData1 = $_POST['imgData1'];
$imgData2 = $_POST['imgData2'];
$tableHTML = $_POST['tableHTML'];

// Crear una nueva instancia de HTML2PDF
$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));

// Crear el contenido HTML para el PDF
$content = '<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm">';
$content .= '<style>
                body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
                h1 { text-align: center; color: #007bff; font-size: 18px; margin-bottom: 10px; }
                h2 { color: #0056b3; font-size: 16px; margin-bottom: 5px; }
                img { display: block; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
                th { background-color: #007bff; color: #fff; }
                tr:nth-child(even) { background-color: #f2f2f2; }
                tr:hover { background-color: #ddd; }
                .footer { text-align: center; font-size: 10px; color: #777; margin-top: 20px; }
            </style>';

// Encabezado
$content .= '<h1>Informe de Datos de la Estación</h1>';

// Incluir las imágenes
$content .= '<h2>Gráfico 1</h2>';
$content .= '<img src="' . $imgData1 . '" style="width: 100%; max-width: 600px;" />';
$content .= '<h2>Gráfico 2</h2>';
$content .= '<img src="' . $imgData2 . '" style="width: 100%; max-width: 600px;" />';

// Incluir la tabla
$content .= '<h2>Datos</h2>';
$content .= $tableHTML;

// Pie de página
$content .= '<div class="footer">Generado el ' . date('d-m-Y H:i:s') . '</div>';

$content .= '</page>';

// Cargar el contenido HTML
$html2pdf->writeHTML($content);

// Enviar el PDF al navegador
$html2pdf->output('Informe_Estacion_' . date('d-m-Y') . '.pdf');
?>
