@auth
    @php($workHour = now('Asia/Jakarta')->hour)
    @if($workHour < 8 || $workHour >= 16)
        <style>
            #working-hours-dialog {width:min(760px,calc(100% - 32px));max-height:90vh;overflow:auto;border:0;border-radius:18px;padding:48px 32px;text-align:center;background:var(--kt-body-bg,#fff);color:var(--kt-gray-900,#182230);box-shadow:0 24px 80px #0004}
            #working-hours-dialog::backdrop {background:rgba(15,23,42,.7)}
            #working-hours-title {font-size:clamp(24px,4vw,36px);font-weight:800;line-height:1.35;margin:20px 0}
            #working-hours-dialog .hours-actions {display:flex;flex-wrap:wrap;justify-content:center;gap:16px;margin-top:28px}
            #working-hours-dialog .hours-actions .btn {min-width:160px}
        </style>
        <dialog id="working-hours-dialog" aria-labelledby="working-hours-title" aria-describedby="working-hours-description">
            <div aria-hidden="true" style="font-size:48px;color:#e59b19">&#9888;</div>
            <h2 id="working-hours-title">ANDA TELAH MELEBIHI BATAS JAM KERJA</h2>
            <p id="working-hours-description">Jam kerja pukul 08.00–16.00 WIB. Apakah Anda ingin melanjutkan?</p>
            <div class="hours-actions">
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-light-danger">Tidak, Logout</button></form>
                <button type="button" id="working-hours-continue" class="btn btn-primary" autofocus>Lanjut</button>
            </div>
        </dialog>
        <script>
            (() => {
                const dialog = document.getElementById('working-hours-dialog');
                dialog.addEventListener('cancel', event => event.preventDefault());
                document.getElementById('working-hours-continue').addEventListener('click', () => dialog.close());
                dialog.showModal();
                window.addEventListener('pageshow', event => { if (event.persisted && !dialog.open) dialog.showModal(); });
            })();
        </script>
    @endif
@endauth
