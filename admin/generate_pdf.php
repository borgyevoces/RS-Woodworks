<?php
error_reporting(0); // Turn off error reporting to prevent output before PDF

require_once('TCPDF-main/tcpdf.php');

include('../includes/connection.php'); 
session_start();

// Capture the table from the URL
$table = isset($_GET['table']) ? $_GET['table'] : 'shipped'; // Default is shipped

switch ($table) {
    case 'recent':
        $title = 'Recent Orders';
        $query = "
            SELECT o.*, u.full_name, u.user_address, p.product_name, p.product_price, p.product_image1, o.date
            FROM orders o
            JOIN user_table u ON o.user_id = u.user_id 
            LEFT JOIN products p ON o.product_id = p.product_id
        ";
        break;

    case 'shipped':
        $title = 'Shipped Orders';
        $query = "
            SELECT so.*, u.full_name, u.user_address, p.product_name, p.product_price, p.product_image1, so.delivery_date
            FROM shipped_orders so
            JOIN user_table u ON so.user_id = u.user_id 
            LEFT JOIN products p ON so.product_id = p.product_id
        ";
        break;

    case 'completed':  // Add the new case for completed orders
        $title = 'Completed Orders';
        $query = "
            SELECT co.*, u.full_name, u.user_address, p.product_name, p.product_price, p.product_image1, co.date_received 
            FROM completed_order co 
            JOIN user_table u ON co.user_id = u.user_id 
            LEFT JOIN products p ON co.product_id = p.product_id
        ";
        break;

    default:
        die('Invalid table parameter.');
}

// Create a new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle($title);
$pdf->SetSubject('Orders PDF');
$pdf->SetKeywords('TCPDF, PDF, orders, management');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, 'Generated on ' . date('Y-m-d H:i:s'));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 35, 15);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();

// Add a title
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 10, $title, 0, 1, 'C');

// Add a line break
$pdf->Ln(10);

// Set font for the table
$pdf->SetFont('helvetica', '', 7);

// Execute the query to fetch the data
$result = mysqli_query($conn, $query);

// Generate the PDF content
$html = '<table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Quantity</th>
                    <th>Payment Status</th>
                    <th>User Address</th>
                    <th>Order Date</th>
                    <th>Date Received</th>
                    <th>Delivery Date</th>
                </tr>
            </thead>
            <tbody>';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if at least one of the fields has data
        if (!empty($row['full_name']) || 
            !empty($row['product_name']) || 
            !empty($row['product_price']) || 
            !empty($row['quantity']) || 
            !empty($row['payment_status']) || 
            !empty($row['user_address']) || 
            !empty($row['date']) || 
            !empty($row['date_received']) || 
            !empty($row['delivery_date'])) {
            
            $html .= '<tr>';
            $html .= '<td>' . (isset($row['full_name']) ? htmlspecialchars($row['full_name']) : '') . '</td>';
            $html .= '<td>' . (isset($row['product_name']) ? htmlspecialchars($row['product_name']) : '') . '</td>';
            $html .= '<td>' . (isset($row['product_price']) ? htmlspecialchars($row['product_price']) : '') . '</td>';
            $html .= '<td>' . (isset($row['quantity']) ? htmlspecialchars($row['quantity']) : '') . '</td>';
            $html .= '<td>' . (isset($row['payment_status']) ? htmlspecialchars($row['payment_status']) : '') . '</td>';
            $html .= '<td>' . (isset($row['user_address']) ? htmlspecialchars($row['user_address']) : '') . '</td>';
            $html .= '<td>' . (isset($row['date']) ? htmlspecialchars($row['date']) : '') . '</td>';
            $html .= '<td>' . (isset($row['date_received']) ? htmlspecialchars($row['date_received']) : '') . '</td>';
            $html .= '<td>' . (isset($row['delivery_date']) ? htmlspecialchars($row['delivery_date']) : '') . '</td>';
            $html .= '</tr>';
        }
    }
} else {
    $html .= '<tr><td colspan="9">No orders found.</td></tr>'; // Adjusted the colspan to match the number of columns
}

$html .= '</tbody></table>';

// Write the HTML content to the PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output($table . '_orders.pdf', 'D');

// Close the database connection
mysqli_close($conn);
?>
