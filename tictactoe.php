<!DOCTYPE html>
<html>
<head>
    <title>Tic Tac Toe Game</title>
    <style>
body {
    background: linear-gradient(45deg, #ff0080, #7928ca, #ff00bf, #1d2671);
    color: #fff;
    font-family: 'Roboto', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 0;
    transition: background 0.5s;
    perspective: 600px;
}

.board {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    gap: 15px;
    justify-items: center;
    align-items: center;
    transform-style: preserve-3d;
    transform: rotateX(20deg);
}

.cell {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.85);
    color: #000;
    border: 1px solid #fff;
    border-radius: 5px;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.5s ease;
    transform-style: preserve-3d;
    backface-visibility: hidden;
}

.cell:hover {
    background: #ffdd57;
    color: #000;
    transform: translateY(-5px) rotateX(20deg);
}

.cell:active {
    background: #ffb600;
    transform: translateY(2px) rotateX(20deg);
}

.reset-button {
    margin-top: 20px;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    background: #ffdd57;
    color: #000;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.5s ease;
    transform-style: preserve-3d;
    backface-visibility: hidden;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
}

.reset-button:hover {
    background: #ffb600;
    transform: translateY(-5px) rotateX(20deg);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.5);
}

.reset-button:active {
    background: #ff7b00;
    transform: translateY(2px) rotateX(20deg);
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
}

p {
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    margin-top: 20px;
    text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
}



    </style>
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION['board'])) {
        $_SESSION['board'] = array_fill(0, 9, '');
        $_SESSION['current_player'] = 'X';
        $_SESSION['game_over'] = false;
    }

    function render_board() {
        echo "<div class='board'>";
        for ($i = 0; $i < 9; $i++) {
            echo "<input type='submit' name='cell{$i}' value='{$_SESSION['board'][$i]}' class='cell'>";
        }
        echo "</div>";
    }
    

    function check_win() {
        $winning_combinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // columns
            [0, 4, 8], [2, 4, 6] // diagonals
        ];

        foreach ($winning_combinations as $combination) {
            $symbol = $_SESSION['board'][$combination[0]];
            if ($symbol != '' &&
                $_SESSION['board'][$combination[1]] == $symbol &&
                $_SESSION['board'][$combination[2]] == $symbol) {
                return $symbol;
            }
        }
        return false;
    }

    function check_draw() {
        return !in_array('', $_SESSION['board'], true);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['reset'])) {
            session_unset();
            header("Refresh:0");
        } else {
            for ($i = 0; $i < 9; $i++) {
                if (isset($_POST["cell{$i}"]) && $_POST["cell{$i}"] == '' && !$_SESSION['game_over']) {
                    $_SESSION['board'][$i] = $_SESSION['current_player'];
                    $winner = check_win();
                    if ($winner) {
                        echo "<p>{$winner} won!</p>";
                        $_SESSION['game_over'] = true;
                    } elseif (check_draw()) {
                        echo "<p>It's a draw!</p>";
                        $_SESSION['game_over'] = true;
                    } else {
                        $_SESSION['current_player'] = $_SESSION['current_player'] == 'X' ? 'O' : 'X';
                    }
                }
            }
       
        }
    }
    ?>
    
    <form method="POST">
        <?php render_board(); ?>
        <br>
        <input type="submit" name="reset" value="Reset Game" class="reset-button">
    </form>
    
    </body>
    </html>
    