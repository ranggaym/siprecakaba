<article class="art simple post">
    
    
    <h2 class="art-title" style="margin-bottom:40px">-</h2>
	
    <div class="art-body">
        <div class="art-body-inner">
            <h2>Pengisian Dataset Latih</h2>
			<?php echo $error;?> <br>
			<?php echo $notif;?>
            <div id="contact-area">
                <?php echo form_open_multipart(site_url('data/isi_dataset'));?>
                    <label for="berkas">Berkas:</label>
                    <input type="file" name="userfile" id="userfile">
					<label for="berkas">Buat model</label>
					<input type="checkbox" class="checkbox" name="buatmodel" id="buatmodel" value="on">
					<br/>
					<br/>
                    <input type="submit" name="submit" value="Simpan" class="submit-button">
                </form>
            </div>
        </div>
    </div>

</article>