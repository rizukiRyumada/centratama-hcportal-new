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
                    <div class="row row-survey row-survey-striped justify-content-center pr-2 py-2 d-flex">
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"></div>
                        <div class="col-5 departemen-name d-flex align-items-center m-0 py-0 pl-0 pr-5 justify-content-center"><div class="text-center">Kompetensi</div></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-danger">0</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-danger">1</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-warning">2</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-warning">3</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-info">4</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-success">5</span></p></div>
                    </div>

                    <div class="row row-survey pr-2 py-2">
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center">
                            <p class="p-0 m-0">1</p>
                        </div>
                        <div class="col-5 departemen-name d-flex align-items-center px-0">sdfdsfds</div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="0" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="1" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="2" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="3" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="4" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="5" required></div></div>
                    </div>
                    <!-- /kompetensi dasar -->

                    <hr/>
                    
                    <!-- tambahan kompetensi untuk supervisor - manager level -->
                    <div class="row row-survey row-survey-striped justify-content-center pr-2 py-2 d-flex">
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"></div>
                        <div class="col-5 departemen-name d-flex align-items-center m-0 py-0 pl-0 pr-5 justify-content-center"><div class="text-center">Tambahan Kompetensi untuk Supervisor<br/>Manager level</div></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-danger">0</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-danger">1</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-warning">2</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-warning">3</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-info">4</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-success">5</span></p></div>
                    </div>

                    <div class="row row-survey pr-2 py-2">
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center">
                            <p class="p-0 m-0">1</p>
                        </div>
                        <div class="col-5 departemen-name d-flex align-items-center px-0">sdfdsfds</div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="0" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="1" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="2" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="3" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="4" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="5" required></div></div>
                    </div>
                    <!-- tambahan kompetensi untuk supervisor - manager level -->

                    <hr/>
                    
                    <!-- tambahan kompetensi untuk supervisor - manager level -->
                    <div class="row row-survey row-survey-striped justify-content-center pr-2 py-2 d-flex">
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"></div>
                        <div class="col-5 departemen-name d-flex align-items-center m-0 py-0 pl-0 pr-5 justify-content-center"><div class="text-center">Tambahan Kompetensi untuk General Manager<br/>Vice President Level</div></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-danger">0</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-danger">1</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-warning">2</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-warning">3</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-info">4</span></p></div>
                        <div class="align-self-center col-1 text-center"><p class="card-text text-center"><span class="py-2 badge badge-survey-tag badge-success">5</span></p></div>
                    </div>

                    <div class="row row-survey pr-2 py-2">
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center">
                            <p class="p-0 m-0">1</p>
                        </div>
                        <div class="col-5 departemen-name d-flex align-items-center px-0">sdfdsfds</div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="0" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="1" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="2" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="3" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="4" required></div></div>
                        <div class="col-1 departemen-nilai d-flex align-items-center m-0 p-0 justify-content-center"><div class="form-check d-flex align-items-center justify-content-center m-0 p-0"><input class="form-check-input m-0 p-0" type="radio" name="name" value="5" required></div></div>
                    </div> <!-- tambahan kompetensi untuk supervisor - manager level -->

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