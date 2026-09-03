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
    .badge-s3 { background: #dcfce7; color: #166534; }
    .badge-s2 { background: #dbeafe; color: #1e40af; }
    .badge-lain { background: #f3f4f6; color: #374151; }
    .badge-punya { background: #dcfce7; color: #166534; }
    .badge-belum { background: #fee2e2; color: #991b1b; }
    .footer { margin-top: 12px; font-size: 8px; color: #999; text-align: right; }
    .total-row { background: #fff8e8 !important; font-weight: bold; }
</style>
</head>
<body>
<div class="header">
    <h1>DATA DOSEN IKIP SILIWANGI</h1>
    <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} &nbsp;|&nbsp; Total: {{ $rows->count() }} dosen</p>
</div>
<table>
    <thead>
        <tr>
            <th style="width:20px">No</th>
            <th style="width:70px">NIDN/NIK</th>
            <th style="width:150px">Nama</th>
            <th style="width:80px">TTL</th>
            <th style="width:28px">Pend.</th>
            <th>Homebase Prodi</th>
            <th style="width:140px">Alamat</th>
            <th style="width:70px">NIK KTP</th>
            <th style="width:45px">Rek. BRI</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $i => $d)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $d->nidn_nik ?? '-' }}</td>
            <td><strong>{{ $d->nama }}</strong></td>
            <td>{{ $d->tempat_lahir }}{{ $d->tanggal_lahir ? ', '.$d->tanggal_lahir : '' }}</td>
            <td>
                <span class="badge {{ $d->pendidikan === 'S3' ? 'badge-s3' : ($d->pendidikan === 'S2' ? 'badge-s2' : 'badge-lain') }}">
                    {{ $d->pendidikan ?? '-' }}
                </span>
            </td>
            <td>{{ $d->homebase_prodi ?? '-' }}</td>
            <td>{{ $d->alamat ?? '-' }}</td>
            <td>{{ $d->nik_ktp ?? '-' }}</td>
            <td>
                <span class="badge {{ $d->kepemilikan_rek_bri === 'Sudah Punya' ? 'badge-punya' : 'badge-belum' }}">
                    {{ $d->kepemilikan_rek_bri ?? '-' }}
                </span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">IKIP Siliwangi &mdash; Sistem Informasi Kepegawaian</div>
</body>
</html>
