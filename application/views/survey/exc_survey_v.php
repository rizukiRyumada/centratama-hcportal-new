<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                
                <p class="card-text">
                    Some quick example text to build on the card title and make up the bulk of the card's
                    content.
                </p>
                
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
            </div>
        </div>
        
        <div class="card card-primary card-outline">
            <div class="card-body">
                <h5 class="card-title">Card title</h5>
                
                <p class="card-text">
                    Some quick example text to build on the card title and make up the bulk of the card's
                    content.
                </p>
                <a href="#" class="card-link">Card link</a>
                <a href="#" class="card-link">Another link</a>
            </div>
        </div><!-- /.card -->
    </div>
    <!-- /.col-md-6 -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Featured</h5>
            </div>
            <div class="card-body">
                <h6 class="card-title">Special title treatment</h6>
                
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
        
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Featured</h5>
            </div>
            <div class="card-body">
                <h6 class="card-title">Special title treatment</h6>
                
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>
    </div>
    <!-- /.col-md-6 -->
</div>

<!-- tema -->
<div class="row m-3">
    <div class="col-12 d-flex justify-content-center">
        <div class="card form-card-wrapper">
            <img class="responsive-image" src="<?= base_url('assets/'); ?>img/tema.jpg" alt="tema">
        </div>
    </div>
</div>

<!-- penjelasan -->
<div class="row m-3">
    <div class="col-12 d-flex justify-content-center">
        <div class="card form-card-wrapper p-4">
            <div class="row">
                <div class="col-12">
                    <h1>Quarterly Service Excellence Survey for Q1 - 2020</h1>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <p>Survey Kepuasan ini dilakukan untuk menilai dan memberikan masukan bagi Departement terkait dalam 
                        melaksanakan tugas dan fungsi Departementnya untuk mendukung dan/atau menunjang kinerja Departement 
                        lainnya:</p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <p class="keterangan-penilaian">Keterangan Penilaian: <br/>
                        <span class="badge badge-danger">1</span> = 0%  |  Jauh Dibawah Harapan  |  "JDH" <br/>
                        <span class="badge badge-warning">2</span> = 35%  |  Kurang Sesuai Harapan  |  "KSH" <br/>
                        <span class="badge badge-info">3</span> = 70%  |  Sesuai Harapan  |  "SH" <br/>
                        <span class="badge badge-success">4</span> = 100%  |  Melebihi Harapan  |  "MH"
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card card-primary card-outline">
            <h5 class="card-title">Quarterly Service Excellence Survey for Q1 - 2020</h1>
            <p class="card-text">Survey Kepuasan ini dilakukan untuk menilai dan memberikan masukan bagi Departement terkait dalam 
                        melaksanakan tugas dan fungsi Departementnya untuk mendukung dan/atau menunjang kinerja Departement 
                        lainnya:</p>
        </div>
    </div>
</div>

<!-- penjelasan -->


<?php $x=1; //index buat pertanyaan ?>

