<?php
require_once 'bdd.php';
require_once 'Exceptions.php';

class Category {
    private int $id;
    private string $name;

    /**
     * Category object
     * @param int $id
     * @param string $name
     */
    function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Return the id of the category
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Return the name of the category
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Delete the category associated with this object
     * @return void
     * @throws DatabaseException
     */
    public function delete(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Update the name of a category
     * @param string $name
     * @return void
     * @throws DatabaseException
     */
    public function update(string $name): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$name, $this->id]);
            $this->name = $name;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Create a new category
     * @param string $name
     * @return Category
     * @throws DatabaseException
     */
    public static function create(string $name): Category {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);

            return new Category($conn->lastInsertId(), $name);
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Retrieve all the categories
     * @return array
     * @throws DatabaseException
     */
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
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Return category by its ID
     * @param int $id
     * @return Category
     * @throws DatabaseException
     * @throws CategoryNotFoundException
     */
    public static function findById(int $id): Category {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM categories where id = ?');
            $stmt->execute([$id]);

            $category = $stmt->fetch();
            if ($category) {
                return new Category($category['id'], $category['name']);
            }
            throw new CategoryNotFoundException("La catégorie $id n'existe pas");
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }
}