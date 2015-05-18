@extends('app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>Count</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($keywords as $keyword)
                        <tr>
                            <td>{{$keyword['_id']}}</td>
                            <td>{{$keyword['value']['count']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $keywords->render() !!}
            </div>
        </div>
    </div>

@endsection
















