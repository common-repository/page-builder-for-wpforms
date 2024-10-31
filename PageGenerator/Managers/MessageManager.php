<?php

namespace rnpagebuilder\PageGenerator\Managers;

class MessageManager
{

    public static function ShowErrorMessage($message)
    {

        return "<style>.messageManager p{margin:0}</style><div class='messageManager' style='padding:15px;background-color: #f8d7da;border-color:#f5c6cb;color:#721c24;border-radius: 5px' >".$message."</div>";
    }

    public static function ShowSuccessMessage($message)
    {

        return "<style>.messageManager p{margin:0}</style><div class='messageManager' style='padding:15px;background-color: #d4edda;border-color:#c3e6cb;color:#155724;border-radius: 5px' >".$message."</div>";
    }
}