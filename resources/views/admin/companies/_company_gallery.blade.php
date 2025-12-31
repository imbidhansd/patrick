@php $pending_counter = 0; @endphp
<div class="banners">
    <div class="row">
        @foreach ($company_galleries AS $gallery_item)
        
        @if ($gallery_item->media)
        <div class="col-md-2 text-center">
            <div class="item active">
                @if ($gallery_item->gallery_type == 'image')
                    <a href="{{ asset('/uploads/media/'.$gallery_item->media->file_name) }}" data-fancybox="company_gallery">
                        <img src="{{ asset('/uploads/media/fit_thumbs/100x100/'.$gallery_item->media->file_name) }}" class="img-thumbnail" />
                    </a>
                @else
                    @if ($gallery_item->video_type == 'vimeo')
                    @php $video_link = 'https://vimeo.com/'.$gallery_item->video_id; @endphp
                    @elseif ($gallery_item->video_type == 'youtube')
                    @php $video_link = 'https://www.youtube.com/watch?v='.$gallery_item->video_id; @endphp
                    @endif
                    
                    <a href="{{ $video_link }}" data-fancybox="company_gallery">
                        <img src="{{ asset('/uploads/media/fit_thumbs/100x100/'.$gallery_item->media->file_name) }}" class="img-thumbnail" />
                    </a>
                @endif

                <div class="clearfix">&nbsp;</div>
                <div class="btn-group text-center">
                    @if ($gallery_item->status == 'pending')
                    @php $pending_counter++; @endphp
                    <span class="badge badge-info">{{ ucfirst($gallery_item->status) }}</span>
                    @elseif ($gallery_item->status == 'approved')
                    <span class="badge badge-success">{{ ucfirst($gallery_item->status) }}</span>
                    @elseif ($gallery_item->status == 'rejected')
                    <span class="badge badge-danger">{{ ucfirst($gallery_item->status) }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
        
        @if ($loop->iteration % 6 == 0)
    </div>
    <hr />
    <div class="row">
        @endif
        @endforeach
    </div>
    
    
    @if ($pending_counter > 0)
    <a href="{{ route('manage-gallery-requests', ['company_id' => $company_item->id]) }}" class="btn btn-warning float-right">Approve Pending Photos</a>
    <div class="clearfix">&nbsp;</div>
    @endif
    
    
</div>