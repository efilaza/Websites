<?php
/**
 * action.php
 * Σελίδα προβολής λεπτομερειών για μια συγκεκριμένη περιβαλλοντική δράση.
 */

// Εισαγωγή απαραίτητων αρχείων για τη σύνδεση και το περιβάλλον εργασίας
include('db_connect.php'); 
include('header.php');     

/**
 * 1. Λήψη και φιλτράρισμα του ID
 * Μετατρέπουμε την τιμή σε ακέραιο (int) για να αποτρέψουμε επιθέσεις τύπου SQL Injection.
 * Αν δεν υπάρχει το id στο URL, ορίζεται ως 0.
 */
$action_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = null;

if ($action_id > 0) {
    /**
     * 2. Ανάκτηση δεδομένων από τη Βάση
     * Χρησιμοποιούμε JOIN για να φέρουμε το όνομα της κατηγορίας από τον πίνακα 'categories'
     * με βάση το category_id που είναι αποθηκευμένο στον πίνακα 'actions'.
     */
    $sql = "SELECT a.*, c.name AS category_name 
            FROM actions a 
            JOIN categories c ON a.category_id = c.id 
            WHERE a.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$action_id]);
    $action = $stmt->fetch();
}

/**
 * 3. Διαχείριση Σφάλματος
 * Αν η μεταβλητή $action είναι κενή, σημαίνει ότι το ID δεν αντιστοιχεί σε καμία δράση.
 * Εμφανίζουμε μήνυμα σφάλματος και διακόπτουμε την εκτέλεση του υπόλοιπου κώδικα.
 */
if (!$action): ?>
    <main class="container">
        <div class="card card-pad" style="margin: 50px auto; max-width: 600px; text-align: center; border: 1px solid var(--danger);">
            <h2 style="color: var(--danger);">⚠️ Η δράση δεν βρέθηκε</h2>
            <p>Το ID που αναζητάτε (<?php echo $action_id; ?>) δεν αντιστοιχεί σε κάποια ενεργή περιβαλλοντική δράση.</p>
            <div style="margin-top: 20px;">
                <a href="actions.php" class="btn">Επιστροφή στις Δράσεις</a>
            </div>
        </div>
    </main>
<?php 
    include('footer.php');
    exit(); 
endif; 

/**
 * 4. Έλεγχος Ιδιοκτησίας (Authorization)
 * Ελέγχουμε αν ο συνδεδεμένος χρήστης είναι αυτός που δημιούργησε τη δράση,
 * ώστε να του εμφανίσουμε τα κουμπιά επεξεργασίας και διαγραφής.
 */
$is_owner = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $action['user_id']);
?>

<main id="main" class="container">
    <div class="page-head">
        <div>
            <h1><?php echo htmlspecialchars($action['title']); ?></h1>
            <p style="color: var(--brand); font-weight: bold; margin-top: 5px;">
                🌿 Κατηγορία: <?php echo htmlspecialchars($action['category_name']); ?>
            </p>
        </div>
        <a class="btn" href="actions.php">← Επιστροφή</a>
        
    </div>

    <?php if ($is_owner): ?>
        <div class="owner-panel" style="background: rgba(54, 211, 153, 0.08); padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px dashed var(--brand); display: flex; align-items: center; gap: 15px;">
            <span>🛠️ <strong>Εργαλεία Διαχειριστή:</strong></span>
            <a href="edit_action.php?id=<?php echo $action['id']; ?>" class="btn btn-primary" style="font-size: 0.85rem;">Επεξεργασία</a>
            <a href="delete_action.php?id=<?php echo $action['id']; ?>" 
               class="btn" 
               style="font-size: 0.85rem; background: rgba(255, 92, 122, 0.15); color: var(--danger); border-color: var(--danger);"
               onclick="return confirm('Προσοχή! Η διαγραφή είναι οριστική. Θέλετε να συνεχίσετε;');">
               Διαγραφή Δράσης
            </a>
        </div>
    <?php endif; ?>

       

    <section class="action-details-grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
        
        <aside>
            <div class="card" style="overflow: hidden; border-radius: 16px;">
                <?php 
                    // Ορισμός διαδρομής εικόνας. Αν δεν υπάρχει, χρησιμοποιείται η προκαθορισμένη (fallback).
                    $img_src = !empty($action['image_path']) ? 'assets/img/actions/' . $action['image_path'] : 'assets/img/actions/default.jpg';
                ?>
                <img src="<?php echo htmlspecialchars($img_src); ?>" 
                     alt="<?php echo htmlspecialchars($action['title']); ?>" 
                     style="width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block;">
            </div>
            
            <div class="card card-pad" style="margin-top: 20px; font-size: 0.95rem; border-left: 4px solid var(--brand);">
                <p style="margin-bottom: 15px;">
                    📍 <strong>Τοποθεσία:</strong><br>
                    <?php echo htmlspecialchars($action['location_name']); ?><br>
                    <small style="color: var(--muted);"><?php echo htmlspecialchars($action['municipality']); ?></small>
                </p>
                <p style="margin-bottom: 15px;">
                    📅 <strong>Πρόγραμμα:</strong><br>
                    Από: <?php echo date("d/m/Y", strtotime($action['start_date'])); ?> 
                    <?php if($action['end_date']): ?>
                        <br>Έως: <?php echo date("d/m/Y", strtotime($action['end_date'])); ?>
                    <?php endif; ?>
                </p>
                <?php if($action['lat'] && $action['lng']): ?>
                    <p>
                        🌐 <strong>Συντεταγμένες GPS:</strong><br>
                        <code style="background: rgba(0,0,0,0.2); padding: 2px 5px; border-radius: 4px;">
                            <?php echo number_format($action['lat'], 6); ?>, <?php echo number_format($action['lng'], 6); ?>
                        </code>
                    </p>
                <?php endif; ?>
            </div>
        </aside>

        <article class="card card-pad">
            <header style="margin-bottom: 25px;">
                <h3 style="color: var(--brand); margin-bottom: 10px;">Σύνοψη</h3>
                <p style="font-size: 1.1rem; font-style: italic; line-height: 1.4;">
                    "<?php echo htmlspecialchars($action['subtitle']); ?>"
                </p>
            </header>
            
            <hr style="border: 0; border-top: 1px solid var(--border); margin: 25px 0;">
            
            <section>
                <h3 style="margin-bottom: 15px;">Αναλυτικές Πληροφορίες</h3>
                <div style="line-height: 1.7; color: rgba(255,255,255,0.9);">
                    <?php echo nl2br(htmlspecialchars($action['description'])); ?>
                </div>
            </section>

            <footer style="margin-top: 40px; padding-top: 20px; border-top: 1px solid var(--border); font-size: 0.8rem; color: var(--muted); display: flex; justify-content: space-between;">
                <span>ID Δράσης: #<?php echo $action['id']; ?></span>
                <span>Δημιουργήθηκε: <?php echo date("d/m/Y H:i", strtotime($action['created_at'])); ?></span>
            </footer>
        </article>
    </section>
</main>

<?php include('footer.php'); ?>