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
                    <h2>Application Details</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('admin.jobs.show', $application->job) }}"
                                    class="card-link h3">{{ $application->job->title }}</a></h5>
                            <p class="card-text">{{ $application->freelancer->name }}</p>
                            <p><strong>Bid:</strong> {{ $application->bid }}</p>
                            <p><strong>Duration:</strong> {{ $application->duration }}</p>
                            <p><strong>Cover Letter:</strong> {{ $application->cover_letter }}</p>
                            <p><strong>Attachments:</strong>
                                @foreach ($application->attachments as $attachment)
                                    <a href="{{ asset($attachment) }}" target="_blank">{{ $attachment }}</a>,
                                @endforeach
                            </p>
                            <p><strong>Status:</strong> {{ $application->status }}</p>
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
