<?php
/**
 * check_username.php
 * Αυτόνομο script για τον ασύγχρονο έλεγχο (AJAX) διαθεσιμότητας ονόματος χρήστη.
 */

// Εισαγωγή του αρχείου σύνδεσης με τη βάση δεδομένων
include('db_connect.php');

/**
 * Έλεγχος αν το αίτημα περιέχει την παράμετρο 'username'.
 * Χρησιμοποιούμε τη μέθοδο POST για μεγαλύτερη ασφάλεια σε σχέση με την GET.
 */
if (isset($_POST['username'])) {
    
    // Καθαρισμός των κενών διαστημάτων στην αρχή και στο τέλος
    $username = trim($_POST['username']);
    
    /**
     * Χρήση Prepared Statement.
     */
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username =?");
    $stmt->execute([$username]);


    
    /**
     * Αν η fetch() επιστρέψει αποτέλεσμα, το username χρησιμοποιείται ήδη.
     * Στέλνουμε μια απλή απάντηση κειμένου (plain text) την οποία θα διαβάσει η JavaScript.
     */
    if ($stmt->fetch()) {
        echo "exists"; // Το username βρέθηκε στη βάση
    } else {
        echo "available"; // Το username είναι ελεύθερο για εγγραφή
    }
}
?>