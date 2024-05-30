<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>

<h1>Birdboard</h1>
<ul>
    @foreach($projects as $project)
        <li>{{$project->title}}</li>
    @endforeach
</ul>

</body>
</html>