<article class="art simple post">
    
    
    <h2 class="art-title" style="margin-bottom:40px">-</h2>
	
    <div class="art-body">
        <div class="art-body-inner">
            <h2>Pengetesan Data Uji</h2>
			<?php echo $error;?>
            <div id="contact-area">
                <?php echo form_open_multipart(site_url('data/uploading_test_data'));?>
                    <label for="berkas">Berkas:</label>
                    <input type="file" name="userfile" id="userfile">
					<label for="berkas">Lakukan prediksi</label>
					<input type="checkbox" class="checkbox" name="buatprediksi" id="buatprediksi" value="on">
					<br/>
					<br/>
                    <input type="submit" name="submit" value="Uji" class="submit-button">
                </form>
            </div>
        </div>
    </div>

</article>