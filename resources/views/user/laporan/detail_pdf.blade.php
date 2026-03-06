<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body{
font-family: DejaVu Sans, sans-serif;
font-size:12px;
color:#1f2937;
}

.container{
width:100%;
}

/* HEADER */

.header{
text-align:center;
margin-bottom:10px;
}

.logo{
font-size:28px;
font-weight:bold;
color:#16a34a;
letter-spacing:4px;
}

.subtitle{
font-size:11px;
color:#6b7280;
}

.line{
border-top:2px solid #16a34a;
margin-top:8px;
}

/* BOX TITLE */

.title-box{
background:#e7f5ec;
border-radius:6px;
text-align:center;
padding:10px;
margin-top:15px;
font-weight:bold;
color:#15803d;
letter-spacing:1px;
}

/* INFO */

.info{
width:100%;
margin-top:15px;
}

.info td{
padding:4px 0;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

th{
background:#16a34a;
color:white;
padding:8px;
font-size:12px;
}

td{
border:1px solid #e5e7eb;
padding:8px;
}

td.center{
text-align:center;
}

.qty{
color:#16a34a;
font-weight:bold;
}

/* SUMMARY */

.summary{
margin-top:20px;
border:1px solid #e5e7eb;
padding:10px;
width:260px;
background:#fafafa;
}

/* SIGN */

.sign{
margin-top:20px;
text-align:right;
}

.sign-space{
height:40px;
}

/* FOOTER */

.footer{
margin-top:20px;
font-size:10px;
color:#6b7280;
border-top:1px dashed #ccc;
padding-top:10px;
}

/* WATERMARK */

.watermark{
position:fixed;
top:40%;
left:25%;
font-size:80px;
color:#16a34a;
opacity:0.05;
transform:rotate(-30deg);
}

</style>

</head>

<body>

<div class="watermark">DOKUMEN ASLI</div>

<div class="container">

<div class="header">

<div class="logo">TEH KITA</div>

<div class="subtitle">
Sistem Manajemen Stok & Distribusi Outlet Terpadu<br>
Cirebon, Jawa Barat - Indonesia | www.tehkita.id
</div>

<div class="line"></div>

</div>


<div class="title-box">
LAPORAN DISTRIBUSI BAHAN BAKU
</div>


<table class="info">

<tr>
<td width="60%">
<b>Outlet</b> : {{ $distribusi->first()->outlet->nama_outlet }}
</td>

<td align="right">
<b>ID Laporan</b>: #399C329F
</td>
</tr>

<tr>
<td>
<b>Periode</b> : Awal s/d Sekarang
</td>

<td align="right">
<b>Dicetak</b>: {{ now()->translatedFormat('d F Y, H:i') }} WIB
</td>
</tr>

</table>


<table>

<thead>
<tr>
<th width="50">NO</th>
<th>WAKTU TERIMA</th>
<th>DETAIL BAHAN BAKU</th>
<th>KUANTITAS</th>
<th>SATUAN</th>
</tr>
</thead>

<tbody>

@foreach($distribusi as $d)

<tr>

<td class="center">{{ $loop->iteration }}</td>

<td class="center">
{{ \Carbon\Carbon::parse($d->created_at)->translatedFormat('d M Y') }}
</td>

<td>
{{ $d->bahan->nama_bahan }}
</td>

<td class="center qty">
+{{ number_format($d->jumlah) }}
</td>

<td class="center">
{{ $d->bahan->satuan ?? 'pcs' }}
</td>

</tr>

@endforeach

</tbody>

</table>


<div style="display:flex; justify-content:space-between;">

<div class="summary">

<b>Ringkasan Data:</b><br><br>

Total Item Masuk:
<b>{{ number_format($distribusi->sum('jumlah')) }} unit</b><br>

Frekuensi:
<b>{{ $distribusi->count() }} Transaksi</b>

</div>


<div class="sign">

Admin Outlet,<br>

<div class="sign-space"></div>

( )<br>

<small>NIP: 206032026</small>

</div>

</div>


<div class="footer">

* Dokumen ini sah dan dihasilkan secara otomatis oleh Sistem Manajemen Teh Kita pada  
{{ now()->format('d/m/Y H:i') }} WIB.<br>

* Segala bentuk perbedaan data fisik wajib dilaporkan maksimal 1x24 jam setelah barang diterima.

</div>

</div>

</body>
</html>