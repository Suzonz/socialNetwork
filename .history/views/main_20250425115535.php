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
    <?php if (isset($_SESSION['pseudo'])): ?>
        <?php if (!empty($posts)): ?>
            <?php
                $lastDate = null;
                foreach ($posts as $post):
                    $currentDate = date('Y-m-d', strtotime($post['created_at']));
                    if ($currentDate !== $lastDate):
                        $lastDate = $currentDate;
            ?>
                        <h2 class="date"><?= date('d F Y', strtotime($post['created_at'])) ?></h2>
            <?php endif; ?>
                    <div class="posts">
                        <?php if ($post['author'] === $_SESSION['pseudo']): ?>
                            <!-- Affichage avec boutons d'édition/suppression -->
                            <!-- ... (ton code d'édition/suppression ici) ... -->
                        <?php else : ?>
                            <p> <strong><?= htmlspecialchars($post['author']) ?></strong> <?= date('H:i', strtotime($post['created_at'])) ?></p>
                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <?php endif; ?>
                    </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun post pour l’instant.</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Aucun post pour l’instant.</p>
    <?php endif; ?>
</main>

</body>

</html>