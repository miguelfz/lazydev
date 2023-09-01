<?php

namespace Lazydev\Core;

class DebugMsg
{

    function __construct($msg, $critical = 0)
    {
        $msgHtml = '<li class="list-group-item bg-danger text-white">' . $msg . '</li>';
        if (!$critical) {
            $msgHtml = '<li class="list-group-item bg-light">' . $msg . '</li>';
        }
        $msgarr = is_array(Session::get('frameworkDebugMsg')) ? Session::get('frameworkDebugMsg') : [];
        array_push($msgarr, $msgHtml);
        Session::set('frameworkDebugMsg', $msgarr);
    }

    public static function getMsg()
    {
        $msgarr = Session::get('frameworkDebugMsg');
        Session::set('frameworkDebugMsg', []);
        if (is_array($msgarr)) {
            return implode("\r\n", $msgarr);
        }
        return NULL;
    }
}
