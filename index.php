<?php 
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Data Mahasiswa</h2>
        <a href="form.php" class="btn-tambah">Tambah Data Baru</a>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>NIM</th>
                    <th>Nama Lengkap</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($conn, "SELECT * FROM mahasiswa");
                $no = 1;
                if(mysqli_num_rows($query) == 0){
                    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data.</td></tr>";
                }
                while($row = mysqli_fetch_array($query)) {
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <img src="uploads/<?= $row['foto']; ?>" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                    </td>
                    <td><?= $row['nim']; ?></td>
                    <td><?= $row['nama_lengkap']; ?></td>
                    <td><?= $row['jurusan']; ?></td>
                    <td>
                        <a href="form.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                        <a href="proses.php?hapus=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>