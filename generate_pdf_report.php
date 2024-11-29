<script>
async function generatePDF() {
    try {
        // Capturar la tabla usando html2canvas
        const tableElement = document.getElementById('dataTable');

        if (!tableElement) {
            throw new Error('No se encontró la tabla para capturar.');
        }

        // Configuración de opciones para html2canvas
        const canvas = await html2canvas(tableElement, {
            scale: 2, // Mejora la calidad de la imagen
            scrollY: -window.scrollY, // Para capturar correctamente si la tabla es larga
        });

        // Convertir la tabla capturada en una imagen PNG
        const imgData = canvas.toDataURL('image/png');

        // Acceder a jsPDF correctamente
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4'); // 'p' = orientación vertical, 'mm' = unidad de medida, 'a4' = tamaño de página

        // Obtener las dimensiones de la imagen y del PDF
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth(); // Ancho del PDF
        const pdfHeight = pdf.internal.pageSize.getHeight(); // Alto del PDF
        const imgWidth = pdfWidth - 20; // Margen de 10mm en cada lado
        const imgHeight = (imgProps.height * imgWidth) / imgProps.width; // Mantener la proporción de la imagen

        // Calcular cuántas páginas se necesitarán para la tabla
        let positionY = 10;
        const totalPages = Math.ceil(imgHeight / (pdfHeight - 20)); // Altura total de la imagen dividido por la altura disponible en una página

        // Dividir la imagen en páginas
        for (let i = 0; i < totalPages; i++) {
            if (i > 0) pdf.addPage(); // Añadir una nueva página si no es la primera
            const srcY = i * (pdfHeight - 20); // La posición de la imagen desde la cual recortar
            pdf.addImage(imgData, 'PNG', 10, positionY, imgWidth, pdfHeight - 20, undefined, 'FAST', 0, srcY);
        }

        // Obtener la fecha y hora actuales
        const now = new Date();
        const date = now.toLocaleDateString('es-ES').replace(/\//g, '-'); // Formato: DD-MM-YYYY
        const time = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/:/g, '-'); // Formato: HH-MM-SS

        // Concatenar la fecha y hora al nombre del archivo PDF
        const fileName = `Informe_Auditoria_${date}_${time}.pdf`;

        // Descargar el archivo PDF con el nombre que incluye la fecha y hora
        pdf.save(fileName);
    } catch (error) {
        console.error('Error al capturar la tabla o generar el PDF:', error);
    }
}

</script>