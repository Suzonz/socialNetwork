<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">

    <title>Connexion</title>
</head>

<body>
<header>
        <div class="barNav">
            <h1>Catbook</h1>
            <div class="main-buttons">
            <form action="/register" method="POST">
                        <button>S'inscrire</button>
                    </form>
            </div>
        </div>
    </header>
    <form class="form" action="/login" method="POST">
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo">
        <label for="password">Mot de passe</label>
        <input type="password" name="password">
        <button>Connexion</button>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color: red;"><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </form>
</body>

</html>