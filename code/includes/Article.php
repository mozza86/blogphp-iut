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
    private bool $is_deleted = false;

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
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->id;
    }

    public function getAuthor(): User {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->author;
    }

    public function isDeleted(): bool {
        return $this->is_deleted;
    }

    public function getUpdatedAt(): string {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->updated_at;
    }

    public function getCreatedAt(): string {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->created_at;
    }

    public function getImageUrl(): string {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->image_url;
    }

    public function getContent(): string {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->content;
    }

    public function getTitle(): string {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        return $this->title;
    }

    public function isOwner($user): bool {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        try {
            return $user->getId() == $this->getAuthor()->getId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete(): void {
        if ($this->is_deleted) throw new Exception('Article is deleted');
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
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
            throw new Exception("PDOException: " . $e->getMessage());
        } catch (Exception $e) {
            throw $e;
        }
    }
}