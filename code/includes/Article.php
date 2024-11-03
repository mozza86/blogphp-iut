<?php
require_once 'bdd.php';
require_once 'Comment.php';

class Article {
    private int $id;
    private string $title;
    private string $content;
    private User $author;
    private string $image_url;
    private string $created_at;
    private string $updated_at;

    private array $comments;

    /**
     * Article object
     * @param int $id
     * @param string $title
     * @param string $content
     * @param User $author
     * @param string $image_url
     * @param string $created_at
     * @param string $updated_at
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    function __construct(int $id, string $title, string $content, User $author, string $image_url, string $created_at, string $updated_at) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
        $this->image_url = $image_url;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;

        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM comments where article_id = ?');
            $stmt->execute([$this->getId()]);

            $comments = $stmt->fetchAll();

            $comments_array = array();
            foreach ($comments as $comment) {
                $comments_array[] = new Comment($comment['id'], User::findById($comment['author_id']), $comment['content'], $this, $comment['created_at'], $comment['updated_at']);
            }
            $this->comments = $comments_array;

        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("Un des auteurs d'un des commentaires n'existe pas", 0, $e);
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
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
    public function getUpdatedAt(): string {
        return $this->updated_at;
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
    public function getImageUrl(): string {
        return $this->image_url;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getComments(): array {
        return $this->comments;
    }

    /**
     * Return if the user is allowed to delete this article
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
     * Delete an article
     * @return void
     * @throws DatabaseException
     */
    public function deleteArticle(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Create a new article
     * @param string $title
     * @param string $content
     * @param User $author
     * @param string $image_url
     * @param Category $category
     * @return Article
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function create(string $title, string $content, User $author, string $image_url, Category $category): Article {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id, image_url) VALUES (?,?,?,?)");
            $stmt->execute([$title, $content, $author->getId(), $image_url]);

            $article = new Article($conn->lastInsertId(), $title, $content, $author, $image_url, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

            $stmt = $conn->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (?,?)");
            $stmt->execute([$article->getId(), $category->getId()]);

            return $article;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException($e->getMessage());
        }
    }

    /**
     * Return article object by its id
     * @param int $id
     * @return Article
     * @throws ArticleNotFoundException
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function findById(int $id): Article {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM articles where id = ?');
            $stmt->execute([$id]);

            $article = $stmt->fetch();
            if ($article) {
                return new Article($article['id'], $article['title'], $article['content'], User::findById($article['author_id']), $article['image_url'], $article['created_at'], $article['updated_at']);
            }

            throw new ArticleNotFoundException("L'article $id n'existe pas");
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("L'auteur de l'article n'existe pas", 0, $e);
        }
    }
}