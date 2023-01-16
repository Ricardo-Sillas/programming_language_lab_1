<?php
// Ricardo Sillas

// Holder for when a spot to block human was found
$block = -1;
$temp = [[]];

class SmartStrategy {

    public function pickSlot($board) {
        global $block;
        global $temp;
        // Checks if there is a winning move for either computer or human
        for($i=0; $i<7; $i++) {
            $temp = $board;
            $win = $this->isWin($i,2);
            // If computer has a winning move, then it would return that slot
            if($win >= 0) {
                return $win;
            }
            // Resets the temporary board
            $temp = $board;
            // If board has a winning move, it would be placed in the holder
            if($block == -1) {
                $block = $this->isWin($i, 1);
            }
        }
        // Once its gone through the board, if there was something to block, it would return that slot
        if($block >= 0) {
            return $block;
        }
        // If it has nothing to block, it would then start picking sposts randomly
        $slot = rand(0,6);
        // Checks the top most position of that column to see if there is space in that slot
        if($board[0][$slot] == 0) {
            return $slot;
        }
        // If the first choice was full, it would then move onto the other slots to see if theres a spot
        for($i=0; $i<6; $i++) {
            if($slot != 6) {
                $slot++;
            }
            else {
                $slot=0;
            }
            if($board[0][$slot] == 0) {
                return $slot;
            }
        }
        // If the whole board was full, then it would return -1
        return -1;
    }

    // Used to see if there is any positions that could be a winning spot
    public function isWin($slot, $player) {
        global $temp;
        // If that certain position was full, it would return -1
        if(fullCol($slot)) {
            return -1;
        }
        // It would update the temporary board that was created
        updateTemp($slot,$player);
        // It then checks if that piece that was put down was a winning spot, and would return if it is
        if(check($temp, $slot, $player)) {
            return $slot;
        }
        // Returns -1 if it wasn't a winning spot
        return -1;
    }
}

// Method used to update the temporary board
function updateTemp($slot, $player) {
    global $temp;
    // Checks from top to bottom to find out where to put the piece
    for ($i = 1; $i < 6; $i++) {
        if ($temp[$i][$slot] == 1 || $temp[$i][$slot] == 2) {
            $temp[$i - 1][$slot] = $player;
            break;
        } else if ($i == 5) {
            $temp[$i][$slot] = $player;
        }
    }
}

// Checks if the column that was given is full
function fullCol($slot) {
    global $temp;
    if($temp[0][$slot] == 0) {
        return false;
    }
    return true;
}

// Used to check if there were any winning spots
function check($board, $slot, $player) {
    $save = -1;
    // vertical
    for($i = 0; $i < 6; $i++) {
        if($board[$i][$slot] == $player) {
            if($i > 2) {
                break;
            }
            if($board[$i+1][$slot]==$player && $board[$i+2][$slot]==$player && $board[$i+3][$slot]==$player) {
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
            return true;
        }
    }
    // rising diagonal
    if($slot+$save > 2 && $slot+$save < 9) {
        if (($save + $slot) % 5 == 3) {
            if ($board[3][0] == $player && $board[2][1] == $player && $board[1][2] == $player && $board[0][3] == $player) {
                return true;
            }
            else if ($board[5][3] == $player && $board[4][4] == $player && $board[3][5] == $player && $board[2][6] == $player) {
                return true;
            }
        } else if ($save + $slot == 4) {
            if ($board[3][1] == $player && $board[2][2] == $player && $board[1][3] == $player) {
                if ($board[0][4] == $player) {
                    return true;
                } else if ($board[4][0] == $player) {
                    return true;
                }
            }
        } else if ($save + $slot == 7) {
            if ($board[4][3] == $player && $board[3][4] == $player && $board[2][5] == $player) {
                if ($board[5][2] == $player) {
                    return true;
                } else if ($board[1][6] == $player) {
                    return true;
                }
            }
        } else {
            if ($slot + $save == 5) {
                if ($board[3][2] == $player && $board[2][3] == $player) {
                    if ($board[1][4] != $player) {
                        if ($board[4][1] == $player && $board[5][0] == $player) {
                            return true;
                        }
                    }
                    else if($board[4][1] != $player) {
                        if($board[1][4] == $player && $board[0][5] == $player) {
                            return true;
                        }
                    }
                    else {
                        return true;
                    }
                }
            }
            if($slot+$save == 6) {
                if ($board[3][3] == $player && $board[2][4] == $player) {
                    if ($board[1][5] != $player) {
                        if ($board[4][2] == $player && $board[5][1] == $player) {
                            return true;
                        }
                    }
                    else if($board[4][2] != $player) {
                        if($board[1][5] == $player && $board[0][6] == $player) {
                            return true;
                        }
                    }
                    else {
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
                return true;
            }
            else if ($board[5][3] == $player && $board[4][2] == $player && $board[3][1] == $player && $board[2][0] == $player) {
                return true;
            }
        } else if ($save + $slot == 4) {
            if ($board[3][5] == $player && $board[2][4] == $player && $board[1][3] == $player) {
                if ($board[0][2] == $player) {
                    return true;
                } else if ($board[4][6] == $player) {
                    return true;
                }
            }
        } else if ($save + $slot == 7) {
            if ($board[4][3] == $player && $board[3][2] == $player && $board[2][1] == $player) {
                if ($board[5][4] == $player) {
                    return true;
                } else if ($board[1][0] == $player) {
                    return true;
                }
            }
        } else {
            if ($slot + $save == 5) {
                if ($board[3][4] == $player && $board[2][3] == $player) {
                    if ($board[1][2] != $player) {
                        if ($board[4][5] == $player && $board[5][6] == $player) {
                            return true;
                        }
                    }
                    else if($board[4][5] != $player) {
                        if($board[1][2] == $player && $board[0][1] == $player) {
                            return true;
                        }
                    }
                    else {
                        return true;
                    }
                }
            }
            if($slot+$save == 6) {
                if ($board[3][3] == $player && $board[2][2] == $player) {
                    if ($board[1][1] != $player) {
                        if ($board[4][4] == $player && $board[5][5] == $player) {
                            return true;
                        }
                    }
                    else if($board[4][4] != $player) {
                        if($board[1][1] == $player && $board[0][6] == $player) {
                            return true;
                        }
                    }
                    else {
                        return true;
                    }
                }
            }
        }
    }
    return false;
}