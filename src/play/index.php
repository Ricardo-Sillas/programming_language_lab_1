<?php

include 'RandomStrategy.php';
include 'SmartStrategy.php';

// Array used once winning row is found
$winningRow = [];

// Board that would be used and updated along the way
// When 0 on board, it means its empty
// When 1 on board, it means humans piece occupies it
// When 2 on board, it means computers piece occupies it
$board = array(
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0)
);

// Gets the strategy that's going to be used from the folder
function getStrategy(){
    $fs = fopen("../strategy/strategy.txt", 'r');
    $strat = fread($fs, filesize("../strategy/strategy.txt"));
    fclose($fs);
    return $strat;
}

// Updates the board depending on where the slot was picked
function updateBoard($slot, $player) {
    global $board;
    if(colFull($slot)) {
        echo json_encode("Column full");
    }
    // Goes from top to bottom when placing the piece
    else {
        for ($i = 1; $i < 6; $i++) {
            if ($board[$i][$slot] == 1 || $board[$i][$slot] == 2) {
                $board[$i - 1][$slot] = $player;
                break;
            } else if ($i == 5) {
                $board[$i][$slot] = $player;
            }
        }
    }
}

// Checks to see if column at certain position is full
function colFull($slot) {
    global $board;
    if($board[0][$slot] == 0) {
        return false;
    }
    return true;
}

// Checks to see if the whole board is full
function boardFull() {
    global $board;
    for($i = 0; $i < 7; $i++) {
        if ($board[0][$i] == 0) {
            return false;
        }
    }
    return true;
}

