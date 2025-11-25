<table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">

    <thead>

        <tr>

            <th>#</th>

            <th>Name</th>

            <th>is New Arrival</th>

            <th>is Trending</th>

            <th>is Top Search</th>

            <th>is Recommended</th>

        </tr>

    </thead>           

    <tbody>

        @foreach ($indexes as $index)

        <tr>

            <td>{{$index->id}}</td>

            <td><a target="_blank" href="{{url('/product')}}/{{$index->id}}/edit">{{$index->name}}</a></td>

            <td><input type="checkbox" value="1" data-productid='{{$index->id}}' data-searchname="is_newarrival" name="is_newarrival" class="form-check-input changechecksearch" @if($index->is_newarrival=='1') checked @endif></td>

            <td><input type="checkbox" value="1" data-productid='{{$index->id}}' data-searchname="is_trending" name="is_trending" class="form-check-input changechecksearch" @if($index->is_trending=='1') checked @endif></td>

            <td><input type="checkbox" value="1" data-productid='{{$index->id}}' data-searchname="is_topsearch" name="is_topsearch" class="form-check-input changechecksearch" @if($index->is_topsearch=='1') checked @endif></td>

            <td><input type="checkbox" value="1" data-productid='{{$index->id}}' data-searchname="is_recommended" name="is_recommended" class="form-check-input changechecksearch" @if($index->is_recommended=='1') checked @endif></td>

        </tr>

        @endforeach

    </tbody>

</table>