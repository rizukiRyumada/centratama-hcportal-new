<!-- TODO personal Level buat ngatur pertanyaan Kompetensi -->
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">
                        <a href="<?= $direct_url; ?>" class="btn btn-primary"><i class="fas fa-chevron-left"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card ">
                            <div class="card-body box-profile">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="row h-100">
                                            <div class="col align-self-center">
                                                <div>
                                                <div class="text-center">
                                                    <!-- PRODUCTION alamat ganti jadi /user-img/ -->
                                                    <!-- <img class="border border-gray-dark img-fluid" src="<?php if($exist_empPhoto == true){ echo base_url('/user-img/'.$employee['nik'].'.jpg'); } else { echo base_url('/assets/img/user.svg'); } ?>" alt="User profile picture"> -->
                                                    <img class="border border-gray-dark img-fluid" src="<?php if($exist_empPhoto == true){ echo base_url('/assets/img/employee/'.$employee['nik'].'.jpg'); } else { echo base_url('/assets/img/user.svg'); } ?>" alt="User profile picture">
                                                </div>
                                                <!-- <h3 class="profile-username text-center"></h3> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>NIK</b> <a class="float-right"><?= $employee['nik']; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Nama</b> <a class="float-right"><?= $employee['emp_name']; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Hierarchy</b>
                                                <a class="float-right">
                                                    <span class="badge 
                                                        <?php switch ($employee['hirarki_org']){
                                                            case "N":
                                                                echo "badge-danger";
                                                                break;
                                                            case "N-1":
                                                                echo "badge-warning";
                                                                break;
                                                            case "N-2":
                                                                echo "badge-success";
                                                                break;
                                                            case "N-3":
                                                                echo "badge-info";
                                                                break;
                                                            default:
                                                                echo "badge-primary";
                                                        } ?>
                                                    ">
                                                        <?= $employee['hirarki_org']; ?>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Position</b> <a class="float-right"><?= $employee['position_name']; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-5">
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Department</b> <a class="float-right"><?= $employee['departemen']; ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>Division</b> <a class="float-right"><?= $employee['divisi']; ?></a>
                                            </li>
                                            <!-- <li class="list-group-item">
                                                <b>Personal Level</b> <a class="float-right">
                                                    <div class="badge
                                                        <?php if($employee['level_personal'] < 10): ?>
                                                            badge-primary
                                                        <?php elseif($employee['level_personal'] < 18): ?>
                                                            bg-orange
                                                        <?php else: ?>
                                                            bg-purple    
                                                        <?php endif; ?>
                                                    "><?= $employee['level_personal']; ?></div>
                                                </a>
                                            </li> -->
                                            <li class="list-group-item">
                                                <b>Start of Contract</b> <a class="float-right"><?= date("j F Y", strtotime($contract['date_start'])); ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <b>End of Contract</b> <a class="float-right"><?= date("j F Y", strtotime($contract['date_end'])); ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <!-- <p class="text">Pilihlah skala penilaian di setiap pernyataan yang paling sesuai berdasarkan pada penilaian yang telah dilakukan dengan menggunakan skala penilaian berikut:</p> -->
                        <div class="row">
                            <div class="col-12">
                                <table id="tabelSkala" >
                                    <thead>
                                        <tr class="text-center">
                                            <th class="border-right border-primary px-2">Skala</th>
                                            <th class="border-right border-primary px-2">Keterangan</th>
                                            <th class="px-2">Batasan Nilai Rata-rata</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center border-right border-primary px-2"><span class="badge badge-danger">0</span></td>
                                            <td class="border-right border-primary px-2">Tidak Mencapai/Gagal</td>
                                            <td class="text-center px-2"><0.50</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border-right border-primary px-2"><span class="badge badge-danger">1</span></td>
                                            <td class="border-right border-primary px-2">Kurang Baik</td>
                                            <td class="text-center px-2">0.50 – 1.50</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border-right border-primary px-2"><span class="badge badge-warning">2</span></td>
                                            <td class="border-right border-primary px-2">Cukup Baik</td>
                                            <td class="text-center px-2">1.51 – 2.50</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border-right border-primary px-2"><span class="badge badge-warning">3</span></td>
                                            <td class="border-right border-primary px-2">Baik</td>
                                            <td class="text-center px-2">2.51 – 3.50</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border-right border-primary px-2"><span class="badge badge-success">4</span></td>
                                            <td class="border-right border-primary px-2">Sangat baik</td>
                                            <td class="text-center px-2">3.51 – 4.50</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center border-right border-primary px-2"><span class="badge badge-success">5</span></td>
                                            <td class="border-right border-primary px-2">Luar Biasa</td>
                                            <td class="text-center px-2">4.51 – 5.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $x = 1; // buat penomoran soal ?>
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline">
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <div class="card-header">
                <h5 class="m-0">
                    Soft Competency
                </h5>
            </div>
            <div class="card-body">
                <!-- kompetensi dasar -->
                <div class="row py-2 bg-primary">
                    <div class="col-6"><p class="m-0 font-weight-bold text-center">Kompetensi</p></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">4</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                </div>
                <?php foreach($pertanyaan as $v): ?>
                    <?php if($v['id_pertanyaan_tipe'] == "A1"): ?>
                        <div class="row border-bottom border-gray-light py-2">
                            <div class="col">
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <p class="m-0 font-weight-bold"><?= $v['pertanyaan_judul']; ?></p>
                                            <p class="m-0"><?= $v['pertanyaan']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>1" name="<?= $v['id_pertanyaan']; ?>" value="0" disabled="true" />
                                            <label for="<?= $v['id_pertanyaan']; ?>1"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>2" name="<?= $v['id_pertanyaan']; ?>" value="1" disabled="true" />
                                            <label for="<?= $v['id_pertanyaan']; ?>2"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>3" name="<?= $v['id_pertanyaan']; ?>" value="2" disabled="true" />
                                            <label for="<?= $v['id_pertanyaan']; ?>3"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>4" name="<?= $v['id_pertanyaan']; ?>" value="3" disabled="true" />
                                            <label for="<?= $v['id_pertanyaan']; ?>4"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                        <div class="icheck-success">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>5" name="<?= $v['id_pertanyaan']; ?>" value="4" disabled="true" />
                                            <label for="<?= $v['id_pertanyaan']; ?>5"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                        <div class="icheck-success">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>6" name="<?= $v['id_pertanyaan']; ?>" value="5" disabled="true" />
                                            <label for="<?= $v['id_pertanyaan']; ?>6"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach;?>
                <!-- rata-rata nilai -->
                <div class="row border-bottom py-2 bg-gray">
                    <div class="col">
                        <div class="row justify-content-end">
                            <div class="col-md-auto align-self-center font-weight-bold">Nilai Rata-Rata (1) <sub>[Jumlah Skala/Jumlah Kompetensi]</sub></div>
                            <div class="col-md-2 text-right mt-2 mt-md-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="rerata_A1" readonly>
                                    <div class="input-group-append">
                                        <span id="jumlah_A1" class="input-group-text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /rata-rata nilai -->
                <!-- /kompetensi dasar -->

                <!-- tambahan kompetensi untuk supervisor - manager level -->
                <?php if($level_personal > 9): ?>
                    <div class="row py-2 bg-primary mt-4">
                        <div class="col-6"><p class="m-0 font-weight-bold text-center">Tambahan Kompetensi untuk Supervisor<br/>Manager level</p></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">4</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                    </div>
                    <?php foreach($pertanyaan as $v): ?>
                        <?php if($v['id_pertanyaan_tipe'] == "A2"): ?>
                            <div class="row border-bottom border-orange py-2">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-6">
                                            <div>
                                                <p class="m-0 font-weight-bold"><?= $v['pertanyaan_judul']; ?></p>
                                                <p class="m-0"><?= $v['pertanyaan']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-danger">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>1" name="<?= $v['id_pertanyaan']; ?>" value="0" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>1"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-danger">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>2" name="<?= $v['id_pertanyaan']; ?>" value="1" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>2"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-warning">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>3" name="<?= $v['id_pertanyaan']; ?>" value="2" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>3"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-warning">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>4" name="<?= $v['id_pertanyaan']; ?>" value="3" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>4"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-success">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>5" name="<?= $v['id_pertanyaan']; ?>" value="4" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>5"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-success">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>6" name="<?= $v['id_pertanyaan']; ?>" value="5" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>6"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach;?>
                    <!-- rata-rata nilai -->
                    <div class="row border-bottom py-2 bg-gray">
                        <div class="col">
                            <div class="row justify-content-end">
                                <div class="col-md-auto align-self-center font-weight-bold">Nilai Rata-Rata (2) <sub>[Jumlah Skala/Jumlah Kompetensi]</sub></div>
                                <div class="col-md-2 text-right mt-2 mt-md-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="rerata_A2" readonly>
                                        <div class="input-group-append">
                                            <span id="jumlah_A2" class="input-group-text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /rata-rata nilai -->
                <?php endif; ?>
                <!-- tambahan kompetensi untuk supervisor - manager level -->

                <!-- tambahan kompetensi untuk General Manager Vice President Level -->
                <?php if($level_personal > 17): ?>
                    <div class="row py-2 bg-primary mt-4">
                        <div class="col-6"><p class="m-0 font-weight-bold text-center">Tambahan Kompetensi untuk General Manager<br/>Vice President Level</p></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">4</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                    </div>
                    <?php foreach($pertanyaan as $v): ?>
                        <?php if($v['id_pertanyaan_tipe'] == "A3"): ?>
                            <div class="row border-bottom border-purple py-2">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-6">
                                            <div>
                                                <p class="m-0 font-weight-bold"><?= $v['pertanyaan_judul']; ?></p>
                                                <p class="m-0"><?= $v['pertanyaan']; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-danger">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>1" name="<?= $v['id_pertanyaan']; ?>" value="0" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>1"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-danger">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>2" name="<?= $v['id_pertanyaan']; ?>" value="1" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>2"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-warning">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>3" name="<?= $v['id_pertanyaan']; ?>" value="2" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>3"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-warning">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>4" name="<?= $v['id_pertanyaan']; ?>" value="3" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>4"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-success">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>5" name="<?= $v['id_pertanyaan']; ?>" value="4" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>5"></label>
                                            </div>
                                        </div>
                                        <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                            <div class="icheck-success">
                                                <input type="radio" id="<?= $v['id_pertanyaan']; ?>6" name="<?= $v['id_pertanyaan']; ?>" value="5" disabled="true" />
                                                <label for="<?= $v['id_pertanyaan']; ?>6"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach;?>
                    <!-- rata-rata nilai -->
                    <div class="row border-bottom py-2 bg-gray">
                        <div class="col">
                            <div class="row justify-content-end">
                                <div class="col-md-auto align-self-center font-weight-bold">Nilai Rata-Rata (3) <sub>[Jumlah Skala/Jumlah Kompetensi]</sub></div>
                                <div class="col-md-2 text-right mt-2 mt-md-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="rerata_A3" readonly>
                                        <div class="input-group-append">
                                            <span id="jumlah_A3" class="input-group-text"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /rata-rata nilai -->
                <?php endif; ?>
                <!-- tambahan kompetensi untuk General Manager Vice President Level -->
            </div>
            <!-- rata-rata nilai soal A -->
            <div class="card-footer bg-gray">
                <div class="row py-2">
                    <div class="col">
                        <div class="row justify-content-end">
                            <div class="col-md-auto align-self-center font-weight-bold">Total Soft Competecy Value Value <sub>[Total Nilai Rata-Rata (1+2+3) / Sub Level Kompetensi]</sub> </div>
                            <div class="col-md-2 text-right mt-2 mt-md-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="rerata_A" readonly>
                                    <div class="input-group-append">
                                        <span id="jumlah_A" class="input-group-text"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /rata-rata nilai soal A -->
        </div>

        <div class="card card-primary card-outline">
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg" width="80" height="80"></div>
            <div class="card-header">
                <h5 class="m-0">
                    Technical Competency
                </h5>
            </div>
            <div class="card-body">
                <!-- kompetensi teknikal -->
                <div class="row py-2 bg-primary">
                    <div class="col-6"><p class="m-0 font-weight-bold text-center">Kompetensi</p></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">4</span></div>
                    <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                </div>
                <?php for($x = 0; $x < 5; $x++): ?>
                    <div class="row border-bottom border-gray-light py-2">
                        <div class="col">
                            <div class="row">
                                <div class="col-6 align-self-center">
                                    <div>
                                        <input class="form-control" type="text" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan" readonly >
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                    <div class="icheck-danger">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>1" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" value="0" disabled="true" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>1"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                    <div class="icheck-danger">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>2" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" value="1" disabled="true" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>2"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                    <div class="icheck-warning">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>3" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" value="2" disabled="true" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>3"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                    <div class="icheck-warning">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>4" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" value="3" disabled="true" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>4"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                    <div class="icheck-success">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>5" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" value="4" disabled="true" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>5"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center px-0 px-sm-2">
                                    <div class="icheck-success">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>6" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" value="5" disabled="true" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>6"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor;?>
                <!-- rata-rata nilai -->
                <div class="row border-bottom py-2 bg-gray">
                    <div class="col">
                        <div class="row justify-content-end">
                            <div class="col-md-auto align-self-center font-weight-bold">Nilai Rata-Rata <sub>[Jumlah Skala/Jumlah Kompetensi]</sub></div>
                            <div class="col-md-2 text-right mt-2 mt-md-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="rerata_B0" readonly>
                                    <div class="input-group-append">
                                        <span id="jumlah_B0" class="input-group-text">/0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /rata-rata nilai -->
                <!-- /kompetensi teknikal -->
            </div>
        </div>

        <div class="card">
            <div class="overlay"></div>
            <div class="card-body bg-gray">
                <!-- rata-rata nilai -->
                <div class="row py-2">
                    <div class="col">
                        <div class="row justify-content-end">
                            <div class="col-md-auto align-self-center font-weight-bold">
                                Total Performance Value All Competency <sub>[(A+B)/2]</sub>
                            </div>
                            <div class="col-md-4 text-right mt-2 mt-md-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="rerata_keseluruhan" readonly>
                                    <div class="input-group-append">
                                        <span id="jumlah_keseluruhan" class="input-group-text"><span id="nilai_keterangan"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /rata-rata nilai -->
            </div>
        </div>
    </div>
</div>