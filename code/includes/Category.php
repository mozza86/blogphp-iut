<?php
require_once 'bdd.php';

class Category {
    private int $id;
    private string $name;

    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function delete(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public function update($name): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $this->id]);
            $this->name = $name;
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function create($name): Category {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);

            return new Category($conn->lastInsertId(), $name);
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function getAll(): array {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("SELECT * FROM categories");
            $stmt->execute();
            $categories = $stmt->fetchAll();

            $categories_obj = array();
            foreach ($categories as $category) {
                $categories_obj[] = new Category($category["id"], $category["name"]);
            }
            return $categories_obj;
        } catch(PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function findById($id): Category {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM categories where id = ?');
            $stmt->execute([$id]);

            $category = $stmt->fetch();
            if ($category) {
                return new Category($category['id'], $category['name']);
            }

            throw new ObjectNotFoundException("La catégorie $id n'existe pas");
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function findByName($name): Category {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM categories where name = ?');
            $stmt->execute([$name]);

            $category = $stmt->fetch();
            if ($category) {
                return new Category($category['id'], $category['name']);
            }

            $htmlname = htmlentities($name);

            throw new ObjectNotFoundException("La catégorie $htmlname n'existe pas");
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }
}