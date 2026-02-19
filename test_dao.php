<?php
declare(strict_types=1);

// Autoload simple
spl_autoload_register(function (string $class){
    $prefix = 'App\\';
    $base   = __DIR__ . '/src/';
    $len    = strlen($prefix);

    if (strncmp($class, $prefix, $len) !== 0) {
        return;
    }

    $rel  = substr($class, $len);
    $file = $base . str_replace('\\', DIRECTORY_SEPARATOR, $rel) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

use App\Log\Logger;
use App\Database\DBConnection;
use App\Dao\FiliereDao;
use App\Dao\EtudiantDao;
use App\Entity\Filiere;
use App\Entity\Etudiant;

function out(string $label, $value) {
    echo "[CHECK] $label => " . 
        (is_scalar($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE)) 
        . PHP_EOL;
}

// Initialisation
$logger = new Logger(__DIR__ . '/logs/pdo_errors.log');
DBConnection::init($logger);

$filiereDao  = new FiliereDao($logger);
$etudiantDao = new EtudiantDao($logger);

echo "===== TEST CRUD FILIERE =====" . PHP_EOL;

// Création filière
$suffix = time();
$f = new Filiere(null, 'MATH_' . $suffix, 'Mathématiques');
$idF = $filiereDao->insert($f);
out('Filiere insert id', $idF);

// Lecture
$foundF = $filiereDao->findById($idF);
out('Filiere findById', $foundF ? $foundF->getLibelle() : 'null');

// Mise à jour
$f->setLibelle('Mathématiques Appliquées');
out('Filiere update', $filiereDao->update($f));

// Liste
$allF = $filiereDao->findAll();
out('Filiere findAll count', count($allF));

// Suppression
out('Filiere delete', $filiereDao->delete($idF));

echo PHP_EOL . "===== TEST CRUD ETUDIANT =====" . PHP_EOL;

// Assurer qu’une filière existe
$fTmp = new Filiere(null, 'GEST', 'Gestion');
$filiereDao->insert($fTmp);

$e = new Etudiant(
    null,
    'CNE5001',
    'Durand',
    'Alice',
    'alice.durand@example.com',
    (int)$fTmp->getId()
);

$idE = $etudiantDao->insert($e);
out('Etudiant insert id', $idE);

// Lecture
$foundE = $etudiantDao->findById($idE);
out('Etudiant findById email', $foundE ? $foundE->getEmail() : 'null');

// Mise à jour
$e->setNom('Durand-Updated');
out('Etudiant update', $etudiantDao->update($e));

// Liste
$allE = $etudiantDao->findAll();
out('Etudiant findAll count', count($allE));

// Suppression
out('Etudiant delete', $etudiantDao->delete($idE));

echo PHP_EOL . "===== TEST DUPLICATE EMAIL =====" . PHP_EOL;

try {
    $e1 = new Etudiant(null, 'CNE5002', 'Test', 'Dup', 'duplicate@test.com', (int)$fTmp->getId());
    $etudiantDao->insert($e1);

    $e2 = new Etudiant(null, 'CNE5003', 'Test2', 'Dup2', 'duplicate@test.com', (int)$fTmp->getId());
    $etudiantDao->insert($e2);

    out('Duplicate test', 'unexpected success');
} catch (\PDOException $ex) {
    out('Duplicate test', 'OK (exception capturée)');
}

echo PHP_EOL . "===== TEST TRANSACTION (COMMIT) =====" . PHP_EOL;

$pdo = DBConnection::get();

try {
    $pdo->beginTransaction();

    $fT = new Filiere(null, 'ARCH', 'Architecture');
    $filiereDao->insert($fT);

    $eT = new Etudiant(
        null,
        'CNE6000',
        'Benoit',
        'Charles',
        'charles.benoit@example.com',
        (int)$fT->getId()
    );

    $etudiantDao->insert($eT);

    $pdo->commit();
    out('Transaction', 'COMMIT');
} catch (\PDOException $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    out('Transaction', 'ROLLBACK: ' . $ex->getMessage());
}

echo PHP_EOL . "===== FIN TEST =====" . PHP_EOL;

