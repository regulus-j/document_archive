@extends('layouts.app')

@section('content')
@php 
use chillerlan\QRCode\QRCode;

$qr = (new QRCode)->render("https://www.google.com");
@endphp

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>QR Code Generated</h1>
            <img src="{{ $qr }}" alt="QR Code">
            <img src="data:image/png;base64,{{ base64_encode($qr) }}" alt="QR Code">
    </div>
</div>

@endsection