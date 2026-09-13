@extends('app.dts.layouts.app')
@section('title', 'Office Management')
@section('content')
    <div class="content-header">
        <h2>DTS System Registered Nodes (Offices)</h2>
        <button class="btn btn-primary"><i class="fa-solid fa-circle-plus"></i> Register Structural Office</button>
    </div>
    <form class="data-card">
        <div class="data-card-header">
            <h4>Add New Workspace Track Group</h4>
        </div>
        <div class="form-grid">
            <div class="form-group"><label>Office Workspace Title</label><input type="text" class="form-control"
                    placeholder="e.g., Supply Section"></div>
            <div class="form-group"><label>Workspace Category Layer</label><select class="form-control">
                    <option>Division Office Department</option>
                    <option>Elementary School Node</option>
                    <option>High School Node</option>
                </select></div>
        </div>
        <div style="padding: 0 1.5rem 1.5rem; text-align: right;"><button type="button" class="btn btn-primary">Save Office
                Unit Configuration</button></div>
    </form>
@endsection
