
<nav id="navTopNavBar" class="navbar"> 

    <div class="navbar-brand"><?=implode(" / ",($this->arrPageTitle ?: []));?></a></div>

</nav>

<div class="reflow-after-fixed-top" ></div>


<div id='divMainContent'>


<?php

//No menus etc


if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    print "<div class='divErrorMessage'>Invalid attempt to access a cbframe component</div>";
    exit;
}

if ($this->SREQUEST["error-message"]){
    print "<div class='divErrorMessage'>" . $this->SREQUEST["error-message"] . "</div>";
}
?>


