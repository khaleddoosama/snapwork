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
                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('User Details') }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h4 class="card-header">{{ __('Name') }}: {{ $user->name }}</h4>
                                <h4 class="card-header">{{ __('Username') }}: {{ $user->username ?? 'N/A' }}</h4>
                                <h4 class="card-header">{{ __('Email') }}: {{ $user->email }}</h4>
                                <h4 class="card-header">{{ __('Phone') }}: {{ $user->phone ?? 'N/A' }}</h4>
                                {{-- <h4 class="card-header">{{ __('Country') }}: {{ $user->country ?? 'N/A' }}</h4> --}}
                                {{-- <h4 class="card-header">{{ __('Address') }}: {{ $user->address ?? 'N/A' }}</h4> --}}
                                <h4 class="card-header">{{ __('Gender') }}: {{ $user->gender ?? 'N/A' }}</h4>
                                <h4 class="card-header">{{ __('Date of Birth') }}: {{ $user->dob ?? 'N/A' }}</h4>
                                {{-- <h4 class="card-header">{{ __('Postal Code') }}: {{ $user->postalcode ?? 'N/A' }}</h4> --}}
                                {{-- <h4 class="card-header">{{ __('City') }}: {{ $user->city ?? 'N/A' }}</h4> --}}
                                <h4 class="card-header">{{ __('Job Title') }}: {{ $user->job_title ?? 'N/A' }}</h4>
                                <h4 class="card-header">{{ __('Role') }}: {{ ucfirst($user->role) }}</h4>
                                <h4 class="card-header">{{ __('Balance') }}: ${{ $user->balance }}</h4>
                                <h4 class="card-header">{{ __('attributes.status') }}:
                                    @switch($user->status)
                                        @case(0)
                                            Pending
                                        @break

                                        @case(1)
                                            Approved
                                        @break

                                        @case(2)
                                            Rejected
                                        @break

                                        @case(3)
                                            Removed
                                        @break
                                    @endswitch
                                </h4>
                                <h4 class="card-header">{{ __('Last Login') }}: {{ $user->last_login ?? 'N/A' }}</h4>
                                {{-- <h4 class="card-header">{{ __('Bio') }}: {{ $user->bio ?? 'N/A' }}</h4> --}}

                                <h4 class="card-header">{{ __('Specialization') }}:
                                    {{ $user->specialization->name ?? 'N/A' }}</h4>

                                <h4 class="card-header">{{ __('Skills') }}</h4>
                                <ul>
                                    @foreach ($user->skills as $skill)
                                        <li class="badge badge-primary m-3">{{ $skill->name }}</li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Languages') }}</h4>
                                <ul>
                                    @foreach ($user->languages as $language)
                                        <li class="badge badge-secondary m-3">{{ $language->name }} -
                                            {{ $language->level }}
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- <h4 class="card-header">{{ __('Projects') }}</h4>
                                <ul>
                                    @foreach ($user->projects as $project)
                                        <li>{{ $project->name }} - {{ $project->description }}</li>
                                    @endforeach
                                </ul> --}}

                                <h4 class="card-header">{{ __('Educations') }}</h4>
                                <ul>
                                    @foreach ($user->educations as $education)
                                        <li class="card card-body">{{ $education->degree }} in {{ $education->major }}
                                            from {{ $education->school }} ({{ $education->start_date }} -
                                            {{ $education->end_date ?? 'Present' }})
                                            <br>
                                            {{ $education->description }}
                                        </li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Employments') }}</h4>
                                <ul>
                                    @foreach ($user->employments as $employment)
                                        <li class="card card-body">{{ $employment->position }} at
                                            {{ $employment->company }} in {{ $employment->city }} -
                                            {{ $employment->country }}
                                            ({{ $employment->start_date }} - {{ $employment->end_date ?? 'Present' }})
                                            <br>
                                            {{ $employment->description }}
                                        </li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Certifications') }}</h4>
                                <ul>
                                    @foreach ($user->certifications as $certification)
                                        <li class="card card-body"><a href="{{ $certification->url }}" target="_blank">
                                                {{ $certification->name }} from {{ $certification->issuer }}
                                                ({{ $certification->issue_date }})
                                                <br>
                                                {{ $certification->description }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Jobs') }}</h4>
                                <ul>
                                    @foreach ($user->jobs as $job)
                                        <li class="card card-body"><a href="{{ route('admin.jobs.show', $job->id) }}"
                                                target="_blank">
                                                {{ $job->title }}
                                            </a></li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Invitations') }}</h4>
                                <ul>
                                    @foreach ($user->invitations as $invitation)
                                        <li class="card card-body">{{ $invitation->job->client->name }} invited
                                            {{ $invitation->freelancer->name }} to
                                            {{ $invitation->job->title }} at
                                            {{ $invitation->created_at?->diffForHumans() }}
                                            ({{ $invitation->status ?? 'pending' }})
                                        </li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Applications') }}</h4>
                                <ul>
                                    @foreach ($user->applications as $application)
                                        <li class="card card-body">
                                            <a href="{{ route('admin.applications.show', $application->id) }}">
                                                {{ $application->job->title }}
                                                with price {{ $application->bid }}$ for {{ $application->duration }} days
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Bookmarks') }}</h4>
                                <ul>
                                    @foreach ($user->bookmarks as $bookmark)
                                        <li class="card card-body">{{ $bookmark->job->title }}</li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Message') }}</h4>
                                <ul>
                                    @foreach ($user->messages as $message)
                                        <li class="card card-body">
                                            {{ $message->sender->name }} sent a message to {{ $message->receiver->name }}
                                            at {{ $message->sent_at?->diffForHumans() }} ({{ $message->status }})
                                            <br>
                                            {{ $message->content }}
                                        </li>
                                    @endforeach
                                </ul>

                                <h4 class="card-header">{{ __('Rates') }}</h4>
                                <ul>
                                    @foreach ($user->rates as $rate)
                                        <li class="card card-body">
                                            <a href="{{ route('admin.rates.show', $rate->id) }}">
                                                {{ $rate->ratingBy->name }} rated {{ $rate->ratedBy->name }}
                                                with a value of <strong
                                                    style="display: contents">{{ $rate->average_value }}</strong>
                                                from {{ $rate->created_at?->diffForHumans() }} <br>
                                                {{ $rate->comment }}
                                            </a>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
