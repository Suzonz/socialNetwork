<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">

    <title>Inscription</title>
</head>

<body>

    <header>
        <div class="barNav">
        <a class="titre" href="/"><h1>Catbook</h1></a>  
        <div class="main-buttons">
                <form action="/login" method="POST">
                    <button>Se connecter</button>
                </form>
            </div>
        </div>
    </header>

    <h1>Inscription</h1>
    <form class="form" action="/register" method="POST">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo">
        <label for="password">Mot de passe</label>
        <input type="password" name="password">
        <label for="email">Email</label>
        <input type="email" name="email">
        <button>S'inscrire</button>
    </form>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li style="color: red;"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</body>

</html>