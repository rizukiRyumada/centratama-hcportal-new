<div class="row justify-content-center">
    <div class="col-md-8 mt-2">
        <div id="healthCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#healthCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#healthCarousel" data-slide-to="1"></li>
                <li data-target="#healthCarousel" data-slide-to="2"></li>
                <li data-target="#healthCarousel" data-slide-to="3"></li>
                <li data-target="#healthCarousel" data-slide-to="4"></li>
                <li data-target="#healthCarousel" data-slide-to="5"></li>
                <li data-target="#healthCarousel" data-slide-to="6"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>1.jpeg" class="d-block w-100" alt="Gerakan hidup sehat">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>2.jpeg" class="d-block w-100" alt="Cuci Tangan">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>3.jpeg" class="d-block w-100" alt="Gunakan Siku saat batuk">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>4.jpeg" class="d-block w-100" alt="Pakai masker">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>5.jpeg" class="d-block w-100" alt="Makan makanan sehat">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>6.jpeg" class="d-block w-100" alt="Jaga jarak">
                </div>
            </div>
            <a class="carousel-control-prev" href="#healthCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#healthCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="col-md-4 mt-2">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <p class="card-title text-center w-100">Are you healthy today?</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <!-- <img class="responsive-image" src="<?= base_url('assets/img/healthReport/main-logo.svg'); ?>" alt="Health Check main logo"> -->
                        <p class="card-text text-center">
                            <?= date("l, j M Y"); ?>
                        </p>
                    </div>
                </div>
                <div class="row mt-lg-5 pt-4">
                    <!-- <div class="col text-center">
                        <a data-toggle="modal" data-target="#modal_healthy" class="btn bg-gray-light text-center">
                            <div class="row">
                                <div class="col">
                                    <img class="img-md" src="<?= base_url('assets/img/healthReport/_healthy.svg'); ?>" alt="healthy" srcset=""> <br/>
                                </div>
                            </div>
                            Healty
                        </a>
                    </div> -->
                    <div class="col-6 text-center">
                        <<?php if($checkedIn){echo('div class="btn '.$btn_healthy);} else {echo('button type="submit" form="CheckInHealthy" class="btn bg-gray-light');}    
                        ?> text-center">
                            <div class="row">
                                <div class="col">
                                    <img class="img-lg d-lg-block d-none" src="<?= base_url('assets/img/healthReport/_healthy.svg'); ?>" alt="healthy" >
                                    <img class="img-md d-lg-none" src="<?= base_url('assets/img/healthReport/_healthy.svg'); ?>" alt="healthy" > <br/>
                                </div>
                            </div>
                            Healthy
                        </<?php if($checkedIn){echo('div');} else {echo('button');}    
                        ?>>
                    </div>
                    <div class="col-6 text-center">
                        <<?php if($checkedIn){echo('div class="btn '.$btn_sick);} else {echo('button data-toggle="modal" data-target="#modal_sick" class="btn bg-gray-light');}    
                        ?> text-center">
                            <img class="img-lg d-lg-block d-none" src="<?= base_url('assets/img/healthReport/_sick.svg'); ?>" alt="sick" srcset="">
                            <img class="img-md d-lg-none" src="<?= base_url('assets/img/healthReport/_sick.svg'); ?>" alt="sick" srcset=""> <br/>
                            Unwell
                        </<?php if($checkedIn){echo('div');} else {echo('button');}    
                        ?>>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col text-center">
                        <small style="font-size: 10px;">
                            Icons made by <a href="https://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /* -------------------------------------------------------------------------- */
     /*                                   MODALS                                   */
     /* -------------------------------------------------------------------------- */ -->
<!-- Modal Healthy -->
<div class="modal fade" id="modal_healthy" tabindex="-1" role="dialog" aria-labelledby="modal_healthyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_healthyLabel"><?= date("l, j M Y"); ?> <span id="checkTime"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="CheckInHealthy" role="form" action="<?= base_url('healthReport/submitCheckIn'); ?>" method="POST">
                    <input type="hidden" name="checkIn" value="1">
                    <div class="row justify-content-center mb-3">
                        <div class="col-3">
                            <img src="<?= base_url('assets/img/healthReport/_healthy.svg'); ?>" alt="Healthy" class="responsive-image">
                        </div>
                    </div>
                    <p class="text-center m-0">Apa anda yakin merasa sehat?</p>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="CheckInHealthy" class="btn btn-primary">Ya</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sick -->
<div class="modal fade" id="modal_sick" tabindex="-1" role="dialog" aria-labelledby="modal_sickLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_sickLabel"><?= date("l, j M Y"); ?> <span id="checkTime"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <p class="text-center text-danger m-0">Please choose your sick category.</p>
                    </div>
                </div>
                <form id="checkInSick" role="form" action="<?= base_url('healthReport/submitCheckIn'); ?>" method="POST">
                    <input type="hidden" name="checkIn" value="0">
                    <!-- checkbox kategori sakit -->
                    <div class="form-group">
                        <div class="row m-0 p-0">
                            <?php foreach($sick_categories as $v): ?>
                                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                    <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                        <input type="checkbox" name="<?= $v['input_name']; ?>" id="<?= $v['input_name']; ?>" />
                                        <label class="text-center" for="<?= $v['input_name']; ?>">
                                            <img src="<?= base_url('assets/img/healthReport/sick categories/').$v['icon']; ?>" /> <br>
                                            <small class="m-0"><?= $v['name']; ?></small>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach;?>
                            <!-- Sakit other -->
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" name="otherTrigger" id="otherTrigger" />
                                    <label class="text-center" for="otherTrigger">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/other.svg'); ?>" /> <br>
                                        <small class="m-0">Others</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div><!-- /checkbox kategori sakit -->
                    <div id="othersForm" class="form-group" style="display: none;">
                        <span class="label-alternate">Others</span>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <img src="<?= base_url('assets/img/healthReport/sick categories/other.svg'); ?>" alt="other" style="width: 1em; height: 1em">
                                </div>
                            </div>
                            <input type="text" class="form-control" name="other" placeholder="Others">
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="form-group mb-0">
                        <span class="label-alternate">Notes</span>
                        <textarea class="form-control" name="notes" rows="5" placeholder="Please enter the notes of your sickness..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="checkInSick" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>