<?php
require_once 'bdd.php';

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

    public function isOwner($user): bool {
        if ($this->is_deleted) throw new ObjectDeletedException('Article is deleted');
        try {
            return $user->getId() == $this->getAuthor()->getId();
        } catch (Exception $e) {
            throw new SQLException($e->getMessage());
        }
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
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException("L'auteur de l'article n'existe pas");
        }
    }
}