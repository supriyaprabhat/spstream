@extends('site_app')

@if($movies_info->seo_title)
  @section('head_title', stripslashes($movies_info->seo_title).' | '.getcong('site_name'))
@else
  @section('head_title', stripslashes($movies_info->video_title).' | '.getcong('site_name'))
@endif

@if($movies_info->seo_description)
  @section('head_description', stripslashes($movies_info->seo_description))
@else
  @section('head_description', Str::limit(stripslashes($movies_info->video_description),160))
@endif

@if($movies_info->seo_keyword)
  @section('head_keywords', stripslashes($movies_info->seo_keyword)) 
@endif


@section('head_image', URL::to('/'.$movies_info->video_image))

@section('head_url', Request::url())

@section('content')

<!-- Banner -->
@if(get_web_banner('details_top')!="")      
<div class="vid-item-ptb banner_ads_item">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				{!!stripslashes(get_web_banner('details_top'))!!}
			</div>
		</div>  
	</div>
</div>
@endif
 
<link rel="stylesheet" type="text/css" href="{{ URL::asset('site_assets/player/content/global.css') }}">
<script type="text/javascript" src="{{ URL::asset('site_assets/player/java/FWDEVPlayer.js') }}"></script>

@if($movies_info->trailer_url!="")

    @include("pages.movies.player.trailer")

