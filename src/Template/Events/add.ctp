<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Group $group
 */
use Cake\Routing\Router;

//
//'_method' => 'POST',
//	'_csrfToken' => '0a063f834b064aea28535a9b4aaaa0653a95b98b01030e136dea18c8e0bf9f1c5544ea9f079d67193301c06943e1532623b74d83156a204c60edf7ef2709bb7f',
//	'title' => 'asdasd',
//	'group_id' => '29',
//	'place' => 'adas',
//	'content' => 'dasdas',
//	'date' => '04/26/2019 4:51 AM',
//	'end_date' => '04/19/2019 4:51 AM'


if(!isset($edit)){
    $edit = false;
}

$title = "";
$content = "";
$group_id = 0;
$place = "";
$date = "";
$end_date = "";
if($edit) {
    $id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];
}

if($_POST != []){
    $title = h($_POST["title"]);
    $content = $_POST["content"];
    $group_id = h($_POST["group_id"]);
    $place = h($_POST["place"]);
    $date = h($_POST["date"]);
    $end_date = h($_POST["end_date"]);
}

if($edit){
    $page_title = "Edit Event";
    $btn_text = "Edit Event";
    $action = "edit";
} else {
    $page_title = "Add Event";
    $btn_text = "Create Event";
    $action = "add";
}

?>


<?= $this->Html->css("event-add.css")?>
<?= $this->Html->css("bootstrap-datetimepicker.min.css")?>
<?= $this->Html->script("bootstrap-datetimepicker.min.js")?>

    <script>

        function showEndDate(e){
            $(".end-date-time").css("display","");
            $(".end-date-link").css("display","none");
            $(".start-date-time").removeClass("mb-0").addClass("mb-3");
        }


        $(function () {
            bsCustomFileInput.init();
            $('#date').datetimepicker({
                showClear: true,
                showClose: false,
                minDate: new Date()
            });
            $('#end_date').datetimepicker({
                showClear: true,
                showClose: false,
                minDate: new Date()
            });

            $(".description textarea").emojioneArea({
                pickerPosition: "right",
                autocomplete: "on"
            })[0].emojioneArea.setText(<?= json_encode($content) ?>);

            $('#date').val("<?= $date ?>");
            $('#end_date').val("<?= $end_date ?>");

            <?php if(!($end_date==="")): ?>
                showEndDate();
            <?php endif; ?>

            $(".end-date-link").on("click", showEndDate);


        })
    </script>

    <div class="row border-bottom border-gray mt-3">
        <div class="col-9">
            <h3><?= $page_title ?></h3>
        </div>
    </div>
<?= $this->Form->create(false, ["class" => "justify-content-center text-left mt-3", "controller" => "Events", "action" => $action, "enctype" => "multipart/form-data"]) ?>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Event Title:</label>
        <input type="text" value="<?= $title ?>" name="title" class="form-control col-10" required>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Group:</label>
        <?= $this->Form->select("group_id",$group_options,["class" => "form-control col-10", "default" => $group_id])?>
    </div>

    <div class="form-group row mb-3">
        <label class="col-2 my-auto">Place:</label>
        <input type="text" value="<?= $place ?>"name="place" class="form-control col-10" required>
    </div>

    <div class="form-group row mb-3 description">
        <label class="col-2 my-auto">Description:</label>
        <textarea name="content" class="form-control col-10" required><?= h($content) ?></textarea>
    </div>

    <div class="form-group row mb-0 start-date-time">
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

    <div class="form-group row mb-3 m-0 p-0 end-date-link">
        <div class=" my-auto col-2"></div>
        <div class="col-10 p-0 ">
            <span>+</span> <small> Add End Date</small>
        </div>
    </div>

    <div class="form-group row mb-3 end-date-time" style="display:none;">
        <label class=" my-auto col-2">End Date & Time:</label>
        <div class="col-10 p-0">
            <div class="input-group">
                <input type="text" name="end_date" id="end_date" class="form-control">
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

    <button class="btn btn-primary" type="submit"><?= $btn_text ?></button>

<?php if($edit):?>
    <input type="hidden" value="<?= $id ?>" name="id">
<?php endif; ?>

<?= $this->Form->end() ?>