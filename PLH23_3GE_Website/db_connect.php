<?php
/**
 * db_connect.php
 * Κεντρικό αρχείο σύνδεσης με τη Βάση Δεδομένων.
 * Χρησιμοποιεί το αντικείμενο PDO για μέγιστη ασφάλεια και ευελιξία.
 */

// Στοιχεία σύνδεσης 
$host    = 'localhost';
$db      = 'eco_tracker_Lazaridou'; 
$user    = 'root';                  
$pass    = '';                      
$charset = 'utf8mb4'; // Υποστήριξη πλήρους σετ ελληνικών χαρακτήρων και emoji

// Δημιουργία του DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

/**
 * Ρυθμίσεις PDO (Options):
 * 1. ATTR_ERRMODE: Μετατρέπει τα σφάλματα της MySQL σε PHP Exceptions για καλύτερο debugging.
 * 2. DEFAULT_FETCH_MODE: Επιστρέφει τα αποτελέσματα ως συσχετιστικούς πίνακες (π.χ. $row['title']).
 * 3. ATTR_EMULATE_PREPARES: Απενεργοποιεί την εξομοίωση προετοιμασμένων δηλώσεων, 
 * αναγκάζοντας τη MySQL να χρησιμοποιεί αληθινά Prepared Statements για προστασία από SQL Injection.
 */
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    // Εκκίνηση της σύνδεσης PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Η επιβεβαίωση σύνδεσης παραμένει σε σχόλιο για λόγους παραγωγικότητας
    // echo "Επιτυχής σύνδεση!"; 
    
} catch (\PDOException $e) {
    /**
     * Σε περίπτωση αποτυχίας, το script σταματά (die).
     * Το μήνυμα σφάλματος περιλαμβάνει την αιτία της αποτυχίας,     */    
       die("Αποτυχία σύνδεσης με τη Βάση Δεδομένων: " . $e->getMessage());
}
?>