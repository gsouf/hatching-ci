<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */


// RUN TEST COMMAND

$command = new \CliStart\CommandDeclaration("runtest","Command\RunTestCommand","run");
$command->setMaxInstances(0);
$cli->registerCommand($command);

$arg = new \CliStart\Argument("project-name");
$arg->setRequired(false);
$command->addArg($arg);

$arg = new \CliStart\Argument("project-id");
$arg->setRequired(false);
$command->addArg($arg);






$command = new \CliStart\CommandDeclaration("test","Command\ProjectCommand","test");
$command->setMaxInstances(0);
$cli->registerCommand($command);


$command = new \CliStart\CommandDeclaration("devtest","Command\ProjectCommand","devtest");
$command->setMaxInstances(0);
$cli->registerCommand($command);







// CREATE PROJECT COMMAND

$command = new \CliStart\CommandDeclaration("create-project","Command\ProjectCommand","createProject");
$command->setMaxInstances(0);
$cli->registerCommand($command);

$arg = new \CliStart\Argument("project-name");
$arg->setRequired();
$command->addArg($arg);

$arg = new \CliStart\Argument("project-location");
$arg->setRequired();
$command->addArg($arg);
