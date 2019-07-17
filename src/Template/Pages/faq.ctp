<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?= $this->Html->css("faq.css") ?>
<?= $this->element("faq-js") ?>

<div class="row border-bottom border-gray profile-header mt-3">
    <div class="col-12 d-flex">
        <h3 class="mt-auto mb-1">FAQ</h3>
    </div>
</div>
<p>Click a topic below to see questions and answers on various features of the social media site. They will detail how certain functions are used and how to perform certain actions.</p>


<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Posts & Comments
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">How do I comment on posts?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You can comment on posts by writing something in the comment text box under a post. You can then press enter to insert your comment on that post. If you wish to write paragraphs on a post, you can use shift+enter for a new line.</li>
                <li class="list-group-item question">How do I add images to my post? <img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To add images to your post, you need to click the image button, located to the far left from the post button, next to the poll button. This will display the drop area to insert images. You may drag and drop a set of images from your computer or click on the drop area to open a file search window. From here you can also select numerous images. After images are attached, simply clicking post will submit the post with those images.</li>
                <li class="list-group-item question">How do I edit a post? <img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To edit a post, click the pencil and paper icon on the post. You may then enter your edits, press enter and those edits will be saved. Otherwise clicking “x” will undo any edits.</li>
                <li class="list-group-item question">How do I view all images on a post?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You can click on a post’s images to view a larger version of those images. There will be arrows to the left and right of the enlarge image, these will scroll through all images in a post, in their enlarged form. You can also click one of the tabs below the image, to quickly jump to a specific image. <img class="answer-image" src="/img/FAQ/ImageCarousel.png"></li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Polls
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">How do I vote on a poll?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To vote on a poll, you must click on the blue poll box. This will expand the poll and allow you to click on the options to vote. Once an option has been selected, click the “Vote” button that will have appeared and your vote will be submitted and the results should display afterwards.</li>
                <li class="list-group-item question">How do I add an option to a poll?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To add an option to a poll, the poll must first have the option enabled. If it does, you’ll see a blue “+” area below it. Click it and a text box will appear with a “+” button on its right. Enter your option, and then click the “+” to submit it and it will be automatically added to the poll.</li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Activity Log
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">What can I do with the activity log?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You can click actions in the activity log to be taken to that activity. The activity will usually be highlighted in some way. <img class="answer-image" src="/img/FAQ/ActivityLogClick.png"></li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Profile
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">How do I change my profile picture?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You can change your profile picture by scrolling over it and clicking it. A white overlay image should appear to indicate this. Clicking will trigger a file select window to appear, you may then upload an image and the image will be displayed on your profile instantly on selection. <img class="answer-image" src="/img/FAQ/ProfilePictureChange.png"></li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Groups
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">How do I visit a group’s page?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To visit a group’s page, click on the group's card within the group’s list. You will then be taken to that group’s page.</li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Events
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">How do I view an event in detail?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">This can be done in two places. On the events page, you can click on an event to open up the detailed view above the events on that page. You can also go to the “view all events” page and all the events here are by default viewed in detail.</li>
                <li class="list-group-item question">How do I edit an event?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To edit an event, you must first view it in detail somewhere. Above the detailed event view you will see an “edit event” tab, click it and you’ll be taken to the event edit page, where you can edit this event. The same may be done on the “view all events” page. <img class="answer-image" src="/img/FAQ/EditEvent.png"></li>
                <li class="list-group-item question">How do I add an event to my Google Calendar?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">When joining an event, it will be automatically added to your Google Calendar. It will also be automatically removed if you were to leave the event.</li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Miscellaneous
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">How do I get comment notifications on a post?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">To get comment notifications from a post, you must subscribe to that post. You can do this by clicking the “email” icon, on any post. When clicked, it will turn green, this means you’re subscribed to that post and will get email notifications as determined by your user settings.
                    <img class="answer-image" src="/img/FAQ/PostCommentNotifications.png">
                </li>
                <li class="list-group-item question">How do I get email notifications when someone posts something new in a group?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You will automatically recieve email notifications of new posts and activites created in a group if you have joined that group. Therefore to receive these email notifications you must join the group you wish to receive these updates on.</li>
                <li class="list-group-item question">How do I get the weekly event emails?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You can get the weekly event emails by going to your options menu and ticking the “weekly email” check box. The words “updated!” will display in green, when the process has been completed.</li>
                <li class="list-group-item question">How do I get per event emails?<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">You can get the weekly event emails by going to your options menu and ticking the “per event email” check box. The words “updated!” will display in green, when the process has been completed.</li>
            </ul>
        </div>
    </div>
</div>

<div class="row topic-container my-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header topic py-1">
                <span>
                    Example
                </span>
                <img class="faq-arrow" src="/img/faq_arrow_down.svg" data-angle="90">
            </div>

            <ul class="list-group list-group-flush topic-questions">
                <li class="list-group-item question">Q<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">A</li>
                <li class="list-group-item question">Q<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">A</li>
                <li class="list-group-item question">Q<img class="question-arrow" data-angle="0" src="/img/TriangleArrow-Down.svg"></li>
                <li class="list-group-item answer">A</li>
            </ul>
        </div>
    </div>
</div>