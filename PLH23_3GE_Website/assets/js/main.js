// Active nav highlight (βάσει filename)
(function setActiveNav(){
  const path = window.location.pathname;
  const page = path.substring(path.lastIndexOf('/') + 1) || 'index.html';

  document.querySelectorAll('nav.site-nav a').forEach(a => {
    const href = a.getAttribute('href');
    if (href === page) {
      a.classList.add('active');
      a.setAttribute('aria-current','page');
    }
  });
})();

// Accordion για actions.html με δυναμική εμφάνιση κουμπιού XML
(function accordion(){
    const buttons = document.querySelectorAll('[data-accordion="btn"]');
    
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const panelId = btn.getAttribute('aria-controls');
            const panel = document.getElementById(panelId);
            
            // Βρίσκουμε το κουμπί XML με βάση το ID της κατηγορίας
            const catId = panelId.split('-')[1];
            const btnXML = document.getElementById('xml-' + catId); 
            
            const isOpen = panel.classList.contains('open');

            // Κλείνουμε τα πάντα πριν ανοίξουμε το νέο
            document.querySelectorAll('.acc-panel').forEach(p => p.classList.remove('open'));
            document.querySelectorAll('.btnXML').forEach(xml => xml.classList.remove('open'));

            // Αν ήταν κλειστό, το ανοίγουμε
            if (!isOpen) {
                panel.classList.add('open');
                if(btnXML) btnXML.classList.add('open');
            }
        });
    });
})();