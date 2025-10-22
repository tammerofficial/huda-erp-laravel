@extends('layouts.app')

@section('title', 'Accounting Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Financial Reports</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Total Revenue</h4>
                                            <h2>{{ number_format($revenue, 2) }} KWD</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-arrow-up fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Total Expenses</h4>
                                            <h2>{{ number_format($expenses, 2) }} KWD</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-arrow-down fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Net Profit</h4>
                                            <h2>{{ number_format($profit, 2) }} KWD</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="card-title">Profit Margin</h4>
                                            <h2>{{ $revenue > 0 ? number_format(($profit / $revenue) * 100, 2) : 0 }}%</h2>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-percentage fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Monthly Financial Data</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Revenue</th>
                                                    <th>Expenses</th>
                                                    <th>Net Profit</th>
                                                    <th>Profit Margin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($monthlyData as $data)
                                                <tr>
                                                    <td>{{ $data->month }}</td>
                                                    <td>{{ number_format($data->revenue, 2) }} KWD</td>
                                                    <td>{{ number_format($data->expense, 2) }} KWD</td>
                                                    <td>{{ number_format($data->revenue - $data->expense, 2) }} KWD</td>
                                                    <td>{{ $data->revenue > 0 ? number_format((($data->revenue - $data->expense) / $data->revenue) * 100, 2) : 0 }}%</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No data available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
