<script>
    $(".nav-faq").addClass("active");

    $(function(){

        $(".topic").on("click", function(){

            var container = $(this).closest(".topic-container");
            var faqArrow = $(container).find(".faq-arrow");
            var angle = +$(faqArrow).data("angle") + 180;

            $(container).find(".topic-questions").slideToggle();
            $(faqArrow).css("transform","rotate(" + angle + "deg)");
            $(faqArrow).data("angle",angle);
        });

        $(".question").on("click", function(){
            console.log("question");

            var questionArrow = $(this).find(".question-arrow");
            var angle = +$(questionArrow).data("angle") + 180;

            $(questionArrow).css("transform","rotate(" + angle + "deg)");
            $(questionArrow).data("angle",angle);

            $(this).next(".answer").slideToggle();
        });

    })

</script>