<!DOCTYPE html>
<html>
<head>
	<title>Laravel</title>
</head>
<body>
	<div class="container">
		<div class="personal">
			<p>Summoner name: {{$summoner->name}}</p>
			<p>Summoner level: {{$summoner->summonerLevel}}</p>
		</div>
		<div class="match">
			{{-- <p> Last {{ $match_data->totalGames }} Games <br> </p> --}}
			<ul class="match-data">
				@foreach( $data_output as $match )
					<li class="single-match">
						<span class="champ"> Champion name: {{ $match['champ_name'] }} </span>
						<img src="{{ $match['champ_icon'] }}" style="width: 30px;">
						<span class="lane"> Lane: {{ $match['lane'] }} </span>
						<span class="date"> Time: {{ $match['date'] }} </span>
					</li>
				@endforeach
			</ul>
		</div>
	</div>

<a href="../lol">Back</a>
</body>
</html>