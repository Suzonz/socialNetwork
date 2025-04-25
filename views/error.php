<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Page non trouvée</title>
</head>

<body>
<header>
        <?php if (!isset($_SESSION['pseudo'])): ?>
            <div class="barNav">
            <a class="titre" href="/"><h1>Catbook</h1></a>  
            <div class="main-buttons">
                    <form action="/register" method="POST">
                        <button>S'inscrire</button>
                    </form>
                    <form action="/login" method="POST">
                        <button>Se connecter</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="barNav">
            <a class="titre" href="/"><h1>Catbook</h1></a>  
            <a href="/logout">Se déconnecter</a>
            </div>
        <?php endif; ?>
    </header>  <div>
        <h3>Page non trouvée</h3>
    </div>
</body>

</html>