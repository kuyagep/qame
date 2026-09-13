@extends('app.dts.layouts.app')
@section('title', 'System Audit Trails')
@section('content')
    <div class="content-header">
        <h2>System Security Log & Audit Records Ledger</h2>
    </div>
    <div class="data-card">
        <div class="data-card-header">
            <h4>Live Activity Ledger Monitoring Logs</h4>
        </div>
        <div class="table-responsive">
            <table class="dts-table">
                <thead>
                    <tr>
                        <th>Trace Token ID</th>
                        <th>Action Operation</th>
                        <th>Executing Context Entity</th>
                        <th>IP Address Target</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>LOG-992182</code></td>
                        <td>Document Transmittal Modified</td>
                        <td>Clara Oswald (Records)</td>
                        <td><code>192.168.4.110</code></td>
                        <td>Just Now</td>
                    </tr>
                    <tr>
                        <td><code>LOG-992167</code></td>
                        <td>Authorized Session Started</td>
                        <td>Admin Account</td>
                        <td><code>192.168.1.12</code></td>
                        <td>14 minutes ago</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
