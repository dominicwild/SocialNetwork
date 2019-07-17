<div class="overlay-container" id="overlay">
    <div id="carouselPost" class="carousel slide image-viewer center-overlay" style="display:none;" data-interval=false data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselPost" data-slide-to="0" class="active"></li>
            <li data-target="#carouselPost" data-slide-to="1"></li>
            <li data-target="#carouselPost" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">

        </div>
        <a class="carousel-control-prev" href="#carouselPost" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselPost" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div id="reportModal" data-id="-1" style="display:none;">
        <div class="card center-overlay w-50 text-left">
            <div class="card-header text-center py-2">
                <h2 class="mb-0">
                    Report
                </h2>
            </div>
            <div class="card-body py-2">
                <p class="card-text">Reports on a post can be made if you feel their content is problematic in some way such as containing inappropriate content, images or behaviour etc. Once a post has been reported:
                    <ul>
                        <li>Admins will see the reported post and take action upon it.</li>
                        <li>Admins may contact you for further details so they can effectively address your concern.</li>
                        <li>The person you reported will not be aware you reported them. Admins also cannot see reports on posts against them.</li>
                    </ul>
                </p>

                <div class="form-group">
                    <label for="reportReason"><h4 class="mb-0">Reason</h4></label>
                    <textarea class="form-control" id="reportReason" rows="3"></textarea>
                </div>
                <button class="btn btn-danger submit-report-btn">Report</button>
            </div>
        </div>
    </div>

</div>