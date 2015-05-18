@extends('app')

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="panel panel-default">
                    <div class="panel-heading">Total Tweets sur Mongo DB</div>
                    <div class="panel-body">
                        {{$total_tweets}}
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Total Hashtag sur Mongo DB</div>
                    <div class="panel-body">
                        {{$total_hashtag}}
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Hashtags Populaires</div>
                    <div class="panel-body">
                        <canvas id="myChart" class="center" width="400" height="400"></canvas>
                    </div>
                </div>




            </div>
        </div>
    </div>


    <script>
        var ctx = $("#myChart").get(0).getContext("2d");
        var myPieChart = new Chart(ctx).Pie();

        @foreach($agg_hashtags['result'] as $hashtag)
        myPieChart.addData({
            value: "{{ $hashtag['number'] }}",
            color: '#' + (Math.random().toString(16) + '0000000').slice(2, 8),
            label: "{{ $hashtag['_id'] }}"
        });
        @endforeach

    </script>
@endsection
