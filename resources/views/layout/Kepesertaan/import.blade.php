<div class="modal fade" id="kepesertaan-import-modal" tabindex="-1" aria-labelledby="kepesertaan-import-title" aria-hidden="true"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content">
<div class="modal-header"><h3 id="kepesertaan-import-title" class="modal-title">Import Data Kepesertaan</h3><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
<div class="modal-body">
<p>Unggah file untuk memeriksa perubahan terlebih dahulu. Data belum disimpan sebelum konfirmasi.</p>
<p class="text-muted">Baris dengan peringatan tetap bisa dicentang dan diimpor. Baris yang identitasnya ambigu atau datanya tidak dapat disimpan akan menampilkan alasan dan tetap dilewati.</p>
<p class="text-muted">Data peserta aktif, pensiunan, dan PHDP/gaji terakhir dicocokkan berdasarkan NIK/NIP UMM. Nama sheet bebas; tabel dikenali dari judul kolomnya. Iuran dibaca sesuai tahun pilihan. Foto/dokumen lama tetap tersimpan.</p>
<details class="text-muted mb-3"><summary>Format kolom iuran XLSX / CSV</summary>Gunakan kolom Tahun, Bulan (1–12), PHDP, Iuran Peserta, dan Iuran Pemberi Kerja bersama identitas peserta. Satu baris untuk setiap bulan; gunakan angka tanpa pemisah ribuan. Untuk XLSX dengan kelompok kolom bulanan, gunakan tanggal bulan di baris 2 dan judul PHDP/Gaji Pokok, Iuran Peserta, Iuran PK/Iuran Pemberi Kerja di baris 3; NIP UMM dan Nama Peserta di baris 2. Nilai nol tetap diimpor, kolom kosong tidak menimpa nilai lama.</details>
<form id="participant-import-form" enctype="multipart/form-data">@csrf
<div><label for="participant-import-file" class="form-label">File XLSX / CSV (maksimal 20 MB)</label><input id="participant-import-file" name="file" type="file" accept=".xlsx,.csv" required class="form-control"></div>
<div class="mt-3"><label for="participant-iuran-year" class="form-label">Tahun iuran yang diimpor</label><input id="participant-iuran-year" name="iuran_year" type="number" min="1900" max="2099" value="{{ now('Asia/Jakarta')->year }}" required class="form-control" style="max-width:150px"></div>
<button id="participant-preview-button" type="submit" class="btn btn-light-primary mt-4">Bandingkan Ulang</button>
</form>
<p id="participant-import-status" class="mt-4" role="status"></p><div id="participant-import-preview"></div>
</div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button id="participant-import-save" type="button" class="btn btn-primary" hidden>Impor Data Pilihan</button></div>
</div></div></div>
<script>
(() => {
    const importUrl = action => window.location.origin + @json(request()->getBaseUrl()) + '/kepesertaan/import/' + action;
    const form = document.getElementById('participant-import-form');
    const status = document.getElementById('participant-import-status');
    const preview = document.getElementById('participant-import-preview');
    const save = document.getElementById('participant-import-save');
    const submit = document.getElementById('participant-preview-button');
    let pending = null, busy = false, controller;
    const choices = new Set(), eligible = new Map();
    const additions = new Map();
    const reset = () => { pending = null; choices.clear(); eligible.clear(); additions.clear(); save.hidden = true; preview.replaceChildren(); };
    const selected = () => Array.from(choices, index => ({ value: index, dataset: { status: eligible.get(index) } }));
    const updateSelection = () => {
        preview.querySelectorAll('[data-import-field]').forEach(input => {
            const value = additions.get(Number(input.dataset.importRow))?.[input.dataset.importField];
            if (value !== undefined) input.value = value;
        });
        preview.querySelectorAll('.participant-import-row:not(:disabled)').forEach(row => row.checked = choices.has(Number(row.value)));
        const picked = selected();
        const bulkIndices = Array.from(eligible, ([index, rowStatus]) => rowStatus !== 'unchanged' ? index : null).filter(index => index !== null);
        const bulkPicked = bulkIndices.filter(index => choices.has(index)).length;
        const all = preview.querySelector('#participant-select-all');
        if (all) { all.checked = bulkIndices.length > 0 && bulkPicked === bulkIndices.length; all.indeterminate = bulkPicked > 0 && bulkPicked < bulkIndices.length; all.disabled = !bulkIndices.length; }
        const count = preview.querySelector('#participant-selected-count');
        if (count) count.textContent = picked.length + ' data dipilih';
        save.disabled = !picked.length || busy;
        save.textContent = 'Impor Data Pilihan (' + picked.length + ')';
    };
    preview.addEventListener('input', event => {
        const input = event.target;
        if (busy || !input.matches('[data-import-field]')) return;
        const index = Number(input.dataset.importRow);
        const fields = additions.get(index) || {};
        fields[input.dataset.importField] = input.value;
        additions.set(index, fields);
    });
    preview.addEventListener('change', async event => {
        if (event.target.matches('[data-import-target]')) {
            if (busy || !pending || !event.target.value) return;
            busy = true; submit.disabled = true; updateSelection();
            try {
                const result = await message(await fetch(importUrl('resolve'), { method: 'POST', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value }, body: JSON.stringify({ token: pending.token, index: Number(event.target.dataset.importTarget), target: event.target.value, search: preview.querySelector('#participant-import-search').value, page: Number(preview.querySelector('[data-preview-page]').dataset.previewPage) }) }));
                eligible.clear(); result.selectable.forEach(row => eligible.set(Number(row.index), row.status));
                for (const index of choices) if (!eligible.has(index)) choices.delete(index);
                pending = result; preview.innerHTML = result.html; save.hidden = !result.can_commit;
            } catch (error) { status.textContent = error.message; }
            finally { busy = false; submit.disabled = false; updateSelection(); }
            return;
        }
        if (busy) { updateSelection(); return; }
        if (event.target.id === 'participant-select-all') {
            choices.clear();
            if (event.target.checked) eligible.forEach((rowStatus, index) => { if (rowStatus !== 'unchanged') choices.add(index); });
        } else if (event.target.matches('.participant-import-row:not(:disabled)')) {
            const index = Number(event.target.value);
            if (event.target.checked) choices.add(index); else choices.delete(index);
        }
        updateSelection();
    });
    form.addEventListener('change', () => {
        if (!busy) { reset(); if (document.getElementById('participant-import-file').files.length && form.reportValidity()) form.requestSubmit(); }
    });
    const message = async response => {
        const raw = await response.text();
        let body;
        try { body = JSON.parse(raw); }
        catch {
            const error = new Error(response.redirected ? 'Sesi login mungkin berakhir. Muat ulang halaman dan login kembali.' : `Server mengembalikan respons selain JSON (HTTP ${response.status}). Periksa alamat aplikasi atau log server.`);
            error.status = response.status;
            throw error;
        }
        if (!response.ok) {
            const error = new Error(body.errors ? Object.values(body.errors).flat().join('\n') : (body.message || 'Permintaan gagal.'));
            error.status = response.status;
            throw error;
        }
        return body;
    };
    form.addEventListener('submit', async event => {
        event.preventDefault(); if (busy) return;
        reset(); busy = true; submit.disabled = true; status.textContent = 'Membaca file dan membandingkan data...';
        const upload = new FormData(form);
        form.querySelectorAll('input[type=file], input[type=number], select').forEach(input => input.disabled = true);
        controller = new AbortController();
        try {
            const result = await message(await fetch(importUrl('preview'), { method: 'POST', body: upload, signal: controller.signal, headers: { Accept: 'application/json' } }));
            result.selectable.forEach(row => {
                eligible.set(Number(row.index), row.status);
                if (row.status === 'new') choices.add(Number(row.index));
            });
            preview.innerHTML = result.html; pending = result; save.hidden = !result.can_commit;
            updateSelection();
            status.textContent = 'Perbandingan selesai. Peserta baru otomatis dicentang dan akan ditambahkan setelah klik Impor Data Pilihan. Centang data lama yang ingin diperbarui.';
        } catch (error) {
            if (error.name !== 'AbortError') {
                status.textContent = error instanceof TypeError ? 'Koneksi ke server terputus saat membaca file. Pastikan Apache/MySQL aktif, lalu klik Bandingkan Ulang. Tahap ini belum menyimpan data.' : error.message;
                status.scrollIntoView({ block: 'nearest' });
            }
        }
        finally { busy = false; submit.disabled = false; form.querySelectorAll('input[type=file], input[type=number], select').forEach(input => input.disabled = false); updateSelection(); }
    });
    preview.addEventListener('keydown', event => {
        if (event.target.id === 'participant-import-search' && event.key === 'Enter') {
            event.preventDefault();
            preview.querySelector('[data-import-page="1"]').click();
        }
    });
    preview.addEventListener('click', async event => {
        const button = event.target.closest('[data-import-page]');
        if (!button || button.disabled || busy || !pending) return;
        busy = true; submit.disabled = true; updateSelection();
        form.querySelectorAll('input[type=file], input[type=number], select').forEach(input => input.disabled = true);
        try {
            const url = new URL(importUrl('page'), window.location.href);
            url.searchParams.set('token', pending.token);
            url.searchParams.set('page', button.dataset.importPage);
            url.searchParams.set('search', preview.querySelector('#participant-import-search').value.trim());
            const result = await message(await fetch(url, { headers: { Accept: 'application/json' } }));
            preview.innerHTML = result.html;
        } catch (error) { status.textContent = error.message; }
        finally {
            busy = false; submit.disabled = false;
            form.querySelectorAll('input[type=file], input[type=number], select').forEach(input => input.disabled = false);
            updateSelection();
        }
    });
    save.addEventListener('click', async () => {
        if (busy || !pending) return;
        const picked = selected();
        if (!picked.length) return;
        if (Array.from(preview.querySelectorAll('[data-import-field]')).some(input => choices.has(Number(input.dataset.importRow)) && !input.reportValidity())) return;
        const changed = picked.filter(row => row.dataset.status === 'changed').length;
        busy = true; save.disabled = submit.disabled = true;
        const confirmation = await Swal.fire({
            icon: 'question', title: 'Impor data pilihan?',
            text: `${picked.length} peserta dipilih. ${changed} data lama akan diperbarui. Data yang tidak dicentang dan foto/dokumen lama tetap dipertahankan.`,
            showCancelButton: true, confirmButtonText: 'Ya, impor sekarang', cancelButtonText: 'Batal'
        });
        if (!confirmation.isConfirmed) { busy = false; submit.disabled = false; updateSelection(); return; }
        save.textContent = 'Sedang mengimpor...';
        status.textContent = 'Sedang menyimpan data pilihan. Tunggu sampai konfirmasi selesai.';
        form.querySelectorAll('input[type=file], input[type=number], select').forEach(input => input.disabled = true);
        try {
            const body = JSON.stringify({ token: pending.token, confirmed: 1, selected: picked.map(row => row.value), additions: Object.fromEntries(Array.from(additions).filter(([index]) => choices.has(index))) });
            const result = await message(await fetch(importUrl('commit'), { method: 'POST', body, headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value } }));
            reset(); status.textContent = result.message;
            const summary = document.createElement('details'); summary.open = true;
            const title = document.createElement('summary'); title.className = 'fw-bold'; title.textContent = 'Rincian hasil impor'; summary.append(title);
            const wrapper = document.createElement('div'); wrapper.className = 'table-responsive mt-3'; wrapper.style.maxHeight = '45vh';
            const report = document.createElement('table'); report.className = 'table table-striped';
            const head = report.createTHead().insertRow();
            for (const label of ['Peserta', 'Hasil profil', 'Keterangan']) { const cell = document.createElement('th'); cell.textContent = label; head.append(cell); }
            const tbody = report.createTBody();
            for (const detail of result.details || []) { const row = tbody.insertRow(); for (const value of [detail.name, detail.status, detail.iuran]) row.insertCell().textContent = value; }
            wrapper.append(report); summary.append(wrapper); preview.append(summary);
            if (typeof onInitTable === 'function') onInitTable();
            if (typeof count === 'function') count();
            await Swal.fire({ icon: 'success', title: 'Impor berhasil', text: result.message, confirmButtonText: 'Selesai' });
        } catch (error) {
            status.textContent = error.message;
            if (error.status === 409) { save.hidden = true; pending = null; }
            await Swal.fire({ icon: 'error', title: 'Impor belum berhasil', text: error.message, confirmButtonText: 'Kembali ke preview' });
        }
        finally { busy = false; submit.disabled = false; form.querySelectorAll('input[type=file], input[type=number], select').forEach(input => input.disabled = false); updateSelection(); }
    });
    document.getElementById('kepesertaan-import-modal').addEventListener('hide.bs.modal', event => {
        if (busy) { event.preventDefault(); return; }
        controller?.abort(); reset(); status.textContent = ''; form.reset();
    });
})();
</script>
