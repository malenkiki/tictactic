tictactic
=========

A simple timer for multiple things at once…

Quick example of use:

``` php

$t = \Malenki\TicTacTic::getInstance();

$t->start('foo');
sleep(4);
$t->finish('foo');

echo "\n";
echo $t->get('foo');
echo "\n";

``` 

Full example (avaible into source files too):

``` php

include('src/Malenki/TicTacTic.php');

$t = \Malenki\TicTacTic::getInstance();

$t->start('foo');
echo "\n";
echo $t->done('foo') ? 'FOO done' : 'FOO is running';
echo "\n";
sleep(3);

$t->finish('foo');
//$t->finish('thing'); // if uncommented, should throw exception

echo "\n";
echo $t->done('foo') ? 'FOO done' : 'FOO is running';
echo "\n";


$t->start('bar');

sleep(2);

$t->finish('bar');


$t->start('something');

sleep(1);

$t->finish('something');



echo "\n";
printf('%d timers:', count($t));
echo "\n";
echo $t->get('foo');
echo "\n";
echo $t->get('bar');
echo "\n";
echo $t->get('something');
echo "\n";
printf('Averages:', count($t));
echo "\n";
echo $t->average();
echo "\n";
echo $t->average(array('foo', 'something')); // for two of them only
echo "\n";
echo "\n";

``` 

And _voilà_! Do you need a full doc for that? ;-)
