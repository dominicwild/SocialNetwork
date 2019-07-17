<?php
use Cake\Routing\Router;
?>
<?php if(isset($user)): ?>
    <a href="<?= Router::url(["controller" => "Users", "action" => "profile", "id" => $user->id])?>"><img class="col-3 p-0 mt-1 rounded-circle" src="<?= $this->Miscellaneous->getImage($user->profile_image) ?>"  style="height: 64px;width: 64px;"></a>
<?php else: ?>
    <img class="col-3 p-0 mt-1 rounded-circle" src="<?= $this->Miscellaneous->getImage("The default Image") ?>"  style="height: 64px;width: 64px;">
<?php endif; ?>
