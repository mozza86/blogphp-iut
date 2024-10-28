<?php
require_once 'bdd.php';

$DEFAULT_AVATAR_URL = 'default.png';

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $avatar_url;
    private bool $admin;
    private bool $is_deleted = false;

    function __construct($id, $username, $email, $password, $avatar_url, $admin) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->avatar_url = $avatar_url;
        $this->admin = $admin;
    }

    public function isAdmin(): bool {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return boolval($this->admin ?? 0);
    }
    public function isArticleOwner($article): bool {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return $article->is_author($this->id) ?? false;
    }
    public function verifyPassword($password): bool {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return password_verify($password, $this->password ?? '');
    }

    public function isDeleted(): bool {
        return $this->is_deleted;
    }

    public function getId(): int {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return $this->id;
    }

    public function getUsername(): string {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return $this->username;
    }

    public function getEmail(): string {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return $this->email;
    }

    public function getAvatarUrl(): string {
        if ($this->is_deleted) throw new Exception('User is deleted');
        return $this->avatar_url;
    }

    public function setAvatarUrl($avatar_url): void {
        if ($this->is_deleted) throw new Exception('User is deleted');
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET avatar_url = ? WHERE id = ?');
            $stmt->execute([$avatar_url, $this->id]);

            $this->avatar_url = $avatar_url;
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }

    public function setUsername(string $username): void {
        if ($this->is_deleted) throw new Exception('User is deleted');
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET username = ? WHERE id = ?');
            $stmt->execute([$username, $this->id]);

            $this->username = $username;
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }

    public function setEmail(string $email): void {
        if ($this->is_deleted) throw new Exception('User is deleted');
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET email = ? WHERE id = ?');
            $stmt->execute([$email, $this->id]);

            $this->email = $email;
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }

    public function setPassword(string $password): void {
        if ($this->is_deleted) throw new Exception('User is deleted');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([$hash, $this->id]);

            $this->password = $hash;
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }

    public static function loginOrCreate($email, $password): User {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }
        try {
            $user = User::findByEmail($email);

            if (!$user) {
                return User::create($email, $password);
            } elseif (!$user->verifyPassword($password)) {
                throw new Exception("Wrong password");
            }

            return $user;
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }

    public static function create($email, $password): User {
        global $DEFAULT_AVATAR_URL;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO users (email, password, avatar_url, username) VALUES(?,?,?,?)");
            $stmt->execute([$email, $hash, $DEFAULT_AVATAR_URL, $email]);

            return new User($conn->lastInsertId(), $email, $email, $hash, $DEFAULT_AVATAR_URL, $email);
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }

    public static function findById($id): ?User {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM users where id = ?');
            $stmt->execute([$id]);

            $user = $stmt->fetch();
            if ($user) {
                return new User($user['id'], $user['username'], $user['email'], $user['password'], $user['avatar_url'], $user['admin']);
            }

            throw new Exception("User does not exist");
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }
    public static function findByEmail($email): ?User {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM users where email = ?');
            $stmt->execute([$email]);

            $user = $stmt->fetch();
            if ($user) {
                return new User($user['id'], $user['username'], $user['email'], $user['password'], $user['avatar_url'], $user['admin']);
            }

            throw new Exception("User does not exist");
        } catch (PDOException $e) {
            throw new Exception("PDOException: ".$e->getMessage());
        }
    }
}