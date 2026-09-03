<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1a1a1a; }
    .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #996600; padding-bottom: 10px; }
    .header h1 { font-size: 14px; font-weight: bold; color: #996600; }
    .header p { font-size: 9px; color: #666; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background-color: #996600; color: #fff; }
    thead th { padding: 5px 6px; text-align: left; font-size: 8px; font-weight: bold; }
    tbody tr:nth-child(even) { background-color: #fdf8ee; }
    tbody tr:nth-child(odd) { background-color: #ffffff; }
    tbody td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
    .badge { display: inline-block; padding: 1px 5px; border-radius: 9999px; font-size: 7px; font-weight: bold; }
    .badge-punya { background: #dcfce7; color: #166534; }
    .badge-belum { background: #fee2e2; color: #991b1b; }
    .footer { margin-top: 12px; font-size: 8px; color: #999; text-align: right; }
</style>
</head>
<body>
<div class="header">
    <h1>DATA PEGAWAI STRUKTURAL IKIP SILIWANGI</h1>
    <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} &nbsp;|&nbsp; Total: {{ $rows->count() }} pegawai</p>
</div>
<table>
    <thead>
        <tr>
            <th style="width:20px">No</th>
            <th style="width:60px">NIK</th>
            <th style="width:130px">Nama</th>
            <th style="width:80px">TTL</th>
            <th style="width:35px">Pend.</th>
            <th>Jabatan</th>
            <th style="width:150px">Alamat</th>
            <th style="width:70px">NIK KTP</th>
            <th style="width:45px">Rek. BRI</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $i => $p)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $p->nomor_induk_kepegawaian ?? '-' }}</td>
            <td><strong>{{ $p->nama }}</strong></td>
            <td>{{ $p->tempat_lahir }}{{ $p->tanggal_lahir ? ', '.$p->tanggal_lahir : '' }}</td>
            <td>{{ $p->pendidikan ?? '-' }}</td>
            <td>{{ $p->jabatan ?? '-' }}</td>
            <td>{{ $p->alamat ?? '-' }}</td>
            <td>{{ $p->nik_ktp ?? '-' }}</td>
            <td>
                <span class="badge {{ $p->kepemilikan_rek_bri === 'Sudah Punya' ? 'badge-punya' : 'badge-belum' }}">
                    {{ $p->kepemilikan_rek_bri ?? '-' }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">IKIP Siliwangi &mdash; Sistem Informasi Kepegawaian</div>
</body>
</html>
