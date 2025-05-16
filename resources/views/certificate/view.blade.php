@extends('include.master')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Certificates Achievement Table</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="thead-custom">
                                                    <tr>
                                                        <th class="text-center">S.No.</th>
                                                        <th class="text-center">Course Name</th>
                                                        <th class="text-center">Result</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (empty($achievements))
                                                        <tr>
                                                            <td colspan="4" class="text-center">No Certificates Found
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($achievements as $index => $achievement)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $achievement['course_title'] }}</td>
                                                                <td>
                                                                    @if ($achievement['is_completed'])
                                                                        <span
                                                                            class="badge badge-success fs-5 p-2">Completed</span>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-info fs-5 p-2">Pending</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($achievement['is_completed'])
                                                                        <a href="{{ route('certificate.download', ['course' => $achievement['course_title']]) }}"
                                                                            class="btn btn-success">
                                                                            Download Certificate
                                                                        </a>
                                                                    @else
                                                                        <span class="badge badge-danger"
                                                                            style="font-size: 0.8rem; padding: 0.7rem 1.5rem;">Not
                                                                            Eligible</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
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
