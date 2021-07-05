<?php
require_once '../vendor/autoload.php';

use Clicars\Models\Mafia;
use Clicars\Models\Member;



$id = 1;

// Define godfather
$boss1 = new Member(1, 80);
var_dump("BOSS ID:".$boss1->getId());
$mafia = new Mafia($boss1);

$boss2 = $mafia->addMember((new Member(2, 74))->setBoss($boss1));
var_dump("ID BOSS2:".$boss2->getId());

$mafia->addMember((new Member(3, 70))->setBoss($boss1));
$boss4 = $mafia->addMember((new Member(4, 73))->setBoss($boss1));
$boss5 = $mafia->addMember((new Member(5, 68))->setBoss($boss2));

var_dump("ID BOSS5:".$boss5->getId());

$mafia->addMember((new Member(6, 52))->setBoss($boss5));
$mafia->addMember((new Member(7, 64))->setBoss($boss2));
$mafia->addMember((new Member(8, 63))->setBoss($boss2));
$mafia->addMember((new Member(9, 65))->setBoss($boss2));


