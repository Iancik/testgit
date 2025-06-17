--config
<?php
$host = 'localhost';
$db   = 'produse_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    echo "Conexiunea a eșuat: " . $e->getMessage();
    exit;
}
?>
--crud
<?php
require_once 'config/database.php';

class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM produse");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produse WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nume, $pret) {
        $stmt = $this->pdo->prepare("INSERT INTO produse (nume, pret) VALUES (?, ?)");
        return $stmt->execute([$nume, $pret]);
    }

    public function update($id, $nume, $pret) {
        $stmt = $this->pdo->prepare("UPDATE produse SET nume = ?, pret = ? WHERE id = ?");
        return $stmt->execute([$nume, $pret, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produse WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
--index
<?php
require_once '../Product.php';
$product = new Product($pdo);
$produse = $product->getAll();
?>

<h1>Lista produse</h1>
<a href="../create.php">Adaugă produs</a>
<table border="1">
    <tr><th>ID</th><th>Nume</th><th>Preț</th><th>Acțiuni</th></tr>
    <?php foreach ($produse as $prod): ?>
    <tr>
        <td><?= $prod['id'] ?></td>
        <td><?= $prod['nume'] ?></td>
        <td><?= $prod['pret'] ?></td>
        <td>
            <a href="../view.php?id=<?= $prod['id'] ?>">Vizualizează</a> |
            <a href="../update.php?id=<?= $prod['id'] ?>">Editează</a> |
            <a href="../delete.php?id=<?= $prod['id'] ?>" onclick="return confirm('Ești sigur?')">Șterge</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
--create
<?php
require_once 'Product.php';
$product = new Product($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product->create($_POST['nume'], $_POST['pret']);
    header("Location: public/index.php");
}
?>

<h2>Adaugă produs</h2>
<form method="post">
    Nume: <input type="text" name="nume"><br>
    Preț: <input type="text" name="pret"><br>
    <button type="submit">Salvează</button>
</form>

--update
<?php
require_once 'Product.php';
$product = new Product($pdo);

$id = $_GET['id'];
$produs = $product->get($id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product->update($id, $_POST['nume'], $_POST['pret']);
    header("Location: public/index.php");
}
?>

<h2>Editează produs</h2>
<form method="post">
    Nume: <input type="text" name="nume" value="<?= $produs['nume'] ?>"><br>
    Preț: <input type="text" name="pret" value="<?= $produs['pret'] ?>"><br>
    <button type="submit">Actualizează</button>
</form>

<?php
require_once 'Product.php';
$product = new Product($pdo);

$id = $_GET['id'];
$product->delete($id);
header("Location: public/index.php");

--delete
<?php
require_once 'Product.php';
$product = new Product($pdo);

$id = $_GET['id'];
$product->delete($id);
header("Location: public/index.php");

--data base
CREATE DATABASE produse_db;
USE produse_db;

CREATE TABLE produse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nume VARCHAR(255) NOT NULL,
    pret DECIMAL(10,2) NOT NULL
);