<!-- form survey -->
<form id="formSurvey" action="<?= base_url('survey/'); ?>submitSurvey" method="post" autocomplete="on">
    <!-- pertanyaan kepuasan -->
    <?php foreach($survey1 as $v): ?>
        <div class="row m-3">
            <div class="col-12 d-flex justify-content-center">
                <div class="card form-card-wrapper"id="<?= $v['id']; ?>">
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="pertanyaan-wrapper">
                                <div class="col-1 pertanyaan-index-wrapper pr-0"><b><?= $x; $x++; ?>.</b></div>
                                <div class="col-11 pertanyaan-text-wrapper pl-0">
                                    <p><?= $v['pertanyaan'] ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong class="text-danger">*)Wajib diisi</strong>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-survey-wrapper">
                                    <div class="form-survey">
                                        <div class="row row-survey row-survey-striped">
                                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"></div>
                                            <div class="col-7 departemen-name d-flex align-items-center m-0 p-0"><div class="text-center">Jawaban untuk<br/>Departemen</div></div>
                                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-danger">1<br/>0%</p></div>
                                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-warning">2<br/>35%</p></div>
                                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-info">3<br/>70%</p></div>
                                            <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><p class="text-center m-0 badge badge-success">4<br/>100%</p></div>
                                        </div>
                                        <?php $y=1; ?>
                                        <?php foreach($departemen as $value):
                                            if($value['id'] != 0):?>
                                            <div class="row row-survey">
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
                                                <div class="col-7 departemen-name d-flex align-items-center pl-0"><?= $value['nama'] ?></div>
                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="1" required></div></div>
                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="2" required></div></div>
                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="3" required></div></div>
                                                <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check"><input class="form-check-input" type="radio" name="<?= $v['id'].'_'.$value['id'] ?>" value="4" required></div></div>
                                            </div>
                                            <?php endif;
                                        endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- pertanyaan kepuasan tipe 2 dengan dropdown list -->
    <!-- <div class="row m-3">
        <div class="col-12 d-flex justify-content-center">
            <div class="card form-card-wrapper p-4">
                <div class="row mt-2">
                    <div class="col-12">
                        <p class="pertanyaan">Penilaian terhadap aspek Kecepatan Departement Berikut dalam menanggapi setiap bantuan dan pertanyaan dalam rangka 
                            mendukung dan/atau menunjang kinerja Departement Saya</p>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-survey-wrapper">
                            <div class="form-survey">
                                <div class="row row-survey-striped">
                                    <div class="col-7 departemen-name d-flex align-items-center">Departemen</div>
                                    <div class="col-5 d-flex align-items-center text-center">Penilaian</div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-7 departemen-name d-flex align-items-center">Compensation & Benefit</div>
                                    <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                </div>
                                <div class="row row-survey-striped">
                                    <div class="col-7 departemen-name d-flex align-items-center">Organization Development</div>
                                    <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                </div>
                                <div class="row">
                                    <div class="col-7 departemen-name d-flex align-items-center">General Affairs</div>
                                    <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                </div>
                                <div class="row row-survey-striped">
                                    <div class="col-7 departemen-name d-flex align-items-center">Procurement & Supply Chain</div>
                                    <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                </div>
                                <div class="row">
                                    <div class="col-7 departemen-name d-flex align-items-center">Legal & Corsec</div>
                                    <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center "><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                </div>
                                <div class="row row-survey-striped mb-3">
                                    <div class="col-7 departemen-name d-flex align-items-center">Information Technology</div>
                                    <div class="col-5 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><select class="custom-select custom-select-sm my-1 mr-sm-2" id="inlineFormCustomSelectPref"><option selected>Nilai...</option><option value="1">1 = JDH (0%)</option><option value="2">2 = KSH (35%)</option><option value="3">3 = SH (70%)</option><option value="4">4 = MH (100%)</option></select></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- pertanyaan isian -->
    <?php foreach($survey2 as $v): ?>
        <div class="row m-3" id="<?= $v['id']; ?>">
            <div class="col-12 d-flex justify-content-center">
                <div class="card form-card-wrapper">
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-1 pertanyaan-index-wrapper pr-0"><b class="pertanyaan-index"><?= $x; $x++; ?>.</b></div>
                            <div class="col-11 pertanyaan-text-wrapper pl-0">
                                <p class="pertanyaan"><?= $v['pertanyaan']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong class="text-danger">*)Wajib diisi</strong>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea id="<?= $v['id_departemen']; ?>" name="<?= $v['id']; ?>_<?= $v['id_departemen'] ?>" class="form-control" rows="5" required placeholder="Jawaban untuk <?= $v['nama_departemen'] ?>" required></textarea>
                                    <small id="feedback-<?= $v['id_departemen'] ?>" class="float-right">0/1000 Karakter</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- tombol logout & submit -->
    <div class="row m-3">
        <div class="col-12 d-flex justify-content-center">
            <div class="card form-card-wrapper p-4 w-100">
                <div class="row mt-2">
                    <div class="col-6 d-block">
                        <button id="logoutButton" type="button" class="btn btn-danger float-left" data-toggle="modal" data-target="#logoutModal"><i class="fa fa-sign-out-alt color-white fa-rotate-180"></i> Logout</button>
                    </div>
                    <div class="col-6 d-block">
                        <button id="submitForm" type="submit" class="btn btn-success float-right d-none"  value="Submit">Submit <i class="fa fa-paper-plane color-white"></i></button>
                        <button id="cekForm" type="button" class="btn btn-success float-right">Submit <i class="fa fa-paper-plane color-white"></i></button>
                    </div>
                    <!-- <div class="col-12">
                        <div class="card d-none" id="konfirmSubmit">
                            <div class="card-body">
                                Jawaban anda akan disimpan dan anda tidak dapat mengisi form ini lagi. <br/><br/>
                                
                                Silakan tunggu arahan apabila ada jadwal untuk mengisi form survey ini lagi, terima kasih.
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success" value="Submit"><i class="fa fa-paper-plane color-white"></i> Ya, Submit</button>
                                <button id="cekLagiForm" type="button" class="btn btn-secondary" data-dismiss="card">Cek lagi <i class="fa fa-chevron-up color-white"></i></button>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</form>