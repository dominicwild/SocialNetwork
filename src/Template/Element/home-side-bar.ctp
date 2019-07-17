<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
use Cake\Routing\Router;
?>

<?php echo $this->Html->css("sidebar.css"); ?>


<?php

$home = Router::url(["controller" => "Posts", "action" => "home"]);
$my_post = Router::url(["controller" => "Posts", "action" => "userPosts", "id" => $user->id]);
$user_list = Router::url(["controller" => "Users", "action" => "userList"]);
$my_groups = Router::url(["controller" => "Groups", "action" => "userGroups", "id" => $user->id]);
$url = "/social" . $this->getRequest()->getRequestTarget();
//if($pos = strpos($url,"?")){
//    $len = strlen($url);
//    $url = substr($url,0,-($len - $pos));
//}
$active = ["","","",""];
switch($url){
    case $home:
        $active[0] = "active";
        break;
    case $my_post:
        $active[1] = "active";
        break;
    case $user_list:
        $active[2] = "active";
        break;
    case $my_groups:
        $active[3] = "active";
        break;
}

?>

<nav class="col-2 d-none d-md-block sidebar pl-0 pr-0">
    <div class="menu-sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link outline <?= $active[0] ?>" href="<?= $home ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link outline <?= $active[1] ?>" href="<?= $my_post ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                    My Posts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link outline <?= $active[3] ?>" href="<?= $my_groups ?>">
                    <svg viewBox="0 0 17 17" version="1.1" class="si-glyph si-glyph-share-3 sidebar-icon" xmlns="http://www.w3.org/2000/svg"><path d="M14.477,1.042 C13.102,1.042 12.007,2.104 12.007,3.484 C12.007,3.839 12.115,4.445 12.25,4.75 L9.458,7.583 L6.687,4.895 C6.848,4.564 6.991,3.961 6.991,3.567 C6.991,2.187 5.893,1.031 4.517,1.031 C3.144,1.031 2.012,2.187 2.012,3.567 C2.012,4.948 3.115,5.994 4.49,5.994 C4.985,5.994 5.445,5.844 5.832,5.594 L9.007,8.737 L9.007,12.032 C7.986,12.344 7,13.326 7,14.454 C7,15.835 8.114,17.001 9.49,17.001 C10.864,17.001 11.978,15.835 11.978,14.454 C11.978,13.327 10.997,12.346 9.976,12.032 L9.976,8.675 L13.14,5.594 L13.117,5.568 C13.513,5.832 13.986,5.987 14.496,5.987 C15.869,5.987 16.984,4.867 16.984,3.486 C16.984,2.104 15.852,1.042 14.477,1.042 L14.477,1.042 Z M4.486,5.023 C3.65,5.023 2.972,4.339 2.972,3.497 C2.972,2.655 3.65,1.971 4.486,1.971 C5.321,1.971 5.999,2.655 5.999,3.497 C5.999,4.339 5.321,5.023 4.486,5.023 L4.486,5.023 Z M11.04,14.516 C11.04,15.37 10.348,16.063 9.492,16.063 C8.635,16.063 7.943,15.371 7.943,14.516 C7.943,13.661 8.634,12.969 9.492,12.969 C10.348,12.969 11.04,13.661 11.04,14.516 L11.04,14.516 Z M14.496,5.04 C13.649,5.04 12.963,4.334 12.963,3.461 C12.963,2.592 13.65,1.884 14.496,1.884 C15.342,1.884 16.03,2.592 16.03,3.461 C16.03,4.334 15.342,5.04 14.496,5.04 L14.496,5.04 Z" fill="#434343" class="si-glyph-fill"></path></g></svg>
                    My Groups
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link outline <?= $active[2] ?>" href="<?= $user_list ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    User List
                </a>
            </li>
        </ul>
    </div>
</nav>