<?php
/**
 * submit.php
 * Σελίδα καταχώρησης νέας περιβαλλοντικής δράσης.
 * Περιλαμβάνει διαχείριση αρχείων και γεωγραφικών συντεταγμένων.
 */
include('db_connect.php');
include('header.php');

/**
 * 1. Έλεγχος Πρόσβασης (Authentication)
 * Μόνο συνδεδεμένοι χρήστες επιτρέπεται να υποβάλλουν δράσεις.
 * Αν το session user_id λείπει, ανακατευθύνουμε στο login με μήνυμα ειδοποίησης.
 */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=required");
    exit();
}

$errors = [];
$success_msg = "";

/**
 * 2. Ανάκτηση Κατηγοριών
 * Χρησιμοποιείται για το δυναμικό γέμισμα του dropdown menu στη φόρμα.
 */
$stmt_cat = $pdo->query("SELECT * FROM categories");
$categories = $stmt_cat->fetchAll();

/**
 * 3. Επεξεργασία Φόρμας (POST Request)
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Λήψη και καθαρισμός δεδομένων κειμένου
    $title         = trim($_POST['title']);
    $subtitle      = trim($_POST['subtitle']);
    $category_id   = $_POST['category_id'];
    $description   = trim($_POST['description']);
    $location_name = trim($_POST['location_name']);
    $municipality  = trim($_POST['municipality']);
    
    // Χρήση null coalescing για προαιρετικά πεδία
    $lat           = !empty($_POST['lat']) ? $_POST['lat'] : null;
    $lng           = !empty($_POST['lng']) ? $_POST['lng'] : null;
    $start_date    = $_POST['start_date'];
    $end_date      = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $user_id       = $_SESSION['user_id'];

    // Βασική επικύρωση υποχρεωτικών πεδίων στο backend
    if (empty($title) || empty($category_id) || empty($start_date)) {
        $errors[] = "Τα πεδία Τίτλος, Κατηγορία και Ημερομηνία Έναρξης είναι υποχρεωτικά.";
    }

    /**
     * 4. Διαχείριση Μεταφόρτωσης Εικόνας (File Upload Security)
     */
    $image_name = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "assets/img/actions/";
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        
        // Δημιουργία μοναδικού ονόματος για την αποφυγή επικαλύψεων και επιθέσεων path traversal
        $image_name = time() . "_" . uniqid() . "." . $file_ext;
        $target_file = $target_dir . $image_name;

        // Έλεγχος επιτρεπόμενων τύπων αρχείων (Whitelisting)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed_types)) {
            $errors[] = "Μόνο αρχεία JPG, JPEG, PNG & GIF επιτρέπονται.";
        } else {
            // Μετακίνηση του αρχείου από τον προσωρινό φάκελο στον τελικό προορισμό
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $errors[] = "Αποτυχία μεταφόρτωσης εικόνας.";
            }
        }
    }

    /**
     * 5. Αποθήκευση στη Βάση Δεδομένων
     * Χρήση Prepared Statements για την απόλυτη προστασία από SQL Injection.
     */
    if (empty($errors)) {
        $sql = "INSERT INTO actions (user_id, category_id, title, subtitle, description, location_name, municipality, lat, lng, start_date, end_date, image_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([$user_id, $category_id, $title, $subtitle, $description, $location_name, $municipality, $lat, $lng, $start_date, $end_date, $image_name]);
            
            // Λήψη του ID της νέας εγγραφής για ανακατεύθυνση στη σελίδα προβολής
            $new_id = $pdo->lastInsertId();
            $success_msg = "Η δράση υποβλήθηκε επιτυχώς!";
            
            // Ανακατεύθυνση μετά από 2 δευτερόλεπτα για καλύτερο UX
            header("refresh:2;url=action.php?id=" . $new_id);
        } catch (PDOException $e) {
            // Σε περίπτωση σφάλματος στη βάση, καταγράφουμε το σφάλμα
            $errors[] = "Σφάλμα κατά την αποθήκευση στη βάση δεδομένων.";
        }
    }
}
?>

<main id="main" class="container">
    <div class="page-head">
        <h1>Υποβολή Νέας Δράσης</h1>
        <p>Συμπληρώστε τα στοιχεία για να καταχωρήσετε τη δράση σας.</p>
    </div>

    <section class="card card-pad" style="max-width: 800px; margin: 0 auto;">
        
        <?php if (!empty($errors)): ?>
            <div style="background: rgba(255, 92, 122, 0.2); border: 1px solid var(--danger); padding: 12px; border-radius: 8px; margin-bottom: 20px; color: #ffebed;">
                <ul style="margin:0; padding-left:20px;">
                    <?php foreach($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>"; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success_msg): ?>
            <div style="background: rgba(54, 211, 153, 0.2); border: 1px solid var(--brand); padding: 12px; border-radius: 8px; margin-bottom: 20px; color: #eafff8;">
                <?php echo $success_msg; ?> Ανακατεύθυνση στη δράση...
            </div>
        <?php endif; ?>

        <form action="submit.php" method="post" enctype="multipart/form-data">
            <div class="field">
                <label for="title">Τίτλος Δράσης *</label>
                <input type="text" id="title" name="title" required placeholder="π.χ. Καθαρισμός παραλίας">
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="category_id">Κατηγορία *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Επιλέξτε...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="subtitle">Σύντομη Περιγραφή</label>
                    <input type="text" id="subtitle" name="subtitle" placeholder=" π.χ. Εθελοντικός καθαρισμός της παραλίας Αλίμου">
                </div>
            </div>

            <div class="field">
                <label for="description">Αναλυτική Περιγραφή</label>
                <textarea id="description" name="description" rows="5" placeholder="Περιγράψτε λεπτομερώς τη δράση..."></textarea>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="location_name">Τοποθεσία</label>
                    <input type="text" id="location_name" name="location_name" placeholder="π.χ. Παραλία Αλίμου">
                </div>
                <div class="field">
                    <label for="municipality">Δήμος</label>
                    <input type="text" id="municipality" name="municipality" placeholder="π.χ. Αλίμου">
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="lat">Γεωγραφικό Πλάτος (Lat)</label>
                    <input type="number" step="any" id="lat" name="lat" placeholder="π.χ. 37.9838">
                </div>
                <div class="field">
                    <label for="lng">Γεωγραφικό Μήκος (Lng)</label>
                    <input type="number" step="any" id="lng" name="lng" placeholder="π.χ. 23.7275">
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="start_date">Έναρξη *</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="field">
                    <label for="end_date">Λήξη</label>
                    <input type="date" id="end_date" name="end_date">
                </div>
            </div>

            <div class="field">
                <label for="image">Εικόνα Δράσης</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Καταχώρηση Δράσης</button>
        </form>
    </section>
</main>

<?php include('footer.php'); ?>