<div class="wrap">
    <h1><?php echo $this->getPageTitle() ?></h1>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-1">Activate providers</a></li>
        <li><a href="#tab-3">About</a></li>
    </ul>
    <div class="tab-content">
        <?php
        require_once('tab-one.php');
        require_once('tab-three.php');
        ?>
    </div>
</div>
