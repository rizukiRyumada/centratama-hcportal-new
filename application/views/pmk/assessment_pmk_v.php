<div class="row"></div>
    <div class="col">
        <div class="card card-outline card-danger">
            <div class="card-body box-profile">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="text-center">
                            <i class="fa fa-user-circle fa-5x"></i>

                            <!-- <img class="profile-user-img img-fluid img-circle"
                            src="../../dist/img/user4-128x128.jpg"
                            alt="User profile picture"> -->
                        </div>
                        <h3 class="profile-username text-center"><?= $employee['emp_name']; ?></h3>
                    </div>
                </div>

                <ul class="list-group list-group-unbordered mb-3">
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
                        <b>Division</b> <a class="float-right"><?= $employee['divisi']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Department</b> <a class="float-right"><?= $employee['departemen']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Position</b> <a class="float-right"><?= $employee['position_name']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- TODO personal Level buat ngatur Kompetensi -->
<div class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-body">
                <p class="text">Pilihlah skala penilaian di setiap pernyataan yang paling sesuai berdasarkan pada penilaian yang telah dilakukan dengan menggunakan skala penilaian berikut:</p>
                <div class="row">
                    <div class="col-lg-7 col-12">
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
                                    <td class="text-center border-right border-primary px-2"><span class="badge badge-info">4</span></td>
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

<?php $x = 1; // buat penomoran soal ?>

<form action="<?= base_url('pmk/saveAssessment'); ?>" method="post">
    <div class="row">
        <div class="col">
            <div class="card card-primary card-outline">
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
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-info">4</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                    </div>
                    <div class="container-fluid mb-3">
                        <?php foreach($pertanyaan as $v): ?>
                            <?php if($v['id_pertanyaan_tipe'] == "A1"): ?>
                                <div class="row border-bottom border-gray-light py-2">
                                    <div class="col-6">
                                        <div>
                                            <p class="m-0 font-weight-bold"><?= $v['pertanyaan_judul']; ?></p>
                                            <p class="m-0"><?= $v['pertanyaan']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>1" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>1"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>2" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>2"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>3" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>3"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>4" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>4"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-info">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>5" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>5"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-success">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>6" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>6"></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach;?>
                    </div>
                    <!-- /kompetensi dasar -->

                    <!-- tambahan kompetensi untuk supervisor - manager level -->
                    <div class="row py-2 bg-orange">
                        <div class="col-6"><p class="m-0 font-weight-bold text-center">Tambahan Kompetensi untuk Supervisor<br/>Manager level</p></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-info">4</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                    </div>
                    <div class="container-fluid mb-3">
                        <?php foreach($pertanyaan as $v): ?>
                            <?php if($v['id_pertanyaan_tipe'] == "A2"): ?>
                                <div class="row border-bottom border-orange py-2">
                                    <div class="col-6">
                                        <div>
                                            <p class="m-0 font-weight-bold"><?= $v['pertanyaan_judul']; ?></p>
                                            <p class="m-0"><?= $v['pertanyaan']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>1" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>1"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>2" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>2"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>3" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>3"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>4" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>4"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-info">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>5" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>5"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-success">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>6" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>6"></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach;?>
                    </div>
                    <!-- tambahan kompetensi untuk supervisor - manager level -->

                    <!-- tambahan kompetensi untuk supervisor - manager level -->
                    <div class="row py-2 bg-purple">
                        <div class="col-6"><p class="m-0 font-weight-bold text-center">Tambahan Kompetensi untuk General Manager<br/>Vice President Level</p></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-info">4</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                    </div>
                    <div class="container-fluid mb-3">
                        <?php foreach($pertanyaan as $v): ?>
                            <?php if($v['id_pertanyaan_tipe'] == "A3"): ?>
                                <div class="row border-bottom border-purple py-2">
                                    <div class="col-6">
                                        <div>
                                            <p class="m-0 font-weight-bold"><?= $v['pertanyaan_judul']; ?></p>
                                            <p class="m-0"><?= $v['pertanyaan']; ?></p>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>1" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>1"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-danger">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>2" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>2"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>3" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>3"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-warning">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>4" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>4"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-info">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>5" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>5"></label>
                                        </div>
                                    </div>
                                    <div class="col-1 text-center align-self-center">
                                        <div class="icheck-success">
                                            <input type="radio" id="<?= $v['id_pertanyaan']; ?>6" name="<?= $v['id_pertanyaan']; ?>" />
                                            <label for="<?= $v['id_pertanyaan']; ?>6"></label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach;?>
                    </div>
                    <!-- tambahan kompetensi untuk supervisor - manager level -->
                </div>
            </div>

            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h5 class="m-0">
                        Technical Competency
                    </h5>
                </div>
                <div class="card-body">
                    <!-- kompetensi dasar -->
                    <div class="row py-2 bg-maroon">
                        <div class="col-6"><p class="m-0 font-weight-bold text-center">Kompetensi</p></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">0</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-danger">1</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">2</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-warning">3</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-info">4</span></div>
                        <div class="col-1 align-self-center px-0 px-sm-2"><span class="py-2 badge badge-survey-tag w-100 font-weight-bold badge-success">5</span></div>
                    </div>
                    <div class="container-fluid mb-3">
                        <?php for($x = 0; $x < 5; $x++): ?>
                            <div class="row border-bottom border-gray-light py-2">
                                <div class="col-6">
                                    <div>
                                        <p class="m-0 font-weight-bold">
                                            <input class="form-control" type="text" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>_pertanyaan" id="">
                                        </p>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center">
                                    <div class="icheck-danger">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>1" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>1"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center">
                                    <div class="icheck-danger">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>2" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>2"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center">
                                    <div class="icheck-warning">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>3" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>3"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center">
                                    <div class="icheck-warning">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>4" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>4"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center">
                                    <div class="icheck-info">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>5" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>5"></label>
                                    </div>
                                </div>
                                <div class="col-1 text-center align-self-center">
                                    <div class="icheck-success">
                                        <input type="radio" id="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>6" name="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>" />
                                        <label for="B0-<?= str_pad($x, 2, '0', STR_PAD_LEFT); ?>6"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endfor;?>
                    </div>
                    <!-- /kompetensi dasar -->
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-4">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-lg btn-success"><i class="fas fa-paper-plane"></i> Submit</button>
                                <button type="submit" class="btn btn-lg btn-warning"><i class="fas fa-save"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>