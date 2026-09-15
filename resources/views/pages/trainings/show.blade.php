@extends('layouts.main') {{-- Adjust to match your master layout --}}

@section('title', 'Training Portfolio - ' . $training->title)
@section('content-header', 'Training Portfolio')
@section('content')

    <div class="container-fluid">

        <!-- Header Info Card -->
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">{{ $training->title }}</h3>
                <div class="card-tools">
                    @if ($training->status === 'ongoing')
                        <span class="badge badge-warning px-3 py-2">Ongoing</span>
                    @elseif($training->status === 'completed')
                        <span class="badge badge-success px-3 py-2">Completed</span>
                    @else
                        <span class="badge badge-secondary px-3 py-2">{{ ucfirst($training->status ?? 'Upcoming') }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-1 text-muted"><i
                                class="fas fa-calendar-alt mr-2 text-primary"></i><strong>Date:</strong></p>
                        <p class="h6 font-weight-bold">{{ $training->date ?? 'N/A' }}
                            {{ $training->end_date ? ' to ' . $training->end_date : '' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted"><i
                                class="fas fa-map-marker-alt mr-2 text-danger"></i><strong>Venue:</strong></p>
                        <p class="h6 font-weight-bold">{{ $training->venue ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 text-muted"><i class="fas fa-users mr-2 text-info"></i><strong>Total
                                Enrolled:</strong></p>
                        <p class="h6 font-weight-bold">{{ $training->participants->count() }} Participant(s)</p>
                    </div>
                </div>
                @if ($training->description)
                    <hr>
                    <p class="mb-1 text-muted"><strong>Description:</strong></p>
                    <p class="text-secondary mb-0">{{ $training->description }}</p>
                @endif
            </div>
            <div class="card-footer bg-white text-right">
                <a href="{{ route('trainings.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Trainings
                </a>
            </div>
        </div>

        <!-- Participants Table Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-light ">
                <h3 class="card-title font-weight-bold text-dark m-0">
                    <i class="fas fa-user-friends mr-2 text-primary"></i>Joined Participants List
                </h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" id="participantSearch" class="form-control float-right"
                            placeholder="Search participant...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap align-middle mb-0" id="participantsTable">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Office / Agency</th>
                            <th>Joined Date</th>
                            <th class="text-center" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($training->participants as $index => $participant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong class="text-dark">{{ $participant->name }}</strong>
                                </td>
                                <td>{{ $participant->email }}</td>
                                <td>{{ $participant->office->name ?? ($participant->agency ?? 'N/A') }}</td>
                                <td>
                                    {{ $participant->pivot->created_at ? $participant->pivot->created_at->format('M d, Y h:i A') : 'N/A' }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-xs btn-info btn-view-participant"
                                        data-name="{{ $participant->name }}" data-email="{{ $participant->email }}"
                                        data-office="{{ $participant->office->name ?? 'N/A' }}" title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block text-secondary"></i>
                                    No participants have joined this training yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white clearfix">
                <small class="text-muted float-left">Showing {{ $training->participants->count() }} entries</small>
            </div>
        </div>

    </div>

    <!-- Participant Details Modal -->
    <div class="modal fade" id="participantModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-user-circle mr-2"></i>Participant Profile</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-muted mb-0">Full Name</label>
                        <p class="h6 font-weight-bold" id="modalParticipantName">-</p>
                    </div>
                    <div class="form-group">
                        <label class="text-muted mb-0">Email Address</label>
                        <p class="h6 font-weight-bold" id="modalParticipantEmail">-</p>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-muted mb-0">Office / Organization</label>
                        <p class="h6 font-weight-bold" id="modalParticipantOffice">-</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Live Search Filter for Participants
            $("#participantSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#participantsTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // View Modal Trigger
            $('.btn-view-participant').on('click', function() {
                $('#modalParticipantName').text($(this).data('name'));
                $('#modalParticipantEmail').text($(this).data('email'));
                $('#modalParticipantOffice').text($(this).data('office'));
                $('#participantModal').modal('show');
            });
        });
    </script>
@endpush
