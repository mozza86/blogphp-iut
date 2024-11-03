<?php
require_once 'bdd.php';
require_once 'Comment.php';

class Article {
    private int $id;
    private string $title;
    private string $content;
    private User $author;
    private string $created_at;
    private string $updated_at;

    private array $comments;

    /**
     * Article object
     * @param int $id
     * @param string $title
     * @param string $content
     * @param User $author
     * @param string $created_at
     * @param string $updated_at
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    function __construct(int $id, string $title, string $content, User $author, string $created_at, string $updated_at) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->author = $author;
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
     * @param Category $category
     * @return Article
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function create(string $title, string $content, User $author, Category $category): Article {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id) VALUES (?,?,?)");
            $stmt->execute([$title, $content, $author->getId()]);

            $article = new Article($conn->lastInsertId(), $title, $content, $author, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

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
                return new Article($article['id'], $article['title'], $article['content'], User::findById($article['author_id']), $article['created_at'], $article['updated_at']);
            }

            throw new ArticleNotFoundException("L'article $id n'existe pas");
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("L'auteur de l'article n'existe pas", 0, $e);
        }
    }


    /**
     * Filter articles based on $values_to_filter, possible values are auteur, categorie, titre, contenu (i.e: $values_to_filter['auteur'] == 'admin')
     * @param array $values_to_filter
     * @return array
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function filter(array $values_to_filter, int $page = 1): array {
        $sql = 'SELECT *, a.id as article_id FROM articles a 
            JOIN users u ON a.author_id = u.id 
            JOIN article_categories ac ON a.id = ac.article_id 
            JOIN categories c ON ac.category_id = c.id 
            WHERE 1=1';
        $params = [];

        // Filtrer par auteur
        if (!empty($values_to_filter['auteur'])) {
            $sql .= ' AND u.username LIKE :auteur';
            $params[':auteur'] = '%' . $values_to_filter['auteur'] . '%';
        }

        // Filtrer par catégorie
        if (!empty($values_to_filter['categorie'])) {
            $sql .= ' AND c.id = :categorie';
            $params[':categorie'] = $values_to_filter['categorie'];
        }

        // Filtrer par titre
        if (!empty($values_to_filter['titre'])) {
            $sql .= ' AND a.title LIKE :titre';
            $params[':titre'] = '%' . $values_to_filter['titre'] . '%';
        }

        // Filtrer par contenu
        if (!empty($values_to_filter['contenu'])) {
            $sql .= ' AND a.content LIKE :contenu';
            $params[':contenu'] = '%' . $values_to_filter['contenu'] . '%';
        }

        $sql .= ' ORDER BY a.created_at DESC LIMIT :limit OFFSET :offset';
        $params[':limit'] = 20;
        $params[':offset'] = ($page - 1) * $params['limit'];

        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $articles = $stmt->fetchAll();
            
            $articles_obj = array();
            foreach ($articles as $article) {
                $articles_obj[] = new Article($article['id'], $article['title'], $article['content'], User::findById($article['author_id']), $article['created_at'], $article['updated_at']);
            }
            return $articles_obj;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("L'auteur d'un des articles n'existe pas", 0, $e);
        }
    }
}