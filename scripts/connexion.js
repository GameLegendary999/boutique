document.getElementById('connexionForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Empêche le rechargement de la page

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('php/traitement_connexion.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, password: password })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect; // Redirige vers dashboard.html
            } else {
                alert(data.message); // Affiche le message d'erreur
            }
        })
        .catch(error => console.error('Erreur lors de la connexion :', error));
});
