<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1a1a1a; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #996600; padding-bottom: 12px; }
    .header h1 { font-size: 15px; font-weight: bold; color: #996600; }
    .header p { font-size: 9px; color: #666; margin-top: 3px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background-color: #996600; color: #fff; }
    thead th { padding: 7px 10px; text-align: left; font-size: 9px; font-weight: bold; }
    thead th.num { text-align: center; }
    tbody tr:nth-child(even) { background-color: #fdf8ee; }
    tbody tr:nth-child(odd) { background-color: #ffffff; }
    tbody td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; }
    tbody td.num { text-align: center; font-weight: bold; }
    .tfoot-row { background-color: #fff3cd !important; font-weight: bold; font-size: 10px; border-top: 2px solid #996600; }
    .tfoot-row td { padding: 7px 10px; }
    .tfoot-row td.num { text-align: center; }
    .footer { margin-top: 14px; font-size: 8px; color: #999; text-align: right; }
</style>
</head>
<body>
<div class="header">
    <h1>REKAP DATA DOSEN PER PROGRAM STUDI</h1>
    <h1 style="font-size:11px; color:#555; margin-top:2px;">IKIP SILIWANGI</h1>
    <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th style="width:25px">No</th>
            <th>Program Studi</th>
            <th class="num" style="width:70px">Jml. Dosen</th>
            <th class="num" style="width:70px">Guru Besar</th>
            <th class="num" style="width:70px">Doktor (S3)</th>
            <th class="num" style="width:70px">Magister (S2)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $r->program_studi }}</td>
            <td class="num" style="color:#1d4ed8">{{ $r->jumlah_dosen }}</td>
            <td class="num" style="color:#7e22ce">{{ $r->jumlah_guru_besar }}</td>
            <td class="num" style="color:#15803d">{{ $r->jumlah_doktor }}</td>
            <td class="num" style="color:#b45309">{{ $r->jumlah_magister }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="tfoot-row">
            <td colspan="2">TOTAL</td>
            <td class="num" style="color:#1d4ed8">{{ $rows->sum('jumlah_dosen') }}</td>
            <td class="num" style="color:#7e22ce">{{ $rows->sum('jumlah_guru_besar') }}</td>
            <td class="num" style="color:#15803d">{{ $rows->sum('jumlah_doktor') }}</td>
            <td class="num" style="color:#b45309">{{ $rows->sum('jumlah_magister') }}</td>
        </tr>
    </tfoot>
</table>
<div class="footer">IKIP Siliwangi &mdash; Sistem Informasi Kepegawaian</div>
</body>
</html>
