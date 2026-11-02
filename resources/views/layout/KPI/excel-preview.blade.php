<!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Preview Excel KPI</title>
<style>body{font-family:Arial,sans-serif;margin:16px;color:#263238;font-size:13px}table{border-collapse:collapse;width:100%}td,th{border:1px solid #ccd4dc;padding:10px;vertical-align:top;white-space:pre-wrap;overflow-wrap:anywhere;min-width:120px}th{background:#edf3f7;text-align:left}.scroll{overflow:auto}p{color:#576574}</style></head><body>
<h2>Preview Excel KPI</h2><p>Pratinjau isi laporan. File Excel berisi data berikut.</p>
@foreach(array_slice($rows, 0, 5) as $row)<p>@foreach($row as $value){{ $value }} @if(!$loop->last) · @endif @endforeach</p>@endforeach
<div class="scroll"><table><thead><tr>@foreach($rows[5] as $heading)<th>{{ $heading }}</th>@endforeach</tr></thead><tbody>
@forelse(array_slice($rows, 6) as $row)<tr>@foreach($row as $value)<td>{{ $value }}</td>@endforeach</tr>@empty<tr><td colspan="8">Tidak ada tugas dalam cakupan laporan ini.</td></tr>@endforelse
</tbody></table></div></body></html>
