<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;
?>

<?php echo $this->Html->css("sidebar.css"); ?>

<script>
    $(function(){
        SVGInjector($("img"));
    });

</script>

<?php

$user_list = Router::url(["controller" => "Users", "action" => "admin"]);
$reports = Router::url(["controller" => "ReportedPosts", "action" => "view"]);

$url = $this->getRequest()->getRequestTarget();

$active = ["",""];
switch($url){
    case $user_list:
        $active[0] = "active";
        break;
    case $reports:
        $active[1] = "active";
        break;
}

?>

<nav class="col-2 d-none d-md-block sidebar pl-0 pr-4">
    <div class="menu-sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $active[0] ?>" href="<?= $user_list ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    User List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active[1] ?>" href="<?= $reports ?>">
                    <img class="sidebar-icon" src="/img/flag.svg">
                    Reports
                </a>
            </li>
        </ul>
    </div>
</nav>