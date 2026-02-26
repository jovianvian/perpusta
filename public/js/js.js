/* ==========================================================================
   1. GLOBAL VARIABLES & CONFIGURATION
   ========================================================================== */
const rowsPerPage = 5; 
let allBooksData = [];
let allDataMasuk = [];
let allLoanData = [];
let allUserData = [];

// --- Trash Data Cache ---
let allTrashBooks = [];
let allTrashMasuk = [];
let allTrashLoan = [];
let allTrashUser = [];

// --- Toggle States ---
let isTrashBooksVisible = false;
let isTrashMasukVisible = false;
let isTrashLoanVisible = false;
let isTrashUserVisible = false;
let isHistoryBooksVisible = false;
let isHistoryMasukVisible = false;
let isHistoryLoanVisible = false;
let isHistoryUserVisible = false;

// --- Modal Elements Cache ---
let modal, modalForm, modalTitle, methodContainer; // Data Masuk
let bookModal, bookForm, bookTitle, bookMethod;    // Data Buku
let loanModal, loanForm, loanTitle, loanMethod;    // Peminjaman
let userModal, userForm, userTitle, userMethod;    // User

// --- Input Elements Cache ---
let inputBookId, inputJumlah, inputTanggal;
let inpB_Judul, inpB_Penulis, inpB_Penerbit, inpB_Tahun, inpB_Stok, inpB_Kategori;
let inpL_Book, inpL_User, inpL_Pinjam, inpL_Kembali, inpL_Status;
let inpU_Name, inpU_Email, inpU_Password, inpU_Level, passHint;


/* ==========================================================================
   2. GLOBAL HELPER FUNCTIONS
   ========================================================================== */
function showModal(message, isSuccess = false) {
    const popupModal = document.getElementById("popupModal");
    const modalText = document.getElementById("modalText");
    if (popupModal && modalText) {
        modalText.innerText = message;
        popupModal.style.display = "flex";
    }
}

// Fungsi yang dipanggil saat tombol Next/Prev diklik
window.changePage = function(wrapperId, newPage) {
    // Cek ID wrapper untuk tau kita lagi di halaman mana
    if (wrapperId === 'paginationBuku') renderPagination('paginationBuku', globalDataBuku, newPage, renderBookTable);
    if (wrapperId === 'paginationMasuk') renderPagination('paginationMasuk', globalDataMasuk, newPage, renderBookMasukTable);
    if (wrapperId === 'paginationPeminjaman') renderPagination('paginationPeminjaman', allLoanData, newPage, renderLoanTable);
    if (wrapperId === 'paginationUser') renderPagination('paginationUser', globalDataUser, newPage, renderUserTable);
}

// Helper umum untuk mengisi dropdown (Dipakai di Peminjaman & User)
function populateDropdown(idElement, data, fieldName) {
    const select = document.getElementById(idElement);
    if(!select) return;

    let options = '<option value="">-- Pilih --</option>';
    data.forEach(item => {
        options += `<option value="${item.id}">${item[fieldName]}</option>`;
    });
    select.innerHTML = options;
}


/* ==========================================================================
   6. FITUR: DATA USER (CRUD)
   ========================================================================== */
window.toggleHistoryUser = function() {
    const mainContainer = document.getElementById('containerDataUser');
    const historyContainer = document.getElementById('containerHistoryUser');
    const trashContainer = document.getElementById('containerTrashUser');
    const btn = document.getElementById('btnToggleHistoryUser');
    const trashBtn = document.getElementById('btnToggleTrashUser');

    if (!mainContainer || !historyContainer || !btn) return;

    isHistoryUserVisible = !isHistoryUserVisible;

    if (isHistoryUserVisible) {
        if (trashContainer) trashContainer.style.display = 'none';
        if (trashBtn && isTrashUserVisible) {
            isTrashUserVisible = false;
            trashBtn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
            if (trashBtn.classList.contains('btn-primary-custom')) {
                trashBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }

        mainContainer.style.display = 'none';
        historyContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data User';
        if (btn.classList.contains('btn-secondary-custom')) {
            btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        }
        loadHistoryUser();
    } else {
        mainContainer.style.display = 'block';
        historyContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
        if (btn.classList.contains('btn-primary-custom')) {
            btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
        }
    }
};

function loadHistoryUser() {
    const tableBody = document.getElementById('tableHistoryUser');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Sedang memuat riwayat...</td></tr>';

    fetch('/api/history/user')
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                renderHistoryUser(result.data || []);
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Gagal mengambil riwayat</td></tr>';
            }
        })
        .catch(() => {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Terjadi kesalahan sistem</td></tr>';
        });
}

function renderHistoryUser(data) {
    const tableBody = document.getElementById('tableHistoryUser');
    if (!tableBody) return;

    if (!data.length) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada riwayat edit.</td></tr>';
        return;
    }

    let html = '';
    data.forEach((row, index) => {
        const namaUser = row.nama_user || '-';
        const perubahan = row.perubahan || 'Data diperbarui';
        const editedBy = row.edited_by_name || 'System/Unknown';
        const editedAt = row.created_at ? new Date(row.created_at).toLocaleString('id-ID') : '-';
        const canRevert = !!row.old_values;
        const actionHtml = canRevert
            ? `<a href="/revert/${row.id}" class="btn-custom btn-warning-custom" onclick="return confirm('Kembalikan data user ke versi ini?')">Revert</a>`
            : '<span style="color:#9ca3af;">-</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${namaUser}</td>
                <td>${perubahan}</td>
                <td>${editedBy}</td>
                <td>${editedAt}</td>
                <td class="text-center">${actionHtml}</td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}
window.toggleHistoryBooks = function() {
    const mainContainer = document.getElementById('containerDataBuku');
    const historyContainer = document.getElementById('containerHistoryBuku');
    const trashContainer = document.getElementById('containerTrashBuku');
    const btn = document.getElementById('btnToggleHistoryBooks');
    const trashBtn = document.getElementById('btnToggleTrashBooks');

    if (!mainContainer || !historyContainer || !btn) return;

    isHistoryBooksVisible = !isHistoryBooksVisible;

    if (isHistoryBooksVisible) {
        if (trashContainer) trashContainer.style.display = 'none';
        if (trashBtn && isTrashBooksVisible) {
            isTrashBooksVisible = false;
            trashBtn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
            if (trashBtn.classList.contains('btn-primary-custom')) {
                trashBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }

        mainContainer.style.display = 'none';
        historyContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data Buku';
        if (btn.classList.contains('btn-secondary-custom')) {
            btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        }
        loadHistoryBooks();
    } else {
        mainContainer.style.display = 'block';
        historyContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
        if (btn.classList.contains('btn-primary-custom')) {
            btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
        }
    }
};

function loadHistoryBooks() {
    const tableBody = document.getElementById('tableHistoryBuku');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Sedang memuat riwayat...</td></tr>';

    fetch('/api/history/books')
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                renderHistoryBooks(result.data || []);
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Gagal mengambil riwayat</td></tr>';
            }
        })
        .catch(() => {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Terjadi kesalahan sistem</td></tr>';
        });
}

