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
                    <h2>Job Details</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $job->title }}</h5>
                            <p class="card-text">{{ $job->description }}</p>
                            <p><strong>Client:</strong> {{ $job->client->name }}</p>
                            <p><strong>Specialization:</strong> {{ $job->specialization->name }}</p>
                            <p><strong>Expected Budget:</strong> {{ $job->expected_budget }}</p>
                            <p><strong>Expected Duration:</strong> {{ $job->expected_duration }}</p>
                            <p><strong>Location:</strong> {{ $job->address }}</p>
                            <p><strong>Type:</strong> {{ $job->type }}</p>
                            <p><strong>Status:</strong> {{ $job->status }}</p>
                            <p class="card-text"><strong>Created At:</strong>
                                {{ Carbon\Carbon::parse($job->created_at)->diffForHumans() }}</p>

                        </div>
                    </div>

                    <h3>Applications</h3>
                    @forelse ($job->applications as $application)
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $application->freelancer->name }}</h5>
                                <p class="card-text">{{ $application->cover_letter }}</p>
                                <p><strong>Bid:</strong> {{ $application->bid }}</p>
                                <p><strong>Duration:</strong> {{ $application->duration }}</p>
                                <p><strong>Status:</strong> {{ $application->status }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text text-center">No Applications</p>
                            </div>
                        </div>
                    @endforelse

                    <h3>Request Changes</h3>
                    @forelse ($job->requestChanges as $requestChange)
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $requestChange->type }}</h5>
                                <p class="card-text"><strong>New Bid:</strong> {{ $requestChange->new_bid }}</p>
                                <p class="card-text"><strong>New Duration:</strong> {{ $requestChange->new_duration }}</p>
                                <p class="card-text"><strong>Response:</strong> {{ $requestChange->response }}</p>
                                <p class="card-text"><strong>Status:</strong> {{ $requestChange->status }}</p>
                                <p class="card-text"><strong>Response At:</strong>
                                    {{ Carbon\Carbon::parse($requestChange->response_at)->diffForHumans() }}</p>

                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text text-center">No Request Changes</p>
                            </div>
                        </div>
                    @endforelse

                    {{-- rates --}}
                    <h3>Rates</h3>
                    @forelse ($job->rates as $rate)
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ $rate->ratingBy->name }} قيم {{ $rate->ratedBy->name }}
                                </h5>
                                @foreach ($rate->rates as $value)
                                    <p class="card-text"><strong>{{ $value['name'] }}:</strong> {{ $value['value'] }}</p>
                                @endforeach
                                <p class="card-text">{{ $rate->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text text-center">No Rates</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection

@section('scripts')
@endsection
