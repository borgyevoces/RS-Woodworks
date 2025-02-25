<?php
include __DIR__ . '/includes/connection.php';

$query = $_GET['query'];
$sql = "SELECT product_id, product_name, product_description, product_image1 FROM products WHERE product_name LIKE ? OR product_keyword LIKE ? OR category_title LIKE ?";
$stmt = $con->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<div class="dropdown-item search-result-item" data-id="' . $row['product_id'] . '">';
        $output .= '<img src="media/' . $row['product_image1'] . '" alt="' . $row['product_name'] . '">';
        $output .= '<div class="details">';
        $output .= '<h5>' . $row['product_name'] . '</h5>';
        $output .= '<p>' . $row['product_description'] . '</p>';
        $output .= '</div></div>';
    }
} else {
    $output .= '<a class="dropdown-item" href="#">No products found</a>';
}

echo $output;
?>
