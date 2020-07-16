<div class="row justify-content-center">
    <div class="col-md-8 mt-2">
        <div id="healthCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#healthCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#healthCarousel" data-slide-to="1"></li>
                <li data-target="#healthCarousel" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>1.jpeg" class="d-block w-100" alt="Gerakan hidup sehat">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>2.jpeg" class="d-block w-100" alt="Konsumsi makanan">
                </div>
                <div class="carousel-item">
                    <img src="<?= base_url('assets/img/healthReport/banners/'); ?>3.jpeg" class="d-block w-100" alt="Jaga jarak">
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
                <p class="card-title">How are you today?</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <!-- <img class="responsive-image" src="<?= base_url('assets/img/healthReport/main-logo.svg'); ?>" alt="Health Check main logo"> -->
                        <p class="card-text text-center">
                            <?= date("l, j M o"); ?>
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col text-center">
                        <a data-toggle="modal" data-target="#modal_healthy" class="btn bg-gray-light text-center">
                            <div class="row">
                                <div class="col">
                                    <img class="img-md" src="<?= base_url('assets/img/healthReport/_healthy.svg'); ?>" alt="healthy" srcset=""> <br/>
                                </div>
                            </div>
                            Healty
                        </a>
                    </div>
                    <div class="col text-center">
                        <button data-toggle="modal" data-target="#modal_sick" class="btn bg-gray-light text-center">
                            <img class="img-md" src="<?= base_url('assets/img/healthReport/_sick.svg'); ?>" alt="sick" srcset=""> <br/>
                            Sick
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <small>
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
                <h5 class="modal-title" id="modal_healthyLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                
                            </div>
                            <div class="col-sm-6">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sick -->
<div class="modal fade" id="modal_sick" tabindex="-1" role="dialog" aria-labelledby="modal_sickLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_sickLabel">Anda sakit apa?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" action="">
                    <!-- checkbox kategori sakit -->
                    <div class="form-group">
                        <div class="row m-0 p-0">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="flu" />
                                    <label class="text-center" for="flu">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/flu.svg'); ?>" /> <br>
                                        <small class="m-0">Flu</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="batuk" />
                                    <label class="text-center" for="batuk">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/batuk.svg'); ?>" /> <br>
                                        <small class="m-0">Batuk</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="demam" />
                                    <label class="text-center" for="demam">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/demam.svg'); ?>" /> <br>
                                        <small class="m-0">Demam</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="diare" />
                                    <label class="text-center" for="diare">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/diare.svg'); ?>" /> <br>
                                        <small class="m-0">Diare</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="ispa" />
                                    <label class="text-center" for="ispa">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/ispa.svg'); ?>" /> <br>
                                        <small class="m-0">ISPA</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="sakit_mata" />
                                    <label class="text-center" for="sakit_mata">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/sakit-mata.svg'); ?>" /> <br>
                                        <small class="m-0">Mata</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="magh" />
                                    <label class="text-center" for="magh">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/magh.svg'); ?>" /> <br>
                                        <small class="m-0">Magh</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="sakit_gigi" />
                                    <label class="ml-2" for="sakit_gigi">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/sakit-gigi.svg'); ?>" /> <br>
                                        <small class="m-0">Gigi</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="pusing" />
                                    <label class="text-center" for="pusing">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/pusing.svg'); ?>" /> <br>
                                        <small class="m-0">Pusing</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-4 col-4 d-flex align-items-center m-0 p-0 justify-content-center">
                                <div class="form-check d-flex align-items-center justify-content-center m-0 p-0">
                                    <input type="checkbox" id="migrain" />
                                    <label class="text-center" for="migrain">
                                        <img src="<?= base_url('assets/img/healthReport/sick categories/migrain.svg'); ?>" /> <br>
                                        <small class="m-0">Migrain</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div><!-- /checkbox kategori sakit -->
                    <div class="form-group">

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>