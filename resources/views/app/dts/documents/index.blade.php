@extends('app.dts.layouts.app')
@section('title', 'All Master Documents')
@section('content')
    <div class="content-header">
        <h2>Global Document Master Registry</h2>
        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create New Tracking File</button>
    </div>
    <div class="data-card">
        <div class="data-card-header">
            <h4>All Active & Historic System Filings</h4>
        </div>
        <div class="table-responsive">
            <table class="dts-table">
                <thead>
                    <tr>
                        <th>Tracking ID</th>
                        <th>Title Block</th>
                        <th>Classification</th>
                        <th>Current Desk Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DTS-2026-1002</strong></td>
                        <td>Resourcing Memo - IT Infrastructure</td>
                        <td>Procurement Order</td>
                        <td><span class="badge bg-light-blue">In-Transit</span></td>
                    </tr>
                    <tr>
                        <td><strong>DTS-2026-0941</strong></td>
                        <td>DepEd Memo Ref 041 - Mid-Year Assessment</td>
                        <td>General Advisory</td>
                        <td><span class="badge bg-light-green">Filed/Complete</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
