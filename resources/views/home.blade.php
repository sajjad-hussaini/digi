@extends('layouts.app')
@section('title','Dashboard')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap');

    body, .content-wrapper {
        background-color: #f4f6fb !important;
        font-family: 'DM Sans', sans-serif;
    }

    /* ===== Page Header ===== */
    .dash-header {
        padding: 28px 0 10px 0;
        border-bottom: 1px solid #e3e8f0;
        margin-bottom: 28px;
    }

    .dash-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 26px;
        font-weight: 600;
        color: #0C1628;
        margin: 0 0 4px 0;
    }

    .dash-header p {
        font-size: 13px;
        color: #8a94a6;
        margin: 0;
    }

    .dash-date {
        font-size: 13px;
        color: #8a94a6;
        text-align: right;
    }

    /* ===== Stat Cards ===== */
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px 22px;
        margin-bottom: 22px;
        border: 1px solid #e8ecf4;
        box-shadow: 0 2px 12px rgba(12,22,40,0.06);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: box-shadow 0.2s, transform 0.2s;
        text-decoration: none;
    }

    .stat-card:hover {
        box-shadow: 0 8px 28px rgba(12,22,40,0.12);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .icon-blue   { background: #dbeafe; color: #1d4ed8; }
    .icon-green  { background: #dcfce7; color: #15803d; }
    .icon-amber  { background: #fef3c7; color: #b45309; }
    .icon-coral  { background: #fee2e2; color: #b91c1c; }
    .icon-purple { background: #ede9fe; color: #6d28d9; }
    .icon-teal   { background: #ccfbf1; color: #0f766e; }

    .stat-body {
        flex: 1;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: #8a94a6;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 4px;
    }

    .stat-number {
        font-size: 30px;
        font-weight: 600;
        color: #0C1628;
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .stat-sub {
        font-size: 12px;
        color: #adb5bd;
    }

    .stat-badge {
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 500;
        align-self: flex-start;
    }

    .badge-blue   { background: #dbeafe; color: #1d4ed8; }
    .badge-green  { background: #dcfce7; color: #15803d; }
    .badge-amber  { background: #fef3c7; color: #b45309; }
    .badge-coral  { background: #fee2e2; color: #b91c1c; }
    .badge-purple { background: #ede9fe; color: #6d28d9; }
    .badge-teal   { background: #ccfbf1; color: #0f766e; }

    /* ===== Section Heading ===== */
    .section-title {
        font-size: 15px;
        font-weight: 600;
        color: #0C1628;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e8ecf4;
    }

    /* ===== Summary Banner ===== */
    .summary-banner {
        background: #0C1628;
        border-radius: 14px;
        padding: 24px 28px;
        margin-bottom: 28px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .summary-banner h3 {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 600;
        margin: 0 0 4px 0;
    }

    .summary-banner p {
        font-size: 13px;
        color: #85B7EB;
        margin: 0;
    }

    .banner-stat {
        text-align: center;
    }

    .banner-stat .b-num {
        font-size: 26px;
        font-weight: 600;
        color: #fff;
        display: block;
    }

    .banner-stat .b-label {
        font-size: 11px;
        color: #85B7EB;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .banner-divider {
        width: 1px;
        height: 40px;
        background: rgba(255,255,255,0.15);
    }

    /* ===== Quick Actions ===== */
    .quick-action {
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        text-decoration: none;
        color: #0C1628;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.15s;
    }

    .quick-action:hover {
        background: #f4f6fb;
        border-color: #c5cfdf;
        text-decoration: none;
        color: #0C1628;
    }

    .quick-action .qa-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .quick-action .qa-arrow {
        margin-left: auto;
        color: #c5cfdf;
        font-size: 12px;
    }
</style>

<section class="content-header">
    <!-- Page Header -->
    <div class="dash-header">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <h1>Dashboard</h1>
                <p>Welcome back! Here's what's happening in your system.</p>
            </div>
            <div class="col-sm-4 dash-date">
                <i class="fa fa-calendar-o mr-1"></i>
                {{ \Carbon\Carbon::now()->format('l, d M Y') }}
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ===== SUMMARY BANNER ===== --}}
    {{-- <div class="summary-banner">
        <div>
            <h3>System Overview</h3>
            <p>All records currently active in {{config('settings.system_title')}}</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 28px; flex-wrap: wrap;">
            <div class="banner-stat">
                <span class="b-num">{{$clientCounts}}</span>
                <span class="b-label">Clients</span>
            </div>
            <div class="banner-divider"></div>
            <div class="banner-stat">
                <span class="b-num">{{$documentCounts}}</span>
                <span class="b-label">{{ucfirst(config('settings.document_label_plural'))}}</span>
            </div>
            <div class="banner-divider"></div>
            <div class="banner-stat">
                <span class="b-num">{{$filesCounts}}</span>
                <span class="b-label">{{ucfirst(config('settings.file_label_plural'))}}</span>
            </div>
        </div>
    </div> --}}

    {{-- ===== STAT CARDS ROW ===== --}}
    <div class="section-title">
        <i class="fa fa-bar-chart mr-2" style="color:#378ADD;"></i> Key Metrics
    </div>

    <div class="row">

        {{-- Clients --}}
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Clients</div>
                    <div class="stat-number">{{$clientCounts}}</div>
                    <div class="stat-sub">Total {{ucfirst(config('settings.tags_label_plural'))}} in system</div>
                </div>
                <span class="stat-badge badge-blue">Active</span>
            </div>
        </div>

        {{-- Documents --}}
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <i class="fa fa-folder-open"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">{{ucfirst(config('settings.document_label_plural'))}}</div>
                    <div class="stat-number">{{$documentCounts}}</div>
                    <div class="stat-sub">Containing {{$filesCounts}} {{ucfirst(config('settings.file_label_plural'))}}</div>
                </div>
                <span class="stat-badge badge-green">Total</span>
            </div>
        </div>

        {{-- Invoices --}}
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon icon-amber">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Invoices</div>
                    <div class="stat-number">{{ $invoiceCounts ?? 0 }}</div>
                    <div class="stat-sub">Total invoices generated</div>
                </div>
                <span class="stat-badge badge-amber">Billed</span>
            </div>
        </div>

        {{-- Receipts --}}
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon icon-teal">
                    <i class="fa fa-receipt"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Receipts</div>
                    <div class="stat-number">{{ $receiptCounts ?? 0 }}</div>
                    <div class="stat-sub">Payments received</div>
                </div>
                <span class="stat-badge badge-teal">Paid</span>
            </div>
        </div>

        {{-- Files --}}
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon icon-purple">
                    <i class="fa fa-paperclip"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">{{ucfirst(config('settings.file_label_plural'))}}</div>
                    <div class="stat-number">{{$filesCounts}}</div>
                    <div class="stat-sub">Uploaded across all documents</div>
                </div>
                <span class="stat-badge badge-purple">Stored</span>
            </div>
        </div>

        {{-- Tags / Categories --}}
        <div class="col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon icon-coral">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="stat-body">
                    <div class="stat-label">{{ucfirst(config('settings.tags_label_plural'))}}</div>
                    <div class="stat-number">{{ $tagCounts ?? 0 }}</div>
                    <div class="stat-sub">Labels used in system</div>
                </div>
                <span class="stat-badge badge-coral">Tags</span>
            </div>
        </div>

    </div>

    {{-- ===== QUICK ACTIONS ===== --}}
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-4">
            <div class="section-title">
                <i class="fa fa-bolt mr-2" style="color:#378ADD;"></i> Quick Actions
            </div>

            <a href="{{ url('/admin/clients/create') }}" class="quick-action">
                <div class="qa-icon icon-blue"><i class="fa fa-user-plus"></i></div>
                Add New Client
                <i class="fa fa-chevron-right qa-arrow"></i>
            </a>

            <a href="{{ url('/admin/documents/create') }}" class="quick-action">
                <div class="qa-icon icon-green"><i class="fa fa-folder-open"></i></div>
                Create Document
                <i class="fa fa-chevron-right qa-arrow"></i>
            </a>

            <a href="{{ url('/admin/invoices/create') }}" class="quick-action">
                <div class="qa-icon icon-amber"><i class="fa fa-file-text-o"></i></div>
                Generate Invoice
                <i class="fa fa-chevron-right qa-arrow"></i>
            </a>

            <a href="{{ url('/admin/receipts/create') }}" class="quick-action">
                <div class="qa-icon icon-teal"><i class="fa fa-plus-circle"></i></div>
                Add Receipt
                <i class="fa fa-chevron-right qa-arrow"></i>
            </a>
        </div>

        {{-- ===== SYSTEM INFO ===== --}}
        <div class="col-md-8">
            <div class="section-title">
                <i class="fa fa-info-circle mr-2" style="color:#378ADD;"></i> System Summary
            </div>
            <div class="table-responsive" style="background:#fff; border-radius:14px; border:1px solid #e8ecf4; overflow:hidden;">
                <table class="table table-hover mb-0" style="font-size:14px;">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th style="border-top:none; color:#8a94a6; font-size:11px; text-transform:uppercase; letter-spacing:0.7px; padding:14px 20px;">Module</th>
                            <th style="border-top:none; color:#8a94a6; font-size:11px; text-transform:uppercase; letter-spacing:0.7px;">Label Used</th>
                            <th style="border-top:none; color:#8a94a6; font-size:11px; text-transform:uppercase; letter-spacing:0.7px; text-align:right; padding-right:20px;">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding:14px 20px; color:#0C1628; font-weight:500;"><i class="fa fa-users mr-2" style="color:#1d4ed8;"></i> Clients</td>
                            <td style="color:#8a94a6;">{{ucfirst(config('settings.tags_label_plural'))}}</td>
                            <td style="text-align:right; padding-right:20px; font-weight:600; color:#0C1628;">{{$clientCounts}}</td>
                        </tr>
                        <tr>
                            <td style="padding:14px 20px; color:#0C1628; font-weight:500;"><i class="fa fa-folder mr-2" style="color:#15803d;"></i> Documents</td>
                            <td style="color:#8a94a6;">{{ucfirst(config('settings.document_label_plural'))}}</td>
                            <td style="text-align:right; padding-right:20px; font-weight:600; color:#0C1628;">{{$documentCounts}}</td>
                        </tr>
                        <tr>
                            <td style="padding:14px 20px; color:#0C1628; font-weight:500;"><i class="fa fa-paperclip mr-2" style="color:#6d28d9;"></i> Files</td>
                            <td style="color:#8a94a6;">{{ucfirst(config('settings.file_label_plural'))}}</td>
                            <td style="text-align:right; padding-right:20px; font-weight:600; color:#0C1628;">{{$filesCounts}}</td>
                        </tr>
                        <tr>
                            <td style="padding:14px 20px; color:#0C1628; font-weight:500;"><i class="fa fa-file-text-o mr-2" style="color:#b45309;"></i> Invoices</td>
                            <td style="color:#8a94a6;">Invoices</td>
                            <td style="text-align:right; padding-right:20px; font-weight:600; color:#0C1628;">{{ $invoiceCounts ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td style="padding:14px 20px; color:#0C1628; font-weight:500;"><i class="fa fa-check-circle mr-2" style="color:#0f766e;"></i> Receipts</td>
                            <td style="color:#8a94a6;">Receipts</td>
                            <td style="text-align:right; padding-right:20px; font-weight:600; color:#0C1628;">{{ $receiptCounts ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection