<!-- Continous Improvement Survey Section -->
<h4>Continuous Improvement Survey</h4>

<!-- Survey Card -->
<div class="row mb-2">
    <div class="col-lg-4 col-md-6 col-12">
        <!-- small card -->
        <<?php if(!empty($survey_status['eng'])){echo"div";}else{echo"a";} ?> href="<?= base_url('survey/engagement'); ?>" class="small-box 
        <?php if(!empty($survey_status['eng'])): ?>
            bg-gray
        <?php else: ?>
            bg-blue    
        <?php endif; ?>">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['eng'])): ?>
                <!-- obverlay -->
                <di class="overlay dark"></di>
            <?php endif; ?>
            <div class="inner">
                <h3>Engagement</h3>

                <p><?= $survey_title['engagement']; ?></p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <!-- <div class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </div> -->
        </<?php if(!empty($survey_status['eng'])){echo"div";}else{echo"a";} ?>><!-- /small card -->
    </div>
    <div class="col-lg-4 col-md-6 col-12">
        <!-- small card -->
        <<?php if(!empty($survey_status['exc'])){echo"div";}else{echo"a";} ?> href="<?= base_url('survey/excellence'); ?>" class="small-box 
        <?php if(!empty($survey_status['exc'])): ?>
            bg-gray
        <?php else: ?>
            bg-orange    
        <?php endif; ?>">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['exc'])): ?>
                <!-- obverlay -->
                <div class="overlay dark"></div>
            <?php endif; ?>
            <div class="inner">
                <h3>Service</h3>

                <p><?= $survey_title['excellence']; ?></p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <!-- <div class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </div> -->
        </<?php if(!empty($survey_status['exc'])){echo"div";}else{echo"a";} ?>><!-- /small card -->
    </div>
    <div class="col-lg-4 col-md-6 col-12">
        <!-- small card -->
        <<?php if(!empty($survey_status['f360'])){echo"div";}else{echo"a";} ?> href="<?= base_url('survey/feedback360'); ?>" class="small-box 
        <?php if(!empty($survey_status['f360'])): ?>
            bg-gray
        <?php else: ?>
            bg-yellow
        <?php endif; ?>">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['f360'])): ?>
                <!-- obverlay -->
                <div class="overlay dark"></div>
            <?php endif; ?>
            <!-- <di class="overlay dark"></di> -->
            <div class="inner">
                <h3>360°</h3>

                <p><?= $survey_title['f360']; ?></p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <!-- <div class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </div> -->
        </<?php if(!empty($survey_status['f360'])){echo"div";}else{echo"a";} ?>>
        <!-- /small card -->
    </div>
</div><!-- /Survey Card -->

<!-- Thank You Card -->
<?php if(!empty($survey_complete)): ?>
    <div class="row mb-2 justify-content-center">
        <div class="col-lg-7">
            <div class="card elevation-2">
                <img class="responsive-image" src="<?= base_url('assets/img/survey/exc_thankyou.jpg'); ?>" alt="tema">  
            </div>
            <!-- <p class="card-text text-center mt-2">
                Thank You for completing this Survey, Have a Nice Day :)
            </p> -->
        </div>
    </div>
<?php endif; ?>
<!-- /Thank You Card -->