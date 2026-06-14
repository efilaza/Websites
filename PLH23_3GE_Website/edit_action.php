<?php
/**
 * edit_action.php
 * Οπτικός το ίδιο με το  submit.php για την τροποποίηση δράσεων.
 */
include('db_connect.php');
include('header.php');

// 2. Έλεγχος Πρόσβασης
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=required");
    exit();
}

$errors = [];
$success_msg = "";
$user_id = $_SESSION['user_id'];

// 3. Ανάκτηση της Δράσης και των Κατηγοριών
if (!isset($_GET['id'])) {
    die("Σφάλμα: Δεν ορίστηκε ID δράσης.");
}

$action_id = (int)$_GET['id'];

// Ανάκτηση δράσης
$stmt = $pdo->prepare("SELECT * FROM actions WHERE id = ?");
$stmt->execute([$action_id]);
$action = $stmt->fetch();

// Έλεγχος Ιδιοκτησίας και Υπάρξης
if (!$action || $action['user_id'] != $user_id) {
    die("<main class='container'><div class='card card-pad' style='color:var(--danger)'>Σφάλμα Ασφαλείας: Δεν έχετε δικαίωμα επεξεργασίας αυτής της δράσης.</div></main>");
}

// Ανάκτηση κατηγοριών για το dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// 4. Επεξεργασία Φόρμας (UPDATE Logic)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title         = trim($_POST['title']);
    $subtitle      = trim($_POST['subtitle']);
    $category_id   = $_POST['category_id'];
    $description   = trim($_POST['description']);
    $location_name = trim($_POST['location_name']);
    $municipality  = trim($_POST['municipality']);
    $lat           = !empty($_POST['lat']) ? $_POST['lat'] : null;
    $lng           = !empty($_POST['lng']) ? $_POST['lng'] : null;
    $start_date    = $_POST['start_date'];
    $end_date      = !empty($_POST['end_date']) ? $_POST['end_date'] : null;

    if (empty($title) || empty($category_id) || empty($start_date)) {
        $errors[] = "Τα πεδία Τίτλος, Κατηγορία και Ημερομηνία Έναρξης είναι υποχρεωτικά.";
    }

    // Διαχείριση Εικόνας (Ανέβασμα νέας ή διατήρηση παλιάς)
    $image_name = $action['image_path']; // Default: η παλιά εικόνα
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "assets/img/actions/";
        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $image_name = time() . "_" . uniqid() . "." . $file_ext;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
            // Προαιρετικά: διαγραφή της παλιάς εικόνας από τον φάκελο εδώ
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE actions SET 
                category_id = ?, title = ?, subtitle = ?, description = ?, 
                location_name = ?, municipality = ?, lat = ?, lng = ?, 
                start_date = ?, end_date = ?, image_path = ? 
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute([
                $category_id, $title, $subtitle, $description, 
                $location_name, $municipality, $lat, $lng, 
                $start_date, $end_date, $image_name, $action_id
            ]);
            $success_msg = "Η δράση ενημερώθηκε επιτυχώς!";
            header("refresh:1;url=action.php?id=" . $action_id);
        } catch (PDOException $e) {
            $errors[] = "Σφάλμα κατά την ενημέρωση στη βάση.";
        }
    }
}
?>

<main id="main" class="container">
    <div class="page-head">
        <h1>Επεξεργασία Δράσης</h1>
        <p>Τροποποιήστε τα στοιχεία της καταχωρημένης δράσης σας.</p>
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
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <form action="edit_action.php?id=<?php echo $action_id; ?>" method="post" enctype="multipart/form-data">
            <div class="field">
                <label for="title">Τίτλος Δράσης *</label>
                <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($action['title']); ?>">
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="category_id">Κατηγορία *</label>
                    <select id="category_id" name="category_id" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $action['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label for="subtitle">Σύντομη Περιγραφή</label>
                    <input type="text" id="subtitle" name="subtitle" value="<?php echo htmlspecialchars($action['subtitle']); ?>">
                </div>
            </div>

            <div class="field">
                <label for="description">Αναλυτική Περιγραφή</label>
                <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($action['description']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="location_name">Τοποθεσία</label>
                    <input type="text" id="location_name" name="location_name" value="<?php echo htmlspecialchars($action['location_name']); ?>">
                </div>
                <div class="field">
                    <label for="municipality">Δήμος</label>
                    <input type="text" id="municipality" name="municipality" value="<?php echo htmlspecialchars($action['municipality']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="lat">Γεωγραφικό Πλάτος (Lat)</label>
                    <input type="number" step="any" id="lat" name="lat" value="<?php echo $action['lat']; ?>">
                </div>
                <div class="field">
                    <label for="lng">Γεωγραφικό Μήκος (Lng)</label>
                    <input type="number" step="any" id="lng" name="lng" value="<?php echo $action['lng']; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="field">
                    <label for="start_date">Έναρξη *</label>
                    <input type="date" id="start_date" name="start_date" required value="<?php echo $action['start_date']; ?>">
                </div>
                <div class="field">
                    <label for="end_date">Λήξη</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo $action['end_date']; ?>">
                </div>
            </div>

            <div class="field">
                <label for="image">Εικόνα Δράσης (Επιλέξτε μόνο αν θέλετε αλλαγή)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if($action['image_path']): ?>
                    <p style="font-size: 0.8em; margin-top: 5px; color: var(--muted);">Τρέχουσα: <?php echo $action['image_path']; ?></p>
                <?php endif; ?>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary" style="flex: 2;">Καταχώρηση Αλλαγών</button>
                <a href="action.php?id=<?php echo $action_id; ?>" class="btn" style="flex: 1; text-align: center; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; text-decoration: none; color: white; border-radius: 8px;">Ακύρωση</a>
            </div>
        </form>
    </section>
</main>

<?php include('footer.php'); ?>