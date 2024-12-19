<?php
namespace Ilya\Game;

require 'Player.php';

use Ilya\Game\Player;

session_start();

if (!isset($_SESSION['players'])) {
    $_SESSION['players'] = [
        new Player("Игрок 1"),
        new Player("Игрок 2")
    ];
    $_SESSION['currentPlayerIndex'] = 0;
    $_SESSION['gameOver'] = false;
}

function rollDice() {
    return rand(1, 6);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        session_destroy();
        session_start(); 
        $_SESSION['players'] = [
            new Player("Игрок 1"),
            new Player("Игрок 2")
        ];
        $_SESSION['currentPlayerIndex'] = 0;
        $_SESSION['gameOver'] = false;
        $message = "Игра сброшена. Нажмите кнопку, чтобы бросить кубик.";
    } elseif ($_SESSION['gameOver']) {
        $message = 'Игра окончена. Нажмите кнопку "Сбросить", чтобы начать новую игру.';
    } else {
        $currentPlayer = $_SESSION['players'][$_SESSION['currentPlayerIndex']];
        $diceRoll = rollDice();
        $newPosition = $currentPlayer->getPosition() + $diceRoll;
        $currentPlayer->setPosition($newPosition);

        if ($newPosition >= 30) {
            $_SESSION['gameOver'] = true;
            $message = $currentPlayer->getName() . ' выиграл игру!';
        } else {
            $_SESSION['currentPlayerIndex'] = ($_SESSION['currentPlayerIndex'] + 1) % count($_SESSION['players']);
            $message = $currentPlayer->getName() . " теперь на позиции: " . $currentPlayer->getPosition() . ". Вы бросили кубик и получили: $diceRoll.";
        }
    }
} else {
    $message = "Нажмите кнопку, чтобы бросить кубик.";
}

$players = $_SESSION['players'];
$currentPlayerIndex = $_SESSION['currentPlayerIndex'];
$currentPlayer = $players[$currentPlayerIndex];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настольная игра</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .board {
            display: flex;
            flex-wrap: wrap;
            width: 300px;
        }

        .cell {
            width: 30px;
            height: 30px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <h1>Настольная игра</h1>
    <p><?php echo $message; ?></p>

    <div class="board">
        <?php
        for ($i = 1; $i <= 30; $i++) {
            echo "<div class='cell' id='cell-$i'>" . ($i <= 30 ? $i : '') . "</div>";
        }
        ?>
    </div>

    <form method="post">
        <button type="submit" name="roll">Бросить кубик</button>
        <button type="submit" name="reset">Сбросить игру</button>
    </form>

</body>

</html>