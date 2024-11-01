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

    function __construct($id, $title, $content, $author, $image_url, $created_at, $updated_at) {
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
            
        } catch (SQLException $e) {
            throw new SQLException($e->getMessage());
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException($e->getMessage());
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getAuthor(): User {
        return $this->author;
    }

    public function getUpdatedAt(): string {
        return $this->updated_at;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getImageUrl(): string {
        return $this->image_url;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getComments(): array {
        return $this->comments;
    }

    public function isAllowedToDelete($user): bool {
        if ($user->getId() == $this->getAuthor()->getId()) {
            return true;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return false;
    }

    public function delete(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function create($title, $content, $author, $image_url, $category): Article {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO articles (title, content, author_id, image_url) VALUES (?,?,?,?)");
            $stmt->execute([$title, $content, $author->getId(), $image_url]);

            $article = new Article($conn->lastInsertId(), $title, $content, $author, $image_url, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"));

            $stmt = $conn->prepare("INSERT INTO article_categories (article_id, category_id) VALUES (?,?)");
            $stmt->execute([$article->getId(), $category->getId()]);

            return $article;
        } catch (PDOException|SQLException  $e) {
            throw new SQLException($e->getMessage());
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException($e->getMessage());
        }
    }

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
        } catch (PDOException|SQLException $e) {
            throw new SQLException($e->getMessage());
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("L'auteur de l'article n'existe pas");
        }
    }
}