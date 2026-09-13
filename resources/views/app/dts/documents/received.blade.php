@extends('app.dts.layouts.app')
@section('title', 'Received Documents')
@section('content')
    <div class="content-header">
        <h2>Received Actions Ledger</h2>
        <button class="btn btn-primary"><i class="fa-solid fa-barcode"></i> Scan Incoming Barcode</button>
    </div>
    <div class="data-card">
        <div class="data-card-header">
            <h4>Documents Signed In & Awaiting Internal Processing</h4>
        </div>
        <div class="table-responsive">
            <table class="dts-table">
                <thead>
                    <tr>
                        <th>Barcode/ID</th>
                        <th>Title</th>
                        <th>Origin Desk</th>
                        <th>Handler Account</th>
                        <th>Timestamp Checked</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DTS-2026-8801</strong></td>
                        <td>Special Order No. 24 - Reassignment</td>
                        <td>Personnel Section</td>
                        <td>Maria Santos</td>
                        <td>Jul 19, 2026, 02:14 PM</td>
                    </tr>
                    <tr>
                        <td><strong>DTS-2026-8804</strong></td>
                        <td>Salary Grade Step Increment Masterlist</td>
                        <td>Accounting Office</td>
                        <td>Juan Dela Cruz</td>
                        <td>Jul 19, 2026, 04:30 PM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
