<h2>LAPORAN STOK MASUK</h2>
<table>
<thead>
<tr>
<th>No</th><th>Bahan</th><th>Jumlah</th><th>Tanggal</th>
</tr>
</thead>
<tbody>
@foreach($data as $key=>$d)
<tr>
<td>{{ $key+1 }}</td>
<td>{{ $d->bahan->nama_bahan ?? '-' }}</td>
<td>{{ $d->jumlah }}</td>
<td>{{ $d->tanggal->format('d/m/Y') }}</td>
</tr>
@endforeach
</tbody>
</table>