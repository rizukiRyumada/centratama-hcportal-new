<!-- Your Survey Section -->
<h4>Your Survey</h4>
<div class="row mb-2">
    <div class="col-lg-4 col-6">
        <!-- small card -->
        <div class="small-box bg-primary">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['exc'])): ?>
                <!-- obverlay -->
                <di class="overlay dark"></di>
            <?php endif; ?>
            <div class="inner">
                <h3>Excellence</h3>

                <p>Service Exellence</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <a href="<?= base_url('survey/excellence'); ?>" class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div><!-- /small card -->
    </div>
    <div class="col-lg-4 col-6">
        <!-- small card -->
        <div class="small-box bg-warning">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['eng'])): ?>
                <!-- obverlay -->
                <di class="overlay dark"></di>
            <?php endif; ?>
            <div class="inner">
                <h3>Engagement</h3>

                <p>Service Engagement</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <a href="<?= base_url('survey/engagement'); ?>" class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div><!-- /small card -->
    </div>
    <div class="col-lg-4 col-6">
        <!-- small card -->
        <div class="small-box bg-danger">
            <!-- obverlay -->
            <di class="overlay dark"></di>

            <div class="inner">
                <h3>360</h3>

                <p>Review 360   </p>
            </div>
            
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>

            <a href="#" class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div><!-- /small card -->
    </div>
</div>