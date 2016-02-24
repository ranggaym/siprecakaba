<article class="art simple post">
    
    
    <h2 class="art-title" style="margin-bottom:40px">-</h2>

    <div class="art-body">
        <div class="art-body-inner">
            <h2>Halaman Depan</h2>
			<div id="contact-area">
                <?php echo form_open_multipart(site_url('login'));?>
                    <label for="Username">Username:</label>
                    <input type="textbox" name="Username" id="Username">
					<label for="berkas">Password</label>
					<input type="password" class="password" name="password" id="password">
					<br/>
					<br/>
                    <input type="submit" name="submit" value="Simpan" class="submit-button">
                </form>
            </div>
			
			<p>Selamat datang di Siprecakaba. Siprecakaba merupakan sistem yang membantu menilai calon karyawan baru dengan melakukan prediksi nilai akhir setiap calon karyawan.</p>
			<p>Untuk mengoperasikan sistem ini, upload data latih terlebih dahulu, disusul dengan data uji. Data latih merupakan data hasil rekrutmen yang lalu, sedangkan data uji merupakan hasil rekrutmen yang sekarang. Setelah itu, Anda dapat menguji data latih yang telah tersimpan di DB.</p>
        </div>
    </div>

</article>