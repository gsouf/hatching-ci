<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */

$command = new \CliStart\CommandDeclaration("runtest","Command\RunTestCommand","run");
$command->setMaxInstances(0);
$cli->registerCommand($command);

$arg = new \CliStart\Argument("project-name");
$arg->setRequired();

$command->addArg($arg);