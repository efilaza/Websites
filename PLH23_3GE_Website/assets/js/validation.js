/**
 * validation.js - Expert Edition
 * Real-time validation για username (AJAX), email, και πολυπλοκότητα κωδικού.
 * Σχεδιασμένο για την ΠΛΗ23 - Eco-Tracker Project.
 */

document.addEventListener('DOMContentLoaded', function() {
    // --- 1. ΑΡΧΙΚΟΠΟΙΗΣΗ ΣΤΟΙΧΕΙΩΝ ---
    const usernameInput = document.getElementById('username');
    const usernameError = document.getElementById('username-error');
    const emailInput    = document.getElementById('email');
    const emailError    = document.getElementById('email-error');
    const passInput     = document.getElementById('password');
    const pass2Input    = document.getElementById('password2');
    const matchMsg      = document.getElementById('match-message');

    // Αντιστοίχιση των στοιχείων UI για τις απαιτήσεις του κωδικού
    const reqs = {
        length: document.getElementById('req-length'),
        upper:  document.getElementById('req-upper'),
        lower:  document.getElementById('req-lower'),
        number: document.getElementById('req-number'),
        symbol: document.getElementById('req-symbol')
    };

    // --- 2. ASYNCHRONOUS USERNAME CHECK (AJAX) ---
    let typingTimer; // Μεταβλητή για το debounce
    const doneTypingInterval = 450; // Καθυστέρηση σε ms

    if (usernameInput) {
        usernameInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            const username = this.value.trim();

            if (username.length >= 3) {
                typingTimer = setTimeout(() => {
                    // Δημιουργία POST αιτήματος για έλεγχο στη βάση
                    const formData = new FormData();
                    formData.append('username', username);

                    fetch('check_username.php', {
                        method: 'POST',
                        body: formData
                    })
                    
                    .then(response => response.text())
                    .then(result => {
                        usernameError.style.display = "block";
                        if (result === "exists") {
                            usernameError.textContent = "❌ Το όνομα χρήστη χρησιμοποιείται ήδη.";
                            usernameError.style.color = "var(--danger)";
                            usernameInput.style.borderColor = "var(--danger)";
                        } else {
                            usernameError.textContent = "✅ Το όνομα είναι διαθέσιμο!";
                            usernameError.style.color = "var(--brand)";
                            usernameInput.style.borderColor = "var(--brand)";
                        }
                    })
                    .catch(err => console.error("AJAX Error:", err));
                }, doneTypingInterval);
            } else {
                usernameError.style.display = "none";
            }
        });
    }

    // --- 3. ΕΛΕΓΧΟΣ EMAIL (Προδιαγραφή β) ---
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            const emailVal = this.value.trim();
            if (emailVal.length > 0) {
                // Η εκφώνηση απαιτεί τουλάχιστον την ύπαρξη του @
                if (!emailVal.includes('@')) {
                    emailError.textContent = "❌ Το email πρέπει να περιέχει το σύμβολο @.";
                    emailError.style.color = "var(--danger)";
                    emailError.style.display = "block";
                    this.style.borderColor = "var(--danger)";
                } else {
                    emailError.textContent = "✅ Έγκυρη μορφή email.";
                    emailError.style.color = "var(--brand)";
                    emailError.style.display = "block";
                    this.style.borderColor = "var(--brand)";
                }
            } else {
                emailError.style.display = "none";
                this.style.borderColor = "var(--border)";
            }
        });
    }

    // --- 4. ΕΛΕΓΧΟΣ ΠΟΛΥΠΛΟΚΟΤΗΤΑΣ ΚΩΔΙΚΟΥ (Προδιαγραφή γ) ---
    if (passInput) {
        passInput.addEventListener('input', function() {
            const val = this.value;

            // Έλεγχοι με Regular Expressions (Regex)
            const checks = {
                length: val.length >= 12,           // Τουλάχιστον 12 χαρακτήρες
                upper:  /[A-Z]/.test(val),          // Τουλάχιστον ένα κεφαλαίο
                lower:  /[a-z]/.test(val),          // Τουλάχιστον ένα πεζό
                number: /\d/.test(val),             // Τουλάχιστον ένας αριθμός
                symbol: /[\W_]/.test(val)           // Τουλάχιστον ένα σύμβολο
            };

            // Οπτική ενημέρωση της λίστας απαιτήσεων
            for (const [key, passed] of Object.entries(checks)) {
                if (reqs[key]) {
                    if (passed) {
                        reqs[key].style.color = "var(--brand)";
                        reqs[key].style.textDecoration = "line-through";
                    } else {
                        reqs[key].style.color = "var(--muted)";
                        reqs[key].style.textDecoration = "none";
                    }
                }
            }
            checkMatch(); // Επικαιροποίηση και του ελέγχου ταύτισης
        });
    }

    // --- 5. ΕΛΕΓΧΟΣ ΤΑΥΤΟΠΟΙΗΣΗΣ ΚΩΔΙΚΩΝ (Προδιαγραφή δ) ---
    function checkMatch() {
        if (pass2Input && pass2Input.value.length > 0) {
            matchMsg.style.display = "block";
            if (passInput.value === pass2Input.value) {
                matchMsg.textContent = "✅ Οι κωδικοί ταυτίζονται.";
                matchMsg.style.color = "var(--brand)";
                pass2Input.style.borderColor = "var(--brand)";
            } else {
                matchMsg.textContent = "❌ Οι κωδικοί δεν ταυτίζονται.";
                matchMsg.style.color = "var(--danger)";
                pass2Input.style.borderColor = "var(--danger)";
            }
        } else if (matchMsg) {
            matchMsg.style.display = "none";
            pass2Input.style.borderColor = "var(--border)";
        }
    }

    if (pass2Input) {
        pass2Input.addEventListener('input', checkMatch);
    }
});

/**
 * validateRegisterForm
 * Τελικός έλεγχος πριν την υποβολή της φόρμας στον Server.
 */
function validateRegisterForm() {
    const email = document.getElementById('email').value.trim();
    const pass = document.getElementById('password').value;
    const pass2 = document.getElementById('password2').value;
    
    // Regex που συνδυάζει όλες τις απαιτήσεις της εκφώνησης (12+ χαρ, κεφ, πεζό, αρ, σύμβολο)
    const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/;

    // Τελικός έλεγχος email
    if (email.length > 0 && !email.includes('@')) {
        alert("Σφάλμα: Παρακαλώ εισάγετε ένα έγκυρο email με το σύμβολο @.");
        return false;
    }

    // Τελικός έλεγχος κωδικού
    if (!passRegex.test(pass)) {
        alert("Σφάλμα: Ο κωδικός δεν πληροί τις προδιαγραφές ασφαλείας.");
        return false;
    }

    // Τελικός έλεγχος ταύτισης
    if (pass !== pass2) {
        alert("Σφάλμα: Η επιβεβαίωση κωδικού δεν ταιριάζει.");
        return false;
    }

    return true; // Όλα καλά, η φόρμα μπορεί να υποβληθεί
}