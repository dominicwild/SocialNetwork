<script>

    function searchInput(e){
        if (e.which === 13){
            $("#search button").click();
        }
    }

    $(function(){
        SVGInjector($(".magnifying-glass"));

        $("#search input[type='text'").on("input",searchInput);
    });

</script>