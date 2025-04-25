<?php


abstract class PostRepository extends Db
{
    private static function request($request)
    {
        $result = self::getInstance()->query($request);
        return $result;
    }

    public static function getAllPosts()
    {
        $sql = "SELECT * FROM post ORDER BY id DESC";
        $stmt = self::request($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addPost(Post $post)
    {
        try {
            $pdo = Db::getInstance();
            $stmt = $pdo->prepare("INSERT INTO post (title, content, author) VALUES (?, ?, ?)");

            error_log("Insertion : " . $post->getTitle() . " | " . $post->getContent() . " | " . $post->getAuthor());

            $result = $stmt->execute([
                $post->getTitle(),
                $post->getContent(),
                $post->getAuthor()
            ]);

            if (!$result) {
                error_log('Erreur PDO lors de l\'exécution de l\'insertion : ' . implode(' | ', $stmt->errorInfo()));
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'insertion du post : " . $e->getMessage());
            return false;
        }
    }



    // public static function editPost()
    // {
    //     if (isset($_POST['update'])) {
    //         try {
    //             $id = intval(htmlspecialchars($_POST['update']));
    //             $pdo = Db::getInstance();
    //             $stmt = $pdo->prepare("UPDATE post SET title = ?, content = ? WHERE id = $id");
    //             return $stmt->execute([]);
    //         } catch (PDOException $e) {
    //             error_log("Erreur lors de l'insertion du post : " . $e->getMessage());
    //             return false;
    //         }
    //     }
    // }

    public static function deletePost($id) {}
        try {
            $pdo = self::getInstance();
            $stmt = $pdo->prepare("DELETE FROM post WHERE id = ?");
            return $stmt->execute([$id]); 
        } catch (PDOException $e) {
            error_log("Erreur suppression : " . $e->getMessage());
            return false;
        }
    }
    
    
}
