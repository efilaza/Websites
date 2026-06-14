<?php
/**
 * extract_xml.php
 * Αυτό το αρχείο είναι υπεύθυνο για την εξαγωγή των περιβαλλοντικών δράσεων σε μορφή XML.
 * Χρησιμοποιεί το DOMDocument για να δημιουργήσει ένα XML που συμμορφώνεται με το DTD που έχουμε ορίσει.
 */

include('db_connect.php'); // Σύνδεση με τη βάση δεδομένων 

$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$actions = [];

if($category_id > 0) {
    // SQL Query με χρήση Prepared Statements 
    $sql = "SELECT a.*, c.name AS category_name 
            FROM actions a 
            JOIN categories c ON a.category_id = c.id 
            WHERE c.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_id]);
    $actions = $stmt->fetchAll();
}

// 1. Προετοιμασία DOM με DOCTYPE 
$imp = new DOMImplementation();

$dtd = $imp->createDocumentType('actions', '', 'assets/xml/action.dtd');

// Δημιουργία του εγγράφου με το DTD και ρύθμιση κωδικοποίησης σε UTF-8 
$dom = $imp->createDocument(null, 'actions', $dtd);
$dom->encoding = 'UTF-8';
$dom->formatOutput = true;

// Ανάκτηση το root element σε μεταβλητή.
$root = $dom->documentElement;

// 2. Loop για τη δημιουργία των στοιχείων XML 
foreach ($actions as $action) {
    $actionElement = $dom->createElement('action');
    $root->appendChild($actionElement);

    // Στοιχεία βάσει του DTD που ορίσαμε 
    $actionElement->appendChild($dom->createElement('category', htmlspecialchars($action['category_name'])));
    $actionElement->appendChild($dom->createElement('title', htmlspecialchars($action['title'])));
    $actionElement->appendChild($dom->createElement('subtitle', htmlspecialchars($action['subtitle'])));

    $location = $dom->createElement('location');
    $location->appendChild($dom->createElement('location_name', htmlspecialchars($action['location_name'])));
    $location->appendChild($dom->createElement('municipality', htmlspecialchars($action['municipality'])));
    $actionElement->appendChild($location);

    $coords = $dom->createElement('coords');
    $coords->appendChild($dom->createElement('lat', $action['lat']));
    $coords->appendChild($dom->createElement('lng', $action['lng']));
    $actionElement->appendChild($coords);

    $date = $dom->createElement('date');
    $date->appendChild($dom->createElement('start_date', $action['start_date']));
    if (!empty($action['end_date'])) {
        $date->appendChild($dom->createElement('end_date', $action['end_date']));
    }
    $actionElement->appendChild($date);

    $actionElement->appendChild($dom->createElement('image', htmlspecialchars($action['image_path'] ?? '')));
    $actionElement->appendChild($dom->createElement('created_at', $action['created_at']));
}

// Δημιουργία  σύνδεσης με το XSL
$xslt = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="assets/xml/stylesheet.xsl"');

// Εισαγωγή της οδηγίας στην αρχή του εγγράφου, πριν από το root στοιχείο
$dom->insertBefore($xslt, $dom->firstChild);

// 3. Validation με βάση το DTD που έχουμε ορίσει

if ($dom->validate()) {
    header('Content-Type: text/xml; charset=UTF-8');
    echo $dom->saveXML();
} else {
    echo "Το XML δεν είναι έγκυρο.";
}
exit();




