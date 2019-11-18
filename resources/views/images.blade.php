@foreach($uploads as $value)
<div class="col-lg-3 col-md-6">
    <div class="card">
        <div class="el-card-item">

            <div class="el-card-avatar el-overlay-1"><img src="{{ $value->file_extension}}" >
               {{ $value->file_extension}}
            </div>


        </div>
    </div>
</div>
@endforeach
