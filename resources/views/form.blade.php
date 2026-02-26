<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ !empty($p) && $p ? 'Edit Buku' : 'Tambah Buku' }}</title>
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="mb-4">{{ !empty($p) && $p ? 'Edit Buku' : 'Tambah Buku' }}</h4>

        <form 
          action="@if(!empty($p) && $p) {{ url('/databuku/update/'.$buku->id) }} @else {{ url('/databuku/save') }} @endif" 
          method="POST" 
          enctype="multipart/form-data"
        >
          @csrf

          @if(!empty($p) && $p)
            <input type="hidden" name="id" value="{{ $buku->id }}">
          @endif

          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $buku->judul ?? '') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Penulis</label>
            <select name="penulis_id" class="form-select" required>
              <option value="">Pilih Penulis</option>
              @foreach($penulis as $pen)
                <option 
                  value="{{ $pen->id }}" 
                  {{ (!empty($buku) && $buku->penulis_id == $pen->id) ? 'selected' : '' }}
                >
                  {{ $pen->nama_penulis }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <select name="penerbit_id" class="form-select" required>
              <option value="">Pilih Penerbit</option>
              @foreach($penerbit as $pn)
                <option 
                  value="{{ $pn->id }}" 
                  {{ (!empty($buku) && $buku->penerbit_id == $pn->id) ? 'selected' : '' }}
                >
                  {{ $pn->nama_penerbit }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tahun</label>
            <input 
              type="number" 
              name="tahun" 
              class="form-control" 
              value="{{ old('tahun', $buku->tahun ?? '') }}" 
              required
            >
          </div>

          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori_id" class="form-select" required>
              <option value="">Pilih Kategori</option>
              @foreach($kategori as $k)
                <option 
                  value="{{ $k->id }}" 
                  {{ (!empty($buku) && $buku->kategori_id == $k->id) ? 'selected' : '' }}
                >
                  {{ $k->nama_kategori }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Stok</label>
            <input 
              type="number" 
              name="stok" 
              class="form-control" 
              value="{{ old('stok', $buku->stok ?? '') }}" 
              required
            >
          </div>

          <div class="mb-4">
            <label class="form-label fw-bold">Foto Cover</label>
            <div id="drop-zone-foto" class="border rounded p-4 text-center position-relative" style="border: 2px dashed #ccc; cursor: pointer; background-color: #f8f9fa;">
                <input type="file" name="foto" id="foto-input" class="position-absolute w-100 h-100 top-0 start-0 opacity-0" accept="image/*" style="cursor: pointer;">
                
                <div id="placeholder-foto" class="{{ (!empty($buku) && $buku->foto) ? 'd-none' : '' }}">
                    <div class="mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-cloud-upload text-secondary" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"/>
                          <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                    </div>
                    <p class="mb-1 text-secondary">Drag & drop foto di sini</p>
                    <small class="text-muted">atau klik untuk memilih (JPG, PNG)</small>
                </div>

                <div id="preview-container-foto" class="{{ (!empty($buku) && $buku->foto) ? '' : 'd-none' }}">
                    <img src="{{ (!empty($buku) && $buku->foto) ? asset('storage/'.$buku->foto) : '' }}" 
                         id="img-preview" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 200px; object-fit: contain;">
                    <p class="mt-2 text-success small mb-0" id="file-name-label">
                        {{ (!empty($buku) && $buku->foto) ? 'Foto saat ini' : '' }}
                    </p>
                </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">File E-Book (PDF)</label>
            <input type="file" name="file_buku" class="form-control" accept=".pdf">
            <div class="form-text">Format wajib: .pdf. Maksimal 10MB.</div>
            @if(!empty($buku) && $buku->file_buku)
              <div class="mt-2 text-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf" viewBox="0 0 16 16">
                  <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                  <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.545-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.065 8.114c.06.52.198 1.121.42 1.64.205.481.338.591.233-.116-.076-.508-.194-1.077-.406-1.573a9.206 9.206 0 0 0-.247-.442z"/>
                </svg>
                E-Book Tersedia
              </div>
            @endif
          </div>

          <div class="d-flex justify-content-between">
            <a href="/databuku" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">
              {{ !empty($p) && $p ? 'Update' : 'Simpan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop-zone-foto');
        const input = document.getElementById('foto-input');
        const previewContainer = document.getElementById('preview-container-foto');
        const placeholder = document.getElementById('placeholder-foto');
        const imgPreview = document.getElementById('img-preview');
        const fileNameLabel = document.getElementById('file-name-label');

        // Handle Drag Events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('bg-light');
            dropZone.style.borderColor = '#198754'; // Bootstrap Success Color
            dropZone.style.backgroundColor = '#e8f5e9';
        }

        function unhighlight(e) {
            dropZone.classList.remove('bg-light');
            dropZone.style.borderColor = '#ccc';
            dropZone.style.backgroundColor = '#f8f9fa';
        }

        // Handle Drop
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            input.files = files; // Assign dropped files to input
            handleFiles(files);
        }

        // Handle Click/Select
        input.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                        placeholder.classList.add('d-none');
                        fileNameLabel.textContent = file.name;
                        fileNameLabel.classList.remove('text-success'); // Reset text color if needed
                        fileNameLabel.classList.add('text-primary');
                    }
                    reader.readAsDataURL(file);
                } else {
                    alert('Mohon upload file gambar.');
                }
            }
        }
    });
  </script>
</body>
</html>
