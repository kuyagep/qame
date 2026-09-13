@extends('app.dts.layouts.app')
@section('title', 'User Accounts Configuration')
@section('content')
    <div class="content-header">
        <h2>Personnel Accounts & Security Authorization Levels</h2>
        <button class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Add User</button>
    </div>
    <div class="data-card">
        <div class="data-card-header">
            <h4>DTS Active System Accounts</h4>
        </div>
        <div class="table-responsive">
            <table class="dts-table">
                <thead>
                    <tr>
                        <th>Account Full Name</th>
                        <th>System Email</th>
                        <th>Assigned Desk Location</th>
                        <th>Permission Role</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Admin Master Account</td>
                        <td>admin@sdo.deped.gov.ph</td>
                        <td>ICT Operations Core</td>
                        <td>System Administrator</td>
                    </tr>
                    <tr>
                        <td>Clara Oswald</td>
                        <td>clara.oswald@deped.gov.ph</td>
                        <td>Records Section Desk</td>
                        <td>Document Dispatch Clerk</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
