@extends('app.dts.layouts.app')
@section('title', 'Released Documents')
@section('content')
    <div class="content-header">
        <h2>Released Dispatch Outpost</h2>
    </div>
    <div class="data-card">
        <div class="data-card-header">
            <h4>Documents Dispatched to Next Target Station Vaults</h4>
        </div>
        <div class="table-responsive">
            <table class="dts-table">
                <thead>
                    <tr>
                        <th>Tracking Ref</th>
                        <th>Document Details</th>
                        <th>Dispatched To</th>
                        <th>Courier Note</th>
                        <th>Exit Handshake Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DTS-2026-7012</strong></td>
                        <td>School Maintenance Fund Allocations</td>
                        <td>Digos Central ES</td>
                        <td>Via Division Liaison Messenger</td>
                        <td>Jul 19, 2026, 11:15 AM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
