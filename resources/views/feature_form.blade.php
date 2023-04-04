
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feature Crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
   <div class="container mt-5" >
    <form method="Post">

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" name="name" id="name" >
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control"style="height: 100px" name="description" ></textarea>
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

<!-- Your form HTML goes here -->

    <div class="container-fluid mt-5">
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Slug</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>

              </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($features as $feature)
                    <tr>
                        <th scope="row">{{$feature->id}}</th>
                        <td>{{$feature->slug}}</td>
                        <td>{{$feature->name}}</td>
                        <td>{{substr($feature->description,0,50)}}</td>
                        <td ><a href="{{ url('/edit') }}?id={{ $feature->id }}" class="btn btn-sm btn-primary">Edit</a></td>
                        <td ><a href="{{ url('/delete') }}?id={{ $feature->id }}" class="btn btn-sm btn-danger">Delete</a></td>

                    </tr>
                @endforeach
            </tbody>
          </table>
    </div>
   </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
