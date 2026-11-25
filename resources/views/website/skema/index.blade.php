@extends('layouts.website')

@section('title', 'Skema sertifikasi | ' . ($aboutUs->company_name ?? config('app.name')))
@section('description', 'Daftar skema sertifikasi kompetensi, dengan pencarian dan filter bidang.')

@section('content')

@include('website.partials.page-header', [
    'title'      => 'Skema sertifikasi',
    'breadcrumb' => 'Skema',
    'subtitle'   => 'Setiap skema memuat unit kompetensi yang diuji. Anda hanya diuji pada skema yang Anda pilih.',
])

@include('website.partials.skema-list', ['skema' => $skema, 'showHeading' => false])

@endsection
