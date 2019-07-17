
<?php if($group->user_in_group):?>
    <button type="button" class="btn btn-sm btn-outline-danger animated leave-btn">Leave</button>
<?php else: ?>
    <button type="button" class="btn btn-sm btn-outline-success animated join-btn">Join</button>
<?php endif; ?>