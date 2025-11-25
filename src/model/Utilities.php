<?php
    function debugBar()
    {
        echo '<div style="padding:10px;">';
        echo '<hr><h3>Barre de débogage</h3><hr>';

        echo '<h4>session_id() ou SID</h4>';
        var_dump(session_id());

        echo '<h4>$_GET</h4>';
        var_dump($_GET);

        echo '<h4>$_SESSION</h4>';
        var_dump($_SESSION);

        echo '<h4>$_POST</h4>';
        var_dump($_POST);

        echo '</div>';
    }
