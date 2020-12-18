

<script language="javascript">
    $(document).ready(function() {
    //var testdiv = document.getElementById("testdiv");
        
        // using html2canvas to export chart
        // setTimeout(function(){
        //     html2canvas($(".orgchart > table")[0],({
        //         allowTaint: false
        //     })).then((canvas) => {
        //         console.log(canvas.toDataURL());
        //     })
        // }, 5000);


        // using dom-to-image to export chart
        // var node = document.getElementById('.orgchart')[0];

        domtoimage.toPng($(".orgchart")[0])
            .then(function (dataUrl) {
                var img = new Image();
                // img.src = dataUrl;
                // document.body.appendChild(img);

                console.log(dataUrl);
            })
            .catch(function (error) {
                console.error('oops, something went wrong!', error);
            });
    });
</script>

<script>
//masukkin data ke variabel javascript dari php

var assistant_atasan1 = "<?php if(!empty($assistant_atasan1)): ?> <?= $assistant_atasan1; ?>; <?php else: ?> <?= ""; ?><?php endif; ?>";
var atasan = <?= $atasan; ?>;
var datasource = <?php echo $orgchart_data; ?>; 
var datasource_assistant1 = <?php echo($orgchart_data_assistant1); ?>;
var datasource_assistant2 = <?php echo($orgchart_data_assistant2); ?>;

// $(document).ready(function() { //buat nyembunyiin menu user
//     $('a[data-target="#collapseUser"]').addClass('d-none');
// });

// console.log(html2canvas('.orgchart'));

// html2canvas('.orgchart').then((canvas) => {
//     document.body.appendChild(canvas);
// });
</script>