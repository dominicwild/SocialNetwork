
<div class="poll-container">
    <form class="form-group p-2 poll-form mb-1" type="post">
        <i class="poll-error poll-question-error" style="display:none;">You haven't entered a poll question</i>
        <div class="form-label-group">
            <input type="text" name="question" id="pollQuestion" class="form-control poll-question" placeholder="Poll Question">
            <label class="poll-question-label" for="pollQuestion">Poll Question</label>
        </div>
        <div class="form-label-group">
            <input type="text" name="expires" id="date" class="form-control" placeholder="Expire Date">
            <label class="poll-question-label" for="pollQuestion">Expire Date (Optional)</label>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="user_add_options" class="custom-control-input" id="user_add_options">
                <label class="custom-control-label poll-labels" for="user_add_options">Allow users to submit options</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="redo" class="custom-control-input" id="redo">
                <label class="custom-control-label poll-labels" for="redo">Allow users to redo their vote</label>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="multi" name="multi" value=1 class="custom-control-input" checked>
                <label class="custom-control-label poll-labels" for="multi">Multiple Choice</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="single" name="multi" value=0 class="custom-control-input">
                <label class="custom-control-label poll-labels" for="single">Single Choice</label>
            </div>
        </div>
        <i class="poll-error poll-option-error" style="display:none;">You haven't entered any poll options</i>
        <div class="form-group poll-options-group mb-0">
            <input type="text" id="option1" name="option1" class="form-control poll-option-input" placeholder="Option 1" data-option=1>
            <input type="text" id="option2" name="option2" class="form-control poll-option-input" placeholder="Option 2" data-option=2>
        </div>
    </form>
</div>