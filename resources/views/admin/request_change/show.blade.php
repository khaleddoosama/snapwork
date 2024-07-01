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
                    <h2>Change Request Details</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('admin.jobs.show', $request_change->job) }}"
                                    class="card-link h3">{{ $request_change->job->title }}</a></h5>
                            <p class="card-text">{{ $request_change->application->freelancer->name }}</p>
                            <p><strong>Type:</strong> {{ $request_change->type }}</p>
                            <p><strong>New Bid:</strong> {{ $request_change->new_bid }}</p>
                            <p><strong>New Duration:</strong> {{ $request_change->new_duration }}</p>
                            <p><strong>Status:</strong> {{ $request_change->status }}</p>
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
