<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Controller\Component\AuthComponent $auth
 */
?>

<?php

$btn_status = "btn-primary active";
$btn_text = "Sign in with Google";
$btn_state = "";
$login_message = "Please sign in";
$nav_state = "disabled";

if ($auth->user() != null) {
    $btn_status = "btn-success disabled";
    $btn_text = "You are signed in with Google";
    $btn_state = "disabled";
    $login_message = "You are logged in";
}

?>

<script>
    $(".nav-login").addClass("active");
</script>

<div class="text-center">
    <?=$this->Form->create(false,["class" => "form-signin", "style" => " 
    width: 50%;
    margin: auto;
"]);?>
        <img class="mb-4" src="/img/yordas-logo.png" alt="">
        <h1 class="h3 mb-3 font-weight-normal"><?= $login_message ?></h1>
        <?= $this->Form->hidden("GoogleLogin", ["value" => $gClient->createAuthUrl()]); ?>
        <button class="btn btn-lg btn-block <?= $btn_status ?>" <?= $btn_state ?> type="submit"><?= $btn_text ?></button>
    <?= $this->Form->end(); ?>
</div>
