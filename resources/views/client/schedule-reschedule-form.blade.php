<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajukan Penjadwalan Ulang</title>
  <style>
    body{font-family: ui-sans-serif,system-ui; margin:40px;}
    input,textarea,button,select{font-size:16px;padding:8px;border:1px solid #d1d5db;border-radius:10px;width:100%;}
    label{font-size:14px;color:#374151}
    .wrap{max-width:640px}
  </style>
</head>
<body>
  <div class="wrap">
    <h2>Ajukan Penjadwalan Ulang</h2>
    <p>Jadwal saat ini: <strong>{{ $schedule->scheduled_at->format('d M Y H:i') }}</strong></p>

    <form method="POST" action="{{ route('schedule.client.reschedule.submit', $schedule) }}">
      @csrf
      <div style="margin-top:12px">
        <label>Tanggal & Waktu yang Diusulkan</label>
        <input type="datetime-local" name="requested_date" required min="{{ now()->format('Y-m-d\TH:i') }}">
        @error('requested_date')<div style="color:#b91c1c">{{ $message }}</div>@enderror
      </div>

      <div style="margin-top:12px">
        <label>Catatan (opsional)</label>
        <textarea name="note" rows="3" placeholder="Misal: mohon dijadwalkan siang hari"></textarea>
        @error('note')<div style="color:#b91c1c">{{ $message }}</div>@enderror
      </div>

      <div style="margin-top:16px; display:flex; gap:8px">
        <button type="submit" style="background:#4f46e5;color:#fff;border:none">Kirim Permintaan</button>
      </div>
    </form>
  </div>
</body>
</html>
