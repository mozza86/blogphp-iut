<?php
require_once 'bdd.php';
require_once 'Exceptions.php';
require_once 'User.php';

class Comment {
    private int $id;
    private User $author;
    private string $content;
    private Article $article;
    private string $created_at;
    private string $updated_at;

    /**
     * Comment object
     * @param int $id
     * @param User $author
     * @param string $content
     * @param Article $article
     * @param string $created_at
     * @param string $updated_at
     */
    function __construct(int $id, User $author, string $content, Article $article, string $created_at, string $updated_at) {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->article = $article;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getAuthor(): User {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @return Article
     */
    public function getArticle(): Article {
        return $this->article;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string {
        return $this->updated_at;
    }

    /**
     * Return if the user is allowed to delete this comment
     * @param User $user
     * @return bool
     */
    public function isAllowedToDelete(User $user): bool {
        if ($user->getId() == $this->getAuthor()->getId()) {
            return true;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * Delete a comment
     * @return void
     * @throws DatabaseException
     */
    public function delete(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Create a new comment
     * @param string $content Le contenu du commentaire
     * @param User $user Auteur du commentaire
     * @param Article $article
     * @return Comment
     * @throws DatabaseException
     */
    public static function create(string $content, User $user, Article $article): Comment {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO comments (author_id, content, article_id) VALUES (?,?,?)");
            $stmt->execute([$user->getId(), $content, $article->getId()]);

            return new Comment($conn->lastInsertId(), $user, $content, $article, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Return comment object associated with its id
     * @param int $id
     * @return Comment
     * @throws ArticleNotFoundException
     * @throws CommentNotFoundException
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function findById(int $id): Comment {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM comments where id = ?');
            $stmt->execute([$id]);

            $comment = $stmt->fetch();
            if ($comment) {
                return new Comment($comment['id'], User::findById($comment['author_id']), $comment['content'], Article::findById($comment['article_id']), $comment['created_at'], $comment['updated_at']);
            }

            throw new CommentNotFoundException("Le commentaire $id n'existe pas");
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("L'auteur du commentaire $id n'existe pas", 0, $e);
        } catch (ArticleNotFoundException $e) {
            throw new ArticleNotFoundException("L'article du commentaire $id n'existe pas", 0, $e);
        } catch (DatabaseException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }
}