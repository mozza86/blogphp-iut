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

    function __construct($id, $username, $email, $password, $avatar_url, $admin) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->avatar_url = $avatar_url;
        $this->admin = $admin;
    }

    public function isAdmin(): bool {
        return boolval($this->admin ?? 0);
    }
    public function isArticleOwner($article): bool {
        return $article->is_author($this->id) ?? false;
    }
    public function verifyPassword($password): bool {
        return password_verify($password, $this->password ?? '');
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getAvatarUrl(): string {
        return $this->avatar_url;
    }

    public function setAvatarUrl($avatar_url): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET avatar_url = ? WHERE id = ?');
            $stmt->execute([$avatar_url, $this->id]);

            $this->avatar_url = $avatar_url;
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public function setUsername(string $username): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET username = ? WHERE id = ?');
            $stmt->execute([$username, $this->id]);

            $this->username = $username;
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public function setEmail(string $email): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET email = ? WHERE id = ?');
            $stmt->execute([$email, $this->id]);

            $this->email = $email;
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public function setPassword(string $password): void {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([$hash, $this->id]);

            $this->password = $hash;
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public function delete(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function loginOrCreate($email, $password): User {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Email invalide");
        }
        try {
            try {
                $user = User::findByEmail($email);
                if (!$user->verifyPassword($password)) {
                    throw new IncorrectPasswordException("Mauvais mot de passe");
                }
            } catch (UserNotFoundException $e) {
                return User::create($email, $password);
            } catch (SQLException $e) {
                throw new SQLException($e->getMessage());
            }

            return $user;
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }

    public static function create($email, $password): User {
        global $DEFAULT_AVATAR_URL;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Email invalide");
        }
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $conn = get_bdd_connection();
            $stmt = $conn->prepare("INSERT INTO users (email, password, avatar_url, username) VALUES(?,?,?,?)");
            $stmt->execute([$email, $hash, $DEFAULT_AVATAR_URL, $email]);

            return new User($conn->lastInsertId(), $email, $email, $hash, $DEFAULT_AVATAR_URL, $email);
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
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

            throw new UserNotFoundException("L'utilisateur $id n'existe pas");
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
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

            $htmlemail = htmlentities($email);
            throw new UserNotFoundException("L'utilisateur $htmlemail n'existe pas");
        } catch (PDOException $e) {
            throw new SQLException($e->getMessage());
        }
    }
}