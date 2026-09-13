@extends('app.dts.layouts.app')
@section('title', 'Deferred Actions')
@section('content')
    <div class="content-header">
        <h2>Deferred & Delayed Requests Desk</h2>
    </div>
    <div class="data-card">
        <div class="data-card-header">
            <h4>Documents Held / Requiring Additional Documentation</h4>
        </div>
        <div class="table-responsive">
            <table class="dts-table">
                <thead>
                    <tr>
                        <th>ID Code</th>
                        <th>Subject Title</th>
                        <th>Holding Office Workspace</th>
                        <th>Reason Flag</th>
                        <th>Last Update Lock</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DTS-2026-4409</strong></td>
                        <td>Travel Claim Reimbursement - FY 2026</td>
                        <td>Budget Section</td>
                        <td><span class="badge bg-light-red">Missing Signatures</span></td>
                        <td>Jul 18, 2026</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
