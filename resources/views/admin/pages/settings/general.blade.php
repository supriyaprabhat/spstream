@extends("admin.admin_app")

@section("content")

<style type="text/css">
  .iframe-container {
  overflow: hidden;
  padding-top: 56.25% !important;
  position: relative;
}
 
.iframe-container iframe {
   border: 0;
   height: 100%;
   left: 0;
   position: absolute;
   top: 0;
   width: 100%;
}
</style>
 
  <div class="content-page">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-box">
                
                <div class="row">

                 <div class="col-md-6"> 
 
                 {!! Form::open(array('url' => array('admin/general_settings'),'class'=>'form-horizontal','name'=>'settings_form','id'=>'settings_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  
                  <input type="hidden" name="id" value="{{ isset($settings->id) ? $settings->id : null }}">
                  
                   
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.site_name')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="site_name" value="{{ isset($settings->site_name) ? stripslashes($settings->site_name) : null }}" class="form-control">
                    </div>
                  </div>
 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.site_logo')}}*</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" name="site_logo" id="site_logo" value="{{ isset($settings->site_logo) ? $settings->site_logo : null }}" class="form-control" readonly>
                        <div class="input-group-append">                           
                          <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="site_logo" data-preview="holder_logo" data-inputid="site_logo">Select</button>                        
                        </div>
                      </div>
                      <small id="emailHelp" class="form-text text-muted">({{trans('words.recommended_resolution')}} : 180x50)</small>
                      <div id="site_logo_holder" style="margin-top:5px;max-height:100px;"></div>                     
                    </div>
                  </div>                 

                  @if(isset($settings->site_logo)) 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">                                                                         
                      <img src="{{URL::to('/'.$settings->site_logo)}}" alt="video image" class="img-thumbnail" width="160">                                               
                    </div>
                  </div>
                  @endif

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.site_favicon')}}*</label>
                    <div class="col-sm-8">
                      <div class="input-group">
                        <input type="text" name="site_favicon" id="site_favicon" value="{{ isset($settings->site_favicon) ? $settings->site_favicon : null }}" class="form-control" readonly>
                        <div class="input-group-append">                           
                            <button type="button" class="btn btn-dark waves-effect waves-light popup_selector" data-input="site_favicon" data-preview="holder_favicon" data-inputid="site_favicon">Select</button>                        
                        </div>
                      </div>
                      <small id="emailHelp" class="form-text text-muted">({{trans('words.recommended_resolution')}} : 16x16, 32X32)</small>
                      <div id="site_favicon_holder" style="margin-top:5px;max-height:100px;"></div>                     
                    </div>
                  </div>

                  @if(isset($settings->site_favicon)) 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">&nbsp;</label>
                    <div class="col-sm-8">                                                                         
                      <img src="{{URL::to('/'.$settings->site_favicon)}}" alt="video image" class="img-thumbnail" width="32">                                               
                    </div>
                  </div>
                  @endif
                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.email')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="site_email" value="{{ isset($settings->site_email) ? $settings->site_email : null }}" class="form-control">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.description')}}</label>
                    <div class="col-sm-8">
                      <textarea name="site_description" class="form-control">{{ isset($settings->site_description) ? stripslashes($settings->site_description) : null }}</textarea>
                       
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.site_keywords')}}</label>
                    <div class="col-sm-8">
                      <textarea name="site_keywords" class="form-control">{{ isset($settings->site_keywords) ? stripslashes($settings->site_keywords) : null }}</textarea>
                       
                    </div>
                  </div>

                  <div class="form-group row">
                     
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Header Code</label>
                    <div class="col-sm-8">
                      <textarea name="site_header_code" class="form-control" placeholder="Custom CSS OR JS script">{{ isset($settings->site_header_code) ? stripslashes($settings->site_header_code) : null }}</textarea>
                       
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Footer Code</label>
                    <div class="col-sm-8">
                      <textarea name="site_footer_code" class="form-control" placeholder="Custom CSS OR JS script">{{ isset($settings->site_footer_code) ? stripslashes($settings->site_footer_code) : null }}</textarea>
                       
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.site_copyright_text')}}</label>
                    <div class="col-sm-8">
                      <textarea name="site_copyright" class="form-control">{{ isset($settings->site_copyright) ? stripslashes($settings->site_copyright) : null }}</textarea>                      
                    </div>
                  </div>

                </div>
                  <div class="col-md-6">   
 
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.default_timezone')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="time_zone">                               
                                @foreach(generate_timezone_list() as $key=>$tz_data)
                                <option value="{{$key}}" @if($settings->time_zone==$key) selected @endif>{{$tz_data}}</option>
                                @endforeach                            
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.default_language')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="default_language">                               
                                 
                                <option value="en" @if($settings->default_language=="en") selected @endif>English</option>
                                <option value="es" @if($settings->default_language=="es") selected @endif>Spanish</option>
                                <option value="fr" @if($settings->default_language=="fr") selected @endif>French</option>
                                <option value="pt" @if($settings->default_language=="pt") selected @endif>Portuguese</option>             
                            </select>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.site_style')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="styling">                               
                                 
                                <option value="style-one" @if($settings->styling=="style-one") selected @endif>Style 1</option>
                                <option value="style-two" @if($settings->styling=="style-two") selected @endif>Style 2</option>
                                <option value="style-three" @if($settings->styling=="style-three") selected @endif>Style 3</option>
                                <option value="style-four" @if($settings->styling=="style-four") selected @endif>Style 4</option>
                                <option value="style-five" @if($settings->styling=="style-five") selected @endif>Style 5</option>
                                <option value="style-six" @if($settings->styling=="style-six") selected @endif>Style 6</option>
                                  
                            </select>
                      </div>
                  </div>
                   <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.currency_code')}}* </label>
                    <div class="col-sm-8">                       
                      <select name="currency_code" id="currency_code" class="form-control select2">
                        @foreach(getCurrencyList() as $index => $currency_list)
                        <option value="{{$index}}" @if($settings->currency_code==$index) selected @endif>{{$index}} - {{$currency_list}}</option>
                        @endforeach
                          
                      </select>

                    </div>
                  </div> 
                  <hr/>                   
                  <h4 class="m-t-0 header-title" id="tmdbapi_id">TMDB API</h4>
                  <br/>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">API Read Access Token</label>
                    <div class="col-sm-8">
                    <textarea name="tmdb_api_key" class="form-control">{{ isset($settings->tmdb_api_key) ? stripslashes($settings->tmdb_api_key) : null }}</textarea>     
                        
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">TMDB API Data Language</label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="tmdb_api_language">
                                  
                                <option value="af-ZA" @if($settings->tmdb_api_language=="af-ZA") selected @endif>Afrikaans (South Africa)</option>
                                <option value="ar-AE" @if($settings->tmdb_api_language=="ar-AE") selected @endif>Arabic (U.A.E.)</option>
                                <option value="ar-SA" @if($settings->tmdb_api_language=="ar-SA") selected @endif>Arabic (Saudi Arabia)</option>
                                <option value="be-BY" @if($settings->tmdb_api_language=="be-BY") selected @endif>Belarusian (Belarus)</option>
                                <option value="bg-BG" @if($settings->tmdb_api_language=="bg-BG") selected @endif>Bulgarian (Bulgaria)</option>
                                <option value="bn-BD" @if($settings->tmdb_api_language=="bn-BD") selected @endif>Bengali (Bangladesh)</option>
                                <option value="ca-ES" @if($settings->tmdb_api_language=="ca-ES") selected @endif>Catalan (Spain)</option>
                                <option value="cs-CZ" @if($settings->tmdb_api_language=="cs-CZ") selected @endif>Czech (Czech Republic)</option>
                                <option value="da-DK" @if($settings->tmdb_api_language=="da-DK") selected @endif>Danish (Denmark)</option>
                                <option value="de-DE" @if($settings->tmdb_api_language=="de-DE") selected @endif>German (Germany)</option>
                                <option value="el-GR" @if($settings->tmdb_api_language=="el-GR") selected @endif>Greek (Greece)</option>
                                <option value="en-US" @if($settings->tmdb_api_language=="en-US") selected @endif>English (United States)</option>
                                <option value="en-GB" @if($settings->tmdb_api_language=="en-GB") selected @endif>English (United Kingdom)</option>
                                <option value="es-ES" @if($settings->tmdb_api_language=="es-ES") selected @endif>Spanish (Spain)</option>
                                <option value="es-MX" @if($settings->tmdb_api_language=="es-MX") selected @endif>Spanish (Mexico)</option>
                                <option value="et-EE" @if($settings->tmdb_api_language=="et-EE") selected @endif>Estonian (Estonia)</option>
                                <option value="fa-IR" @if($settings->tmdb_api_language=="fa-IR") selected @endif>Persian (Iran)</option>
                                <option value="fi-FI" @if($settings->tmdb_api_language=="fi-FI") selected @endif>Finnish (Finland)</option>
                                <option value="fr-FR" @if($settings->tmdb_api_language=="fr-FR") selected @endif>French (France)</option>
                                <option value="he-IL" @if($settings->tmdb_api_language=="he-IL") selected @endif>Hebrew (Israel)</option>
                                <option value="hi-IN" @if($settings->tmdb_api_language=="hi-IN") selected @endif>Hindi (India)</option>
                                <option value="hr-HR" @if($settings->tmdb_api_language=="hr-HR") selected @endif>Croatian (Croatia)</option>
                                <option value="hu-HU" @if($settings->tmdb_api_language=="hu-HU") selected @endif>Hungarian (Hungary)</option>
                                <option value="id-ID" @if($settings->tmdb_api_language=="id-ID") selected @endif>Indonesian (Indonesia)</option>
                                <option value="it-IT" @if($settings->tmdb_api_language=="it-IT") selected @endif>Italian (Italy)</option>
                                <option value="ja-JP" @if($settings->tmdb_api_language=="ja-JP") selected @endif>Japanese (Japan)</option>
                                <option value="ka-GE" @if($settings->tmdb_api_language=="ka-GE") selected @endif>Georgian (Georgia)</option>
                                <option value="kk-KZ" @if($settings->tmdb_api_language=="kk-KZ") selected @endif>Kazakh (Kazakhstan)</option>
                                <option value="ko-KR" @if($settings->tmdb_api_language=="ko-KR") selected @endif>Korean (South Korea)</option>
                                <option value="lt-LT" @if($settings->tmdb_api_language=="lt-LT") selected @endif>Lithuanian (Lithuania)</option>
                                <option value="lv-LV" @if($settings->tmdb_api_language=="lv-LV") selected @endif>Latvian (Latvia)</option>
                                <option value="mk-MK" @if($settings->tmdb_api_language=="mk-MK") selected @endif>Macedonian (North Macedonia)</option>
                                <option value="ms-MY" @if($settings->tmdb_api_language=="ms-MY") selected @endif>Malay (Malaysia)</option>
                                <option value="nb-NO" @if($settings->tmdb_api_language=="nb-NO") selected @endif>Norwegian Bokmål (Norway)</option>
                                <option value="nl-NL" @if($settings->tmdb_api_language=="nl-NL") selected @endif>Dutch (Netherlands)</option>
                                <option value="no-NO" @if($settings->tmdb_api_language=="no-NO") selected @endif>Norwegian (Norway)</option>
                                <option value="pl-PL" @if($settings->tmdb_api_language=="pl-PL") selected @endif>Polish (Poland)</option>
                                <option value="pt-BR" @if($settings->tmdb_api_language=="pt-BR") selected @endif>Portuguese (Brazil)</option>
                                <option value="pt-PT" @if($settings->tmdb_api_language=="pt-PT") selected @endif>Portuguese (Portugal)</option>
                                <option value="ro-RO" @if($settings->tmdb_api_language=="ro-RO") selected @endif>Romanian (Romania)</option>
                                <option value="ru-RU" @if($settings->tmdb_api_language=="ru-RU") selected @endif>Russian (Russia)</option>
                                <option value="sk-SK" @if($settings->tmdb_api_language=="sk-SK") selected @endif>Slovak (Slovakia)</option>
                                <option value="sl-SI" @if($settings->tmdb_api_language=="sl-SI") selected @endif>Slovenian (Slovenia)</option>
                                <option value="sr-RS" @if($settings->tmdb_api_language=="sr-RS") selected @endif>Serbian (Serbia)</option>
                                <option value="sv-SE" @if($settings->tmdb_api_language=="sv-SE") selected @endif>Swedish (Sweden)</option>
                                <option value="th-TH" @if($settings->tmdb_api_language=="th-TH") selected @endif>Thai (Thailand)</option>
                                <option value="tr-TR" @if($settings->tmdb_api_language=="tr-TR") selected @endif>Turkish (Turkey)</option>
                                <option value="uk-UA" @if($settings->tmdb_api_language=="uk-UA") selected @endif>Ukrainian (Ukraine)</option>
                                <option value="vi-VN" @if($settings->tmdb_api_language=="vi-VN") selected @endif>Vietnamese (Vietnam)</option>
                                <option value="zh-CN" @if($settings->tmdb_api_language=="zh-CN") selected @endif>Chinese (Simplified)</option>
                                <option value="zh-TW" @if($settings->tmdb_api_language=="zh-TW") selected @endif>Chinese (Traditional)</option>
                                           
                            </select>
                      </div>
                  </div>
 

                  <hr/>
                  <h4 class="m-t-0 header-title">{{trans('words.footer_icon')}} 
                  <small id="emailHelp" class="form-text text-muted pt-1">Leave empty if you don't want to display the social icon.</small>
                  </h4>

                  <br/>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Facebook URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_fb_link" value="{{ isset($settings->footer_fb_link) ? stripslashes($settings->footer_fb_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Twitter URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_twitter_link" value="{{ isset($settings->footer_twitter_link) ? stripslashes($settings->footer_twitter_link) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Instagram URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_instagram_link" value="{{ isset($settings->footer_instagram_link) ? stripslashes($settings->footer_instagram_link) : null }}" class="form-control">
                    </div>
                  </div>

                  <hr/>
                  <h4 class="m-t-0 header-title">{{trans('words.apps_text')}} <small id="emailHelp" class="form-text text-muted pt-1">Leave empty if you don't want to display the app download button.</small></h4>
                  
                  
                  
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Google Play URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_google_play_link" value="{{ isset($settings->footer_google_play_link) ? stripslashes($settings->footer_google_play_link) : null }}" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Apple Store URL</label>
                    <div class="col-sm-8">
                      <input type="text" name="footer_apple_store_link" value="{{ isset($settings->footer_apple_store_link) ? stripslashes($settings->footer_apple_store_link) : null }}" class="form-control">
                    </div>
                  </div>


                </div>
                
            </div>      

                  
                  <hr/>
                  <h4 class="m-t-0 mb-4 header-title">{{trans('words.gdpr_cookie_consent')}}</h4>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.gdpr_cookie_consent')}} </label>
                      <div class="col-sm-8">
                            <select class="form-control select2" name="gdpr_cookie_on_off">                               
                                 
                                <option value="1" @if($settings->gdpr_cookie_on_off=="1") selected @endif>{{trans('words.active')}}</option>
                                <option value="0" @if($settings->gdpr_cookie_on_off=="0") selected @endif>{{trans('words.inactive')}}</option>
                                 
                            </select>
                      </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.gdpr_cookie_title')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="gdpr_cookie_title" value="{{ isset($settings->gdpr_cookie_title) ? stripslashes($settings->gdpr_cookie_title) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.gdpr_cookie_text')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="gdpr_cookie_text" value="{{ isset($settings->gdpr_cookie_text) ? stripslashes($settings->gdpr_cookie_text) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.gdpr_cookie_url')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="gdpr_cookie_url" value="{{ isset($settings->gdpr_cookie_url) ? stripslashes($settings->gdpr_cookie_url) : null }}" class="form-control">
                    </div>
                  </div>

                  <hr/>
                  <h4 class="m-t-0 mb-4 header-title">Envato Buyer Details</h4>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Envato Username</label>
                    <div class="col-sm-8">
                      <input type="text" name="envato_buyer_name" value="{{ isset($settings->envato_buyer_name) ? stripslashes($settings->envato_buyer_name) : null }}" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Buyer Purchase Code</label>
                    <div class="col-sm-8">
                      <input type="text" name="envato_purchase_code" value="{{ isset($settings->envato_purchase_code) ? stripslashes($settings->envato_purchase_code) : null }}" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="offset-sm-3 col-sm-9 pl-1">
                      <button type="submit" class="btn btn-primary waves-effect waves-light"> {{trans('words.save_settings')}} </button>                      
                    </div>
                  </div>
                {!! Form::close() !!} 
              </div>
            </div>            
          </div>              
        </div>
      </div>
      @include("admin.copyright") 
    </div> 
 
 

<script type="text/javascript">
     
     
// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {

    //alert(requestingField);

    var elfinderUrl = "{{ URL::to('/') }}/";

    if(requestingField=="site_logo")
    {
      var target_preview = $('#site_logo_holder');
      target_preview.html('');
      target_preview.append(
              $('<img>').css('height', '5rem').attr('src', elfinderUrl + filePath.replace(/\\/g,"/"))
            );
      target_preview.trigger('change');
    }

    if(requestingField=="site_favicon")
    {
      var target_preview = $('#site_favicon_holder');
      target_preview.html('');
      target_preview.append(
              $('<img>').css('height', '5rem').attr('src', elfinderUrl + filePath.replace(/\\/g,"/"))
            );
      target_preview.trigger('change');
    }
 
    //$('#' + requestingField).val(filePath.split('\\').pop()).trigger('change'); //For only filename
    $('#' + requestingField).val(filePath.replace(/\\/g,"/")).trigger('change');
 
}
 
 </script>

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

  @if (count($errors) > 0)
                  
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: '<p>@foreach ($errors->all() as $error) {{$error}}<br/> @endforeach</p>',
            showConfirmButton: true,
            confirmButtonColor: '#10c469',
            background:"#1a2234",
            color:"#fff"
           }) 
  @endif

  </script>

@endsection