<?php
/**
 * actions.php
 * Προβολή του καταλόγου περιβαλλοντικών δράσεων ομαδοποιημένων ανά κατηγορία.
 */

// Εισαγωγή ρυθμίσεων βάσης και κοινής κεφαλίδας
include('db_connect.php'); 
include('header.php');     

/**
 * 1. Ανάκτηση Κατηγοριών
 * Φέρνουμε όλες τις διαθέσιμες κατηγορίες για να χτίσουμε τη δομή του accordion.
 */
$stmt_cat = $pdo->query("SELECT * FROM categories");
$categories = $stmt_cat->fetchAll();
?>

<main id="main" class="container">
    <div class="page-head">
        <div>
            <h1>Περιβαλλοντικές Δράσεις</h1>
            <p>Εξερευνήστε τις κατηγορίες και ανακαλύψτε πώς μπορείτε να βοηθήσετε τον πλανήτη.</p>
        </div>
        <?php 
        /**
         * Έλεγχος Πρόσβασης: Το κουμπί υποβολής εμφανίζεται μόνο 
         * αν ο χρήστης έχει ενεργό session (είναι συνδεδεμένος).
         */
        if(isset($_SESSION['user_id'])): 
        ?>
            <a class="btn btn-primary" href="submit.php">Υποβολή Νέας Δράσης</a>
        <?php endif; ?>
    </div>

    <section class="accordion" aria-label="Κατηγορίες δράσεων">
        <?php foreach ($categories as $cat): ?>
            <button class="acc-btn" type="button" 
                    data-accordion="btn" 
                    aria-controls="panel-<?php echo $cat['id']; ?>">
                
               
                 <?php echo htmlspecialchars($cat['name']); ?> 


                <!-- Κουμπί εξαγωγής XML για κάθε κατηγορία, το οποίο θα δημιουργεί ένα αρχείο XML με όλες τις δράσεις της κατηγορίας -->
                 
                 <a class="btnXML stop-propagation" id="xml-<?php echo $cat['id'];?>" href="export_xml.php?category_id=<?php echo $cat["id"]; ?>">Εξαγωγή XML για την κατηγορία </a>
                 

                <?php 
                    /**
                     * Δυναμική Μέτρηση: Υπολογίζουμε το πλήθος των δράσεων 
                     * που ανήκουν στην τρέχουσα κατηγορία σε πραγματικό χρόνο.
                     */
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM actions WHERE category_id = ?");
                    $stmt_count->execute([$cat['id']]);
                    $count = $stmt_count->fetchColumn();
                ?>
                <span><?php echo $count; ?> δράσεις</span>
            </button>

            <div class="acc-panel" id="panel-<?php echo $cat['id']; ?>">
                <?php
                /**
                 * Ανάκτηση Δράσεων Κατηγορίας
                 * Χρησιμοποιούμε Prepared Statement για προστασία από SQL Injection,
                 * παρόλο που το $cat['id'] προέρχεται από τη δική μας βάση.
                 */
                $stmt_act = $pdo->prepare("SELECT * FROM actions WHERE category_id = ?");
                $stmt_act->execute([$cat['id']]);
                $actions = $stmt_act->fetchAll();

                if ($count > 0):
                    foreach ($actions as $action):
                ?>
                    <article class="action-card">
                        <?php 
                        // Έλεγχος ύπαρξης εικόνας και ορισμός fallback αν λείπει
                        $img_path = !empty($action['image_path']) ? 'assets/img/actions/' . $action['image_path'] : 'assets/img/actions/default.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($img_path); ?>" alt="<?php echo htmlspecialchars($action['title']); ?>">
                        
                        <div>
                            <h2><?php echo htmlspecialchars($action['title']); ?></h2>
                            <p><?php echo htmlspecialchars($action['subtitle']); ?></p>
                            
                            <div class="meta">
                                <span>📍 <?php echo htmlspecialchars($action['municipality']); ?></span>
                                <span>🗓️ <?php echo date("d/m/Y", strtotime($action['start_date'])); ?></span>
                            </div>
                            
                            <div class="actions">
                                <a class="btn btn-primary" href="action.php?id=<?php echo $action['id']; ?>">Περισσότερα</a>
                            </div>
                        </div>
                    </article>
                <?php 
                    endforeach; 
                else: 
                ?>
                    <p class="help">Δεν υπάρχουν καταχωρημένες δράσεις σε αυτή την κατηγορία ακόμη.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<?php 
// Εισαγωγή του κοινού υποσέλιδου
include('footer.php'); 
?>