@endif 
 
   
<!-- Start Page Content Area -->
<div class="page-content-area vfx-item-ptb pt-0">

  <div class="container-fluid bg-dark video-player-base"> 
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="video-posts-video">         
        
        @if($movies_info->video_url!="")

          @if($movies_info->video_type=="Embed")

          @include("pages.movies.player.embed")

          @elseif($movies_info->video_type=="Local")

          @include("pages.movies.player.local")           

          @elseif($movies_info->video_type=="URL")

          @include("pages.movies.player.url")

          @else

          @include("pages.movies.player.other")

          @endif    

        @else

          <div style="text-align: center;padding: 70px 30px;font-size: 24px;	font-weight: 700;	background: #101011;border-radius: 10px;margin-top: 15px;min-height: 280px;
	line-height: 6;">NO Source  URL Set</div>
  
        @endif

            

        </div>
      </div>
    </div>  
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <!-- Start Video Post -->
        <div class="video-post-wrapper">
        <div class="row mt-30">
			    
        <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="video-posts-data mt-0 mb-0">
            <div class="video-post-info">
            <div class="d-flex dtl-title-block mb-2">  
              <h2 class="mb-0">{{stripslashes($movies_info->video_title)}}</h2>
              @if($movies_info->trailer_url!="")
              <div class="video-watch-share-item">          
                <div class="subscribe-btn-item">
                    <a href="javascript:window['player2'].showLightbox();" title="{{trans('words.watch_triler')}}"><i class="fa fa-play-circle"></i> {{trans('words.watch_triler')}}</a>
 
                </div>                
              </div>
              @endif
            </div>

            <div class="video-post-date">
                <span class="video-posts-author"><i class="fa fa-eye"></i>{{number_format_short($movies_info->views)}} {{trans('words.video_views')}}</span>
                @if($movies_info->release_date)           
                  <span class="video-posts-author"><i class="fa fa-calendar-alt"></i>{{ isset($movies_info->release_date) ? date('M d Y',$movies_info->release_date) : null }}</span>
                @endif 

                @if($movies_info->duration)          
                 <span class="video-posts-author"><i class="fa fa-clock"></i>{{$movies_info->duration}}</span>
                @endif

                @if($movies_info->imdb_rating)           
                 <span class="video-imdb-view"><img src="{{URL::to('site_assets/images/imdb-logo.png')}}" alt="imdb-logo" title="imdb-logo" />{{$movies_info->imdb_rating}}</span> 
                @endif 
            </div>
        <ul class="actor-video-link">
          @foreach(explode(',',$movies_info->movie_genre_id) as $genres_ids)
          <li><a href="{{ URL::to('movies?genre_id='.$genres_ids) }}" title="{{App\Genres::getGenresInfo($genres_ids,'genre_name')}}">{{App\Genres::getGenresInfo($genres_ids,'genre_name')}}</a></li>
          @endforeach
          <li><a href="{{ URL::to('movies?lang_id='.$movies_info->movie_lang_id) }}" title="{{App\Language::getLanguageInfo($movies_info->movie_lang_id,'language_name')}}">{{App\Language::getLanguageInfo($movies_info->movie_lang_id,'language_name')}}</a></li>           
        </ul>
        <div class="video-watch-share-item">
          @if(Auth::check()) 
             
             @if(check_watchlist(Auth::user()->id,$movies_info->id,'Movies'))
              <span class="btn-watchlist"><a href="{{URL::to('watchlist/remove')}}?post_id={{$movies_info->id}}&post_type=Movies" title="watchlist"><i class="fa fa-check"></i>{{trans('words.remove_from_watchlist')}}</a></span>
             @else               
              <span class="btn-watchlist"><a href="{{URL::to('watchlist/add')}}?post_id={{$movies_info->id}}&post_type=Movies" title="watchlist"><i class="fa fa-plus"></i>{{trans('words.add_to_watchlist')}}</a></span>
             @endif  
          @else
             <span class="btn-watchlist"><a href="{{URL::to('watchlist/add')}}?post_id={{$movies_info->id}}&post_type=Movies" title="watchlist"><i class="fa fa-plus"></i>{{trans('words.add_to_watchlist')}}</a></span>
          @endif
          <span class="btn-share"><a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#social-media"><i class="fas fa-share-alt mr-5"></i>{{trans('words.share_text')}}</a></span>
          
          <!-- Start Social Media Icon Popup -->
          <div id="social-media" class="modal fade centered-modal in" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content bg-dark-2 text-light">
              <div class="modal-header">
              <h4 class="modal-title text-white">{{trans('words.share_text')}}</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body p-4">
              <div class="social-media-modal">
                <ul>
                  <li><a title="Sharing" href="https://www.facebook.com/sharer/sharer.php?u={{share_url_get('movies',$movies_info->video_slug,$movies_info->id)}}" class="facebook-icon" target="_blank"><i class="ion-social-facebook"></i></a></li>
                  <li><a title="Sharing" href="https://twitter.com/intent/tweet?text={{$movies_info->video_title}}&amp;url={{share_url_get('movies',$movies_info->video_slug,$movies_info->id)}}" class="twitter-icon" target="_blank"><i class="ion-social-twitter"></i></a></li>
                  <li><a title="Sharing" href="https://www.instagram.com/?url={{share_url_get('movies',$movies_info->video_slug,$movies_info->id)}}" class="instagram-icon" target="_blank"><i class="ion-social-instagram"></i></a></li>
                   <li><a title="Sharing" href="https://wa.me?text={{share_url_get('movies',$movies_info->video_slug,$movies_info->id)}}" class="whatsapp-icon" target="_blank"><i class="ion-social-whatsapp"></i></a></li>
                </ul>
              </div>        
              </div>
            </div>
            </div>
          </div>
          <!-- End Social Media Icon Popup -->    

           
          @if($movies_info->download_enable)
            <div class="subscribe-btn-item">
             <a href="{{$movies_info->download_url}}" target="_blank" title="download"><i class="fa fa-download"></i>&nbsp;{{trans('words.download')}}</a> 
            </div>
          @endif

        </div>
      </div>
          </div>
        </div>
      </div>  

          
          <div class="vfx-tabs-item mt-30">
        <input checked="checked" id="tab1" type="radio" name="pct" />
        <input id="tab2" type="radio" name="pct" />
        <input id="tab3" type="radio" name="pct" />
        <nav>
        <ul>
          <li class="tab1">
          <label for="tab1">{{trans('words.description')}}</label>
          </li>
          @if($movies_info->actor_id) 
          <li class="tab2">
          <label for="tab2">{{trans('words.actors')}}</label>
          </li>
          @endif
          @if($movies_info->director_id) 
          <li class="tab3">
          <label for="tab3">{{trans('words.directors')}}</label>
          </li>
          @endif
        </ul>
        </nav>
        <section class="tabs_item_block">
        <div class="tab1">
          <div class="description-detail-item">
           
            <p>{!!stripslashes($movies_info->video_description)!!}</p>
          
          </div>
        </div>
        <div class="tab2">
          <div class="row">
          @if($movies_info->actor_id)  
            @foreach(explode(',',$movies_info->actor_id) as $i => $actor_ids)
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 col-6">
              <div class="actors-member-item"> 
                <a href="{{ URL::to('actors/'.App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_slug')) }}/{{$actor_ids}}" title="actors details"> 
                  @if(App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_image')) 
                  <img src="{{URL::to('/'.App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_image'))}}" alt="{{App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_name')}}" title="{{App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_name')}}">  
                  @else
                    <img src="{{URL::to('images/user_icon.png')}}" alt="{{App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_name')}}" title="{{App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_name')}}"> 
                  @endif
                  
                  
                  <span>{{App\ActorDirector::getActorDirectorInfo($actor_ids,'ad_name')}}</span> 
                </a>
              </div>
            </div>
            @endforeach
          @endif
          </div>
          
        </div>
        <div class="tab3">
            
            <div class="row">
            @if($movies_info->director_id)  
              @foreach(explode(',',$movies_info->director_id) as $i => $director_ids)
              <div class="col-lg-2 col-md-3 col-sm-4 col-xs-12 col-6">
                <div class="actors-member-item"> 
                  <a href="{{ URL::to('directors/'.App\ActorDirector::getActorDirectorInfo($director_ids,'ad_slug')) }}/{{$director_ids}}" title="directors details"> 
                    @if(App\ActorDirector::getActorDirectorInfo($director_ids,'ad_image')) 
                    <img src="{{URL::to('/'.App\ActorDirector::getActorDirectorInfo($director_ids,'ad_image'))}}" alt="{{App\ActorDirector::getActorDirectorInfo($director_ids,'ad_name')}}" title="{{App\ActorDirector::getActorDirectorInfo($director_ids,'ad_name')}}"> 
                    @else
                      <img src="{{URL::to('images/user_icon.png')}}" alt="{{App\ActorDirector::getActorDirectorInfo($director_ids,'ad_name')}}" title="{{App\ActorDirector::getActorDirectorInfo($director_ids,'ad_name')}}"> 
                    @endif

                    <span>{{App\ActorDirector::getActorDirectorInfo($director_ids,'ad_name')}}</span> 
                  </a>
                </div>
              </div>
              @endforeach
          @endif
          </div>

        </div>

        </section>
      </div>
        </div>    
      </div>
      <!-- Start Popular Videos --> 
    
    <!-- Start You May Also Like Video Carousel -->
    <div class="video-carousel-area vfx-item-ptb related-video-item">
      <div class="container-fluid">
      <div class="row">
        <div class="col-md-12 p-0">
        <div class="vfx-item-section">
          <h3>{{trans('words.you_may_like')}}</h3>           
        </div>
        <div class="video-carousel owl-carousel">
           
           @foreach($related_movies_list as $movies_data) 
          <div class="single-video">
          <a href="{{ URL::to('movies/details/'.$movies_data->video_slug.'/'.$movies_data->id) }}" title="{{stripslashes($movies_data->video_title)}}">
             <div class="video-img">          
              
              @if($movies_data->video_access =="Paid")       
              <div class="vid-lab-premium">
                <img src="{{ URL::asset('site_assets/images/ic-premium.png') }}" alt="ic-premium" title="ic-premium">
              </div> 
              @endif  

              <span class="video-item-content">{{stripslashes($movies_data->video_title)}}</span> 
              <img src="{{URL::to('/'.$movies_data->video_image_thumb)}}" alt="{{stripslashes($movies_data->video_title)}}" title="{{stripslashes($movies_data->video_title)}}">         
             </div>       
          </a>
          </div>
          @endforeach
                 
        </div>
        </div>
      </div>
      </div>
    </div>
    <!-- End You May Also Like Video Carousel -->   
    </div>
  </div>
</div>
<!-- End Page Content Area --> 

<!-- Banner -->
@if(get_web_banner('details_bottom')!="")      
<div class="vid-item-ptb banner_ads_item pb-3">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				{!!stripslashes(get_web_banner('details_bottom'))!!}
			</div>
		</div>  
	</div>
</div>
@endif

 <script type="text/javascript">
    
    @if(Session::has('flash_message'))     
 
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: false,
        /*didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }*/
      })

      Toast.fire({
        icon: 'success',
        title: '{{ Session::get('flash_message') }}'
      })     
     
  @endif
  
  </script>

@endsection