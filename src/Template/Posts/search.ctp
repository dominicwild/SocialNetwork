<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 * @var string $search
 * @var array $search_results
 */
use Cake\Routing\Router;

$this->set("show_activity", false);
$this->set("default_load_more", false);
$this->extend("home-base");

echo $this->Html->css("search.css");

if(isset($search)){
    $search = h($search);
}

$title_context = $search !== "" ? "Results for: \"" . $search . "\"" : "";

?>

<script>

    function searchPageInput(e){
        if (e.which === 13){
            $("#page-search button").click();
        }
    }

    function addEllipsis(index,element){
        var ellipsis = new Ellipsis(element);

        ellipsis.calc();
        ellipsis.set();
    }

    function initNewResults(results){
        SVGInjector($(results).find(".search-icon"));
        SVGInjector($(results).find(".search-type-icon"));
    }

    $(function(){
        SVGInjector($(".search-icon"));
        SVGInjector($(".search-type-icon"));

        $("#page-search").on("input",searchPageInput);
        $(".search-content").each(addEllipsis);

        var stopRender = false;
        var renderRequestSent = false;

        $(window).scroll(function () {
            if (!stopRender && !renderRequestSent) {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 4000) {
                    renderRequestSent = true;

                    var resultCount = $(".search-result").length;
                    var search = $("#page-search").data("search");

                    $.ajax({
                        url: "<?= Router::url(['controller' => 'Posts', 'action' => "loadMoreSearchResults"]); ?>",
                        type: "post",
                        data: {count: resultCount,search: search},
                        dataType: "html",
                        beforeSend: function (xhr) {
                            xhr.setRequestHeader('X-CSRF-Token', "<?php echo $_COOKIE["csrfToken"] ?>");
                        },
                        success: function (data) {
                            var div = $(document.createElement("div")).css("display","none").html(data);
                            $(".wall").children().last().after(div);
                            initNewResults(div);
                            $(div).slideDown();
                            $(div).find(".search-content").each(addEllipsis);
                            if ($(".end-search-footer").length > 0) { //Means no more results to get
                                stopRender = true;
                            }
                            renderRequestSent =false;
                        },
                        error: function(data){
                            renderRequestSent =false;
                        }
                    });
                }
            }
        });
    });

</script>

<div class="row border-bottom border-gray mt-3 mx-1">
    <div class="col-12">
        <h3 class="mb-1">Search <?= $title_context ?></h3>
    </div>
</div>

<form class="row mt-3 form-inline" action="<?= \Cake\Routing\Router::url(["controller" => "Posts", "action"=>"search"])?>">
    <div class="form-group col-12">
        <label class="label col-1">Search:</label>
        <input class="form-control col-10" data-search="<?= $search ?>" id="page-search" type="text" name="search" placeholder="Search..." value="<?= $search ?>">
        <button class="btn btn-primary ml-2">Search</button>
    </div>
</form>

<?=$this->element("search-results", ["search_results" => $search_results]);?>

<?php if(sizeof($search_results) == 0 && $search !== ""): ?>
<div class="row">
    <div class="col-12">
        <h2 class="text-muted text-center mt-3"><i>There are no results for "<?= $search ?>"</i></h2>
    </div>
</div>
<?php endif; ?>
