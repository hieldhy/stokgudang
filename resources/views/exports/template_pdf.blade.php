<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $fileName ?? 'Exported Document' }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; line-height: 1.4; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 15px; }
        h1 { text-align: center; font-size: 16px; margin-bottom: 20px; color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; font-weight: bold; color: #555; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { text-align: center; font-size: 9px; color: #777; margin-top: 30px; }
        .page-break { page-break-after: always; }
        .whitespace-nowrap { white-space: nowrap; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-semibold { font-weight: 600; }
        .text-green-600 { color: #16a34a; }
        .text-red-600 { color: #dc2626; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title ?? 'Laporan Data' }}</h1>

        @if(isset($data) && $data instanceof \Illuminate\Support\Collection && !$data->isEmpty())
            <table>
                <thead>
                    <tr>
                        @if(!empty($headers) && count($headers) > 0)
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        @else
                            @php $firstRow = $data->first(); @endphp
                            @if($firstRow && is_array($firstRow))
                                @foreach(array_keys((array)$firstRow) as $key)
                                    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                @endforeach
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                           @if(is_array($row) || is_object($row))
                                @foreach((array)$row as $key => $value)
                                    <td 
                                        class="
                                        @if(is_numeric($value) && !in_array(strtolower($key), ['no', 'nomor', 'index', 'sn', 'serialnumber', 'serial number'])) text-right @endif
                                        @if(strtolower($key) === 'volume' && is_string($value) && strpos($value, '+') === 0) text-green-600 font-semibold @endif
                                        @if(strtolower($key) === 'volume' && is_string($value) && strpos($value, '-') === 0) text-red-600 font-semibold @endif
                                        @if(in_array(strtolower($key), ['tanggal', 'tanggal masuk', 'tanggal keluar', 'created_at', 'updated_at'])) whitespace-nowrap @endif
                                        @if(in_array(strtolower($key), ['no', 'nomor', 'index'])) text-center @endif
                                        "
                                    >
                                        {{ $value }}
                                    </td>
                                @endforeach
                            @else
                                <td colspan="{{ !empty($headers) ? count($headers) : ( (isset($firstRow) && is_array($firstRow)) ? count((array)$firstRow) : 1) }}">
                                    Invalid data format for this row.
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align:center;">Tidak ada data untuk ditampilkan.</p>
        @endif

        <div class="footer">
            Dicetak pada: {{ date('d M Y, H:i:s') }} <br>
            Dokumen ini dihasilkan secara otomatis oleh sistem.
        </div>
    </div>
</body>
</html>