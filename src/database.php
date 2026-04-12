<?php

function db(): SQLite3
{
    static $db = null;

    if ($db === null) {
        $db = new SQLite3(__DIR__ . '/../database/rureview.sqlite');
        $db->enableExceptions(true);
        $db->exec('PRAGMA foreign_keys = ON');
    };

    return $db;
}
