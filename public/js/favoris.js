console.log("test");

function activeFav() {
    const favoriteButtons = document.querySelectorAll('.fav');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Évite les comportements par défaut si nécessaire
            console.log(button.dataset.service);

            fetch(urlfavoris+"?id="+button.dataset.service)   
            .then(response => response.json()) 
            .then(data => console.log("Réponse du serveur:", data))
            .catch(error => console.error("Erreur lors de la requête:", error));
            
        });
    });

    console.dir(favoriteButtons);
}