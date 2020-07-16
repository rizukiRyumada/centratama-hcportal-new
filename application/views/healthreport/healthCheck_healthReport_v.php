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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-6 text-center">
                                        <div class="form-check">
                                            <input type="checkbox" id="flu" />
                                            <label class="ml-2" for="flu">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/flu.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Flu</p>
                                            </label>
                                        </div>
                                        <div class="form-check mt-3">
                                            <input type="checkbox" id="batuk" />
                                            <label class="ml-2" for="batuk">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/batuk.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Batuk</p>
                                            </label>
                                        </div>
                                        <div class="form-check mt-3">
                                            <input type="checkbox" id="demam" />
                                            <label class="ml-2" for="demam">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/demam.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Demam</p>
                                            </label>
                                        </div>
                                        <div class="form-check mt-3">
                                            <input type="checkbox" id="diare" />
                                            <label class="ml-2" for="diare">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/diare.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Diare</p>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-center">
                                        <div class="form-check">
                                            <input type="checkbox" id="ispa" />
                                            <label class="ml-2" for="ispa">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/ispa.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">ISPA</p>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="sakit_mata" />
                                            <label class="ml-2" for="sakit_mata">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/sakit-mata.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Sakit Mata</p>
                                            </label>
                                        </div>
                                        <div class="form-check mt-3">
                                            <input type="checkbox" id="magh" />
                                            <label class="ml-2" for="magh">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/magh.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Magh</p>
                                            </label>
                                        </div>
                                        <div class="form-check mt-3">
                                            <input type="checkbox" id="sakit_gigi" />
                                            <label class="ml-2" for="sakit_gigi">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/sakit-gigi.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Sakit Gigi</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 text-center">
                                <div class="row">
                                    <div class="col-sm-12 col-6">
                                        <div class="form-check mt-3">
                                            <input type="checkbox" id="pusing" />
                                            <label class="ml-2" for="pusing">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/pusing.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Pusing</p>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-6">
                                        <div class="form-check">
                                            <input type="checkbox" id="migrain" />
                                            <label class="ml-2" for="migrain">
                                                <img src="<?= base_url('assets/img/healthReport/sick categories/migrain.svg'); ?>" /> <br>
                                                <p class="m-0 text-sm-center">Migrain</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
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