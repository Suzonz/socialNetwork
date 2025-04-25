<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Créer un post</title>
</head>

<body>
    <header>
        <?php if (!isset($_SESSION['pseudo'])): ?>
            <div class="barNav">
                <h1>Catbook</h1>
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
                <h1>Catbook</h1>
                <a href="/logout">Se déconnecter</a>
            </div>
            <h2 class="welcome">Bienvenue <?= $_SESSION['pseudo'] ?></h2>
        <?php endif; ?>
    </header>

    <main>
    <?php if (isset($_SESSION['pseudo'])): ?>
        <h2>Créer un post</h2>
        <form class="form" action="/" method="POST">
            <label for="new_post_title">Titre</label>
            <input type="text" name="new_post_title" id="new_post_title"
                value="<?= isset($_POST['new_post_title']) ? htmlspecialchars($_POST['new_post_title']) : '' ?>" required>

            <label for="new_post_content">Contenu</label>
            <textarea name="new_post_content" id="new_post_content" required><?= isset($_POST['new_post_content']) ? htmlspecialchars($_POST['new_post_content']) : '' ?></textarea>

            <button type="submit">Publier</button>
            <a href="/" class="btn-cancel">Annuler</a>
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
    <?php else: ?>
        <p style="color:red; text-align:center;">Vous devez être connecté pour créer un post.</p>
    <?php endif; ?>
</main>

</body>

</html>