function renderHistoryBooks(data) {
    const tableBody = document.getElementById('tableHistoryBuku');
    if (!tableBody) return;

    if (!data.length) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada riwayat edit.</td></tr>';
        return;
    }

    let html = '';
    data.forEach((row, index) => {
        const judul = row.judul || '-';
        const perubahan = row.perubahan || 'Data diperbarui';
        const editedBy = row.edited_by_name || 'System/Unknown';
        const editedAt = row.created_at ? new Date(row.created_at).toLocaleString('id-ID') : '-';
        const canRevert = !!row.old_values;
        const actionHtml = canRevert
            ? `<a href="/revert/${row.id}" class="btn-custom btn-warning-custom" onclick="return confirm('Kembalikan data buku ke versi ini?')">Revert</a>`
            : '<span style="color:#9ca3af;">-</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${perubahan}</td>
                <td>${editedBy}</td>
                <td>${editedAt}</td>
                <td class="text-center">${actionHtml}</td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}

window.toggleTrashBooks = function() {
    const mainContainer = document.getElementById('containerDataBuku');
    const trashContainer = document.getElementById('containerTrashBuku');
    const historyContainer = document.getElementById('containerHistoryBuku');
    const historyBtn = document.getElementById('btnToggleHistoryBooks');
    const btn = document.getElementById('btnToggleTrashBooks');

    isTrashBooksVisible = !isTrashBooksVisible;

    if (isTrashBooksVisible) {
        mainContainer.style.display = 'none';
        if (historyContainer) historyContainer.style.display = 'none';
        if (historyBtn && isHistoryBooksVisible) {
            isHistoryBooksVisible = false;
            historyBtn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
            if (historyBtn.classList.contains('btn-primary-custom')) {
                historyBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }
        trashContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data Buku';
        btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        loadTrashBooks();
    } else {
        mainContainer.style.display = 'block';
        trashContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
        btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
    }
}

function loadTrashBooks() {
    const tableBody = document.getElementById('tableTrashBuku');
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sedang memuat data sampah...</td></tr>';

    fetch('/api/databuku?trash=1')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allTrashBooks = result.data;
            renderTrashBooks(allTrashBooks);
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error(error);
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function renderTrashBooks(data) {
    const tableBody = document.getElementById('tableTrashBuku');
    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sampah kosong (Tidak ada data terhapus)</td></tr>';
        return;
    }

    let html = '';
    data.forEach((d, index) => {
        let deletedBy = d.deleted_by_name || '<span style="color:#9ca3af;">System/Unknown</span>';
        let deletedAt = d.deleted_at ? new Date(d.deleted_at).toLocaleString('id-ID') : '-';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${d.judul}</td>
                <td>${d.nama_penulis || '-'}</td>
                <td>${d.nama_penerbit || '-'}</td>
                <td>${deletedBy}</td>
                <td>${deletedAt}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="/restore/book/${d.id}" class="btn-custom btn-success-custom">Restore</a>
                        <a href="/force-delete/book/${d.id}" onclick="return confirm('Hapus permanen? Data tidak bisa kembali!')" class="btn-custom btn-danger-custom">Hapus Permanen</a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

function loadDataBuku() {
    const tableBody = document.getElementById('tableDataBuku');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Sedang memuat data...</td></tr>';

    fetch('/api/databuku')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allBooksData = result.data;
            
            changePageBuku(1);
        } else {
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error(error);
        tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function changePageBuku(page) {
    const container = document.getElementById('paginationBuku');
    if(!container) return;

    const totalPages = Math.ceil(allBooksData.length / rowsPerPage);

    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = allBooksData.slice(start, end);

    renderBookTable(pageData, start);

    let buttons = '';

    if(page > 1) {
        buttons += `<button onclick="changePageBuku(${page - 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white;">&laquo; Prev</button>`;
    }
    buttons += `<span style="color:white; font-size:0.9rem;">Halaman ${page} dari ${totalPages}</span>`;

    if(page < totalPages) {
        buttons += `<button onclick="changePageBuku(${page + 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white;">Next &raquo;</button>`;
    }

    container.innerHTML = buttons;
}

function renderBookTable(books) {
    const tableBody = document.getElementById('tableDataBuku');
    let html = '';

    if(books.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Belum ada data buku</td></tr>';
        return;
    }

    books.forEach((b, index) => {
        let penulis = b.nama_penulis || (b.penulis ? b.penulis.nama_penulis : '-');
        let penerbit = b.nama_penerbit || (b.penerbit ? b.penerbit.nama_penerbit : '-');
        let kategori = b.nama_kategori || (b.kategori ? b.kategori.nama_kategori : '-');
        
        let imgHtml = b.foto 
        ? `<img src="/storage/${b.foto}" width="50" style="border-radius: 4px;">` 
        : `<span style="color: #6b7280; font-size: 12px;">No Image</span>`;

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${b.judul}</td>
                <td>${penulis}</td>
                <td>${penerbit}</td>
                <td>${b.tahun}</td>
                <td>${b.stok}</td>
                <td>${kategori}</td>
                <td>${imgHtml}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button 
                            onclick="openEditBookModal(this)"
                            data-id="${b.id}"
                            data-judul="${b.judul}"
                            data-penulis="${b.penulis_id}"
                            data-penerbit="${b.penerbit_id}"
                            data-tahun="${b.tahun}"
                            data-stok="${b.stok}"
                            data-kategori_id="${b.kategori_id}"
                            class="btn-custom btn-warning-custom">
                            Edit
                        </button>
                        <a href="/databuku/delete/${b.id}" 
                         class="btn-custom btn-danger-custom btn-delete-ajax">
                         Hapus
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

// --- Modal Logic Buku ---
function initBookElements() {
    if (!bookModal) {
        bookModal = document.getElementById('bookModal');
        bookForm = document.getElementById('bookModalForm');
        bookTitle = document.getElementById('bookModalTitle');
        bookMethod = document.getElementById('bookMethodContainer');
        inpB_Judul = document.getElementById('bookJudul');
        inpB_Penulis = document.getElementById('bookPenulis');
        inpB_Penerbit = document.getElementById('bookPenerbit');
        inpB_Tahun = document.getElementById('bookTahun');
        inpB_Stok = document.getElementById('bookStok');
        inpB_Kategori = document.getElementById('bookKategori');
    }
}
window.openAddBookModal = function() {
    initBookElements(); if(!bookModal) return;
    bookForm.reset(); 
    bookForm.action = "/databuku/store"; 
    bookTitle.innerText = "Tambah Buku Baru";
    if(bookMethod) bookMethod.innerHTML = ""; 
    bookModal.style.display = 'flex';
}
window.openEditBookModal = function(button) {
    initBookElements(); if(!bookModal) return;
    const id = button.getAttribute('data-id');
    
    if(inpB_Judul) inpB_Judul.value = button.getAttribute('data-judul');
    if(inpB_Penulis) inpB_Penulis.value = button.getAttribute('data-penulis');
    if(inpB_Penerbit) inpB_Penerbit.value = button.getAttribute('data-penerbit');
    if(inpB_Tahun) inpB_Tahun.value = button.getAttribute('data-tahun');
    if(inpB_Stok) inpB_Stok.value = button.getAttribute('data-stok');
    if(inpB_Kategori) inpB_Kategori.value = button.getAttribute('data-kategori_id');

    bookForm.action = "/databuku/update/" + id;
    bookTitle.innerText = "Edit Data Buku";
    if(bookMethod) bookMethod.innerHTML = "";
    bookModal.style.display = 'flex';
}
window.closeBookModal = function() {
    initBookElements(); if(bookModal) bookModal.style.display = 'none';
}


/* ==========================================================================
   4. FITUR: DATA BUKU MASUK (CRUD)
   ========================================================================== */
window.toggleHistoryMasuk = function() {
    const mainContainer = document.getElementById('containerDataMasuk');
    const historyContainer = document.getElementById('containerHistoryMasuk');
    const trashContainer = document.getElementById('containerTrashMasuk');
    const btn = document.getElementById('btnToggleHistoryMasuk');
    const trashBtn = document.getElementById('btnToggleTrashMasuk');

    if (!mainContainer || !historyContainer || !btn) return;

    isHistoryMasukVisible = !isHistoryMasukVisible;

    if (isHistoryMasukVisible) {
        if (trashContainer) trashContainer.style.display = 'none';
        if (trashBtn && isTrashMasukVisible) {
            isTrashMasukVisible = false;
            trashBtn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
            if (trashBtn.classList.contains('btn-primary-custom')) {
                trashBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }

        mainContainer.style.display = 'none';
        historyContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data Buku Masuk';
        if (btn.classList.contains('btn-secondary-custom')) {
            btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        }
        loadHistoryMasuk();
    } else {
        mainContainer.style.display = 'block';
        historyContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
        if (btn.classList.contains('btn-primary-custom')) {
            btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
        }
    }
};

function loadHistoryMasuk() {
    const tableBody = document.getElementById('tableHistoryMasuk');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Sedang memuat riwayat...</td></tr>';

    fetch('/api/history/datamasuk')
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                renderHistoryMasuk(result.data || []);
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Gagal mengambil riwayat</td></tr>';
            }
        })
        .catch(() => {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Terjadi kesalahan sistem</td></tr>';
        });
}

function renderHistoryMasuk(data) {
    const tableBody = document.getElementById('tableHistoryMasuk');
    if (!tableBody) return;

    if (!data.length) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada riwayat edit.</td></tr>';
        return;
    }

    let html = '';
    data.forEach((row, index) => {
        const judul = row.judul || '-';
        const perubahan = row.perubahan || 'Data diperbarui';
        const editedBy = row.edited_by_name || 'System/Unknown';
        const editedAt = row.created_at ? new Date(row.created_at).toLocaleString('id-ID') : '-';
        const canRevert = !!row.old_values;
        const actionHtml = canRevert
            ? `<a href="/revert/${row.id}" class="btn-custom btn-warning-custom" onclick="return confirm('Kembalikan data buku masuk ke versi ini?')">Revert</a>`
            : '<span style="color:#9ca3af;">-</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${perubahan}</td>
                <td>${editedBy}</td>
                <td>${editedAt}</td>
                <td class="text-center">${actionHtml}</td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}
function loadDataBukuMasuk() {
    const tableBody = document.getElementById('tableDataBukuMasuk');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Sedang memuat data...</td></tr>';

    fetch('/api/datamasuk') 
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allDataMasuk = result.data;
            
            changePageMasuk(1);
            
            renderBookOptions(result.options_buku);
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function changePageMasuk(page) {
    const container = document.getElementById('paginationMasuk');
    if(!container) return;

    const totalPages = Math.ceil(allDataMasuk.length / rowsPerPage);

    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = allDataMasuk.slice(start, end);

    renderBookMasukTable(pageData, start);

    let buttons = '';
    
    if(page > 1) {
        buttons += `<button onclick="changePageMasuk(${page - 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white; margin-right:5px;">&laquo; Prev</button>`;
    }

    buttons += `<span style="color:white; font-size:0.9rem;">Halaman ${page} dari ${totalPages || 1}</span>`;

    if(page < totalPages) {
        buttons += `<button onclick="changePageMasuk(${page + 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white; margin-left:5px;">Next &raquo;</button>`;
    }

    container.innerHTML = buttons;
}

function renderBookMasukTable(data) {
    const tableBody = document.getElementById('tableDataBukuMasuk');
    let html = '';

    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada data buku masuk</td></tr>';
        return;
    }

    data.forEach((d, index) => {
        let judul = d.judul || '<span style="color:red">Judul Tidak Ditemukan</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${d.jumlah}</td>
                <td>${d.tanggal_masuk}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button 
                            onclick="openEditModal(this)"
                            data-id="${d.id}"
                            data-book_id="${d.book_id}" 
                            data-jumlah="${d.jumlah}"
                            data-tanggal="${d.tanggal_masuk}"
                            class="btn-custom btn-warning-custom">
                            Edit
                        </button>
                        
                        <a href="/datamasuk/hapus/${d.id}" 
                         class="btn-custom btn-danger-custom btn-delete-ajax">
                         Hapus
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

function renderBookOptions(books) {
    const select = document.getElementById('inputBookId');
    if(!select) return;

    let options = '<option value="">-- Pilih Judul Buku --</option>';
    
    books.forEach(b => {
        options += `<option value="${b.id}">${b.judul}</option>`;
    });

    select.innerHTML = options;
}

// --- Modal Logic Buku Masuk ---
function initModalElements() {
    if (!modal) {
        modal = document.getElementById('dataModal');
        modalForm = document.getElementById('modalForm');
        modalTitle = document.getElementById('modalTitle');
        methodContainer = document.getElementById('methodInputContainer');
        inputBookId = document.getElementById('inputBookId');
        inputJumlah = document.getElementById('inputJumlah');
        inputTanggal = document.getElementById('inputTanggal');
    }
}
window.openAddModal = function() {
    initModalElements(); if(!modal) return;
    modalForm.reset(); 
    modalForm.action = "/datamasuk/store";
    modalTitle.innerText = "Tambah Data Masuk";
    if(methodContainer) methodContainer.innerHTML = "";
    modal.style.display = 'flex';
}
window.openEditModal = function(button) {
    initModalElements(); if(!modal) return;
    const id = button.getAttribute('data-id');
    if(inputBookId) inputBookId.value = button.getAttribute('data-book_id');
    if(inputJumlah) inputJumlah.value = button.getAttribute('data-jumlah');
    if(inputTanggal) inputTanggal.value = button.getAttribute('data-tanggal');
    
    modalForm.action = "/datamasuk/update/" + id;
    modalTitle.innerText = "Edit Data Masuk";
    if(methodContainer) methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    modal.style.display = 'flex';
}
window.closeModal = function() {
    initModalElements(); if(modal) modal.style.display = 'none';
}


/* ==========================================================================
   5. FITUR: PEMINJAMAN (CRUD + APPROVAL SYSTEM)
   ========================================================================== */

// Fungsi untuk memanggil Laravel Controller (Approval)
window.konfirmasiPetugas = function(id, aksi) {
    const pesan = aksi === 'setujui_pinjam' 
        ? "Serahkan buku ke user dan setujui peminjaman?" 
        : "Pastikan buku sudah diterima fisik. Setujui pengembalian?";

    if (confirm(pesan)) {
        // Mengarahkan ke route konfirmasi di MyController
        window.location.href = `/peminjaman/konfirmasi/${id}/${aksi}`;
    }
}

function loadDataPeminjaman() {
    const tableBody = document.getElementById('tableDataPeminjaman');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sedang memuat data...</td></tr>';

    fetch('/api/peminjaman')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if(result.status === 'success') {
            allLoanData = result.data || [];
            changePagePeminjaman(1);

            if(result.opt_buku) populateDropdown('loanBookId', result.opt_buku, 'judul');
            if(result.opt_user) populateDropdown('loanUserId', result.opt_user, 'name');
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error loading peminjaman:', error);
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Terjadi kesalahan sistem. Silakan refresh halaman.</td></tr>';
    });
}

function renderLoanTable(data, start) {
    const tableBody = document.getElementById('tableDataPeminjaman');
    let html = '';

    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data peminjaman</td></tr>';
        return;
    }

    data.forEach((d, index) => {
        let statusBadge = '';
        let actionBtn = '';

        // --- LOGIKA STATUS & TOMBOL APPROVAL DINAMIS ---
        if (d.status === 'pending_pinjam') {
            statusBadge = `<span class="badge-custom" style="background:#f59e0b; color:black;">Menunggu Diambil</span>`;
            actionBtn = `<button onclick="konfirmasiPetugas(${d.id}, 'setujui_pinjam')" class="btn-custom btn-primary-custom" style="font-size:11px;">Setujui Pinjam</button>`;
        } 
        else if (d.status === 'pending_kembali') {
            statusBadge = `<span class="badge-custom" style="background:#0ea5e9; color:white;">Menunggu Kembali</span>`;
            actionBtn = `<button onclick="konfirmasiPetugas(${d.id}, 'setujui_kembali')" class="btn-custom btn-success-custom" style="font-size:11px;">Terima Buku</button>`;
        } 
        else if (d.status === 'dipinjam') {
            statusBadge = `<span class="badge-custom" style="background:#3b82f6; color:white;">Sedang Dipinjam</span>`;
            // Mengganti tombol Edit menjadi tombol Tarik Buku (Force Return)
            actionBtn = `<a href="/peminjaman/kembalikan/${d.id}" 
                            onclick="return confirm('Apakah Anda yakin ingin menarik kembali buku ini? Stok akan otomatis bertambah.')"
                            class="btn-custom btn-danger-custom" style="font-size:11px;">Tarik Buku</a>`;
        } 
        else {
            statusBadge = `<span class="badge-custom" style="background:#10b981; color:white;">Selesai</span>`;
            actionBtn = `<span style="color:#9ca3af;">-</span>`;
        }

        html += `
            <tr>
                <td>${start + index + 1}</td>
                <td class="font-medium">${d.judul || '<span style="color:red">Buku Terhapus</span>'}</td>
                <td>${d.name || '<span style="color:red">User Terhapus</span>'}</td>
                <td>${d.tanggal_pinjam}</td>
                <td>${d.tanggal_kembali || '-'}</td>
                <td>${statusBadge}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        ${actionBtn}
                        <a href="/peminjaman/hapus/${d.id}" class="btn-custom btn-danger-custom btn-delete">Hapus</a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

function changePagePeminjaman(page) {
    const container = document.getElementById('paginationPeminjaman');
    if(!container) return;

    const totalPages = Math.ceil(allLoanData.length / rowsPerPage);
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = allLoanData.slice(start, end);

    renderLoanTable(pageData, start);

    let buttons = '';
    if(page > 1) buttons += `<button onclick="changePagePeminjaman(${page - 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white; margin-right:5px;">&laquo; Prev</button>`;
    buttons += `<span style="color:white; font-size:0.9rem;">Halaman ${page} dari ${totalPages || 1}</span>`;
    if(page < totalPages) buttons += `<button onclick="changePagePeminjaman(${page + 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white; margin-left:5px;">Next &raquo;</button>`;

    container.innerHTML = buttons;
}

// --- Modal Logic Peminjaman ---
function initLoanElements() {
    if (!loanModal) {
        loanModal = document.getElementById('loanModal');
        loanForm = document.getElementById('loanModalForm');
        loanTitle = document.getElementById('loanModalTitle');
        loanMethod = document.getElementById('loanMethodContainer');
        inpL_Book = document.getElementById('loanBookId');
        inpL_User = document.getElementById('loanUserId');
        inpL_Pinjam = document.getElementById('loanTanggalPinjam');
        inpL_Kembali = document.getElementById('loanTanggalKembali');
        inpL_Status = document.getElementById('loanStatus');
    }
}
window.openAddLoanModal = function() {
    initLoanElements(); if(!loanModal) return;
    loanForm.reset(); 
    loanForm.action = "/peminjaman/store"; 
    loanTitle.innerText = "Tambah Peminjaman Baru";
    if(loanMethod) loanMethod.innerHTML = ""; 
    loanModal.style.display = 'flex';
}
window.openEditLoanModal = function(button) {
    initLoanElements(); if(!loanModal) return;
    const id = button.getAttribute('data-id');
    
    if(inpL_Book) inpL_Book.value = button.getAttribute('data-book_id');
    if(inpL_User) inpL_User.value = button.getAttribute('data-user_id');
    if(inpL_Pinjam) inpL_Pinjam.value = button.getAttribute('data-tanggal_pinjam');
    if(inpL_Kembali) inpL_Kembali.value = button.getAttribute('data-tanggal_kembali');
    if(inpL_Status) inpL_Status.value = button.getAttribute('data-status');

    loanForm.action = "/peminjaman/update/" + id;
    loanTitle.innerText = "Edit Data Peminjaman";
    if(loanMethod) loanMethod.innerHTML = '<input type="hidden" name="_method" value="PUT">';
    loanModal.style.display = 'flex';
}
window.closeLoanModal = function() {
    initLoanElements(); if(loanModal) loanModal.style.display = 'none';
}


/* ==========================================================================
   5. FITUR: DATA PEMINJAMAN (CRUD)
   ========================================================================== */
window.toggleHistoryPeminjaman = function() {
    const mainContainer = document.getElementById('containerDataPeminjaman');
    const historyContainer = document.getElementById('containerHistoryPeminjaman');
    const trashContainer = document.getElementById('containerTrashPeminjaman');
    const btn = document.getElementById('btnToggleHistoryPeminjaman');
    const trashBtn = document.getElementById('btnToggleTrashPeminjaman');

    if (!mainContainer || !historyContainer || !btn) return;

    isHistoryLoanVisible = !isHistoryLoanVisible;

    if (isHistoryLoanVisible) {
        if (trashContainer) trashContainer.style.display = 'none';
        if (trashBtn && isTrashLoanVisible) {
            isTrashLoanVisible = false;
            trashBtn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
            if (trashBtn.classList.contains('btn-primary-custom')) {
                trashBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }

        mainContainer.style.display = 'none';
        historyContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data Peminjaman';
        if (btn.classList.contains('btn-secondary-custom')) {
            btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        }
        loadHistoryPeminjaman();
    } else {
        mainContainer.style.display = 'block';
        historyContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
        if (btn.classList.contains('btn-primary-custom')) {
            btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
        }
    }
};

function loadHistoryPeminjaman() {
    const tableBody = document.getElementById('tableHistoryPeminjaman');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Sedang memuat riwayat...</td></tr>';

    fetch('/api/history/peminjaman')
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                renderHistoryPeminjaman(result.data || []);
            } else {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Gagal mengambil riwayat</td></tr>';
            }
        })
        .catch(() => {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Terjadi kesalahan sistem</td></tr>';
        });
}

function renderHistoryPeminjaman(data) {
    const tableBody = document.getElementById('tableHistoryPeminjaman');
    if (!tableBody) return;

    if (!data.length) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada riwayat edit.</td></tr>';
        return;
    }

    let html = '';
    data.forEach((row, index) => {
        const judul = row.judul || '-';
        const peminjam = row.nama_peminjam || '-';
        const perubahan = row.perubahan || 'Data diperbarui';
        const editedBy = row.edited_by_name || 'System/Unknown';
        const editedAt = row.created_at ? new Date(row.created_at).toLocaleString('id-ID') : '-';
        const canRevert = !!row.old_values;
        const actionHtml = canRevert
            ? `<a href="/revert/${row.id}" class="btn-custom btn-warning-custom" onclick="return confirm('Kembalikan data peminjaman ke versi ini?')">Revert</a>`
            : '<span style="color:#9ca3af;">-</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${peminjam}</td>
                <td>${perubahan}</td>
                <td>${editedBy}</td>
                <td>${editedAt}</td>
                <td class="text-center">${actionHtml}</td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}
function loadDataUser() {
    const tableBody = document.getElementById('tableDataUser');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Sedang memuat data...</td></tr>';

    fetch('/api/datauser')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allUserData = result.data;

            changePageUser(1);

            if(result.opt_level) populateDropdown('userLevel', result.opt_level, 'nama_level');
        } else {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
}

function changePageUser(page) {
    const container = document.getElementById('paginationUser');
    if(!container) return;

    const totalPages = Math.ceil(allUserData.length / rowsPerPage);

    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = allUserData.slice(start, end);

    renderUserTable(pageData, start);

    let buttons = '';
    
    if(page > 1) {
        buttons += `<button onclick="changePageUser(${page - 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white; margin-right:5px;">&laquo; Prev</button>`;
    }

    buttons += `<span style="color:white; font-size:0.9rem;">Halaman ${page} dari ${totalPages || 1}</span>`;

    if(page < totalPages) {
        buttons += `<button onclick="changePageUser(${page + 1})" class="btn-custom" style="padding:5px 10px; background:#374151; color:white; margin-left:5px;">Next &raquo;</button>`;
    }

    container.innerHTML = buttons;
}

function renderUserTable(users) {
    const tableBody = document.getElementById('tableDataUser');
    let html = '';

    if(users.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Belum ada data user</td></tr>';
        return;
    }

    users.forEach((u, index) => {
        let levelName = u.nama_level || 'User'; 

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${u.name}</td>
                <td>${u.email}</td>
                <td>
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; background: #374151; color: #e5e7eb; border: 1px solid #4b5563;">
                        ${levelName}
                    </span>
                </td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button 
                            onclick="openEditUserModal(this)"
                            data-id="${u.id}"
                            data-name="${u.name}"
                            data-email="${u.email}"
                            data-level_id="${u.level_id}"
                            class="btn-custom btn-warning-custom">
                            Edit
                        </button>

                        <a href="/datauser/reset/${u.id}" 
                           class="btn-custom btn-info-custom"
                           onclick="return confirm('Reset password user ini jadi 12345678?')">
                           Reset
                        </a>

                        <a href="/datauser/delete/${u.id}" 
                           class="btn-custom btn-danger-custom btn-delete">
                           Hapus
                        </a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

// --- Modal Logic User ---
function initUserElements() {
    if (!userModal) {
        userModal = document.getElementById('userModal');
        userForm = document.getElementById('userModalForm');
        userTitle = document.getElementById('userModalTitle');
        userMethod = document.getElementById('userMethodContainer');
        inpU_Name = document.getElementById('userName');
        inpU_Email = document.getElementById('userEmail');
        inpU_Password = document.getElementById('userPassword');
        inpU_Level = document.getElementById('userLevel');
        passHint = document.getElementById('passwordHint');
    }
}
window.openAddUserModal = function() {
    initUserElements(); if(!userModal) return;
    userForm.reset(); userForm.action = "/datauser/store"; 
    userTitle.innerText = "Tambah User Baru";
    if(userMethod) userMethod.innerHTML = "";
    if(inpU_Password) inpU_Password.required = true;
    if(passHint) passHint.style.display = 'none';
    userModal.style.display = 'flex';
}
window.openEditUserModal = function(button) {
    initUserElements(); if(!userModal) return;
    const id = button.getAttribute('data-id');
    if(inpU_Name) inpU_Name.value = button.getAttribute('data-name');
    if(inpU_Email) inpU_Email.value = button.getAttribute('data-email');
    if(inpU_Level) inpU_Level.value = button.getAttribute('data-level_id');
    if(inpU_Password) { inpU_Password.value = ""; inpU_Password.required = false; }
    if(passHint) passHint.style.display = 'block';
    
    userForm.action = "/datauser/update/" + id;
    userTitle.innerText = "Edit Data User";
    if(userMethod) userMethod.innerHTML = ""; 
    userModal.style.display = 'flex';
}
window.closeUserModal = function() {
    initUserElements(); if(userModal) userModal.style.display = 'none';
}

/* ==========================================================================
   7. FITUR: LAPORAN (FILTER, PAGINATION & EXPORT DINAMIS)
   ========================================================================== */

// Variabel Khusus Laporan (Biar gak bentrok sama variable CRUD di atas)
let reportCurrentPage = 1;
let reportFilteredRows = [];

// --- LOGIC PAGINATION LAPORAN (CLIENT SIDE) ---
function setupPaginationHTML() {
    const table = document.getElementById('tableLaporan');
    if (!table) return;

    // 1. Cek apakah container pagination sudah ada?
    // Kita cari ID umum 'pagLaporanMasuk', 'pagLaporanPinjam', atau 'paginationContainer'
    let pagContainer = document.getElementById('pagLaporanMasuk') || 
                       document.getElementById('pagLaporanPinjam') || 
                       document.getElementById('paginationContainer');

    // 2. Kalau belum ada, kita bikin baru secara otomatis
    if (!pagContainer) {
        pagContainer = document.createElement('div');
        pagContainer.id = 'paginationContainer';
        pagContainer.style.cssText = "display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px;";
        table.parentElement.appendChild(pagContainer);
    } else {
        // Pastikan ID-nya kita standarisasi di JS biar gampang dipanggil
        pagContainer.id = 'paginationContainer';
    }
    
    // 3. Ambil semua baris data dari HTML
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    reportFilteredRows = Array.from(rows); 
}

function renderReportTablePartition() {
    const table = document.getElementById('tableLaporan');
    if(!table) return;
    
    const allRows = Array.from(table.getElementsByTagName('tbody')[0].getElementsByTagName('tr'));
    
    // Sembunyikan SEMUA baris
    allRows.forEach(row => row.style.display = 'none');

    // Hitung range (Start - End)
    const startIndex = (reportCurrentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    
    // Tampilkan HANYA baris yang sesuai halaman
    const rowsToShow = reportFilteredRows.slice(startIndex, endIndex);
    rowsToShow.forEach(row => row.style.display = '');

    renderReportPaginationControls();
}

function renderReportPaginationControls() {
    const pagContainer = document.getElementById('paginationContainer');
    if (!pagContainer) return;

    const totalPages = Math.ceil(reportFilteredRows.length / rowsPerPage);
    
    // Kalau cuma 1 halaman atau data kosong, sembunyikan tombol
    if (totalPages <= 1) {
        pagContainer.innerHTML = ''; 
        return;
    }

    let html = '';
    // Tombol Prev (Perhatikan nama fungsinya: changeReportPage)
    if (reportCurrentPage > 1) {
        html += `<button onclick="changeReportPage(${reportCurrentPage - 1})" class="btn-custom" style="padding: 5px 10px; background: #374151; color: white;">&laquo; Prev</button>`;
    }
    
    // Info Halaman
    html += `<span style="color: white; font-size: 0.9rem;">Page ${reportCurrentPage} of ${totalPages}</span>`;
    
    // Tombol Next (Perhatikan nama fungsinya: changeReportPage)
    if (reportCurrentPage < totalPages) {
        html += `<button onclick="changeReportPage(${reportCurrentPage + 1})" class="btn-custom" style="padding: 5px 10px; background: #374151; color: white;">Next &raquo;</button>`;
    }
    
    pagContainer.innerHTML = html;
}

// --- FUNGSI GANTI HALAMAN KHUSUS LAPORAN ---
// PENTING: Nama fungsi ini BEDA dengan 'changePage' milik CRUD di atas
window.changeReportPage = function(page) {
    reportCurrentPage = page;
    renderReportTablePartition();
}

// --- LOGIC FILTER TANGGAL ---
window.filterTableByDate = function() {
    const fromDateVal = document.getElementById('filterFrom').value;
    const toDateVal = document.getElementById('filterTo').value;
    const table = document.getElementById('tableLaporan');
    
    if(!table) return;

    const tbody = table.getElementsByTagName('tbody')[0];
    const allRows = Array.from(tbody.getElementsByTagName('tr')); 

    // Update Link Export (Print/PDF/Excel)
    updateExportLinks(fromDateVal, toDateVal);

    // Filter Logic
    reportFilteredRows = allRows.filter(row => {
        // Pastikan kolom tanggal di HTML punya class="tgl-pinjam"
        const dateCell = row.getElementsByClassName('tgl-pinjam')[0];
        if (!dateCell) return false; 
        
        const rowDateStr = dateCell.textContent.trim();
        
        let showRow = true;
        if (fromDateVal && rowDateStr < fromDateVal) showRow = false;
        if (toDateVal && rowDateStr > toDateVal) showRow = false;
        return showRow;
    });

    reportCurrentPage = 1; // Reset ke halaman 1 setiap filter
    
    const noDataMsg = document.getElementById('noDataMessage');
    
    if (reportFilteredRows.length === 0) {
        if(noDataMsg) noDataMsg.style.display = "block";
        table.style.display = "none";
        document.getElementById('paginationContainer').innerHTML = '';
    } else {
        if(noDataMsg) noDataMsg.style.display = "none";
        table.style.display = "table";
        renderReportTablePartition(); 
    }
}

window.resetFilter = function() {
    document.getElementById('filterFrom').value = "";
    document.getElementById('filterTo').value = "";
    
    const table = document.getElementById('tableLaporan');
    if(table) {
        const tbody = table.getElementsByTagName('tbody')[0];
        reportFilteredRows = Array.from(tbody.getElementsByTagName('tr')); 
        
        document.getElementById('noDataMessage').style.display = "none";
        table.style.display = "table";
        
        reportCurrentPage = 1;
        renderReportTablePartition();
        updateExportLinks("", ""); 
    }
}

// --- LOGIC EXPORT DINAMIS ---
function updateExportLinks(from, to) {
    const btnPrint = document.getElementById('btnPrint');
    const btnPdf = document.getElementById('btnPdf');
    const btnExcel = document.getElementById('btnExcel');

    const setLink = (btn) => {
        if(!btn) return;
        const baseUrl = btn.getAttribute('data-base-url'); 
        if(baseUrl) {
            btn.href = `${baseUrl}?from=${from}&to=${to}`;
        }
    };

    setLink(btnPrint);
    setLink(btnPdf);
    setLink(btnExcel);
}


/* ==========================================================================
   7.5. FITUR: RIWAYAT PEMINJAMAN (UNTUK USER/ANGGOTA)
   ========================================================================== */

function loadDataRiwayat() {
    const tableBody = document.getElementById('tableRiwayatPeminjaman');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Sedang memuat data...</td></tr>';

    fetch('/api/riwayat')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if(result.status === 'success') {
            renderRiwayatTable(result.data || []);
            startCountdown(); // Start timer setelah data dimuat
        } else {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error loading riwayat:', error);
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Terjadi kesalahan sistem. Silakan refresh halaman.</td></tr>';
    });
}

function renderRiwayatTable(data) {
    const tableBody = document.getElementById('tableRiwayatPeminjaman');
    if (!tableBody) return;

    let html = '';

    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada riwayat peminjaman</td></tr>';
        return;
    }

    data.forEach((r, index) => {
        // Hitung deadline
        // Gunakan tanggal_kembali dari DB jika ada (karena sekarang disimpan saat approval)
        // Ini menggantikan logika JS lama yang menghitung manual +7 hari
        let deadline;
        
        if (r.status === 'dipinjam' && r.tanggal_kembali) {
            deadline = new Date(r.tanggal_kembali);
        } else {
            // Fallback logika lama (hitung manual dari tanggal pinjam)
            // Berguna jika tanggal_kembali belum diset (misal pending)
            const tanggalPinjam = new Date(r.tanggal_pinjam);
            deadline = new Date(tanggalPinjam);
            deadline.setDate(deadline.getDate() + 7);
        }

        // Set deadline ke akhir hari (23:59:59) hanya jika tidak ada info jam (misal data lama atau fallback)
        // Tapi karena sekarang kita pakai datetime, kita tidak perlu override jamnya jika valid.
        // Cek apakah deadline punya jam 00:00:00 (indikasi date only) atau bukan.
        // Namun, jika r.tanggal_kembali dari DB sudah datetime, kita pakai apa adanya.
        
        if (r.status !== 'dipinjam' || !r.tanggal_kembali) {
             // Fallback atau status lain, set end of day biar aman
             deadline.setHours(23, 59, 59, 999);
        }
        
        const now = new Date();
        
        const isExpired = now > deadline;
        
        // FIX: Gunakan string asli dari DB jika ada, agar tidak kena konversi timezone UTC via toISOString()
        // Jika hasil kalkulasi manual (fallback), format manual ke local string
        let deadlineStr;
        if (r.status === 'dipinjam' && r.tanggal_kembali) {
            deadlineStr = r.tanggal_kembali; // "2026-01-14 08:24:47"
        } else {
            // Manual format: YYYY-MM-DD HH:mm:ss
            const pad = (n) => n.toString().padStart(2, '0');
            deadlineStr = deadline.getFullYear() + '-' +
                pad(deadline.getMonth() + 1) + '-' +
                pad(deadline.getDate()) + ' ' +
                pad(deadline.getHours()) + ':' +
                pad(deadline.getMinutes()) + ':' +
                pad(deadline.getSeconds());
        }

        // Format tanggal pinjam
        const tanggalPinjamFormatted = new Date(r.tanggal_pinjam).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        // Status badge
        let statusBadge = '';
        if (isExpired && r.status === 'dipinjam') {
            statusBadge = '<span class="badge-custom" style="background:#ef4444; color:white; white-space:nowrap;">Waktu Habis</span>';
        } else if (r.status === 'pending_pinjam') {
            statusBadge = '<span class="badge-custom" style="background:#f59e0b; color:black; white-space:nowrap;">Menunggu Diambil</span>';
        } else if (r.status === 'pending_kembali') {
            statusBadge = '<span class="badge-custom" style="background:#0ea5e9; color:white; white-space:nowrap;">Menunggu Kembali</span>';
        } else if (r.status === 'dipinjam') {
            statusBadge = '<span class="badge-custom" style="background:#3b82f6; color:white; white-space:nowrap;">Sedang Dipinjam</span>';
        } else {
            statusBadge = '<span class="badge-custom" style="background:#10b981; color:white; white-space:nowrap;">Selesai</span>';
        }

        // --- LOGIKA TAMPILAN TIMER (DIGANTI BAGIAN INI) ---
        let timerDisplay = '';
        
        if (r.status === 'pending_pinjam') {
            // Kalau masih pending, jangan tampilkan jam
            timerDisplay = '<span style="color: #9ca3af; font-size: 0.9rem;">Menunggu Konfirmasi</span>';
        } 
        else if (r.status === 'pending_kembali') {
             timerDisplay = '<span style="color: #0ea5e9; font-weight:bold;">Proses Pengembalian</span>';
        }
        else if (r.status === 'dikembalikan' || r.status === 'selesai') {
             timerDisplay = '<span style="color: #22c55e; font-weight:bold;">SELESAI</span>';
        }
        else {
            // Kalau status 'dipinjam', baru tampilkan slot timer
            // Nanti startCountdown() yang akan mengisi angkanya
            timerDisplay = '<span class="timer-clock" style="font-weight: bold; font-family: monospace; color: #60a5fa;">--:--:--</span>';
        }

        // Tombol aksi
        let actionBtn = '';
        if (r.status === 'dipinjam' && !isExpired) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                             document.querySelector('input[name="_token"]')?.value || '';
            actionBtn = `
                <div class="action-buttons">
                    <form method="POST" action="/peminjaman/ajukan-kembali/${r.id}" style="display:inline;">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn-custom btn-success-custom">Kembalikan</button>
                    </form>
                </div>
            `;
        } else if (r.status === 'pending_pinjam') {
            actionBtn = '<span style="color:#9ca3af; font-size:0.875rem;">Menunggu Approval</span>';
        } else if (r.status === 'pending_kembali') {
            actionBtn = '<span style="color:#9ca3af; font-size:0.875rem;">Sedang Dicek</span>';
        } else {
            actionBtn = '<span style="color:#9ca3af;">-</span>';
        }

        html += `
            <tr data-status="${r.status}" data-deadline="${deadlineStr}">
                <td>${index + 1}</td>
                <td class="font-medium">${r.judul || '<span style="color:red">Buku Terhapus</span>'}</td>
                <td>${tanggalPinjamFormatted}</td>
                <td>${statusBadge}</td>
                <td>${timerDisplay}</td>
                <td class="text-center">${actionBtn}</td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
}

function startCountdown() {
    // Clear previous interval if it exists
    if (window.riwayatCountdownInterval) {
        clearInterval(window.riwayatCountdownInterval);
    }

    window.riwayatCountdownInterval = setInterval(() => {
        const now = new Date().getTime();

        document.querySelectorAll('#tableRiwayatPeminjaman tr[data-deadline]').forEach(row => {
            const status = row.dataset.status;
            const deadlineStr = row.dataset.deadline;
            const timerElement = row.querySelector('.timer-clock');
            
            if (!timerElement || !deadlineStr) return;

            // --- FIX START: Handle Returned Books ---
            // If the book is returned (dikembalikan) or finished (selesai), 
            // stop the timer and show "SELESAI"
            if (status === 'dikembalikan' || status === 'selesai') {
                timerElement.innerHTML = "SELESAI";
                timerElement.style.color = "#22c55e"; // Green color
                return; // Stop processing this row
            }

            // If the status is 'pending_kembali' (waiting for admin approval to return),
            // you might want to show a specific message or pause the timer.
            // For now, let's assume the timer stops when the user requests return.
            if (status === 'pending_kembali') {
                timerElement.innerHTML = "MENUNGGU VERIFIKASI";
                timerElement.style.color = "#0ea5e9"; // Blue color
                return;
            }
            // --- FIX END ---

            // Parse deadline
            // Replace space with T for ISO format compatibility (e.g., "2023-10-27 10:00:00" -> "2023-10-27T10:00:00")
            const deadline = new Date(deadlineStr.replace(' ', 'T'));
        const now = new Date();
        const distance = deadline - now;

            if (distance < 0) {
                timerElement.innerHTML = "EXPIRED";
                timerElement.style.color = "#ef4444"; // Red color
                
                // Hide the return button if expired (optional, based on your logic)
                const btn = row.querySelector('.btn-success-custom');
                if(btn) btn.style.display = 'none';
            } else {
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                timerElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                
                // Change color to yellow if less than 1 day remains
                if (days < 1) {
                    timerElement.style.color = "#facc15";
                } else {
                    timerElement.style.color = ""; // Default color
                }
            }
        });
    }, 1000);
}


/* ==========================================================================
   7.6. FITUR: TRASH (DATA MASUK, PEMINJAMAN, USER)
   ========================================================================== */

// --- TRASH DATA MASUK ---
window.toggleTrashMasuk = function() {
    const mainContainer = document.getElementById('containerDataMasuk');
    const trashContainer = document.getElementById('containerTrashMasuk');
    const historyContainer = document.getElementById('containerHistoryMasuk');
    const historyBtn = document.getElementById('btnToggleHistoryMasuk');
    const btn = document.getElementById('btnToggleTrashMasuk');

    isTrashMasukVisible = !isTrashMasukVisible;

    if (isTrashMasukVisible) {
        mainContainer.style.display = 'none';
        if (historyContainer) historyContainer.style.display = 'none';
        if (historyBtn && isHistoryMasukVisible) {
            isHistoryMasukVisible = false;
            historyBtn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
            if (historyBtn.classList.contains('btn-primary-custom')) {
                historyBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }
        trashContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data';
        btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        loadTrashMasuk();
    } else {
        mainContainer.style.display = 'block';
        trashContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
        btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
    }
}

function loadTrashMasuk() {
    const tableBody = document.getElementById('tableTrashMasuk');
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sedang memuat data sampah...</td></tr>';

    fetch('/api/datamasuk?trash=1')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allTrashMasuk = result.data;
            renderTrashMasuk(allTrashMasuk);
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error(error);
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function renderTrashMasuk(data) {
    const tableBody = document.getElementById('tableTrashMasuk');
    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sampah kosong (Tidak ada data terhapus)</td></tr>';
        return;
    }

    let html = '';
    data.forEach((d, index) => {
        let deletedBy = d.deleted_by_name || '<span style="color:#9ca3af;">System/Unknown</span>';
        let deletedAt = d.deleted_at ? new Date(d.deleted_at).toLocaleString('id-ID') : '-';
        let judul = d.judul || '<span style="color:red">Buku Terhapus</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${d.jumlah}</td>
                <td>${d.tanggal_masuk}</td>
                <td>${deletedBy}</td>
                <td>${deletedAt}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="/restore/datamasuk/${d.id}" class="btn-custom btn-success-custom">Restore</a>
                        <a href="/force-delete/datamasuk/${d.id}" onclick="return confirm('Hapus permanen? Data tidak bisa kembali!')" class="btn-custom btn-danger-custom">Hapus Permanen</a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

// --- TRASH PEMINJAMAN ---
window.toggleTrashPeminjaman = function() {
    const mainContainer = document.getElementById('containerDataPeminjaman');
    const trashContainer = document.getElementById('containerTrashPeminjaman');
    const historyContainer = document.getElementById('containerHistoryPeminjaman');
    const historyBtn = document.getElementById('btnToggleHistoryPeminjaman');
    const btn = document.getElementById('btnToggleTrashPeminjaman');

    isTrashLoanVisible = !isTrashLoanVisible;

    if (isTrashLoanVisible) {
        mainContainer.style.display = 'none';
        if (historyContainer) historyContainer.style.display = 'none';
        if (historyBtn && isHistoryLoanVisible) {
            isHistoryLoanVisible = false;
            historyBtn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
            if (historyBtn.classList.contains('btn-primary-custom')) {
                historyBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }
        trashContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data';
        btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        loadTrashPeminjaman();
    } else {
        mainContainer.style.display = 'block';
        trashContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
        btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
    }
}

function loadTrashPeminjaman() {
    const tableBody = document.getElementById('tableTrashPeminjaman');
    tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Sedang memuat data sampah...</td></tr>';

    fetch('/api/peminjaman?trash=1')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allTrashLoan = result.data;
            renderTrashPeminjaman(allTrashLoan);
        } else {
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error(error);
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function renderTrashPeminjaman(data) {
    const tableBody = document.getElementById('tableTrashPeminjaman');
    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Sampah kosong (Tidak ada data terhapus)</td></tr>';
        return;
    }

    let html = '';
    data.forEach((d, index) => {
        let deletedBy = d.deleted_by_name || '<span style="color:#9ca3af;">System/Unknown</span>';
        let deletedAt = d.deleted_at ? new Date(d.deleted_at).toLocaleString('id-ID') : '-';
        let judul = d.judul || '<span style="color:red">Buku Terhapus</span>';
        let peminjam = d.name || '<span style="color:red">User Terhapus</span>';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${judul}</td>
                <td>${peminjam}</td>
                <td>${d.tanggal_pinjam}</td>
                <td>${d.tanggal_kembali}</td>
                <td>${deletedBy}</td>
                <td>${deletedAt}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="/restore/peminjaman/${d.id}" class="btn-custom btn-success-custom">Restore</a>
                        <a href="/force-delete/peminjaman/${d.id}" onclick="return confirm('Hapus permanen? Data tidak bisa kembali!')" class="btn-custom btn-danger-custom">Hapus Permanen</a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}

// --- TRASH USER ---
window.toggleTrashUser = function() {
    const mainContainer = document.getElementById('containerDataUser');
    const trashContainer = document.getElementById('containerTrashUser');
    const historyContainer = document.getElementById('containerHistoryUser');
    const historyBtn = document.getElementById('btnToggleHistoryUser');
    const btn = document.getElementById('btnToggleTrashUser');

    isTrashUserVisible = !isTrashUserVisible;

    if (isTrashUserVisible) {
        mainContainer.style.display = 'none';
        if (historyContainer) historyContainer.style.display = 'none';
        if (historyBtn && isHistoryUserVisible) {
            isHistoryUserVisible = false;
            historyBtn.innerHTML = '<i class="fas fa-history"></i> Riwayat Edit';
            if (historyBtn.classList.contains('btn-primary-custom')) {
                historyBtn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
            }
        }
        trashContainer.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Data';
        btn.classList.replace('btn-secondary-custom', 'btn-primary-custom');
        loadTrashUser();
    } else {
        mainContainer.style.display = 'block';
        trashContainer.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-trash"></i> Lihat Sampah';
        btn.classList.replace('btn-primary-custom', 'btn-secondary-custom');
    }
}

function loadTrashUser() {
    const tableBody = document.getElementById('tableTrashUser');
    tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sedang memuat data sampah...</td></tr>';

    fetch('/api/datauser?trash=1')
    .then(response => response.json())
    .then(result => {
        if(result.status === 'success') {
            allTrashUser = result.data;
            renderTrashUser(allTrashUser);
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Gagal mengambil data</td></tr>';
        }
    })
    .catch(error => {
        console.error(error);
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Terjadi kesalahan sistem</td></tr>';
    });
}

function renderTrashUser(data) {
    const tableBody = document.getElementById('tableTrashUser');
    if(data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">Sampah kosong (Tidak ada data terhapus)</td></tr>';
        return;
    }

    let html = '';
    data.forEach((d, index) => {
        let deletedBy = d.deleted_by_name || '<span style="color:#9ca3af;">System/Unknown</span>';
        let deletedAt = d.deleted_at ? new Date(d.deleted_at).toLocaleString('id-ID') : '-';
        let levelName = d.nama_level || '-';

        html += `
            <tr>
                <td>${index + 1}</td>
                <td class="font-medium">${d.name}</td>
                <td>${d.email}</td>
                <td>${levelName}</td>
                <td>${deletedBy}</td>
                <td>${deletedAt}</td>
                <td class="text-center">
                    <div class="action-buttons">
                        <a href="/restore/user/${d.id}" class="btn-custom btn-success-custom">Restore</a>
                        <a href="/force-delete/user/${d.id}" onclick="return confirm('Hapus permanen? Data tidak bisa kembali!')" class="btn-custom btn-danger-custom">Hapus Permanen</a>
                    </div>
                </td>
            </tr>
        `;
    });
    tableBody.innerHTML = html;
}


/* ==========================================================================
   8. MAIN EVENT LISTENERS (ON LOAD)
   ========================================================================== */
document.addEventListener('DOMContentLoaded', () => {

    // 1. Cek Halaman CRUD (AJAX) - Biar gak error di halaman laporan
    if(document.getElementById('tableDataBuku'))      loadDataBuku();
    if(document.getElementById('tableDataBukuMasuk')) loadDataBukuMasuk();
    if(document.getElementById('tableDataPeminjaman')) loadDataPeminjaman();
    if(document.getElementById('tableDataUser'))      loadDataUser();
    if(document.getElementById('tableRiwayatPeminjaman')) loadDataRiwayat();

    // 2. Cek Halaman Laporan (Static HTML)
    if(document.getElementById('tableLaporan')) {
        setupPaginationHTML();
        renderReportTablePartition(); 
    }

    // 3. Init Modals
    initModalElements(); 
    initBookElements(); 
    initLoanElements(); 
    initUserElements();

    // 4. Global Modal Close (Click Outside)
    window.onclick = function(event) {
        if (modal && event.target == modal) modal.style.display = 'none';
        if (bookModal && event.target == bookModal) bookModal.style.display = 'none';
        if (loanModal && event.target == loanModal) loanModal.style.display = 'none';
        if (userModal && event.target == userModal) userModal.style.display = 'none';
    }

    // 5. Login Logic
    const loginBtn = document.getElementById('loginBtn');
    if (loginBtn) {
        loginBtn.addEventListener("click", function (e) {
            e.preventDefault(); 
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const emailVal = emailInput.value.trim();
            const passVal = passwordInput.value; // Jangan trim password, biarkan user kontrol spasi mereka
            
            if (!emailVal || !passVal) {
                showModal("Woi bro email sama password jangan kosong lah 😤");
                return;
            }
            
            // Update email value dengan yang sudah di-trim
            emailInput.value = emailVal;
            
            document.getElementById('login-form').submit();
        });
    }

    const closeModalBtn = document.getElementById("closeModal");
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", () => {
            document.getElementById("popupModal").style.display = "none";
        });
    }

    // 6. Mobile Menu
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    if (menuToggleBtn) {
        menuToggleBtn.addEventListener('click', () => {
            const navMobile = document.getElementById('nav-mobile');
            navMobile.style.display = (navMobile.style.display === 'block') ? 'none' : 'block';
        });
    }

    // 7. Delete Confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete');
    if(deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if(this.classList.contains('btn-delete-ajax')) return; 
                e.preventDefault();
                const href = this.getAttribute('href');
                if (confirm("Yakin mau hapus data ini secara permanen?")) {
                    window.location.href = href;
                }
            });
        });
    }
});
