<!-- Continous Improvement Survey Section -->
<h4>Continuous Improvement Survey</h4>
<div class="row mb-2">
    <div class="col-lg-4 col-6">
        <!-- small card -->
        <<?php if(!empty($survey_status['eng'])){echo"div";}else{echo"a";} ?> href="<?= base_url('survey/engagement'); ?>" class="small-box 
        <?php if(!empty($survey_status['eng'])): ?>
            bg-gray
        <?php else: ?>
            bg-yellow    
        <?php endif; ?>">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['eng'])): ?>
                <!-- obverlay -->
                <di class="overlay dark"></di>
            <?php endif; ?>
            <div class="inner">
                <h3>Engagement</h3>

                <p>Employe Engagement Survey</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <!-- <div class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </div> -->
        </<?php if(!empty($survey_status['eng'])){echo"div";}else{echo"a";} ?>><!-- /small card -->
    </div>
    <div class="col-lg-4 col-6">
        <!-- small card -->
        <<?php if(!empty($survey_status['exc'])){echo"div";}else{echo"a";} ?> href="<?= base_url('survey/excellence'); ?>" class="small-box 
        <?php if(!empty($survey_status['exc'])): ?>
            bg-gray
        <?php else: ?>
            bg-blue    
        <?php endif; ?>">
            <!-- kasih overlay apabila survey sudah diisi -->
            <?php if(!empty($survey_status['exc'])): ?>
                <!-- obverlay -->
                <div class="overlay dark"></div>
            <?php endif; ?>
            <div class="inner">
                <h3>Service</h3>

                <p>Service Exellence Survey</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <!-- <div class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </div> -->
        </<?php if(!empty($survey_status['exc'])){echo"div";}else{echo"a";} ?>><!-- /small card -->
    </div>
    <div class="col-lg-4 col-6">
        <!-- small card -->
        <a href="<?= base_url('survey/feedback360'); ?>" class="small-box bg-orange">
            <!-- obverlay -->
            <!-- <di class="overlay dark"></di> -->
            <div class="inner">
                <h3>360°</h3>

                <p>360° Feedback</p>
            </div>
            <div class="icon">
                <i class="fa fa-file-alt"></i>
            </div>
            <!-- <div class="small-box-footer">
                Let's fill the survey <i class="fas fa-arrow-circle-right"></i>
            </div> -->
        </a><!-- /small card -->
    </div>
</div>