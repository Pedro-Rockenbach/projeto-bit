<?php
$host   = 'localhost';
$dbname = 'ub';
$user   = 'root';
$pass   = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec("CREATE TABLE IF NOT EXISTS subjects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        elo INT DEFAULT 1200,
        wins INT DEFAULT 0,
        matches INT DEFAULT 0
    ) ENGINE=InnoDB;");
} catch (Exception $e) {
    die("Erro ao conectar ao MariaDB: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'vote') {
    $winnerId = (int)$_POST['winnerId'];
    $loserId = (int)$_POST['loserId'];

    $stmt = $pdo->prepare("SELECT id, elo FROM subjects WHERE id IN (?, ?)");
    $stmt->execute([$winnerId, $loserId]);
    $results = $stmt->fetchAll();

    $winner = current(array_filter($results, fn($r) => $r['id'] == $winnerId));
    $loser = current(array_filter($results, fn($r) => $r['id'] == $loserId));

    if ($winner && $loser) {
        $expectedWinner = 1 / (1 + pow(10, ($loser['elo'] - $winner['elo']) / 400));
        $expectedLoser = 1 / (1 + pow(10, ($winner['elo'] - $loser['elo']) / 400));

        $newWinnerElo = (int) round($winner['elo'] + 32 * (1 - $expectedWinner));
        $newLoserElo = (int) round($loser['elo'] + 32 * (0 - $expectedLoser));

        $pdo->beginTransaction();
        $updateWinner = $pdo->prepare("UPDATE subjects SET elo = ?, wins = wins + 1, matches = matches + 1 WHERE id = ?");
        $updateWinner->execute([$newWinnerElo, $winnerId]);

        $updateLoser = $pdo->prepare("UPDATE subjects SET elo = ?, matches = matches + 1 WHERE id = ?");
        $updateLoser->execute([$newLoserElo, $loserId]);
        $pdo->commit();
    }

    $mode = $_GET['mode'] ?? 'good';
    header("Location: index.php?mode=" . $mode);
    exit;
}

$stmtMatchup = $pdo->query("SELECT id, name, elo FROM subjects ORDER BY RAND() LIMIT 2");
$matchup = $stmtMatchup->fetchAll();

$subjectA = $matchup[0] ?? null;
$subjectB = $matchup[1] ?? null;

$battleTitle = "Qual é a melhor da Computação?";

$mode = $_GET['mode'] ?? 'good';
$order = ($mode === 'evil') ? 'ASC' : 'DESC';

$stmtRanking = $pdo->query("SELECT name, elo, wins, matches FROM subjects ORDER BY elo $order LIMIT 20");
$rankingList = $stmtRanking->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UnioBattle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custom.css"/>
</head>

<body class="bg-body-primary">

    <header class="navbar bg-body border-bottom justify-content-center py-2 mb-4">
        <span class="navbar-brand fw-bold m-0 fs-4">UnioBattle</span>
    </header>

    <main class="container my-3" style="max-width: 600px;">

        <section class="card bg-body p-3 mb-4 shadow-sm text-center">
            <h2 class="h5 fw-bold mb-3"><?= htmlspecialchars($battleTitle) ?></h2>

            <?php if ($subjectA && $subjectB): ?>
                <div class="row g-2 align-items-center justify-content-center">

                    <div class="col-12 col-sm-5">
                        <form method="POST" action="index.php?mode=<?= $mode ?>">
                            <input type="hidden" name="action" value="vote">
                            <input type="hidden" name="winnerId" value="<?= $subjectA['id'] ?>">
                            <input type="hidden" name="loserId" value="<?= $subjectB['id'] ?>">
                            <button type="submit" class="card bg-body-secondary p-3 text-center border-0 shadow-sm w-100 btn text-start">
                                <div class="fs-1 mb-1 text-center">📘</div>
                                <h6 class="fw-bold mb-1 text-truncate text-center text-body"><?= htmlspecialchars($subjectA['name']) ?></h6>
                                <div class="text-center">
                                    <span class="badge text-bg-dark border"><?= $subjectA['elo'] ?> Elo</span>
                                </div>
                            </button>
                        </form>
                    </div>

                    <div class="col-auto fw-bold text-secondary">VS</div>

                    <div class="col-12 col-sm-5">
                        <form method="POST" action="index.php?mode=<?= $mode ?>">
                            <input type="hidden" name="action" value="vote">
                            <input type="hidden" name="winnerId" value="<?= $subjectB['id'] ?>">
                            <input type="hidden" name="loserId" value="<?= $subjectA['id'] ?>">
                            <button type="submit" class="card bg-body-secondary p-3 text-center border-0 shadow-sm w-100 btn text-start">
                                <div class="fs-1 mb-1 text-center">💡</div>
                                <h6 class="fw-bold mb-1 text-truncate text-center text-body"><?= htmlspecialchars($subjectB['name']) ?></h6>
                                <div class="text-center">
                                    <span class="badge text-bg-dark border"><?= $subjectB['elo'] ?> Elo</span>
                                </div>
                            </button>
                        </form>
                    </div>

                </div>
            <?php else: ?>
                <p class="text-body-secondary my-3">Precisa de 2 materias cadastras para iniciar o confronto</p>
            <?php endif; ?>

        </section>

        <section class="mb-4">
            <div class="btn-group w-100 mb-2" role="group">
                <a href="index.php?mode=good" class="btn btn-sm <?= $mode === 'good' ? 'btn-secondary fw-bold' : 'btn-outline-secondary' ?>">
                    Melhores
                </a>
                <a href="index.php?mode=evil" class="btn btn-sm <?= $mode === 'evil' ? 'btn-secondary fw-bold' : 'btn-outline-secondary' ?>">
                    Piores
                </a>
            </div>

            <div class="list-group shadow-sm">
                <?php if (empty($rankingList)): ?>
                    <div class="list-group-item text-center text-body-secondary py-2 small">Sem registos.</div>
                <?php else: ?>
                    <?php foreach ($rankingList as $idx => $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-body py-2">
                            <div class="d-flex align-items-center gap-3">
                                <span class="fw-bold text-secondary">#<?= $idx + 1 ?></span>
                                <div>
                                    <div class="fw-bold small"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="text-body-secondary" style="font-size: 0.75rem;">
                                        <?= $item['wins'] ?> vitórias em <?= $item['matches'] ?> duelos
                                    </div>
                                </div>
                            </div>
                            <span class="badge <?= $mode === 'evil' ? 'text-bg-danger' : 'text-bg-primary' ?>">
                                <?= $item['elo'] ?> Elo
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </main>

</body>

</html>
