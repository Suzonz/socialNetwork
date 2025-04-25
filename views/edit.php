<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Modifier le post</title>
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
    </header>

    <main>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color: red;"><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($post)): ?>
            <h2>Modifier le post</h2>
            <form method="POST">
                <label for="new_post_title">Titre</label>
                <input type="text" name="new_post_title" id="new_post_title" value="<?= htmlspecialchars($post->getTitle()) ?>" required>

                <label for="new_post_content">Contenu</label>
                <textarea name="new_post_content" id="new_post_content" required><?= htmlspecialchars($post->getContent()) ?></textarea>

                <input type="hidden" name="update" value="<?= $post->getId() ?>">
                <button type="submit">Confirmer</button>
            </form>
        <?php else: ?>
            <p>Le post n'a pas été trouvé ou vous n'êtes pas l'auteur.</p>
        <?php endif; ?>
    </main>
</body>

</html>
