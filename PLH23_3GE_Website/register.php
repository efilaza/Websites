<?php
/**
 * register.php
 * Σελίδα εγγραφής νέου χρήστη.
 * Ενσωματώνει διπλό έλεγχο (Backend & Frontend) για μέγιστη ακεραιότητα δεδομένων.
 */
include('db_connect.php'); // Σύνδεση με τη βάση δεδομένων
include('header.php');     // Εισαγωγή του δυναμικού header

// Αρχικοποίηση μεταβλητών για τη διαχείριση καταστάσεων
$errors = [];
$success_msg = "";

/**
 * 1. Επεξεργασία Αιτήματος POST
 * Η λογική εκτελείται μόνο όταν η φόρμα υποβληθεί στον server.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Καθαρισμός και λήψη δεδομένων
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];

    /**
     * 2. PHP BACKEND VALIDATION 
     */
    
    // Έλεγχος Μοναδικότητας: Διασφαλίζουμε ότι το username δεν υπάρχει ήδη στη βάση.
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $errors[] = "Το όνομα χρήστη χρησιμοποιείται ήδη. Παρακαλώ επιλέξτε άλλο.";
    }

    // Έλεγχος Email: Χρήση του filter_var για την εγκυρότητα της διεύθυνσης.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Παρακαλώ εισάγετε μια έγκυρη διεύθυνση email.";
    }

    // Έλεγχος Πολυπλοκότητας Κωδικού: Επιβολή ισχυρού password μέσω Regex.
    // Απαιτήσεις: 12+ χαρακτήρες, Κεφαλαίο, Πεζό, Αριθμός, Σύμβολο.
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/';
    if (!preg_match($pattern, $pass)) {
        $errors[] = "Ο κωδικός δεν πληροί τις ελάχιστες απαιτήσεις ασφαλείας.";
    }

    // Έλεγχος Ταυτοποίησης: Επιβεβαίωση ότι οι δύο κωδικοί συμπίπτουν.
    if ($pass !== $pass2) {
        $errors[] = "Η επιβεβαίωση κωδικού δεν ταιριάζει με τον κωδικό πρόσβασης.";
    }

    /**
     * 3. Αποθήκευση Δεδομένων
     * Αν ο πίνακας $errors είναι κενός, προχωράμε στην εγγραφή.
     */
    if (empty($errors)) {
        // Κρυπτογράφηση κωδικού (Hashing) με τον αλγόριθμο BCRYPT.
        // ΠΟΤΕ δεν αποθηκεύουμε κωδικούς σε plain text.
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$username, $email, $hashed_password]);
            $success_msg = "Η εγγραφή σας ολοκληρώθηκε με επιτυχία!";
        } catch (PDOException $e) {
            // Καταγραφή σφάλματος (logging) και εμφάνιση γενικού μηνύματος στον χρήστη.
            $errors[] = "Παρουσιάστηκε σφάλμα κατά την εγγραφή. Δοκιμάστε ξανά.";
        }
    }
}
?>

<main id="main" class="container">
    <div class="page-head">
        <h1>Εγγραφή νέου χρήστη</h1>
        <p>Δημιουργήστε έναν λογαριασμό για να ξεκινήσετε την υποβολή περιβαλλοντικών δράσεων.</p>
    </div>

    <section class="card card-pad" style="max-width:720px; margin: 0 auto;">
        
        <?php if (!empty($errors)): ?>
            <div style="background: rgba(255, 92, 122, 0.2); border: 1px solid var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px; color: #ffebed;">
                    <?php foreach($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success_msg): ?>
            <div style="background: rgba(54, 211, 153, 0.2); border: 1px solid var(--brand); padding: 12px; border-radius: 8px; margin-bottom: 20px; color: #eafff8;">
                <?php echo $success_msg; ?> <a href="login.php" style="font-weight: bold; color: #fff; text-decoration: underline;">Συνδεθείτε εδώ</a>.
            </div>
        <?php endif; ?>

        <form id="registerForm" action="register.php" method="post" onsubmit="return validateRegisterForm()">
            <div class="field">
                <label for="username">Όνομα χρήστη</label>
                <input id="username" name="username" type="text" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required />
                <span id="username-error" style="font-size: 0.8rem; margin-top: 5px; display: none;"></span>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required />
                <span id="email-error" style="font-size: 0.8rem; margin-top: 5px; display: none;"></span>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="password">Κωδικός Πρόσβασης </label>
                    <input type="password" id="password" name="password" required>

                    <ul id="password-requirements" style="font-size: 0.8rem; margin: 8px 0; padding-left: 20px; color: var(--muted);">
                        <li id="req-length">12+ χαρακτήρες</li>
                        <li id="req-upper">Τουλάχιστον 1 κεφαλαίο</li>
                        <li id="req-lower">Τουλάχιστον 1 πεζό</li>
                        <li id="req-number">Τουλάχιστον 1 αριθμό</li>
                        <li id="req-symbol">Τουλάχιστον 1 σύμβολο</li>
                    </ul>
                </div>

                <div class="field">
                    <label for="password2">Επιβεβαίωση Κωδικού *</label>
                    <input type="password" id="password2" name="password2" required>
                    <span id="match-message" style="font-size: 0.8rem; margin-top: 5px; display: none;"></span>
                </div>
            </div>

            
            <button class="btn btn-primary" type="submit" style="width: 100%;">Δημιουργία Λογαριασμού</button>

            <p class="help" style="margin-top: 20px; text-align: center;">
                Έχετε ήδη λογαριασμό; <a href="login.php" style="color: var(--brand); text-decoration: underline;">Είσοδος Χρήστη</a>
            </p>
        </form>
    </section>
</main>

<script src="assets/js/validation.js"></script>

<?php 
include('footer.php'); // Εισαγωγή του υποσέλιδου
?>