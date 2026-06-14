<?php
/**
 * setup_users.php
 * Script για τη μαζική εισαγωγή χρηστών με hashed passwords.
 */
include('db_connect.php');

// Λίστα χρηστών: [Username, Email, Plain_Password]
$users = [
    ['lazaridou_admin', 'admin@eco-tracker.gr', 'EcoTracker!2026'],
    ['green_tester', 'test@eco-tracker.gr', 'Security#First23'],
    ['nature_lover', 'nature@example.com', 'Planet@Save99!'],
    ['ocean_guard', 'ocean@example.com', 'CleanSeas_2025*'],
    ['forest_protector', 'forest@example.com', 'TreesRule#8888'],
    ['recycle_hero', 'recycle@example.com', 'ZeroWaste$1234'],
    ['eco_warrior', 'warrior@example.com', 'PureEarth%2026'],
    ['sustainability_pro', 'pro@example.com', 'BioFuture!#777']
];

try {
    // Χρήση Prepared Statement για ασφάλεια
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    
    echo "<h2>Εκτέλεση Εισαγωγής Χρηστών:</h2><ul>";
    
    foreach ($users as $u) {
        // Δημιουργία του Hash 
        $hashed = password_hash($u[2], PASSWORD_DEFAULT);
        
        $stmt->execute([$u[0], $u[1], $hashed]);
        echo "<li>✅ Ο χρήστης <strong>" . $u[0] . "</strong> δημιουργήθηκε επιτυχώς.</li>";
    }
    
    echo "</ul><p><strong>Η διαδικασία ολοκληρώθηκε!</strong></p>";
    echo "<p><a href='index.php'>Επιστροφή στην Αρχική</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Σφάλμα: " . $e->getMessage() . "</p>";
}
?>