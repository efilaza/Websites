<?php
/**
 * login.php
 * Σελίδα αυθεντικοποίησης χρηστών για το Eco-Tracker.
 */

// Εισαγωγή της σύνδεσης με τη βάση δεδομένων
include('db_connect.php'); 
include('header.php'); // Εισαγωγή του δυναμικού header

// Αρχικοποίηση μεταβλητής για μηνύματα σφάλματος
$error_message = "";

/**
 * 1. Επεξεργασία Φόρμας Εισόδου
 * Εκτελείται μόνο όταν ο χρήστης πατήσει το κουμπί "Είσοδος".
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Καθαρισμός εισόδου (trim) για την αποφυγή τυχαίων κενών διαστημάτων
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        /**
         * 2. Ασφαλής Αναζήτηση Χρήστη
         * Χρησιμοποιούμε Prepared Statement για την αποτροπή SQL Injection.
         * Αναζητούμε μόνο το ID, το όνομα και το hash του κωδικού.
         */
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        /**
         * 3. Επαλήθευση Κωδικού (Hashing)
        * Η συνάρτηση password_verify συγκρίνει το input με το αποθηκευμένο hash.
         */
        if ($user && password_verify($password, $user['password_hash'])) {
            
            // Επιτυχής ταυτοποίηση: Έναρξη συνεδρίας και αποθήκευση στοιχείων
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Ανακατεύθυνση στην αρχική σελίδα μετά την είσοδο
            header("Location: index.php");
            exit();
        } else {
            // Γενικό μήνυμα σφάλματος
            $error_message = "Λανθασμένο όνομα χρήστη ή κωδικός πρόσβασης.";
        }
    } else {
        $error_message = "Παρακαλώ συμπληρώστε όλα τα πεδία.";
    }
}

?>

<main id="main" class="container">
    <div class="page-head">
        <div>
            <h1>Είσοδος χρήστη</h1>
            <p>Συνδέσου για να έχεις πρόσβαση στην υποβολή δράσεων.</p>
        </div>
    </div>

    <section class="card card-pad" style="max-width:560px; margin: 0 auto;">
        
        <?php if ($error_message): ?>
            <div style="background: rgba(255, 92, 122, 0.2); border: 1px solid var(--danger); padding: 10px; border-radius: 8px; margin-bottom: 15px; color: #ffebed;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'required'): ?>
            <div style="background: rgba(54, 211, 153, 0.2); border: 1px solid var(--brand); padding: 10px; border-radius: 8px; margin-bottom: 15px; color: #eafff8;">
                ⚠️ Πρέπει να συνδεθείτε για να εκτελέσετε αυτή την ενέργεια.
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="field">
                <label for="username">Όνομα Χρήστη</label>
                <input id="username" name="username" type="text" autocomplete="username" required />
            </div>

            <div class="field">
                <label for="password">Κωδικός Πρόσβασης</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required />
            </div>

            <button class="btn btn-primary" type="submit" style="width: 100%; margin-top: 10px;">Είσοδος</button>

            <p class="help" style="margin:15px 0 0; text-align: center;">
                Δεν έχεις λογαριασμό; <a href="register.php" style="text-decoration:underline; color: var(--brand);">Εγγράψου εδώ</a>.
            </p>
        </form>
    </section>
</main>

<?php 
// Εισαγωγή του υποσέλιδου
include('footer.php'); 
?>