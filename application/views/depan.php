<article class="art simple post">
    
    
    <h2 class="art-title" style="margin-bottom:40px">-</h2>

    <div class="art-body">
        <div class="art-body-inner">
            <h2>Halaman Depan</h2>
			<h5>Status data di DB</h5>
			<?php echo $cekdatalatih; ?>
			<?php echo $cekdatauji; ?>
			
            <p><a href="<?php echo site_url('isidataset') ?>">Isi Data Latih</a></p>
            <p><a href="<?php echo site_url('tesdata') ?>">Isi Data Uji</a></p>
            <p><a href="<?php echo site_url('tesdata/test_from_db') ?>">Tes Data Uji dari DB</a></p>
            <p><a href="<?php echo site_url('data/train_from_db') ?>">Buat Model dari DB</a></p>
			
			<p>Selamat datang di Siprecakaba. Siprecakaba merupakan sistem yang membantu menilai calon karyawan baru dengan melakukan prediksi nilai akhir setiap calon karyawan.</p>
			<p>Untuk mengoperasikan sistem ini, upload data latih terlebih dahulu, disusul dengan data uji. Data latih merupakan data hasil rekrutmen yang lalu, sedangkan data uji merupakan hasil rekrutmen yang sekarang. Setelah itu, Anda dapat menguji data latih yang telah tersimpan di DB.</p>
        </div>
    </div>

</article>