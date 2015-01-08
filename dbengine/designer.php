<!--
/*
Script Name: Codiad Database Query Designer
Author: JSX.RED 
Author URI: http://www.jsx.red

Description: This plugin allow designing database queries through Codiad user interface.

Copyright (c) 2015 by JSX.RED 
distributed as-is and without warranty under the MIT License. See
[root]/license.txt for more. This information must remain intact.

Let us build a better future for all humanity.
Share knowledge. Share emotions. Share fun.
*/
-->
<?php

require_once('../../../common.php');
checkSession();
?>

<!doctype html>

<head>
    <meta charset="utf-8">
    <title>Database Query Designer</title>
    <?php
        $stylesheets = array("reset.css","fonts.css", "/screen.css", "project/screen.css");
        $theme = THEME;
        if(isset($_SESSION['theme'])) {
          $theme = $_SESSION['theme'];
        }
        foreach($stylesheets as $sheet){
            if(file_exists(THEMES . "/". $theme . "/".$sheet)){
                echo('<link rel="stylesheet" href="../../../themes/'.$theme.'/'.$sheet.'">');
            } else {
                echo('<link rel="stylesheet" href="../../../themes/default/'.$sheet.'">');
            }
        }

    ?>
    <link rel="stylesheet" href="css/screen.css">
</head>

<body>
    <div class="dqd-left">
        <div class="project-list-title">
        <h2>Database</h2>
        </div>
        <select id="connection">
            <option value="">Connection...</option>
        </select>
        <div class="dqd-spacer">
            <ul id="dbobjs">
            </ul>
        </div>
    </div>
    <div class="dqd-mid"></div>
    <div class="dqd-right">
         <div class="project-list-title">
        <h2>Query</h2>
        <a class="icon-alert icon" onclick="JSXRED.DQD.executeQuery(); return false;" alt="Execute"></a>
        </div>
        <div>
          <div id="editor" class="editor">SELECT 1</div>
          <textarea name="editor" class="editor">SELECT 1</textarea>
        </div>
        <div class="project-list-title">
        <h2>Result</h2>
        </div>
        <div class="dqd-result">
            <table class="dqd-tableresult" id="dqd-tableresult">
            </table>
        </div>
    </div>
    <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="js/jsx.dqd.js"></script>
    <script src="../../../components/editor/ace-editor/ace.js"></script>
</body>
</html>