// Checks to see if there was a winner as soon as a piece is placed
function checkWin($player, $slot) {
    global $board;
    global $winningRow;
    $save = -1;
    // vertical
    for($i = 0; $i < 6; $i++) {
        if($board[$i][$slot] == $player) {
            if($i > 2) {
                break;
            }
            if($board[$i+1][$slot]==$player && $board[$i+2][$slot]==$player && $board[$i+3][$slot]==$player) {
                $winningRow = [$slot, $i, $slot, $i+1, $slot, $i+2, $slot, $i+3];
                return true;
            }
            else {
                break;
            }
        }
    }
    // horizontal
    for($i = 0; $i < 6; $i++) {
        if($board[$i][$slot] == $player) {
            $save = $i;
            break;
        }
    }
    for($i = 0; $i < 4; $i++) {
        if($board[$save][$i] == $player && $board[$save][$i+1] == $player && $board[$save][$i+2] == $player && $board[$save][$i+3] == $player) {
            $winningRow = [$i, $save, $i+1, $save, $i+2, $save, $i+3, $save];
            return true;
        }
    }
    // rising diagonal
    if($slot+$save > 2 && $slot+$save < 9) {
        if (($save + $slot) % 5 == 3) {
            if ($board[3][0] == $player && $board[2][1] == $player && $board[1][2] == $player && $board[0][3] == $player) {
                $winningRow = [0, 3, 1, 2, 2, 1, 3, 0];
                return true;
            }
            else if ($board[5][3] == $player && $board[4][4] == $player && $board[3][5] == $player && $board[2][6] == $player) {
                $winningRow = [3, 5, 4, 4, 5, 3, 6, 2];
                return true;
            }
        } else if ($save + $slot == 4) {
            if ($board[3][1] == $player && $board[2][2] == $player && $board[1][3] == $player) {
                if ($board[0][4] == $player) {
                    $winningRow = [1, 3, 2, 2, 3, 1, 4, 0];
                    return true;
                } else if ($board[4][0] == $player) {
                    $winningRow = [0, 4, 1, 3, 2, 2, 3, 1];
                    return true;
                }
            }
        } else if ($save + $slot == 7) {
            if ($board[4][3] == $player && $board[3][4] == $player && $board[2][5] == $player) {
                if ($board[5][2] == $player) {
                    $winningRow = [2, 5, 3, 4, 4, 3, 5, 2];
                    return true;
                } else if ($board[1][6] == $player) {
                    $winningRow = [3, 4, 4, 3, 5, 2, 6, 1];
                    return true;
                }
            }
        } else {
            if ($slot + $save == 5) {
                if ($board[3][2] == $player && $board[2][3] == $player) {
                    if ($board[1][4] != $player) {
                        if ($board[4][1] == $player && $board[5][0] == $player) {
                            $winningRow = [0,5,1,4,2,3,3,2];
                            return true;
                        }
                    }
                    else if($board[4][1] != $player) {
                        if($board[1][4] == $player && $board[0][5] == $player) {
                            $winningRow = [5,0,4,1,3,2,2,3];
                            return true;
                        }
                    }
                    else {
                        $winningRow = [1,4,2,3,3,2,4,1];
                        return true;
                    }
                }
            }
            if($slot+$save == 6) {
                if ($board[3][3] == $player && $board[2][4] == $player) {
                    if ($board[1][5] != $player) {
                        if ($board[4][2] == $player && $board[5][1] == $player) {
                            $winningRow = [1,5,2,4,3,3,4,2];
                            return true;
                        }
                    }
                    else if($board[4][2] != $player) {
                        if($board[1][5] == $player && $board[0][6] == $player) {
                            $winningRow = [6,0,5,1,4,2,3,3];
                            return true;
                        }
                    }
                    else {
                        $winningRow = [1,5,2,4,3,3,4,2];
                        return true;
                    }
                }
            }
        }
    }
    // falling diagonal
    $slot=6-$slot;
    if($slot+$save > 2 && $slot+$save < 9) {
        if (($save + $slot) % 5 == 3) {
            if ($board[3][6] == $player && $board[2][5] == $player && $board[1][4] == $player && $board[0][3] == $player) {
                $winningRow = [6, 3, 5, 2, 4, 1, 3, 0];
                return true;
            }
            else if ($board[5][3] == $player && $board[4][2] == $player && $board[3][1] == $player && $board[2][0] == $player) {
                $winningRow = [3, 5, 2, 4, 1, 3, 0, 2];
                return true;
            }
        } else if ($save + $slot == 4) {
            if ($board[3][5] == $player & $board[2][4] == $player && $board[1][3] == $player) {
                if ($board[0][2] == $player) {
                    $winningRow = [5, 3, 4, 2, 3, 1, 2, 0];
                    return true;
                } else if ($board[4][6] == $player) {
                    $winningRow = [6, 4, 5, 3, 4, 2, 3, 1];
                    return true;
                }
            }
        } else if ($save + $slot == 7) {
            if ($board[4][3] == $player && $board[3][2] == $player && $board[2][1] == $player) {
                if ($board[5][4] == $player) {
                    $winningRow = [4, 5, 3, 4, 2, 3, 1, 2];
                    return true;
                } else if ($board[1][0] == $player) {
                    $winningRow = [3, 4, 2, 3, 1, 2, 0, 1];
                    return true;
                }
            }
        } else {
            if ($slot + $save == 5) {
                if ($board[3][4] == $player && $board[2][3] == $player) {
                    if ($board[1][2] != $player) {
                        if ($board[4][5] == $player && $board[5][6] == $player) {
                            $winningRow = [6,5,5,4,4,3,3,2];
                            return true;
                        }
                    }
                    else if($board[4][5] != $player) {
                        if($board[1][2] == $player && $board[0][1] == $player) {
                            $winningRow = [1,0,2,1,3,2,4,3];
                            return true;
                        }
                    }
                    else {
                        $winningRow = [5,4,4,3,3,2,2,1];
                        return true;
                    }
                }
            }
            if($slot+$save == 6) {
                if ($board[3][3] == $player && $board[2][2] == $player) {
                    if ($board[1][1] != $player) {
                        if ($board[4][4] == $player && $board[5][5] == $player) {
                            $winningRow = [5,5,4,4,3,3,2,2];
                            return true;
                        }
                    }
                    else if($board[4][4] != $player) {
                        if($board[1][1] == $player && $board[0][6] == $player) {
                            $winningRow = [0,0,1,1,2,2,3,3];
                            return true;
                        }
                    }
                    else {
                        $winningRow = [5,5,4,4,3,3,2,2];
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

if(!isset($_GET['pid'])) {
    echo json_encode(array("response" => false, "reason" => "Pid not specified"));
    exit;
}

// Checks to see if the file that was supposed to be created exists
$pid = $_GET['pid'];
if(!file_exists("../data/$pid.txt")) {
    echo json_encode(array("response"=>false, "reason"=>"Invalid pid"));
    exit;
}

// Getting the board from the file
$fs = fopen("../data/$pid.txt", 'r');
$board = json_decode(fread($fs,filesize("../data/$pid.txt")));
fclose($fs);

if(!isset($_GET['move'])) {
    echo json_encode(array("response" => false, "reason" => "Move Not Specified"));
    exit;
}

$move = $_GET['move'];

// Checks if move is in range of board.
if($move<0 || $move>6) {
    echo json_encode(array("response" => false, "reason" => "Invalid slot"));
    exit;
}

// Updates the move made by human
updateBoard($move,1);

// Checks if the move made by human is a winning move
if(checkWin(1, $move)){
    echo json_encode(array("response" => true,
        "ack_move" => array("slot" => $move, "isWin" => true,
            "isDraw" => false, "row" => $winningRow)));
    exit;
}

// Sets what strategy we want to play against
$strat = getStrategy();

// Gets the slot the computer wants to place the piece on accordingly to the strategy
if($strat == "Smart") {
    $smart = new SmartStrategy();
    $computer = $smart->pickSlot($board);
}
else {
    $random = new RandomStrategy();
    $computer = $random->pickSlot($board);
}

// Updates the computers move
updateBoard($computer,2);

// Checks to see if the computers move is a winning move
if(checkWin(2, $computer)){
    echo json_encode(array("response" => true,
        "ack_move" => array("slot" => $move, "isWin" => false,
            "isDraw" => false, "row" => []),
        "move" => array("slot" => $computer, "isWin" => true,
            "isDraw" => false, "row" => $winningRow)));
    exit;
}

// Checks if the board is full and echos draw if it is
if(boardFull()) {
    echo json_encode(array("response" => true,
        "ack_move" => array("slot" => $move, "isWin" => false,
            "isDraw" => false, "row" => []),
        "move" => array("slot" => $computer, "isWin" => false,
            "isDraw" => true, "row" => [])));
    exit;
}

// Writes the new updated board on the original file
$fs = fopen("../data/$pid.txt", 'w');
fputs($fs, json_encode($board));
fclose($fs);

// echos the computers and humans turn
echo json_encode(array("response" => true,
    "ack_move" => array("slot" => $move, "isWin" => false,
        "isDraw" => false, "row" => []),
    "move" => array("slot" => $computer, "isWin" => false,
        "isDraw" => false, "row" => [])));
