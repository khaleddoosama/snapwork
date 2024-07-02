@extends('admin.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <x-custom.header-page title="{{ $title }}" />


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="container">
                    <h2>Rate Details</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('admin.jobs.show', $rate->job) }}"
                                    class="card-link h3">{{ $rate->job->title }}</a></h5>
                            <p class="card-text">{{ $rate->ratingBy->name }}</p>
                            <p><strong>Rated:</strong> {{ $rate->ratedBy->name }}</p>
                            <hr>
                            <strong class="border-bottom mb-2">Rates:</strong>
                            <div class="row mt-3">
                                @foreach ($rate->rates as $value)
                                    <p class="col-6"><strong>{{ $value['name'] }}:</strong> {{ $value['value'] }}</p>
                                @endforeach
                            </div>
                            <hr>
                            <p><strong>Average Value:</strong> {{ $rate->average_value }}</p>
                            <p><strong>Comment:</strong> {{ $rate->comment }}</p>
                        </div>
                    </div>


                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')
@endsection
