<?php

/**
 * @var \Cake\View\View $this
 */
use Cake\Routing\Router;



//$_SESSION = [];
//session_destroy();
$this->getRequest()->getSession()->destroy();
if (isset($_COOKIE['access_token'])) {
    unset($_COOKIE['access_token']);
    setcookie('access_token', null, 1, "/"); // empty value and old timestamp
}
?>
<script>
    $(".nav-logout").addClass("active");
</script>
<div class="col-12 text-center mt-3">
    <h1 class="text-muted"><i>You have been logged out.</i></h1>
</div>