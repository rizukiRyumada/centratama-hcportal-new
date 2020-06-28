<!-- gambar tema -->
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-0">
                <img class="responsive-image" src="<?= base_url('assets/'); ?>img/survey/eng_tema.png" alt="tema">
            </div>
        </div>
    </div>
</div><!-- /gambar tema -->

<!-- penjelasan -->
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-warning card-outline">
            <div class="card-header"><?= $survey_title; ?></div>
            <div class="card-body">
                <p class="card-text">
                    <ul class="pl-4">
                        <li>Survey ini terdiri dari 16 pertanyaan</li>
                        <li>Jawaban / opini Individu adalah sepenuhnya <b>RAHASIA</b></li>
                        <li>Diharapkan kejujuran dan spontanistas dalam pengisian survey</li>
                        <li>Tidak ada jawaban / opini yang benar atau salah</li>
                        <li>Gunakan kesempatan sebaik-baiknya ini untuk memberikan opini Saudara</li>
                    </ul>
                </p>
                <p class="card-text">
                    Isilah pertanyaan survey pada nomor jawaban yang sesuai dengan opini Saudara dengan kategori: <br/>
                    <table>
                        <tr>
                            <td><span class="badge badge-danger">1</span></td>
                            <td>:</td>
                            <td>Sangat tidak sesuai</td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-orange">2</span></td>
                            <td>:</td>
                            <td>Tidak sesuai</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-warning">3</span></td>
                            <td>:</td>
                            <td>Netral, antara sesuai dan tidak</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-info">4</span></td>
                            <td>:</td>
                            <td>Sesuai</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-success">5</span></td>
                            <td>:</td>
                            <td>Sangat Sesuai</td>
                        </tr>
                    </table>
                </p>
            </div>
        </div>
    </div>
</div><!-- /penjelasan -->

<!-- index nomor -->
<?php $x=1; ?>

<form id="formEngSuvey" action="<?= base_url('survey/'); ?>engSubmit" method="post" autocomplete="true">
    <!-- form survey enggagement -->
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body border-bottom border-warning">
                    <div class="row justify-content-center">
                        <div class="col-1"><p class="card-text text-center text-index-responsive">No.</p></div>
                        <div class="col"><p class="card-text text-center text-responsive">Pertanyaan</p> </div>
                        <div class="col-1 text-center"><span class="badge badge-danger">1</span></div>
                        <div class="col-1 text-center"><span class="badge bg-orange">2</span></div>
                        <div class="col-1 text-center"><span class="badge badge-warning">3</span></div>
                        <div class="col-1 text-center"><span class="badge badge-info">4</span></div>
                        <div class="col-1 text-center"><span class="badge badge-success">5</span></div>
                    </div>
                </div>
                <div class="card-body">
                    <?php foreach($survey_data as $v): ?>
                        <div id="<?= $v['id']; ?>" class="row py-2 <?php 
                                if($x % 2 != 0){
                                    echo"bg-gray-light  ";
                                }
                            ?>">
                            <div class="col-1"><p class="card-text text-center text-index-responsive"><?= $x.'.'; ?></p></div>
                            <div class="col text-responsive"><?= $v['pertanyaan']; ?></div>
                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center"><input class="form-check-input" type="radio" name="<?= $v['id']; ?>" value="1" required></div></div>
                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center"><input class="form-check-input" type="radio" name="<?= $v['id']; ?>" value="2" required></div></div>
                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center"><input class="form-check-input" type="radio" name="<?= $v['id']; ?>" value="3" required></div></div>
                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center"><input class="form-check-input" type="radio" name="<?= $v['id']; ?>" value="4" required></div></div>
                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center"><input class="form-check-input" type="radio" name="<?= $v['id']; ?>" value="5" required></div></div>
                        </div>
                        <?php $x++; ?>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div><!-- /form survey enggagement -->

    <!-- tombol submit -->
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <button id="submitForm" type="submit" class="btn btn-success float-right d-none" value="Submit"><i class="fa fa-paper-plane color-white"></i> Submit</button>
                    <button id="cekEngForm" type="button" class="btn btn-warning float-right"><i class="fa fa-paper-plane color-white"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>