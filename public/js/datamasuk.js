const modal = document.getElementById('dataModal');
const modalForm = document.getElementById('modalForm');
const modalTitle = document.getElementById('modalTitle');
const methodContainer = document.getElementById('methodInputContainer');

// Input fields
const inputJudul = document.getElementById('inputJudul');
const inputJumlah = document.getElementById('inputJumlah');
const inputTanggal = document.getElementById('inputTanggal');

// 1. Fungsi Buka Modal TAMBAH
function openAddModal() {
    // Reset form agar kosong
    modalForm.reset();
    
    // Set Action URL ke route STORE (Sesuaikan dengan route Laravel kamu)
    modalForm.action = "/datamasuk/store";
    
    // Set Judul Modal
    modalTitle.innerText = "Tambah Data Masuk";
    
    // Hapus input hidden _method (karena Tambah pakai POST biasa)
    methodContainer.innerHTML = "";
    
    // Tampilkan Modal
    modal.style.display = 'flex';
}

function openEditModal(button) {
    // Ambil data dari tombol yang diklik
    const id = button.getAttribute('data-id');
    const judul = button.getAttribute('data-judul');
    const jumlah = button.getAttribute('data-jumlah');
    const tanggal = button.getAttribute('data-tanggal');
    
    // Isi form dengan data tersebut
    inputJudul.value = judul;
    inputJumlah.value = jumlah;
    inputTanggal.value = tanggal;
    
    // Set Action URL ke route UPDATE (Sesuaikan dengan route Laravel kamu)
    modalForm.action = "/datamasuk/update/" + id;
    
    // Set Judul Modal
    modalTitle.innerText = "Edit Data Masuk";
    
    // Tambahkan input hidden _method PUT (Karena Laravel butuh PUT untuk update)
    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    // Tampilkan Modal
    modal.style.display = 'flex';
}

function closeModal() {
    modal.style.display = 'none';
}

// Tutup modal jika user klik di luar area kotak modal (overlay)
window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}
