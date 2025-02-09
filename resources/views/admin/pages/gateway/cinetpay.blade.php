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
                 <div class="col-sm-6">
                      <a href="{{ URL::to('admin/payment_gateway') }}"><h4 class="header-title m-t-0 m-b-30 text-primary pull-left" style="font-size: 20px;"><i class="fa fa-arrow-left"></i> {{trans('words.back')}}</h4></a>
                 </div>                  
                 <div class="col-sm-6">
                    &nbsp;
                 </div>                  
                </div>  

                 

                 {!! Form::open(array('url' => array('admin/payment_gateway/cinetpay'),'class'=>'form-horizontal','name'=>'settings_form','id'=>'settings_form','role'=>'form','enctype' => 'multipart/form-data')) !!}  
                  
                  <input type="hidden" name="id" value="{{ isset($post_info->id) ? $post_info->id : null }}">
  
                   
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.title')}}*</label>
                    <div class="col-sm-8">
                      <input type="text" name="gateway_name" value="{{ isset($post_info->gateway_name) ? stripslashes($post_info->gateway_name) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.short_info')}}</label>
                    <div class="col-sm-8">
                      <input type="text" name="gateway_short_info" value="{{ isset($post_info->gateway_short_info) ? stripslashes($post_info->gateway_short_info) : null }}" class="form-control">
                    </div>
                  </div>

                  <hr/>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.payment_mode')}}</label>
                    <div class="col-sm-8">
                      <select class="form-control" name="mode">                                
                                <option value="sandbox" @if(isset($gateway_info) AND $gateway_info->mode=="sandbox") selected @endif>Sandbox</option>
                                <option value="live" @if(isset($gateway_info) AND $gateway_info->mode=="live") selected @endif>Live</option>
                                              
                            </select>
                    </div>
                  </div>
                   
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Cinetpay API Key</label>
                    <div class="col-sm-8">
                      <input type="text" name="cinetpay_api_key" value="{{ isset($gateway_info->cinetpay_api_key) ? stripslashes($gateway_info->cinetpay_api_key) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Cinetpay Secret Key</label>
                    <div class="col-sm-8">
                      <input type="text" name="cinetpay_secret_key" value="{{ isset($gateway_info->cinetpay_secret_key) ? stripslashes($gateway_info->cinetpay_secret_key) : null }}" class="form-control">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Cinetpay Site ID</label>
                    <div class="col-sm-8">
                      <input type="text" name="cinetpay_site_id" value="{{ isset($gateway_info->cinetpay_site_id) ? stripslashes($gateway_info->cinetpay_site_id) : null }}" class="form-control">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label">{{trans('words.status')}}</label>
                      <div class="col-sm-8">
                            <select class="form-control" name="status">                               
                                <option value="1" @if(isset($post_info->status) AND $post_info->status==1) selected @endif>{{trans('words.active')}}</option>
                                <option value="0" @if(isset($post_info->status) AND $post_info->status==0) selected @endif>{{trans('words.inactive')}}</option>                            
                            </select>
                      </div>
                  </div>
                  <div class="form-group">
                    <div class="offset-sm-3 col-sm-9 pl-1">
                      <button type="submit" class="btn btn-primary waves-effect waves-light"> {{trans('words.save')}}</button>                      
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