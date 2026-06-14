<?php
/**
 * index.php
 * Η κεντρική σελίδα (Landing Page) του Eco-Tracker.
 * Δημιουργεί την πρώτη επαφή με τον χρήστη 
 */

// Εισαγωγή των απαραίτητων αρχείων για τη σύνδεση με τη βάση και το layout
include('db_connect.php'); 
include('header.php');     
?>

<main id="main" class="container">
    <section class="hero" aria-label="Καλωσόρισμα">
        <div class="hero-inner">
            
            <div class="hero-content">
                <h1>Καλωσήρθες στο Eco-Tracker!</h1>
                <p>Κάνε το επόμενο βήμα για τον πλανήτη. Ενημερώσου, κατάγραψε, οργάνωσε και μοιράσου περιβαλλοντικές δράσεις με όλο τον κόσμο.</p>

                <div class="hero-actions">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a class="btn btn-primary" href="login.php">Είσοδος</a>
                        <a class="btn" href="register.php">Εγγραφή</a>
                    <?php else: ?>
                        <a class="btn btn-primary" href="actions.php">Δείτε τις Δράσεις</a>
                        <a class="btn" href="submit.php">Νέα Υποβολή</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="action-marquee">
                <div class="marquee">
                    <div class="marquee-track">
                        <div class="marquee-item"><img src="assets/img/actions/cleanup.jpg" alt="Καθαρισμός παραλίας"></div>
                        <div class="marquee-item"><img src="assets/img/actions/treeplanting.jpg" alt="Αναδάσωση"></div>
                        <div class="marquee-item"><img src="assets/img/actions/recycle.jpg" alt="Ανακύκλωση"></div>
                        <div class="marquee-item"><img src="assets/img/actions/river.jpg" alt="Καθαρισμός πάρκου"></div>
                        <div class="marquee-item"><img src="assets/img/actions/volunteers.jpg" alt="Εθελοντισμός"></div>
                        <div class="marquee-item"><img src="assets/img/actions/cleanup.jpg" alt="Καθαρισμός παραλίας"></div>
                        <div class="marquee-item"><img src="assets/img/actions/treeplanting.jpg" alt="Αναδάσωση"></div>
                        <div class="marquee-item"><img src="assets/img/actions/recycle.jpg" alt="Ανακύκλωση"></div>
                        <div class="marquee-item"><img src="assets/img/actions/river.jpg" alt="Καθαρισμός πάρκου"></div>
                        <div class="marquee-item"><img src="assets/img/actions/volunteers.jpg" alt="Εθελοντισμός"></div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<?php 
/**
 * Εισαγωγή του υποσέλιδου, το οποίο περιλαμβάνει και το κλείσιμο του <body> και <html>.
 */
include('footer.php'); 
?>