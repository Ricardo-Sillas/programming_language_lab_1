<?php

include 'ConsoleUI.php';
include 'WebClient.php';
include 'RandomStrategy.php';
include 'SmartStrategy.php';
include 'board.php';

$board = array(
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0),
    array(0, 0, 0, 0, 0, 0, 0)
);

$ConsoleUI = new ConsoleUI();
$WebClient = new WebClient();
$url = $ConsoleUI->promptServer("https://cssrvlab01.utep.edu/Classes/cs3360/rrsillas/info");
$info = $WebClient->getInfo($url);
while(true) {
    $selection = $ConsoleUI->promptStrategy($info);
    $strat = $ConsoleUI->showMessage($selection);
    if($strat == 0) {
        $strat = new SmartStrategy();
        break;
    }
    elseif($strat == 1) {
        $strat = new RandomStrategy();
        break;
    }
}
while(true) {
    $move = $ConsoleUI->promptMove();
    while ($move < 1 || $move > 7) {
        print("Invalid Selection: $move");
        $move = $ConsoleUI->promptMove();
    }
    $move --;
    updateBoard($move, 1);
    if(checkWin(1, $move)) {
        printBoard($board, true);
        print("User wins");
        exit;
    }
    $comp = $strat->pickSlot($board);
    updateBoard($comp, 2);
    if (checkWin(2, $comp)) {
        printBoard($board, true);
        print("Computer wins");
        exit;
    }
    if(boardFull()) {
        print("Board is full");
        exit;
    }
    printBoard($board);
}