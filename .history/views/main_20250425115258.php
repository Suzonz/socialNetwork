<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Accueil</title>
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
    </header>
    <main>
        <h2>Liste des posts</h2>
        <?php if (!empty($posts)): ?>
            <?php
                $lastDate = null;
                foreach ($posts as $post):
                    // Extraire la date du jour (sans l'heure)
                    $currentDate = date('Y-m-d', strtotime($post['created_at']));
                    // Si la date change, on l'affiche
                    if ($currentDate !== $lastDate):
                        $lastDate = $currentDate;
            ?>
                    <h2 class="date"><?= date('d F Y', strtotime($post['created_at'])) ?></h2>
                <?php endif; ?>

                <div class="posts">
                    <?php if ($post['author'] === $_SESSION['pseudo']): ?>
                        <?php if (!(isset($_POST["editView"]) && $_POST["editView"] == $post['id'])) : ?>
                            <p> <strong><?= htmlspecialchars($post['author']) ?></strong> <?= date('H:i', strtotime($post['created_at'])) ?></p>
                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                            <form method="POST">
                                <button name="editView" value="<?= $post['id'] ?>" class="edit-btn">
                                    <img src="/assets/images/pencil.svg" alt="modifier" class="edit-icon">
                                </button>
                            </form>
                        <?php else : ?>
                            <form method="POST">
                                <input type="text" name="new_post_title" id="new_post_title_<?= $post['id'] ?>" value="<?= htmlspecialchars($post['title']) ?>">
                                <textarea name="new_post_content" id="new_post_title_<?= $post['id'] ?>"><?= htmlspecialchars($post['content']) ?></textarea>
                                <button name="update" value="<?= $post['id'] ?>">Confirmer</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST">
                            <button name="delete" value="<?= $post['id'] ?>">
                                <img src="/assets/images/bin.svg" alt="supprimer">
                            </button>
                        </form>
                    <?php else : ?>
                        <p> <strong><?= htmlspecialchars($post['author']) ?></strong> <?= date('H:i', strtotime($post['created_at'])) ?></p>
                        <h3><?= htmlspecialchars($post['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
        <?php endif; ?> <p>Aucun post pour l’instant.</p>

    <?php endif ?>
    </main>
</body>

</html>