<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var \App\Model\Entity\Group $group
 * @var MiscellaneousHelper $Miscellaneous
 */
use App\View\Helper\MiscellaneousHelper;
use Cake\Routing\Router;
?>

<?= $this->Html->css("poll.css"); ?>

<?= $this->element("poll-controls-js") ?>
<?= $this->element("poll-js"); ?>

<script>

    var slideTime = 400;
    var fadeTime = 500;
    var canPressLoadMore = true;
    var postBodyLimit;
    var dropArea;
    var commentAreaEmojiOneOptions = {
        pickerPosition: "right",
        autocomplete: "on",
        shortnames: true,
        events : {
            keydown: submitCommentEmoji
        }
    };

    var images = new Array();

    function linkText(index, commentContent){
        var text = $(commentContent).html();
        text = text.replace(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/gm,'<a href="$&">$&</a>').replace(/([\w\d\.]+\@[\w\d\.]+(\/)?)/gm,'<a href="mailto:$1$>$1</a>');
        $(commentContent).html(text);
    }

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function decodeEntities(encodedString) {
        var div = document.createElement('div');
        div.innerHTML = encodedString;
        return div.textContent;
    }

    function handleDrop(e) {
        let dt = e.dataTransfer;
        let files = dt.files;

        handleFiles(files)
    }

    function handleFiles(files){
        files = [...files];
        console.log(files);

        files.forEach(function(file){
            console.log(file);
            for(let i in images){
                let image = images[i];
                if(image.name === file.name && image.size === file.size){ //Probably the same file
                    toastr["warning"]("Duplicate image submitted.");
                    return;
                }
            }
            let ext = file.type.split("/")[0];
            console.log(file.type.split("/"));
            if(ext !== "image"){
                toastr["warning"]("A file was submitted that wasn't an image.");
                return;
            }
            images.push(file);
            previewFile(file);
        });
        if(images.length > 0) {
            $("#drop-text").css("display", "none");
        }
    }

    function highlight(e){
        $(this).removeClass("drop-unhightlight");
        $(this).addClass("drop-hightlight");
    }

    function removeHighlight(e){
        $(this).removeClass("drop-hightlight");
        $(this).addClass("drop-unhightlight");
    }

    function previewFile(file) {
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function() {
            let div = document.createElement("div");
            let img = document.createElement("img");
            let cancel = document.createElement("img");
            $(cancel).attr("src","<?= $this->Miscellaneous->getImage("/img/close-button.svg")?>");
            $(cancel).addClass("image-cancel animated");
            $(div).addClass("preview-image-container");
            $(div).attr("data-name",file.name).attr("data-size",file.size);
            $(img).addClass("preview-image");
            img.src = reader.result;
            div.appendChild(cancel);
            div.appendChild(img);
            $(div).fadeOut(10,function () {
                document.getElementById("drop-area").appendChild(div);
                $(div).fadeIn(1000);
            });
        }
    }

    function showPostControls(element, event){
        $(element).closest(".card").find(".postControls").slideDown(slideTime);
    }

    function hiddenDiv(data){ //Creates div with display none encasing "data"
        return $(document.createElement("div")).html(data).css("display", "none")
    }

    function textAreaCss(textArea = this){
        $(textArea).css("height", this.scrollHeight + "px");
        $(textArea).css("overflow-y", "hidden");
    }

    function textAreaAutoExtend(limit){
        return function() {
            if (this.scrollHeight < limit) {
                $(this).css("overflow-y", "hidden");
                $(this).css("height", "auto");
                $(this).css("height", this.scrollHeight);
            } else {
                $(this).css("overflow-y", "scroll");
            }
        }
    }

    function resetDropArea(){
        $("#drop-text").css("display","");
    }

    function submitComment(e) {
        //Post comment by pressing enter in comment box
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            var textArea = $(this);
            var postId = textArea.closest(".post").attr("data-post-id");
            var content = textArea.val();

            if (!(content === "")) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Comments', 'action' => 'add']); ?>",
                    type: "post",
                    data: {postId: postId, content: content},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        var div = $(document.createElement("div")).html(data).css("display", "none").attr("class", "loadedUserComment");
                        textArea.closest(".card").find("ul").last("li").append(div);
                        textArea.val("");
                        textArea.css("height", "auto");
                        textArea.css("overflow-y", "hidden");
                        $(div).slideDown(slideTime);
                    }
                });
            }
        }
    }

    function submitCommentEmoji(editor, e){
        //Post comment by pressing enter in comment box
        console.log("called " + e.which + " " + editor);
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            console.log(this.getText());
            var emojiArea = this;
            var textArea = $(editor);
            var postId = $(editor).closest(".post").attr("data-post-id");
            var content = emojiArea.getText();

            if (!(content === "")) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Comments', 'action' => 'add']); ?>",
                    type: "post",
                    data: {postId: postId, content: content},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        var div = $(document.createElement("div")).html(data).css("display", "none").attr("class", "loadedUserComment");
                        textArea.closest(".post").find("ul").last("li").append(div);
                        emojiArea.setText("");
                        // textArea.css("height", "auto");
                        // textArea.css("overflow-y", "hidden");
                        $(div).slideDown(slideTime);
                    }
                });
            } else {
                toastr["warning"]("Comment must contain some text.");
            }
        }
    }

    function configureContentArea(index, textArea = this){
        textAreaCss(textArea);
        $(textArea).on("input", textAreaAutoExtend(150));//.on("keydown", submitComment);
    }

    function editCommentTextAreaKeyDown(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();

            var textArea = $(this);
            var commentId = textArea.closest(".comment").attr("data-comment-id");
            var content = textArea.val();

            if (!(content === "")) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Comments', 'action' => 'edit']); ?>",
                    type: "post",
                    data: {commentId: commentId, content: content},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        var editBtn = textArea.closest(".comment").find(".editBtn");
                        var commentContent = textArea.closest(".commentContent");
                        $(commentContent).html(emojione.toImage(data));
                        //$(commentContent).each(linkText);
                        editBtn.click();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //Give some feedback on error
                    }
                });
            } else {
                toastr["warning"]("Comment must contain some text.");
            }
        }
    }

    function editCommentTextAreaKeyDownEmoji(editor, e) {
        console.log(this);
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();

            var emojiArea = this;
            var textArea = emojiArea.source;
            var commentId = textArea.closest(".comment").attr("data-comment-id");
            var content = emojiArea.getText();

            if (!(content === "")) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Comments', 'action' => 'edit']); ?>",
                    type: "post",
                    data: {commentId: commentId, content: content},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        var editBtn = textArea.closest(".comment").find(".editBtn");
                        var commentContent = textArea.closest(".commentContent");
                        $(commentContent).html(data);
                        //$(commentContent).each(linkText);
                        editBtn.click();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //Give some feedback on error
                    }
                });
            }
        }
    }

    function editPostTextAreaKeyDown(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();

            var textArea = $(this);
            var postId = textArea.closest(".post").attr("data-post-id");
            var content = textArea.val();

            if (!(content === "")) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Posts', 'action' => 'edit']); ?>",
                    type: "post",
                    data: {postId: postId, content: content},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        var editBtn = textArea.closest(".media-body").find(".editBtn");
                        var commentContent = textArea.closest(".commentContent");
                        $(commentContent).html(data);
                        //$(commentContent).each(linkText);
                        editBtn.click();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //Give some feedback on error
                    }
                });
            }
        }
    }

    function editPostTextAreaKeyDownEmoji(editor, e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();

            var textArea = $(this.source);
            var postId = textArea.closest(".post").attr("data-post-id");
            var content = this.getText();

            if (!(content === "")) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Posts', 'action' => 'edit']); ?>",
                    type: "post",
                    data: {postId: postId, content: content},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        var editBtn = textArea.closest(".media-body").find(".editBtn");
                        var commentContent = textArea.closest(".commentContent");
                        $(commentContent).html(data);
                        //$(commentContent).each(linkText);
                        editBtn.click();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //Give some feedback on error
                    }
                });
            } else {
                toastr["warning"]("Post must contain some text.");
            }
        }
    }

    function readMoreCheck(index, postBodyContent){

        var height = $(postBodyContent).css("height");

        if(height === "0px") {
            var clone = $(postBodyContent).clone();

            $(clone).css({
                position: 'absolute',
                visibility: 'hidden',
                display: 'block'
            });
            $(".content-container").after(clone);

            height = $(clone).css("height");
            $(clone).remove();
        }

        if(+height.split("px")[0] > +postBodyLimit.split("px")[0]){
            $(postBodyContent).closest(".post").find(".read-more").css("display","");
        }
    }

    function readToggle(e){
        var post = $(this).closest(".post");
        var postBody = $(post).find(".post-card-body");
        var arrows = $(post).find(".read-more-arrow");
        if($(postBody).css("max-height") === postBodyLimit){
            $(postBody).css("max-height","initial");
            $(arrows).css("transform","rotate(180deg)");
        } else {
            $(postBody).css("max-height",postBodyLimit);
            $(arrows).css("transform","rotate(0deg)");
        }
    }

    var initTextAreas = [];
    var initRunning = false;
    var initDelay = 200;

    function queueArea(toQueue){
        if(toQueue.length > 1){
            for(var item of toQueue){
                initTextAreas.push(item);
            }
        } else {
            initTextAreas.push(toQueue);
        }

        if(!initRunning){
            initRunning = true;
            setTimeout(emojiInitRun,initDelay);
        }
    }

    function emojiInitRun(){
        if(initTextAreas.length > 0) {
            $(initTextAreas.shift()).emojioneArea(commentAreaEmojiOneOptions);
            setTimeout(emojiInitRun,initDelay);
        } else {
            initRunning = false;
        }
    }

    function initPost(post, readMore = true){
        $(post).find(".contentArea").each(configureContentArea);
        $(post).find(".commentArea").on("keydown", submitComment);
        queueArea($(post).find(".commentArea"));
        //$(post).find(".commentArea").emojioneArea(commentAreaEmojiOneOptions);
        //$(post).find(".commentContent").each(linkText);
        $(post).find(".post-image-container").on("click", overlayOn);
        $(post).find(".report-btn")
            .on("mouseover",flagHover)
            .on("mouseleave",flagLeave)
            .on("mousedown mouseup",flagHoldClick) //Triggers on both mouse down and mouse up events
            .on("click",reportOverlayOn);
        if(typeof initPoll === "function") {
            initPoll(post);
        }
        if(readMore) {
            $(post).find(".read-more-btn").on("click", readToggle);
            $(post).find(".post-card-body-content").each(readMoreCheck);
        }
    }

    function flagHover(e){
        $(e.target).attr("src","/img/flag-red.svg");
    }
    function flagLeave(e){
        $(e.target).attr("src","/img/flag.svg");
    }

    function flagHoldClick(e) {
        if (e.type === "mousedown") { //Triggers on mouse hold
            $(e.target).attr("src","/img/flag-click.svg");
        } else {
            $(e.target).attr("src","/img/flag-red.svg");
        }
    }

    function overlayOn() {
        $("#overlay").css("display","block");
    }

    function overlayOff(e) {
        if(e === true || $(e.target).hasClass("overlay-container")) {
            $("#overlay").css("display","none");
            imageOverlayOff();
            reportOverlayOff();
        }
    }

    function imageOverlayOff(e){
        $("#carouselPost").css("display","none");
    }

    function imageOverlayOn(e){
        overlayOn();
        $("#carouselPost").css("display","initial");
    }

    function reportOverlayOn(e){
        overlayOn();
        var reportModal = $("#reportModal");
        var id = $(e.target).closest(".post").data("post-id");

        $(reportModal).data("id",id);
        $(reportModal).css("display","initial");
    }

    function reportOverlayOff(e){
        $("#reportModal").css("display","none");
    }

    function submitPostReport(e){
        var btn = $(e.target);
        var id = $("#reportModal").data("id");
        var reason = $("#reportReason").val();

        $.ajax({
            url: "<?= Router::url(['controller' => 'ReportedPosts', 'action' => 'add']); ?>",
            type: "post",
            data: {post_id: id, reason: reason},
            dataType: "html",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', csrfToken);
            },
            success: function (data) {
                overlayOff(true);
                $("#reportReason").val("");
                $("#reportModal").data("id", -1);
                toastr["success"]("Post report submitted.");
                var flag = $("[data-post-id='" + id + "']").find(".report-btn");
                $(flag).off();
                flag.removeClass("report-btn");
                $(flag).find("img").attr("src","/img/flag-click.svg").attr("title","Flagged Post");
            }
        });

    }

    function loadEmojiOneCommentArea(commentAreas){
        var length = commentAreas.length;
        console.log(length);
        setTimeout(progressEmojiLoad,100,commentAreas,0,length);
    }

    function progressEmojiLoad(commentAreas, count,length){
        if(count < length){
            $(commentAreas[count]).emojioneArea(commentAreaEmojiOneOptions);
            setTimeout(progressEmojiLoad,100,commentAreas,count + 1,length);
        }
    }

    function toEmojiImage(index,element){
        var emoji = emojione.toImage($(element).html());
        $(element).html(emoji);
    }

    $(function () {

        $(".postArea").emojioneArea({
            pickerPosition: "right",
            autocomplete: "on",
            shortnames: true,
            events : {
                click: showPostControls,
                emojibtn_click: showPostControls,
                focus: showPostControls
            }
        });

        //$(".commentArea").emojioneArea(commentAreaEmojiOneOptions);
        loadEmojiOneCommentArea($(".commentArea"));


        //$(".commentArea").emojioneArea(commentAreaEmojiOneOptions);

        $(".post-image-container").on("click", imageOverlayOn);
        $("#overlay").on("click",overlayOff);

        $(".report-btn")
            .on("mouseover",flagHover)
            .on("mouseleave",flagLeave)
            .on("mousedown mouseup",flagHoldClick) //Triggers on both mouse down and mouse up events
            .on("click",reportOverlayOn);

        $(".submit-report-btn").on("click",submitPostReport);


        dropArea = document.getElementById("drop-area");
        postBodyLimit = $(".post-card-body").css("max-height");

        $(document).on("dragenter dragover dragleave drop", "#drop-area" ,preventDefaults);
        $("#drop-area").on("dragenter dragover" ,highlight);
        $("#drop-area").on("dragleave drop",removeHighlight);

        if(dropArea != null) {
            dropArea.addEventListener('drop', handleDrop, false);
        }

        $("#fileInput").on("change", function(){
            var files = [...this.files];
            handleFiles(files);
        });

        $(document).on("click",".image-cancel", function(e){
            e.stopPropagation();
            e.preventDefault();
            var container = $(this).closest(".preview-image-container");
            let size = $(container).attr("data-size");
            let name = $(container).attr("data-name");
            for(let i in images){
                let image = images[i];
                if(image.name === name && image.size == size){ //Probably the same file
                    images.splice(i,1);
                }
            }
            container.fadeOut(500, function(){
                container.remove();
                if(Object.keys(images).length == 0){
                    resetDropArea();
                }
            });
        });

        $(".read-more-btn").on("click", readToggle);

        $('.contentArea').each(configureContentArea);
        $(".commentArea").on("keydown", submitComment);

        $(".post-card-body-content").each(readMoreCheck);

        // $(".postArea").each(textAreaCss).on("input", textAreaAutoExtend(Infinity)).on("focus", function () {
        //     // $(this).closest(".card").find(".postControls").slideDown(slideTime);
        // });



        // $(".postArea.emojionearea").on("click", showPostControls);

        $(document).on("click", function(e){
            if($(e.target).closest(".postContainer").length == 0){
                if ($(".postArea").val() === "" && Object.keys(images).length == 0 && $("#pollQuestion").val() === "" && $("#date").val() === "" && $("#option1").val() === "" && $("#option2").val() === "") {
                    $(".postArea").closest(".card").find(".postControls").slideUp(slideTime, function(){
                        $(".upload-img-btn").data("toggle",0).trigger("click");
                        $(".poll-img-btn").data("toggle",0).trigger("click");
                        resetDropArea();
                        $(".upload-img-btn").css("display","");
                    });
                }
            }
        });
        if ($(".postArea").is(":focus")) {
            $(".postArea").trigger("focus")
        }

        $(document).on("click", ".loadMoreBtn", function () {
            //Loads more comments below the button with AJAX
            var btn = $(this);
            var commentCount = btn.closest(".card").find(".comment").length;
            var postId = btn.closest(".post").attr("data-post-id");
            var loadedComment = btn.closest(".card").find(".loadedComment");


            if(loadedComment.length == 0) {
                $.ajax({
                    url: "<?= Router::url(['controller' => 'Posts', 'action' => 'loadMoreComments']); ?>",
                    type: "post",
                    data: {postId: postId, commentCount: commentCount},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        if (!(data === "")) {
                            var div = hiddenDiv(data).attr("class", "loadedComment");
                            $(btn).parent().after(div);
                            $(div).slideDown(slideTime);
                            btn.html("Hide Comments");
                            btn.removeClass("bg-info");
                            btn.addClass("bg-success");
                            btn.attr("data-pressed", 0);
                        }
                    }
                });
            } else {
                //loadedComment.stop(); causes glitch of leaving empty spaces when removing comments too fast
                if(canPressLoadMore) {
                    if (+btn.attr("data-pressed")) { //Toggle action based on if button pressed
                        btn.html("Hide Comments");
                        btn.removeClass("bg-info");
                        btn.addClass("bg-success");
                        btn.attr("data-pressed", 0);
                    } else {
                        btn.html("Show Comments");
                        btn.removeClass("bg-success");
                        btn.addClass("bg-info");
                        btn.attr("data-pressed", 1);
                    }
                    canPressLoadMore = false;
                    loadedComment.slideToggle(slideTime, function(){canPressLoadMore = true;});
                }
            }
        });

        $(document).on("click", ".deleteBtn", function (){
            //Delete comment from database backend and on success hide the comment in the browser
            var comment = $(this).closest(".comment");
            if(comment.length == 0){ //If no comment class in tag, that means we're in a post's delete button
                var post = $(this).closest(".post");
                var postId = post.attr("data-post-id");

                $.ajax({
                    url: "<?= Router::url(['controller' => 'Posts', 'action' => 'delete']); ?>",
                    type: "post",
                    data: {postId: postId},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        console.log(data);
                        if (data === "true") {
                            if (typeof deletePostCallBack === "function") {
                                deletePostCallBack(post);
                            } else {
                                post.closest(".slidingDiv").slideToggle(slideTime, function () {
                                    post.remove()
                                });
                            }
                            post.closest(".slidingDiv").slideToggle(slideTime, function () {
                                if (typeof deletePostCallBack === "function") {
                                    deletePostCallBack(post);
                                } else {
                                    post.remove()
                                }
                            });
                        } else {
                            alert("The post could not be deleted.");
                        }
                    }
                });
            } else {
                var commentId = comment.attr("data-comment-id");

                $.ajax({
                    url: "<?= Router::url(['controller' => 'Comments', 'action' => 'remove']); ?>",
                    type: "post",
                    data: {commentId: commentId},
                    dataType: "html",
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                    },
                    success: function (data) {
                        if (data === "true") {
                            comment.closest(".slidingDiv").slideToggle(slideTime, function () {
                                comment.remove()
                            });
                        } else {
                            alert("The comment could not be deleted.");
                        }
                    }
                });
            }
        });

        $(document).on("click", ".post .editBtn", function (){
            var editBtn = $(this);
            var comment = editBtn.closest(".media-body");
            var content = comment.find(".commentContent");

            if(+editBtn.attr("data-pressed")){ //Toggle action based on if button pressed
                //Changing styling in button to edit button
                editBtn.css("flex", "");
                editBtn.find("img").attr("src","/img/si-glyph-document-edit.svg");

                //Removing text area and restoring to previous text
                var dataContent = comment.find(".editTextArea").attr("data-content");
                if(dataContent !== undefined) {
                    content.html(emojione.toImage(dataContent));
                }

                $(editBtn).closest(".card-body").addClass("post-card-body")
                $(editBtn).closest(".post-card-body").css("max-height","");
                editBtn.attr("data-pressed", 0);
            } else {
                //Changing styling in button to cancel button
                var flex = 2.5;
                editBtn.css("flex", flex + " 1 "  + " auto");
                editBtn.find("img").attr("src","/img/x.svg");

                content = processContent(content);

                //Adding text area
                var textArea = createEditTextArea(this);
                textArea.attr("data-content", content.html());
                var contentText = content.html();
                content.html(textArea);
                var emojiArea = textArea.emojioneArea({
                    pickerPosition: "right",
                    autocomplete: "on",
                    shortnames: true,
                    events : {
                        keydown: textAreaFunction(this)
                    }
                });
                console.log(emojiArea);
                emojiArea[0].emojioneArea.setText(decodeEntities(contentText));
                $(emojiArea.editor).focus();
                $(editBtn).closest(".post-card-body").css("max-height","initial");
                $(editBtn).closest(".card-body").removeClass("post-card-body");
                editBtn.attr("data-pressed", 1);
            }

        });

        function textAreaFunction(trigger){
            if(trigger.closest(".comment")) {
                return editCommentTextAreaKeyDownEmoji;
            } else {
                return editPostTextAreaKeyDownEmoji;
            }
        }

        function createEditTextArea(trigger) {
            //var textArea = $("#comment").clone().each(textAreaCss).on("input", textAreaAutoExtend(Infinity)).addClass("mt-2").addClass("editTextArea");
            var textArea = $(document.createElement("textarea")).addClass("mt-2 commentArea contentArea editTextArea");
            if(trigger.closest(".comment")) {
                textArea.on("keydown", editCommentTextAreaKeyDown);
            } else {
                textArea.on("keydown", editPostTextAreaKeyDown);
            }
            return textArea;
        }


        $(document).on("click",".post-image-container", function(){
            var carousel = $("#carouselPost");
            var id = $(this).closest(".post").attr("data-post-id");
            $.ajax({
                url: "<?= Router::url(['controller' => 'Posts', 'action' => 'getImages']); ?>",
                type: "post",
                data: {id:id},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {
                    carousel.css("display","none");
                    carousel.html(data);
                    $('.carousel').carousel("dispose");
                    $('.carousel').carousel();
                    carousel.css("display","");
                }
            });
        });

        $(document).on("click",".upload-img-btn", function(){
            var btn = this;

            if($(btn).data("toggle") === 0){
                $(this).removeClass("btn-secondary").addClass("btn-primary");
                $("#drop-area").css("display","none");
                $(btn).data("toggle",1);
            } else {
                $(this).removeClass("btn-primary").addClass("btn-secondary");
                $("#drop-area").css("display","");
                $(btn).data("toggle",0);
            }
        });

        $(document).on("click",".poll-img-btn", function(){
            var btn = this;

            if($(btn).data("toggle") === 0){
                $(this).removeClass("btn-secondary").addClass("btn-primary");
                $(".poll-controls").css("display","none");
                $(btn).data("toggle",1);
            } else {
                $(this).removeClass("btn-primary").addClass("btn-secondary");
                $(".poll-controls").css("display","");
                $(btn).data("toggle",0);
            }
        });

        $(".postBtn").on("click", function(){

            var postContainer = $(this).closest(".card");
            var textArea = $(postContainer).find(".postArea");
            var content = textArea.val();

            var data = new FormData($(".poll-form")[0]);

            var hasQuestion = false;
            var hasOptions = false;

            data.forEach(function(value, key){
               if(key === "question" && value !== ""){
                   hasQuestion = true;
               }
               if(key.substring(0,6) === "option" && value !== ""){
                   hasOptions = true;
               }
            });

            if((hasQuestion && hasOptions) || (!hasQuestion && !hasOptions)) {

                images.forEach(function (image, i) {
                    data.append('image_' + i, image);
                });

                data.append("content", content);

                if (typeof addPostData === "function") {
                    addPostData(data);
                }

                if (!(content === "")) {
                    $.ajax({
                        url: "<?= Router::url(['controller' => 'Posts', 'action' => 'add']); ?>",
                        type: "post",
                        data: data,
                        dataType: "html",
                        contentType: false,
                        processData: false,
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                        },
                        success: function (data) {
                            var div = hiddenDiv(data).attr("class", "loadedPost");
                            var emojiArea = $(postContainer).find(".emojionearea-editor");
                            textArea.val("");
                            emojiArea.html("");
                            textArea.closest(".postContainer").find(".postControls").slideUp(slideTime);
                            textArea.closest(".postContainer").after(div);

                            initPost(div);

                            images = new Array();
                            $(".preview-image-container").remove();
                            resetDropArea();
                            resetPoll();
                            $(".postArea").trigger("input");

                            div.slideDown(slideTime);
                        }
                    });
                } else {
                    toastr["warning"]("Post has no text.");
                }
            } else {

                if(!hasQuestion){
                    $(".poll-question-error").css("display","initial");
                }
                if(!hasOptions){
                    $(".poll-option-error").css("display","initial")
                }
            }
        });

        $(document).on("click",".notification-btn", function(e){
            var btn = $(this);
            var getNotifications;
            var postId = $(btn).closest(".post").attr("data-post-id");
            if($(btn).hasClass("btn-secondary")){
                getNotifications = true;
            } else {
                getNotifications = false;
            }
            $.ajax({
                url: "<?= Router::url(['controller' => 'UserPostNotifications', 'action' => 'modifyNotification']); ?>",
                type: "post",
                data: {post_id: postId, notifications: getNotifications},
                dataType: "html",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrfToken);
                },
                success: function (data) {
                    if (data === "true") { //Means no more posts to get
                        if($(btn).hasClass("btn-secondary")){
                            $(btn).removeClass("btn-secondary").addClass("btn-success");
                            $(btn).find("img").attr("src","/img/si-glyph-mail-send.svg");
                        } else {
                            $(btn).removeClass("btn-success").addClass("btn-secondary");
                            $(btn).find("img").attr("src","/img/si-glyph-mail.svg");
                        }
                    } else {
                        //Handle error
                    }
                },
            });
        });


        <?= $this->fetch("append-post-js")?>

    });

</script>