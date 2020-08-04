<!-- initial variabel orgchart -->
<script>
//masukkin data ke variabel javascript dari php

var assistant_atasan1 = <?= $assistant_atasan1; ?>;
var atasan = <?= $atasan; ?>;
var datasource = <?php echo $orgchart_data; ?>; 
var datasource_assistant1 = <?php echo($orgchart_data_assistant1); ?>;
var datasource_assistant2 = <?php echo($orgchart_data_assistant2); ?>;
</script>

<script>
	$(document).ready(function() {
		// hide semua elemen editor
		$('.editor-tujuan, .editor-ruang, .editor-tantangan, .editor-jenkar, .editor-hubEks, .editor-hubInt').hide();
		
		$('.hapusJobs').click(function(e) {
			// e.preventDefault();
			const href = $(this).attr("href");
			Swal.fire({
				title: "Yakin Ingin Menghapus?",
				text: "Aksi ini akan menghapus data secara permanen!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya!",
				cancelButtonText: "Batal!"
			}).then((result) => {
				if(result.value) {
					$.ajax({
						url: href,
						success: function() {
							swal.fire(
								'Berhasil!',
								'Data Berhasil Dihapus!<br>Halaman sedang direfresh ulang...',
								'success'
							);
							setTimeout(function() {
								location.reload();
							}, 2000);
						}
					});
				} else {
					//nothing
				}
			});
		});

		// start aksi tujuan jabatan
		$('.edit-tujuan').on('click', function() {
			$(this).hide();
			$("div[class~='view-tujuan']").hide();
			$('.editor-tujuan').fadeIn().focus();
		});

		$('#simpan-tujuan').on('click', function() {
			const id = $(this).data('id');
			const tujuan = CKEDITOR.instances['tujuan'].getData();
			$.ajax({
				url: "<?php echo base_url('job_profile/edittujuan'); ?>",
				data: {
					id: id,
					tujuan: tujuan
				},
				method: "POST",
				success: function(data) {
					$('.edit-tujuan').fadeIn();
					$("div[class~='view-tujuan']").html(tujuan).fadeIn();
					$('.editor-tujuan').hide();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					)
				}
			});
		});

		$('#simpan-tujuan-baru').click(function() {
			const id = $(this).data('id');
			const tujuan = CKEDITOR.instances['tujuanbaru'].getData();
			$.ajax({
				url: "<?= base_url('job_profile/uptuj'); ?>",
				data: {
					id: id,
					tujuan: tujuan
				},
				method: "POST",
				success: function(data) {
					$('.edit-tujuan').removeClass('d-none');
					$('.edit-tujuan').addClass('d-block');
					$("div[class~='view-tujuan']").removeClass('d-none');
					$("div[class~='view-tujuan']").html(tujuan).addClass('d-block');
					$("#add-tujuan_jabatan").hide();
					location.reload();//for speed

					// swal.fire( //for showing the modal, it is like application
					// 	'Berhasil!',
					// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
					// 	'success'
					// )
					// setTimeout(function() {
					// 	location.reload();
					// }, 2000);
				}
			});
		});

		$('.batal-edit-tujuan').on('click', function() {
			$('.edit-tujuan').fadeIn();
			$("div[class~='view-tujuan']").fadeIn();
			$('.editor-tujuan').hide();
		});
		// end aksi tujuan jabatan

		//start edit tanggung jawab
		$(function() {
			$('.nTgjwb').on('click', function() {
				$('#modalTanggungJwbTitle').html('Tambah Tanggung Jawab Utama, Aktivitas Utama & Indikator Kerja');
				$('.modal-footer button[type=submit]').html('Simpan');
				$('#idtgjwb').val("");
				$('#tJwb-text').val("");
				CKEDITOR.instances['aUtm-text'].setData("");
				CKEDITOR.instances['pgkrn-text'].setData("");
				// $('.modal-body form').attr('action', '<?= base_url('job_profile/addtanggungjawab') ?>');
			});

			$('.eTgjwb').on('click', function() {
				$('#modalTanggungJwbTitle').html('Edit Tanggung Jawab Utama, Aktivitas Utama & Indikator Kerja');
				$('.modal-footer button[type=submit]').html('Simpan Perubahan');
				$('.modal-body form').attr('action', '<?= base_url('job_profile/edittanggungjawab') ?>');
				const id = $(this).data('id');

				$.ajax({
					url: '<?= base_url('job_profile/getTjByID') ?>',
					data: {
						id: id
					},
					method: 'post',
					dataType: 'json',
					success: function(data) {
						$('#idtgjwb').val(data.id_tgjwb);
						$('#tJwb-text').val(data.keterangan);
						CKEDITOR.instances['aUtm-text'].setData(data.list_aktivitas);
						CKEDITOR.instances['pgkrn-text'].setData(data.list_pengukuran);
					}
				})
			});
		});

		/////////////////////////////////// Tambah Tanggung jawab baru /////////////////////////////////
		$('#submit-tgjwb').click(function() {
			const id_posisi = $(this).data('id_posisi');
			const idtgjwb = $('input[name="idtgjwb"]').val();
			const tjwb = $('textarea[name="tanggung_jawab"]').val();
			const autm = CKEDITOR.instances['aUtm-text'].getData();
			const pgkrn = CKEDITOR.instances['pgkrn-text'].getData();
			
			if(idtgjwb == ""){
				// console.log("add mode");
				var url_submit = '<?= base_url('job_profile/addtanggungjawab'); ?>';
			}else{
				// console.log("edit mode");
				var url_submit = '<?= base_url('job_profile/edittanggungjawab') ?>';
			}

			$.ajax({
				url: url_submit,
				data: {
					idtgjwb: idtgjwb,
					id_posisi: id_posisi,
					tanggung_jawab: tjwb,
					aktivitas: autm,
					pengukuran: pgkrn
				},
				method: "POST",
				success: function(data){
					if(idtgjwb == ""){
						location.reload();

						// swal.fire(
						// 	'Berhasil!',
						// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
						// 	'success'
						// )
						// setTimeout(function() {
						// 	location.reload();
						// }, 2000);
					}else{
						location.reload();

						// swal.fire(
						// 	'Berhasil!',
						// 	'Data berhasil diubah.<br/>Halaman sedang direfresh ulang',
						// 	'success'
						// );
						// setTimeout(function() {
						// 	location.reload();
						// }, 2000);
					}
				}
			});
		});

		// -------------start ruang lingkup---------------//
		$('.edit-ruang').on('click', function() {
			$(this).hide();
			$("div[class~='view-ruang']").hide();
			$('.editor-ruang').slideDown("fast");
		});

		$('#simpan-ruang').on('click', function() {
			const id = $(this).data('id');
			const ruang = CKEDITOR.instances['ruang'].getData();
			$.ajax({
				url: "<?php echo base_url('job_profile/editruanglingkup'); ?>",
				data: {
					id: id,
					ruang: ruang
				},
				method: "POST",
				success: function(data) {
					$('.edit-ruang').fadeIn(1000);
					$('.editor-ruang').hide();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					);
					if (data === '<b>-</br>') {
						$("div[class~='view-ruang']").html(data).fadeIn(1000);
					} else {
						$("div[class~='view-ruang']").html(ruang).fadeIn(1000);
					}
				}
			});
		});

		$('#simpan-ruangl-baru').click(function(){
			const id = $(this).data('id');
			const ruangl = CKEDITOR.instances['add-ruangl'].getData();
			$.ajax({
				url: "<?= base_url('job_profile/addruanglingkup') ?>",
				data: {
					id: id,
					ruangl: ruangl
				},
				method: "POST",
				success: function(data){
					location.reload();

					// swal.fire(
					// 	'Berhasil!',
					// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
					// 	'success'
					// )
					// setTimeout(function() {
					// 	location.reload();
					// }, 2000);
				}
			});
		});

		$('.batal-edit-ruang').on('click', function() {
			$('.edit-ruang').fadeIn(1000);
			$("div[class~='view-ruang']").fadeIn(500);
			$('.editor-ruang').hide();
		});

		// start add wewenang
		const newWen = $('#newWen').hide();
		$('#addwen').on('click', function() {
			newWen.show().fadeIn();
		});

		// start edit wewenang
		$(document).ready(function() {
			$('#wewenang').Tabledit({
				url: '<?= base_url('job_profile/aksiwewenang') ?>',
				inputClass: 'form-control input-sm',
				editButton: false,
				restoreButton: false,
				hideIdentifier: true,
				buttons: {
					delete: {
						class: 'btn btn-sm btn-circle btn-danger hapus',
						html: '<i class="fas fa-trash-alt"></i>',
						action: 'delete'
					},
					confirm: {
						class: 'btn btn-sm btn-danger',
						html: 'Yakin?'
					}
				},
				columns: {
					identifier: [0, 'id'],
					editable: [
						[1, 'kewenangan'],
						[2, 'wen_sendiri', '{"R": "R", "A": "A", "V": "V", "C": "C", "I": "I"}'],
						[3, 'wen_atasan1', '{"R": "R", "A": "A", "V": "V", "C": "C", "I": "I"}'],
						[4, 'wen_atasan2', '{"R": "R", "A": "A", "V": "V", "C": "C", "I": "I"}']
					]
				},
				onSuccess: function(data, textStatus, jqXHR) {
					if (data == 'delete') {
						$('.tabledit-deleted-row').remove();
					}
				},
				onAjax: function(action, serialize) {
					// open your xhr here 
					console.log("on Ajax");
					console.log("action : ", action);
					console.log("data : ", serialize);
				}
			});
			$('.tabledit-toolbar-column').hide();
		});

		// add wewenang baru
		$('#add-wewenang-baru').click(function() {
			let id = $(this).data('id');
			let wewenang = $('input[name="wewenang"]').val();
			let wen_sendiri = $('#wen_sendiri').val();
			let wen_atasan1 = $('#wen_atasan1').val();
			let wen_atasan2 = $('#wen_atasan2').val();

			console.log(wewenang);
			console.log(wen_sendiri);
			console.log(wen_atasan1);
			console.log(wen_atasan2);

			if(wewenang == ""){
				Swal.fire(
					'Error!',
					'Wewenang tidak boleh kosong!',
					'error'
				)
			}else{
				$.ajax({
					url: '<?= base_url('job_profile/addwen') ?>',
					data: {
						id: id,
						wewenang: wewenang,
						wen_sendiri: wen_sendiri,
						wen_atasan1: wen_atasan1,
						wen_atasan2: wen_atasan2
					},
					method: "POST",
					success: function(data) {
						location.reload();

						// swal.fire(
                        //     'Berhasil!',
                        //     'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
                        //     'success'
                        // )
                        // setTimeout(function() {
                        //     location.reload();
                        // }, 2000);
					}
				});
			}
		});


		// start hub_kerja
		const flashjobs = $(".flash-jobs").data("flashdata");
		if (flashjobs) {
			Swal.fire({
				title: "Data",
				text: "Success " + flashjobs,
				type: "success",
				animation: false,
				customClass: {
					popup: "animated jackInTheBox"
				}
			});
		}

		// Hubungan Internal Script
		$('.edit-hubInt').on('click', function() {
			$(this).hide();
			$("div[class~='hubIntData']").hide();
			$('#cke_hubInt').fadeIn().focus();
			$('.simpanhubInt').fadeIn();
			$('.batalhubInt').fadeIn();
			$('.editor-hubInt').fadeIn();
		});
		// ------------------------------aksi hub int ----------------//
		$('.simpanhubInt').on('click', function() {
			const hubInt = CKEDITOR.instances['hubInt'].getData();
			const id = $(this).data('id');
			var tipe = 'internal';
			$.ajax({
				url: "<?php echo base_url('job_profile/edithub'); ?>",
				data: {
					id: id,
					hubInt: hubInt,
					tipe: tipe
				},
				method: 'POST',
				success: function(data) {
					$("div[class~='hubIntData']").html(hubInt).fadeIn();
					$('#cke_hubInt').hide();
					$('.simpanhubInt').hide();
					$('.batalhubInt').hide();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					)
				}
			})
		});

		$('.batalhubInt').on('click', function() {
			$("div[class~='hubIntData']").fadeIn();
			$('#cke_hubInt').hide();
			$('.simpanhubInt').hide();
			$('.batalhubInt').hide();
			$('.edit-hubInt').fadeIn();
			$('.editor-hubInt').fadeOut();
		});
		//endhubint

		$('.edit-hubEks').on('click', function() {
			$(this).hide();
			$("div[class~='hubEksData']").hide();
			$('#cke_hubEks').fadeIn().focus();
			$('.simpanhubEks').fadeIn();
			$('.batalhubEks').fadeIn();
			$('.editor-hubEks').fadeIn();
		});

		//////////////////////////////// aksi hub eks //////////////////////////////////
		$('.simpanhubEks').on('click', function() {
			const hubEks = CKEDITOR.instances['hubEks'].getData();
			const id = $(this).data('id');
			var tipe = 'eksternal';
			$.ajax({
				url: "<?php echo base_url('job_profile/edithub'); ?>",
				data: {
					id: id,
					hubEks: hubEks,
					tipe: tipe
				},
				method: 'POST',
				success: function(data) {
					$("div[class~='hubEksData']").html(hubEks).fadeIn();
					$('#cke_hubEks').hide();
					$('.simpanhubEks').hide();
					$('.batalhubEks').hide();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					)
				}
			})
		});

		$('.batalhubEks').on('click', function() {
			$("div[class~='hubEksData']").fadeIn();
			$('#cke_hubEks').hide();
			$('.simpanhubEks').hide();
			$('.batalhubEks').hide();
			$('.edit-hubEks').fadeIn();
			$('.editor-hubEks').fadeOut();
		});

		/* aksi untuk tambah hubungan eksternal dan internal baru */
		$('#simpan-hubungan-baru').click(function() {
			const id = $(this).data('id');
			const internal = CKEDITOR.instances['internal'].getData();
			const eksternal = CKEDITOR.instances['eksternal'].getData();
			$.ajax({
				url: "<?= base_url('job_profile/addHubungan'); ?>",
				data: {
					id: id,
					internal: internal,
					eksternal: eksternal
				},
				method: "POST",
				success: function(data) {
					location.reload();

					// swal.fire(
					// 	'Berhasil!',
					// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
					// 	'success'
					// 	);
					// setTimeout(function() {
					// 	location.reload();
					// }, 2000);
				}
			});
		});

		//start jumlah staff
		$('document').ready(function() {
			function hitung() {
				mgr = parseInt($("#totMgr").val());
				spvr = parseInt($("#totSpvr").val());
				staf = parseInt($("#totStaf").val());
				total = 0;

				if (isNaN(mgr)) mgr = 0;
				if (isNaN(spvr)) spvr = 0;
				if (isNaN(staf)) staf = 0;

				total = mgr + spvr + staf;
				$(".jumTotStaff").html(total);
			};

			$("#totMgr, #totSpvr, #totStaf").keyup(function() {
				hitung();
				$.ajax({
					url: "<?= base_url('job_profile/updatestaff') ?>",
					data: {
						id_posisi: <?= $staff['id_posisi'] ?>,
						mgr: mgr,
						spvr: spvr,
						staf: staf
					},
					method: 'post',
					success: function(data) {
						console.log(data);
					}
				})
			});
		});


		// ---------------aksi tantangan---------------//
		$('.edit-tantangan').on('click', function() {
			$(this).hide();
			$("div[class~='view-tantangan']").hide();
			$('.editor-tantangan').slideDown("fast");
		});

		$('#simpan-tantangan').on('click', function() {
			const id = $(this).data('id');
			const tantangan = CKEDITOR.instances['tantangan'].getData();
			$.ajax({
				url: "<?php echo base_url('job_profile/edittantangan'); ?>",
				data: {
					id: id,
					tantangan: tantangan
				},
				method: "POST",
				success: function(data) {
					$('.edit-tantangan').fadeIn();
					$("div[class~='view-tantangan']").html(tantangan).fadeIn(2000);
					$('.editor-tantangan').hide();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					)
				}
			});
		});

		$('#simpan-tantangan_baru').click(function() {
			const id = $(this).data('id');
			const tantangan = CKEDITOR.instances['tantangan-baru'].getData();
			$.ajax({
				url: "<?= base_url('job_profile/addtantangan'); ?>",
				data: {
					id: id,
					tantangan: tantangan
				},
				method: "POST",
				success: function(data) {
					location.reload();

					// swal.fire(
					// 	'Berhasil!',
					// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
					// 	'success'
					// )
					// setTimeout(function() {
					// 	location.reload();
					// }, 2000);
				}
			});
		});

		$('.batal-edit-tantangan').on('click', function() {
			$('.edit-tantangan').fadeIn();
			$("div[class~='view-tantangan']").fadeIn(500);
			$('.editor-tantangan').hide();
		});

		// --------------aksi kualifikasi----------------//
		$('.edit-kualifikasi').on('click', function() {
			const id = $(this).data('id');
			$.ajax({
				url: '<?= base_url('job_profile/getKualifikasiById') ?>',
				data: {
					id: id
				},
				method: 'post',
				dataType: 'json',
				success: function(data) {
					$('#pend').val(data.pendidikan);
					$('#pengalmn').val(data.pengalaman);
					$('#pengtahu').val(data.pengetahuan);
					$('#kptnsi').val(data.kompetensi);
				}
			});
		});

		$('#save-kualifikasi').on('click', function() {
			var id_posisi = $('#id').val();
			var pendidikan = $('#pend').val();
			var pengalaman = $('#pengalmn').val();
			var pengetahuan = $('#pengtahu').val();
			var kompetensi = $('#kptnsi').val();
			$.ajax({
				url: '<?= base_url('job_profile/updateKualifikasi') ?>',
				data: {
					id_posisi: id_posisi,
					pendidikan: pendidikan,
					pengalaman: pengalaman,
					pengetahuan: pengetahuan,
					kompetensi: kompetensi
				},
				method: 'post',
				success: function(data) {

					$('td#pendidikan').html(pendidikan)
					$('td#pengalaman').html(pengalaman)
					$('td#pengetahuan').html(pengetahuan)
					$('td#kompetensi').html(kompetensi)

					$('.close').click();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					);
				}
			});
		});

		$('#simpan-kualifikasi-baru').click(function() {
			const id_posisi = $('#id').val();
			const pendidikan = $('#pend').val();
			const pengalaman = $('#pengalmn').val();
			const pengetahuan = $('#pengtahu').val();
			const kompetensi = $('#kptnsi').val();

			//cek jika salah satu form ada yang kosong
			if(pendidikan != "" && pengalaman != "" && pengetahuan != "" && kompetensi != ""){
				$.ajax({
					url: '<?= base_url('job_profile/addkualifikasi') ?>',
					data: {
						id_posisi: id_posisi,
						pendidikan: pendidikan,
						pengalaman: pengalaman,
						pengetahuan: pengetahuan,
						kompetensi: kompetensi
					},
					method: 'POST',
					success: function(data){
						location.reload();

						// swal.fire(
						// 	'Berhasil!',
						// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
						// 	'success'
						// )
						// setTimeout(function() {
						// 	location.reload();
						// }, 2000);
					}
				});
			} else {
				swal.fire(
					'Ada data yang masih kosong',
					'Harap isi form kualifikasi dan pengalaman dengan lengkap!',
					'error'
				);
			}
		});

		// --------------aksi jenjang karir----------------//
		$('.edit-jenjang').on('click', function() {
			$(this).hide();
			$("div[class~='view-jenjang']").hide();
			$('.editor-jenkar').fadeIn(1000);
		});

		$('#simpan-jenjang').on('click', function() {
			const id = $(this).data('id');
			const jenkar = CKEDITOR.instances['jenkar'].getData();
			$.ajax({
				url: "<?php echo base_url('job_profile/editjenjang'); ?>",
				data: {
					id: id,
					jenkar: jenkar
				},
				method: "POST",
				success: function(data) {
					$('.edit-jenjang').fadeIn();
					$("div[class~='view-jenjang']").html(jenkar).fadeIn(1000);
					$('.editor-jenkar').hide();
					Swal.fire(
						'Berhasil!',
						'Data Berhasil Diubah!',
						'success'
					)
				}
			});
		});

		$('#simpan-jenk-baru').click(function() {
			const id = $(this).data('id');
			const jenkar = CKEDITOR.instances['jenkar'].getData();
			$.ajax({
				url: "<?= base_url('job_profile/addjenjangkarir'); ?>",
				data: {
					id: id,
					jenkar: jenkar
				},
				method: "POST",
				success: function(data){
					location.reload();
					
					// swal.fire(
					// 	'Berhasil!',
					// 	'Data Berhasil Dimasukkan!<br>Halaman sedang direfresh ulang...',
					// 	'success'
					// )
					// setTimeout(function() {
					// 	location.reload();
                    // }, 2000);
				},
				fail: function(data){
					swal.fire(
						'ERROR!',
						'Data tidak berhasil dimasukkan!<br/>Silakan cek form jenjang karir anda',
						'error'
					);
				}
			});
		});

		$('.batal-edit-jenjang').on('click', function() {
			$('.edit-jenjang').fadeIn();
			$("div[class~='view-jenjang']").fadeIn(500);
			$('.editor-jenkar').hide();
		});
		// ---------------end jenjang karir---------------///

		$('.custom-file-input').on('change', function() {
			let filename = $(this).val().split('\\').pop();
			$(this).next('.custom-file-label').addClass("selected").html(filename);
		});

		$('.form-check-input').on('click', function() {
			const menuId = $(this).data('menu');
			const roleId = $(this).data('role');

			$.ajax({
				url: "<?= base_url('admin/changeaccess'); ?>",
				type: 'post',
				data: {
					menuId: menuId,
					roleId: roleId
				},
				success: function() {
					document.location.href = "<?= base_url('admin/roleaccess/'); ?>" + roleId;
				}
			});
		});

		//tombol buat submit Job Profile
		$('.btnApprove').on('click', function() {
			const nik = $(this).data('mynik');
			const id_posisi = $(this).data('position');
			const approver1 = $(this).data('approver1');
			const approver2 = $(this).data('approver2');

			// console.log(valid_tujuanjabatan);
			// console.log(valid_ruangl);
			// console.log(valid_tu_mu);
			// console.log(valid_kualifikasi);
			// console.log(valid_jenk);
			// console.log(valid_hub);
			// console.log(valid_tgjwb);
			// console.log(valid_wen);
			// console.log(valid_atasan);

			if(	valid_tujuanjabatan == "fill" &&
				valid_ruangl == "fill" && 
				valid_tu_mu == "fill" &&
				valid_kualifikasi == "fill" &&
				valid_jenk == "fill" &&
				valid_hub == "fill" &&
				valid_tgjwb == "fill" &&
				valid_wen == "fill" &&
				valid_atasan == "fill"
				)	{ //validator Job Profile, variabelnya ada di file ../application/views/job_profile/jp_editor.php

						$('#submit-modal').modal('hide'); //hide modal submit
						Swal.fire(
							'Job Profile anda sudah lengkap',
							'Terima kasih sudah mengisi Job Profile anda, berikutnya Job Profile akan di-<i>review</i> oleh approver 1 anda.<br/><br/><small>mengarahkan anda kembali ke halaman index JP...</small>',
							'success'
						);
						
						
						// setTimeout(function() { //nunggu waktu 1 detik lalu set approve status
							$.ajax({
								url: "<?= base_url('job_profile/setApprove') ?>",
								type: 'post',
								data: {
									nik: nik,
									id_posisi: id_posisi,
									approver1: approver1,
									approver2: approver2
								},
								success: function(data) {
									document.location.href = "<?= base_url('job_profile'); ?>";
								},
								fail: function() {
									console.log('fail');
								}
							});		
						// }, 1000);
					} else {
						$('#submit-modal').modal('hide');// hide modal submit
						swal.fire(
							'Error',
							'Harap isi semua data Job Profile!',
							'error'
						);
					}
		});

		//scroll ke atas jika submit modal ketutup, modal ada di ../application/view/job_profile/myjp.php
		$('#submit-modal').on('hidden.bs.modal', function (e) {
			$('html, body').animate({scrollTop:(0)}, '2000');
		});

		//replace input text with CKEDITOR
		/* add setting like this if enter scrolled page to bottom
		CKEDITOR.replace( 'editor1', {
			enterMode: CKEDITOR.ENTER_BR
		} ); 
		*/
		CKEDITOR.replace('tujuan_jabatan', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('hubInt', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('hubEks', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('aUtm-text', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('pgkrn-text', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('tantangan', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('jenkar', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('internal', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('eksternal', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('add-ruangl', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('ruang', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('tujuan', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('tujuanbaru', {
			enterMode: CKEDITOR.ENTER_BR
		});
		CKEDITOR.replace('tantangan-baru', {
			enterMode: CKEDITOR.ENTER_BR
		});

		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
			$('.nTgjwb').tooltip();
			$('.eTgjwb').tooltip();
			$('.hapusJobs').tooltip();
			$('.edit-kualifikasi').tooltip();
		});

		$('#departemen-table').DataTable();

		/* ---------------------- baca input checkbox verify od --------------------- */
		$('#verify_od').on('change', function(){
			$verify_od = $('#verify_od:checked').val(); // get verify value on checked

			$.ajax({
				url: '<?= base_url('job_profile/setVerifyJP'); ?>',
				type: 'POST',
				data: {
					verify_od: $verify_od,
					id_posisi: $(this).data('id_posisi')
				},
				success: function(data){
					if($verify_od == undefined){
						// do nothing
					} else {
						Swal.fire(
							'Sudah diverifikasi',
							'Job Profile ini selesai diperiksa <br/> Terima kasih.',
							'success'
						);
					}
					
				}
			});
			
			// if($verify_od == undefined){
			// 	console.log('data kosong');
			// } else {
			// 	Swal.fire({
			// 		title: 'Sudah selesai memeriksa Job Profile ini?',
			// 		text: "Menandai Job Profile ini sebagai sudah diperiksa",
			// 		icon: 'warning',
			// 		showCancelButton: true,
			// 		confirmButtonText: 'Ya',
			// 		cancelButtonText: 'Tidak, periksa kembali',
			// 		reverseButtons: true
			// 	}).then((result) => {
			// 		if (result.value) {
			// 			$.ajax({
			// 				url: '<?= base_url('job_profile/setVerifyJP'); ?>',
			// 				type: 'POST',
			// 				data: {
			// 					verify_od: $verify_od,
			// 					id_posisi: $(this).data('id_posisi')
			// 				},
			// 				success: function(data){
			// 					Swal.fire(
			// 						'Sudah diverifikasi',
			// 						'Job Profile ini sudah diperksa oleh OD',
			// 						'success'
			// 					);

			// 					console.log(data);
			// 				}
			// 			});
			// 		} else if (
			// 			/* Read more about handling dismissals below */
			// 			result.dismiss === Swal.DismissReason.cancel
			// 		) {
			// 			Swal.fire(
			// 				'Dibatalkan',
			// 				'Silakan periksa kembali Job Profile ini.',
			// 				'error'
			// 			);
			// 			$('#verify_od').prop('checked', false); // uncheck kotak verufy od
			// 		}
			// 	})
			// }

		});
		
        $('#submitRevisi').on('click', function(){
			$pesan_revisi = $('textarea[name="pesan_revisi"]').val();
			if($pesan_revisi == ""){
				swal.fire(
					'Error',
					'Harap isi pesan revisi.',
					'error'
				);
			} else {
				$('#revisiForm').submit();
			}
        });

	});

$(document).ready(function() { //buat nyembunyiin menu user
    $('a[data-target="#collapseUser"]').addClass('d-none');
});
</script>