<?php
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

require_once('../../common.php');
checkSession();

$file="designer";
$height="500";
$width="100%";

?>
<style>#terminal { border: 1px solid #2b2b2b; }</style>
<iframe id="jsxdqd" width="<?php echo $width;?>" height="<?php echo $height;?>" src="<?php if($file=="designer")echo str_replace(BASE_PATH."/","",str_replace("dialog.php", "", $_SERVER['SCRIPT_FILENAME']))."dbengine/"; ?><?php echo $file ?>.php"></iframe>
<?php if($file == "designer"){ ?>
<button onclick="document.getElementById('jsxdqd').contentWindow.JSXRED.DQD.executeQuery(); return false;">Excute Query</button>
<button onclick="codiad.modal.unload(); return false;">Close</button>

<script>
    $(function(){ 
        var wheight = $(window).outerHeight() * 0.5;
        $('#jsxdqd').css('height',wheight+'px');
    });
</script>
<?php } ?>