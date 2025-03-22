document.getElementById('inscriptionForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Empêche le rechargement de la page

    const nom = document.getElementById('nom').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('php/inscription.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nom: nom, email: email, password: password })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Inscription réussie ! Vous pouvez maintenant vous connecter.');
                window.location.href = 'connexion.html'; // Redirige vers la page de connexion
            } else {
                alert(data.message); // Affiche le message d'erreur
            }
        })
        .catch(error => console.error('Erreur lors de l\'inscription :', error));
});
