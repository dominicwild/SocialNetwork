<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 * @var \App\Model\Entity\Event $event
 */
use Cake\Routing\Router;

//https://www.jqueryscript.net/time-clock/Clean-Data-Timepicker-with-jQuery-Bootstrap-3.html
?>

<?= $this->Html->css("bootstrap-datetimepicker.min.css") ?>
<?= $this->Html->script("bootstrap-datetimepicker.min.js") ?>


    <script>
        $(function () {
            var date = new Date(<?php strtotime($this->Miscellaneous->toEditTimeFormat($event->date))?>);
            bsCustomFileInput.init()
            $('#date').datetimepicker({
                showClear: true,
                showClose: false,
                defaultDate: "<?=$this->Miscellaneous->toEditTimeFormat($event->date);?>"
            });
            $('#end_date').datetimepicker({
                showClear: true,
                showClose: false,
                minDate: new Date()
            });
        })
    </script>

<style>
    .event-edit-image{
        max-height: 200px;
    }
</style>


    <div class="row border-bottom border-gray mt-3">
        <div class="col-9">
            <h3>Add an Event</h3>
        </div>
    </div>
<?= $this->Form->create(false, ["class" => "justify-content-center text-left mt-3", "controller" => "Events", "action" => "edit", "enctype" => "multipart/form-data"]) ?>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Event Title:</label>
        <input type="text" value="<?= $event->title ?>" name="title" class="form-control col-10" required>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Group:</label>
        <?= $this->Form->select("group_id",$group_options,["class" => "form-control col-10"])?>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Place:</label>
        <input type="text" value="<?= $event->place ?>" name="place" class="form-control col-10" required>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Description:</label>
        <textarea name="content" class="form-control col-10" required><?= $event->post->content ?></textarea>
    </div>

    <div class="form-group row mb-3">
        <label class=" my-auto col-2">Date & Time:</label>
        <div class="col-10 p-0">
            <div class="input-group">
                <input type="text" name="date" id="date" class="form-control" required>
                <div class="input-group-append">
                    <span class="input-group-text">📅</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Event Image:</label>
        <div class="custom-file col-10">
            <input type="file" name="image" class="custom-file-input" id="customFile">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Current Image:</label>
        <img class="event-edit-image" src="<?=$event->image?>">
    </div>

    <button class="btn btn-primary" type="submit">Confirm Edits</button>

<input type="hidden" value="<?= $_GET["id"] ?>" name="id" >

<?= $this->Form->end() ?>