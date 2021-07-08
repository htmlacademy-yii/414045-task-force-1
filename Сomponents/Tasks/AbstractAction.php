<?php


namespace Components\Tasks;


abstract class AbstractAction
{
    abstract public function getAction();
    abstract public function getActionName();
    abstract public function authUser();
}