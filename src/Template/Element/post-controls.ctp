<style>

    .form-label-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .form-label-group > input,
    .form-label-group > label {
        height: 3.125rem;
        padding: .75rem;
    }

    .form-label-group > label {
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        width: 100%;
        margin-bottom: 0; /* Override default `<label>` margin */
        line-height: 1.5;
        color: #495057;
        pointer-events: none;
        cursor: text; /* Match the input under the label */
        border: 1px solid transparent;
        border-radius: .25rem;
        transition: all .1s ease-in-out;
    }

    .form-label-group input::-webkit-input-placeholder {
        color: transparent;
    }

    .form-label-group input:-ms-input-placeholder {
        color: transparent;
    }

    .form-label-group input::-ms-input-placeholder {
        color: transparent;
    }

    .form-label-group input::-moz-placeholder {
        color: transparent;
    }

    .form-label-group input::placeholder {
        color: transparent;
    }

    .form-label-group input:not(:placeholder-shown) {
        padding-top: 1.25rem;
        padding-bottom: .25rem;
    }

    .form-label-group input:not(:placeholder-shown) ~ label {
        padding-top: .25rem;
        padding-bottom: .25rem;
        font-size: 12px;
        color: #777;
    }

</style>

<?= $this->Html->css("bootstrap-datetimepicker.min.css")?>
<?= $this->Html->script("bootstrap-datetimepicker.min.js")?>

<script>

</script>

<div class="card mt-3 postContainer">
    <div class="card-header pr-0 pl-2 pt-2 pb-1">
        <div class="media small">
            <img class="bd-placeholder-img mr-2 rounded-circle" width="32px" height="64px" src="<?= $this->Miscellaneous->getImage($user["profile_image"]) ?>"focusable="false" role="img" style="height: 54px; width: 54px;">
            <span class="media-body mb-0">
                <div class="row m-0 p-1 pr-2">
                    <div class="form-group m-0 p-0 col-12">
                        <textarea class="form-control postArea" placeholder="What did you do today?" rows="1"style="height: 48px; overflow-y: hidden;"></textarea>
                    </div>
                </div>
                <div class="postControls" style="display:none;">

                    <div class="row m-0 p-1 pr-2 d-flex">
                        <div class="col-12 p-0 post-btns">
                            <button class="btn btn-primary px-1 p-0 float-left post-control-btn upload-img-btn" title="Add Images" type="button">
                                <img src="/img/si-glyph-image.svg">
                            </button>

                            <button class="btn btn-primary px-1 p-0 float-left post-control-btn poll-img-btn" title="Create Poll" type="button">
                                <img src="/img/si-glyph-bullet-checked-list.svg">
                            </button>


                            <button class="btn btn-primary postBtn" type="button">Post</button>

                        </div>
<!--                        <div class="col-2 align-items ml-auto pr-0">-->
<!--                            <button class="btn btn-primary postBtn" type="button">Post</button>-->
<!--                        </div>-->
                    </div>

                    <div class="row m-0 p-0">
                         <div class="col-10 p-0 align-items-center">
                            <label class="text-center drop-unhightlight " id="drop-area" for="fileInput" style="display:none;">
                                <input type="file" id="fileInput" accept="image/*" name="image" multiple>
                                <div class="my-2" id="drop-text">
                                    Add Image
                                    <div>
                                        <img src="/img/si-glyph-file-download.svg" style="height:50px">
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="row m-0 p-1 pr-2 poll-controls" style="display:none;">
                        <div class="col-10 p-0">
                            <?= $this->element("poll-controls"); ?>
                        </div>
                    </div>
                </div>
            </span>
        </div>
    </div>
</div>
