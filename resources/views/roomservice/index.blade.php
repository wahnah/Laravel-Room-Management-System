@extends('template.master')
@section('title', 'Dashboard')
@section('content')

    <div id="dashboard">
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border">
                            <div class="card-header">
                                <div class="row ">
                                    <div class="col-lg-12 d-flex justify-content-between">
                                        <h3>Room Service</h3>

                                    </div>

                                </div>
                                <form id="form-save-room" class="row g-3" method="POST" action="{{ route('room.service', ['room' => $room->id, 'customer' => $customer->id]) }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="col-md-12">
                                        <label for="room_status_id" class="form-label">Room Status</label>
                                        <select id="room_status_id" name="room_status_id" class="form-control select2">
                                            <option value="1">Clean Room</option>
                                            <option value="2">Other Room Issue</option>
                                        </select>
                                        <div id="error_room_status_id" class="text-danger error"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="other_issue_container" style="display: none;">
                                            <label for="other_issue" class="form-label">Specify Other Issue</label>
                                            <input type="text" id="other_issue" name="other_issue" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>

                                <script>
                                    document.getElementById('room_status_id').addEventListener('change', function() {
                                        var otherIssueContainer = document.getElementById('other_issue_container');
                                        if (this.value == '2') {
                                            otherIssueContainer.style.display = 'block';
                                        } else {
                                            otherIssueContainer.style.display = 'none';
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Order Food</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <a class="btn btn-light btn-sm rounded shadow-sm border p-1 m-0"
                                                    href="{{ route('order.menu', ['room' => $room->id, 'customer' => $customer->id]) }}">
                                                        See Todays Menu
                                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection
