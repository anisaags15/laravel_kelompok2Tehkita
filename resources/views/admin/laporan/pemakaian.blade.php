<h2>LAPORAN PEMAKAIAN</h2>
<table>
<thead>
<tr>
<th>No</th><th>Outlet</th><th>Bahan</th><th>Jumlah</th><th>Tanggal</th>
</tr>
</thead>
<tbody>
@foreach($data as $key=>$d)
<tr>
<td>{{ $key+1 }}</td>
<td>{{ $d->outlet->nama_outlet ?? '-' }}</td>
<td>{{ $d->bahan->nama_bahan ?? '-' }}</td>
<td>{{ $d->jumlah }}</td>
<td>{{ $d->created_at->format('d/m/Y') }}</td>
</tr>
@endforeach
</tbody>
</table>