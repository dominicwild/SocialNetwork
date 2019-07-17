<script>

    function newOption(){
        if($(this).val() !== ""){
            var optionNum = +$(this).attr("data-option") + 1;
            var input = document.createElement("input");
            var optionId = "option" + optionNum;
            $(input).attr("id",optionId).attr("name", optionId).attr("placeholder", "Option " + optionNum).attr("data-option", optionNum);
            $(input).addClass("form-control poll-option-input");
            $(this).off("input",newOption);
            $(input).on("input", newOption);
            $(".poll-option-input").last().after(input);
        }
    }

    function resetPoll(){
        $(".poll-error").css("display","none");
        $(".poll-question").val("");
        var optionGroup = $(".poll-options-group");
        var defaultOptions = $(optionGroup).children().slice(0,2);
        $(defaultOptions).each(function(index,element){
            $(element).val("");
        });
        $("#date").val("");
        $(optionGroup).html(defaultOptions);
        $("#option2").on("input", newOption);
    }

    $(function(){
        $("#option2").on("input", newOption);

        if($('#date').length != 0) {
            bsCustomFileInput.init();
            $('#date').datetimepicker({
                showClear: true,
                showClose: false,
                useCurrent: false,
                minDate: new Date()
            });
        }
    })

</script>

<div class="col-12 cloneable poll-add-option-input" id="poll-add-option-input-clone">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Option">
        <div class="input-group-append">
            <button class="btn btn-primary poll-btn-option-add p-0 px-2" type="button">
                <img src="/img/plus.svg">
            </button>
        </div>
    </div>
</div>