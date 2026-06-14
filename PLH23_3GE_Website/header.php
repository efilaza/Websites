<?php
/**
 * header.php
 * Κεντρικό αρχείο κεφαλίδας για το Eco-Tracker.
 * Διαχειρίζεται την πλοήγηση και την εμφάνιση στοιχείων ανάλογα με τον χρήστη.
 */

/**
 * 1. Διαχείριση Συνεδρίας (Session Management)
 * Ελέγχουμε αν έχει ξεκινήσει ήδη η συνεδρία για να αποφύγουμε σφάλματα (PHP Notices).
 * Η έναρξη του session είναι απαραίτητη για την πρόσβαση στη μεταβλητή $_SESSION.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="el">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Eco-Tracker | Σύστημα Καταγραφής Περιβαλλοντικών Δράσεων</title>
    
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="index.php" aria-label="Eco-Tracker - Αρχική">
                <img src="assets/img/logo.png" alt="Λογότυπο Eco-Tracker">
                <span>Eco-Tracker</span>
            </a>

            <nav class="site-nav" aria-label="Κύρια πλοήγηση">
                <a href="index.php">Αρχική</a>
                <a href="actions.php">Δράσεις</a>
                <?php 
                /**
                 * Έλεγχος Πρόσβασης: Ο σύνδεσμος "Υποβολή Δράσης" εμφανίζεται 
                 * αποκλειστικά σε χρήστες που έχουν πραγματοποιήσει επιτυχή είσοδο.
                 */
                if (isset($_SESSION['user_id'])): 
                ?>
                    <a href="submit.php">Υποβολή Δράσης</a>
                <?php endif; ?>
            </nav>

            <div class="header-actions">
                <?php 
                /**
                 * Δυναμική Εναλλαγή Εργαλείων Εισόδου/Εξόδου.
                 * Αν ο χρήστης είναι συνδεδεμένος, εμφανίζουμε το όνομά του και το Logout.
                 */
                if (isset($_SESSION['user_id'])): 
                ?>
                    <span style="color: var(--muted); font-size: 0.85rem; margin-right: 12px;">
                        Συνδεδεμένος: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </span>
                    <a class="btn" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-primary" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>