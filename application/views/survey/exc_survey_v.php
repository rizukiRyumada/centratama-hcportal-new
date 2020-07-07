<!-- gambar tema -->
<!-- <div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-body p-0">
                <img class="responsive-image" src="<?= base_url('assets/'); ?>img/survey/exc_tema.jpg" alt="tema">
            </div>
        </div>
    </div>
</div> -->

<!-- penjelasan survey -->
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-orange card-outline">
            <div class="card-header">
                <h5 class="card-title"><?= $survey_title; ?></h1>
            </div>
            <div class="card-body">
                <p class="card-text">Survey Kepuasan ini dilakukan untuk menilai dan memberikan masukan bagi Departement terkait dalam 
                            melaksanakan tugas dan fungsi Departementnya untuk mendukung dan/atau menunjang kinerja Departement 
                            lainnya.</p>
            </div>
            <div class="card-body">
                <p class="keterangan-penilaian">Keterangan Penilaian: </p>
                <table>
                    <tr>
                        <td class="px-2"><span class="badge badge-danger">1</span></td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">0%</td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">Jauh Dibawah Harapan</td>
                    </tr>
                    <tr>
                        <td class="px-2"><span class="badge badge-warning">2</span></td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">35%</td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">Kurang Sesuai Harapan</td>
                    </tr>
                    <tr>
                        <td class="px-2"><span class="badge badge-info">3</span></td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">70%</td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">Sesuai Harapan</td>
                    </tr>
                    <tr>
                        <td class="px-2"><span class="badge badge-success">4</span></td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">100%</td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">Melebihi Harapan</td>
                    </tr>
                    <tr>
                        <td class="px-2"><span class="badge bg-gray text-white">TB</span></td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">N/A</td>
                        <td class="border-left px-2" style="border-color: #F05922 !important;">Tidak Berhubungan dengan Departemen</td>
                    </tr>
                </table>
                </p>
            </div>
        </div>
    </div>
</div>

<?php $x=1; //index buat pertanyaan ?>

<!-- form survey -->
<form id="formSurvey" action="<?= base_url('survey/'); ?>excSubmit" method="post" autocomplete="true">
    <!-- pertanyaan kepuasan -->
    <?php foreach($survey1 as $v): ?>
        <div class="row justify-content-center" id="<?= $v['id']; ?>">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><?= $x.'. '.$v['judul_pertanyaan']; ?><?php $x++; ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= $v['pertanyaan']; ?></p>
                        <p class="card-text"><strong class="text-danger">*)Wajib diisi</strong></p>
                    </div>
                    <div class="card-body">
                        <div class="form-survey-wrapper">
                            <div class="form-survey">
                                <div class="row row-survey row-survey-striped justify-content-center pr-2 py-2 d-flex">
                                    <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"></div>
                                    <div class="col-6 departemen-name d-flex align-items-center m-0 py-0 pl-0 pr-5 justify-content-center"><div class="text-center">Jawaban untuk<br/>Departemen</div></div>
                                    <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-danger">1<br/><span class="badge-survey-tag-keterangan">0%</span></span></p></div>
                                    <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-warning">2<br/><span class="badge-survey-tag-keterangan">35%</span></span></p></div>
                                    <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-info">3<br/><span class="badge-survey-tag-keterangan">70%</span></span></p></div>
                                    <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag badge-success">4<br/><span class="badge-survey-tag-keterangan">100%</span></span></p></div>
                                    <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="badge badge-survey-tag bg-gray">TB<br/><span class="badge-survey-tag-keterangan">N/A</span></span></p></div>
                                    <!-- <div class="col-1 d-flex align-items-center justify-content-center"><span class="text-center badge badge-survey-tag bg-gray text-white">TB<br/>N/A</span></div> -->
                                </div>
                                <?php $y=1; ?>
                                <?php foreach($departemen as $value):
                                    if($value['id'] != 0):?>
                                    <div class="row row-survey pr-2 py-2">
                                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center">
                                            <p class="p-0 m-0">
                                                <?php switch($y){ 
                                                    case 1:
                                                        echo "a.";
                                                        break;
                                                    case 2:
                                                        echo "b.";
                                                        break;
                                                    case 3:
                                                        echo "c.";
                                                        break;
                                                    case 4:
                                                        echo "d.";
                                                        break;
                                                    case 5:
                                                        echo "e.";
                                                        break;
                                                    case 6:
                                                        echo "f.";
                                                        break;
                                                }
                                                $y++; ?>
                                            </p>
                                        </div>
                                        <div class="col-6 departemen-name d-flex align-items-center px-0"><?= $value['nama'] ?></div>
                                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="1" required></div></div>
                                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="2" required></div></div>
                                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="3" required></div></div>
                                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="4" required></div></div>
                                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="N" required></div></div>
                                    </div>
                                    <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?><!-- pertanyaan kepuasan -->

    <!-- pertanyaan isian -->
    <?php foreach($survey2 as $v): ?>
        <div class="row justify-content-center" id="<?= $v['id']; ?>">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><?= $x.'. '.$v['judul_pertanyaan']; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="card-text mb-1"><?= $v['pertanyaan']; ?></div>
                        <p class="card-text mb-3"><strong class="text-danger">*)Wajib diisi</strong></p>
                        <div class="form-group-lg">
                            <textarea id="<?= $v['id_departemen']; ?>" name="<?= $v['id']; ?>_<?= $v['id_departemen'] ?>" class="form-control" rows="5" required placeholder="Jawaban untuk <?= $v['nama_departemen'] ?>" required></textarea>
                            <small id="feedback-<?= $v['id_departemen'] ?>" class="float-right">0/1000 Karakter</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $x++; ?>
    <?php endforeach;?><!-- /pertanyaan isian -->

    <!-- tombol submit -->
    <div class="row justify-content-center mb-3">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body">
                    <button id="submitForm" type="submit" class="btn btn-success float-right d-none"  value="Submit"><i class="fa fa-paper-plane color-white"></i> Submit</button>
                    <button id="cekForm" type="button" class="btn btn-warning float-right"><i class="fa fa-paper-plane color-white"></i> Submit</button>
                </div>
            </div>
        </div>
    </div><!-- /tombol submit -->
</form><!-- /form survey -->