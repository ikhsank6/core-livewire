@extends('layouts.website')

@section('title', 'Jadwal uji kompetensi | ' . ($aboutUs->company_name ?? config('app.name')))
@section('description', 'Jadwal uji kompetensi, lengkap dengan tempat uji dan status pendaftaran.')

@section('content')

@include('website.partials.page-header', [
    'title'      => 'Jadwal uji kompetensi',
    'breadcrumb' => 'Jadwal',
    'subtitle'   => 'Jadwal ditetapkan LSP. Tempat uji (TUK) tertera di setiap baris.',
])

@include('website.partials.jadwal-list', ['jadwal' => $jadwal, 'showHeading' => false])

@endsection
