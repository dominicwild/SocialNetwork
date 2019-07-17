<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
use Cake\Routing\Router;
use App\Model\Entity\User;
?>

<?php
/**
 * @var \Cake\View\View $this
 */

$pathToEmojiArea = "./../../vendor/mervick/emojionearea/dist/";

$home_link = "#";
$event_link = "#";
$profile_link = "#";
$profile_image = "#";
$options_link = "#";
$logout_link = "#";
$group_link = "#";
$faq_link = "#";
$admin_link = "#";
$session = $this->getRequest()->getSession();

if(isset($_SESSION["Auth"])){
    $home_link = Router::url(['controller' => 'Posts', 'action' => 'home']);
    $event_link = Router::url(['controller' => 'Events', 'action' => 'index']);
    $profile_link = Router::url(['controller' => 'Users', 'action' => 'profile', "id" => $session->read("Auth.User.id")]);//$_SESSION["Auth"]["User"]["id"]]);
    $profile_image = $this->Miscellaneous->getImage($logged_user["profile_image"]);
    $options_link = Router::url(['controller' => 'Users', 'action' => 'options']);
    $logout_link = Router::url(['controller' => 'Users', 'action' => 'logout']);
    $group_link = Router::url(["controller" => "Groups", "action" => "index"]);
    $faq_link = Router::url(["controller" => "Pages", "action" => "faq"]);
    $admin_link = Router::url(["controller" => "Users", "action" => "admin"]);
}
$login_link = Router::url(['controller' => 'Users', 'action' => 'login']);

?>

<!DOCTYPE html>
<html>
<head>

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?php $this->Html->css('base.css') ?>
    <?php $this->Html->css('style.css') ?>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('default.css') ?>
    <?= $this->Html->css('toastr.min.css') ?>
    <?= $this->Html->css('emojione.min.css') ?>
    <?= $this->Html->css('emojionearea.min.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <?= $this->Html->script("jquery-3.3.1.min.js") ?>
    <?= $this->Html->script("helper.js"); ?>
    <?= $this->Html->script("popper.min.js") ?>
    <?= $this->Html->script("bootstrap.min.js") ?>
    <?= $this->Html->script("bs-custom-file-input.min.js") ?>
    <?= $this->Html->script("jQueryRotate.js") ?>
    <?= $this->Html->script("toastr.min.js") ?>
    <?= $this->Html->script("svg-injector.min.js") ?>
    <?php // $this->Html->script("emojione.js") ?>
    <?= $this->Html->script("emojionearea.js") ?>
    <?= $this->Html->script("ftellipsis.min.js") ?>
    <script>window.emojioneVersion = '4.5';</script>

<!--DataTable-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
<!--Data Table-->
    <script>
        var csrfToken = "<?php echo $_COOKIE["csrfToken"] ?>";
        $(function(){
            $('[data-toggle="tooltip"]').tooltip() //Enable all tooltips
        });

        //Options for toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

    </script>
    <?= $this->element("search-js")?>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>


    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="738449930784-k0olilku4bht6om9ftkmcdrhco8fr10r.apps.googleusercontent.com">
    <?= $this->Html->script("platform.js") ?>

    <?php if(!isset($_SESSION["Auth"])): ?>
        <script>

            $(function(){
                $(".events").addClass("disabled");
                $(".profile-link").addClass("disabled");
                $(".home").addClass("disabled");
                $(".options").addClass("disabled");
            })

        </script>
    <?php endif;?>

    <?php
        $default = "container";
        if(isset($customer_container)){
            $default = $customer_container;
        }
    ?>

</head>
<body>

<div class="post-card-body" style="display:none;">

</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark pl-2 py-2">
    <a class="navbar-brand py-0" href="<?= $profile_link ?>">
        <img class="bd-placeholder-img p-0 rounded-circle" width="32px" height="64px" src="<?= $this->Miscellaneous->getImage($profile_image) ?>"  focusable="false" role="img" style="height: 40px; width: 40px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link nav-left nav-home" href="<?= $home_link ?>">Home</a>
            <a class="nav-item nav-link nav-left nav-profile-link" href="<?= $profile_link ?>">Profile</a>
            <a class="nav-item nav-link nav-left nav-groups" href="<?= $group_link ?>">Groups</a>
            <a class="nav-item nav-link nav-left nav-events" href="<?= $event_link ?>">Events</a>
            <a class="nav-item nav-link nav-left nav-options" href="<?= $options_link ?>">Options</a>
        </div>
        <div class="navbar-nav ml-auto">
            <form class="form-inline" id="search" action="<?= Router::url(["controller" => "Posts", "action" => "search"])?>">
                <input type="text" name="search" class="form-control mx-sm-3" placeholder="Search...">
                <button class="btn search-btn"><img class="magnifying-glass" src="/img/magnifying-glass.svg"></button>
            </form>
            <?php if($this->getRequest()->getSession()->read("Auth.User.Permissions") == 100): ?>
            <a class="nav-item nav-link nav-admin" href="<?= $admin_link ?>">Admin</a>
            <?php endif; ?>
            <a class="nav-item nav-link nav-faq" href="<?= $faq_link ?>">FAQ</a>
            <?php if($logged_in): ?>
                <a class="nav-item nav-link nav-logout" href="<?= $logout_link ?>">Logout</a>
            <?php else: ?>
                <script>
                    $(".nav-left").addClass("disabled");
                </script>
                <a class="nav-item nav-link nav-login" href="<?= $login_link ?>">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?= $this->Flash->render() ?>


<div class="container content-container clearfix">
    <?= $this->fetch('content') ?>
</div>



<footer style="height:100px">
</footer>

</body>
</html>
