@extends('layouts.admin')

@section('content')
    <h4 class="fw-bold mb-4">Jadwal Latihan</h4>

    @foreach ($jadwal as $tanggal => $data)
        <div class="card mb-3">

            <div class="card-header fw-bold">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            </div>

            <div class="card-body">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Jam Latihan</th>
                            <th>Member</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d['jam'] }}</td>
                                <td>{{ $d['nama'] }}</td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    @endforeach
@endsection
