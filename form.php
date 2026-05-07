<?php 
include 'koneksi.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
$nim = $nama = $jurusan = $foto = "";

if ($id != "") {
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE id='$id'");
    $data = mysqli_fetch_array($query);
    $nim = $data['nim'];
    $nama = $data['nama_lengkap'];
    $jurusan = $data['jurusan'];
    $foto = $data['foto'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Form Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2><?= ($id != "") ? "Edit" : "Tambah"; ?> Data Mahasiswa</h2>
        <form action="proses.php" method="POST" enctype="multipart/form-data" id="mhsForm">
            <input type="hidden" name="id" value="<?= $id; ?>">
            
            <label>NIM</label>
            <input type="text" name="nim" id="nim" value="<?= $nim; ?>">

            <label>Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?= $nama; ?>">

            <label>Jurusan</label>
            <input type="text" name="jurusan" id="jurusan" value="<?= $jurusan; ?>">

            <label>Foto Profil</label>
            <?php if($foto != ""): ?>
                <img src="uploads/<?= $foto; ?>" width="80" style="display:block; margin-bottom:10px;">
            <?php endif; ?>
            <input type="file" name="foto" id="foto">
            <small>*Format: JPG/JPEG/PNG, Maks: 2MB</small>

            <div class="btn-group">
                <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
                <a href="index.php" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>

    <script>
    document.getElementById('mhsForm').onsubmit = function(e) {
        let nim = document.getElementById('nim').value;
        let nama = document.getElementById('nama').value;
        let jurusan = document.getElementById('jurusan').value;
        let foto = document.getElementById('foto');
        let id = "<?= $id ?>";

        if(nim == "" || nama == "" || jurusan == "") {
            alert("Semua field teks wajib diisi!");
            return false;
        }

        if(id == "" && foto.files.length == 0) {
            alert("Harap unggah foto!");
            return false;
        }

        if(foto.files.length > 0) {
            let file = foto.files[0];
            let type = file.type.split('/').pop().toLowerCase();
            if(type != "jpeg" && type != "jpg" && type != "png") {
                alert("Hanya file JPG, JPEG, atau PNG yang diizinkan!");
                return false;
            }
            if(file.size > 2 * 1024 * 1024) {
                alert("Ukuran file maksimal 2 MB!");
                return false;
            }
        }
        return true;
    };
    </script>
</body>
</html>