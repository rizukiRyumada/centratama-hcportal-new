<!-- gambar tema -->
<!-- <div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-0">
                <img class="responsive-image" src="<?= base_url('assets/'); ?>img/survey/f360_tema.png" alt="tema">
            </div>
        </div>
    </div>
</div> -->
<!-- /gambar tema -->

<!-- penjelasan -->
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-warning card-outline">
            <div class="card-header"><?= $survey_title; ?></div>
            <div class="card-body">
                <p class="card-text">
                    <span class="font-weight-bolder text-orange">360° Feedback</span> merupakan evaluasi penilaian kinerja karyawan 
                    berdasarkan feedback secara 360° untuk pengamatan menyeluruh kepada <span class="font-weight-bolder">N-1</span> dan 
                    <span class="font-weight-bolder">N-2</span> dengan menggunakan dasar penilaian <span class="font-weight-bolder">Core Value</span> 
                    dari Perusahaan, yaitu <span class="font-weight-bolder">CENT</span>.
                </p>
                <ul class="pl-4">
                    <li>Jawaban / opini Individu adalah sepenuhnya <b>RAHASIA</b></li>
                    <li>Diharapkan kejujuran dan spontanistas dalam pengisian survey</li>
                    <li>Tidak ada jawaban / opini yang benar atau salah</li>
                    <li>Gunakan kesempatan sebaik-baiknya ini untuk memberikan opini Saudara</li>
                </ul>
                <p class="card-text">
                    Isilah pertanyaan survey pada nomor jawaban yang sesuai dengan opini Saudara dengan kategori:
                </p>
                <table>
                    <tbody>
                        <tr>
                            <td class="px-2"><span class="badge badge-danger">1</span></td>
                            <td class="border-left px-2 border-warning">SK</td>
                            <td class="border-left px-2 border-warning">Sangat Kurang</td>
                        </tr>
                        <tr>
                            <td class="px-2"><span class="badge badge-warning">2</span></td>
                            <td class="border-left px-2 border-warning">K</td>
                            <td class="border-left px-2 border-warning">Kurang</td>
                        </tr>
                        <tr>
                            <td class="px-2"><span class="badge badge-info">3</span></td>
                            <td class="border-left px-2 border-warning">B</td>
                            <td class="border-left px-2 border-warning">Baik</td>
                        </tr>
                        <tr>
                            <td class="px-2"><span class="badge badge-success">4</span></td>
                            <td class="border-left px-2 border-warning">SB</td>
                            <td class="border-left px-2 border-warning">Sangat Baik</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- /penjelasan -->

<!-- form survey -->
<form id="formF360survey" action="<?= base_url('survey/'); ?>f360Submit" method="post" autocomplete="true">
    <!-- nik dinilai -->
    <input type="hidden" name="nik_dinilai" value="<?= $nik_dinilai; ?>">

    <!-- form survey 360 Feedback -->
    <?php foreach($pertanyaan as $value): ?>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card card-warning">
                    <div class="card-header">
                            <h5 class="card-title"><?= $value['nama_kategori']; ?></h5>
                    </div>
                    <!-- header jawaban -->
                    <div class="card-body border-bottom border-orange">
                        <div class="row justify-content-center d-flex">
                            <div class="align-self-center col-1"><p class="card-text text-center text-index-responsive">No.</p></div>
                            <div class="align-self-center col"><p class="card-text text-center text-responsive">Pertanyaan</p> </div>
                            <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-danger">1<br/><span class="badge-survey-tag-keterangan">SK</span></span></p></div>
                            <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag bg-warning">2<br/><span class="badge-survey-tag-keterangan">K</span></span></p></div>
                            <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-info">3<br/><span class="badge-survey-tag-keterangan">B</span></span></p></div>
                            <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-success">4<br/><span class="badge-survey-tag-keterangan">SB</span></span></p></div>
                        </div>
                    </div><!-- /header jawaban -->

                    <!-- index pertanyaan -->
                    <?php $x=1; ?>
                    <div class="card-body">
                        <?php foreach($value['survey_pertanyaan'] as $v): ?>
                            <div id="<?= $v['id']; ?>" class="row py-2 <?php 
                                    if($x % 2 != 0){
                                        echo"bg-gray-light  ";
                                    }
                                ?>">
                                <div class="col-1"><p class="card-text text-center text-index-responsive"><?= $x.'.'; ?></p></div>
                                <div class="col text-responsive"><?= $v['pertanyaan']; ?></div>
                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex m-0 p-0 align-items-center justify-content-center"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id']; ?>" value="1" required></div></div>
                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex m-0 p-0 align-items-center justify-content-center"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id']; ?>" value="2" required></div></div>
                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex m-0 p-0 align-items-center justify-content-center"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id']; ?>" value="3" required></div></div>
                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex m-0 p-0 align-items-center justify-content-center"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id']; ?>" value="4" required></div></div>
                            </div>
                            <?php $x++; ?>
                        <?php endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach;?><!-- /form survey 360 Feedback -->

    <!-- tombol submit -->
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <button id="submitForm" type="submit" class="btn btn-success float-right d-none" value="Submit"><i class="fa fa-paper-plane color-white"></i> Submit</button>
                    <button id="cekF360Form" type="button" class="btn btn-warning float-right"><i class="fa fa-paper-plane color-white"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
</form><!-- /form survey -->