<script>
    // Ambil data employee
    /* ------------------------- employe detail desktop ------------------------- */
    <?php for($x=1; $x<=$max_feedback_other_peers; $x++){ ?>
        $('#f360getEmployeDesktop_<?= $x; ?>').on('change', () => {
            let nik = $('#f360getEmployeDesktop_<?= $x; ?>').val();
            let urutan = <?= $x; ?>;
            let useragent = 0 // penanda user agent desktop

            getEmployeDetail(nik, urutan, useragent);
        });
    <?php } ?>
    /* -------------------------- employe detail mobile ------------------------- */
    <?php for($x=1; $x<=$max_feedback_other_peers; $x++){ ?>
        $('#f360getEmployeMobile_<?= $x; ?>').on('change', () => {
            let nik = $('#f360getEmployeMobile_<?= $x; ?>').val();
            let urutan = <?= $x; ?>;
            let useragent = 1;

            getEmployeDetail(nik, urutan, useragent);
        });
    <?php } ?>

    // modal trigger button Employe info
    $('.btnEmployeInfo').click(function(e){
        e.preventDefault(); // prevent default action on href click

        // take data from button
        let nik = $(this).data('nik');
        let emp_name = $(this).data('emp_name');
        let departemen = $(this).data('departemen');
        let divisi = $(this).data('divisi');
        let position_name = $(this).data('position_name');

        console.log(nik);
        console.log(emp_name);
        console.log(departemen);
        console.log(divisi);
        console.log(position_name);

        // place the data to modal
        $('#modalEmployeInfoLabel').text(emp_name);
        $('#infoModal_nik').text(nik);
        $('#infoModal_divisi').text(divisi);
        $('#infoModal_departemen').text(departemen);
        $('#infoModal_position').text(position_name);

        // show the info Modal
        $('#modalEmployeInfo').modal('show');
    });

    /* -------------------------------------------------------------------------- */
    /*                                  FUNCTIONs                                 */
    /* -------------------------------------------------------------------------- */
    
    // function get Employe Detail
    function getEmployeDetail(nik, urutan, useragent){
        $.ajax({
            url: "<?= base_url('survey/ajaxF360getEmployeDetail'); ?>",
            method: 'POST',
            data: {
                nik: nik
            },
            success: (data) => {
                data = JSON.parse(data);
                if(useragent == 0){ // jika user agentnya desktop
                    // taruh data ke dalam tabel dengan urutannya
                    $('#dept_'+urutan).text(data.departemen);
                    $('#div_'+urutan).text(data.divisi);
                    $('#pos_'+urutan).text(data.position_name);
                    $('#hrefWrapper_'+urutan).removeClass('invisible');
                    // $('#hrefWrapper_'+urutan).addClass('d-flex');
                    $('#href_'+urutan).attr('href', "<?= base_url('survey/f360survey/'); ?>?nik="+nik);
                } else { // jika user agentnya selain desktop
                    // masukkan data ke tombol info
                    $('#btnEmployeInfo_mobile_'+urutan).data('nik', nik);
                    $('#btnEmployeInfo_mobile_'+urutan).data('emp_name', data.emp_name);
                    $('#btnEmployeInfo_mobile_'+urutan).data('departemen', data.departemen);
                    $('#btnEmployeInfo_mobile_'+urutan).data('divisi', data.divisi);
                    $('#btnEmployeInfo_mobile_'+urutan).data('position_name', data.position_name);
                    $('#btnEmployeInfo_mobile_'+urutan).data('position_name', data.position_name);
                    // masukkan data ke link 
                    $('#href_mobile_'+urutan).attr('href', "<?= base_url('survey/f360survey/'); ?>?nik="+nik);
                    // tampilkan button info dan link
                    $('#btnEmployeInfo_mobile_'+urutan).removeClass('invisible');
                    $('#href_mobile_'+urutan).removeClass('invisible');
                }
            }
        })
    }
    
    // employe deteail mobile
    $('#f360getEmployeMobile').on('change', () => {
        let nik = $('#f360getEmployeMobile').val();
        console.log(nik);
    });
</script>