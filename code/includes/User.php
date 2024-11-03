<?php
require_once 'bdd.php';
require_once 'Exceptions.php';

$DEFAULT_AVATAR_URL = 'res/img/default-avatar.png';

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private string $avatar_url;
    private bool $admin;

    /**
     * User object
     * @param int $id
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $avatar_url
     * @param bool $admin
     */
    function __construct(int $id, string $username, string $email, string $password, string $avatar_url, bool $admin) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->avatar_url = $avatar_url;
        $this->admin = $admin;
    }

    /**
     * Return true if the user is an administrator otherwise return false
     * @return bool
     */
    public function isAdmin(): bool {
        return boolval($this->admin ?? 0);
    }

    /**
     * Return true if the stored password match the given password otherwise return false
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->password ?? '');
    }

    /**
     * Get the user id
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Get the username
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * Get the user email
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Get the avatar url of the user
     * @return string
     */
    public function getAvatarUrl(): string {
        return $this->avatar_url;
    }

    /**
     * Update the avatar url
     * @param string $avatar_url
     * @return void
     * @throws DatabaseException
     */
    public function setAvatarUrl(string $avatar_url): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET avatar_url = ? WHERE id = ?');
            $stmt->execute([$avatar_url, $this->id]);

            $this->avatar_url = $avatar_url;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Update the username
     * @param string $username
     * @return void
     * @throws DatabaseException
     */
    public function setUsername(string $username): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET username = ? WHERE id = ?');
            $stmt->execute([$username, $this->id]);

            $this->username = $username;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Update the email
     * @param string $email
     * @return void
     * @throws DatabaseException
     */
    public function setEmail(string $email): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET email = ? WHERE id = ?');
            $stmt->execute([$email, $this->id]);

            $this->email = $email;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Hash and update the password
     * @param string $password
     * @return void
     * @throws DatabaseException
     */
    public function setPassword(string $password): void {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([$hash, $this->id]);

            $this->password = $hash;
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Delete a user
     * @return void
     * @throws DatabaseException
     */
    public function delete(): void {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Try to log in the user if the user exists, otherwise create it
     * @param string $email
     * @param string $password
     * @return User
     * @throws DatabaseException
     * @throws IncorrectPasswordException
     * @throws InvalidEmailException
     */
    public static function loginOrCreate(string $email, string $password): User {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Email invalide");
        }
        try {
            $user = User::findByEmail($email);
            if (!$user->verifyPassword($password)) {
                throw new IncorrectPasswordException("Mauvais mot de passe");
            }
            return $user;
        } catch (DatabaseException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        } catch (UserNotFoundException $e) {
            try {
                return User::create($email, $password);
            } catch (InvalidEmailException $e) {
                throw new InvalidEmailException($e->getMessage(), 0, $e);
            } catch (DatabaseException $e) {
                throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
            }
        }
    }

    /**
     * Create a new user object and insert it to the db
     * @param string $email
     * @param string $password
     * @return User
     * @throws DatabaseException
     * @throws InvalidEmailException
     */
    public static function create(string $email, string $password): User {
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
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Return a user object or an error if it doesn't find the user
     * @param int $id
     * @return User|null
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function findById(int $id): ?User {
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
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }

    /**
     * Return a user object or an error if it doesn't find the user
     * @param string $email
     * @return User
     * @throws DatabaseException
     * @throws UserNotFoundException
     */
    public static function findByEmail(string $email): User {
        try {
            $conn = get_bdd_connection();
            $stmt = $conn->prepare('SELECT * FROM users where email = ?');
            $stmt->execute([$email]);

            $user = $stmt->fetch();
            if ($user) {
                return new User($user['id'], $user['username'], $user['email'], $user['password'], $user['avatar_url'], $user['admin']);
            }

            $htmlemail = htmlspecialchars($email);
            throw new UserNotFoundException("L'utilisateur $htmlemail n'existe pas");
        } catch (PDOException $e) {
            throw new DatabaseException("Erreur lors de la requête à la base de données", 0, $e);
        }
    }
}