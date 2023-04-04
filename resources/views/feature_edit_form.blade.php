
{{--<?php--}}
{{--    dd($content);--}}
{{--//    ?><!---->--}}
<head>
    <title>Feature Crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5" >
    <form method="Post">

        <input type="hidden" name="id" value="<?php echo $content->id ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo $content->name ?>" id="name" >
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control"style="height: 100px" name="description"  ><?php echo $content->description ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="mt-3">
        @if ($errors)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
