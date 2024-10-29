(function( $ ) { 

  var tema = $('#tema').attr("data-value"); 
  $('#tema-'+tema).removeClass('blp-hide');
  $('#show-tema #tema-'+tema).removeClass('blp-hide');
  $('#tema option[value="'+tema+'"]').prop('selected', 'true');    
  if(tema == 3){
    $('tr#tit_login, tr#desc_login').css({'display':'none'});
  } else {
    $('tr#tit_login, tr#desc_login').css({'display':''});
  }
  
  $(document).change('#tema', function(){
    var t = $('#tema option:selected').val();
      $('#tema-1, #tema-2, #tema-3').addClass('blp-hide');
      $('#show-tema #tema-1, #show-tema #tema-2, #show-tema #tema-3').addClass('blp-hide');
      $('#show-tema #tema-'+t).removeClass('blp-hide');
      $('#tema-'+t).removeClass('blp-hide');
      if(t == 3){
        $('tr#tit_login, tr#desc_login').css({'display':'none'});
      } else {
        $('tr#tit_login, tr#desc_login').css({'display':''});
      }
  });
   				  			  
  var fonte = $('#fonte').attr('data-value');
  $('#fonte option[value="'+fonte+'"]').prop('selected', 'true');  
  $('#bg_color, #text_color').each(function(){
    $(this).wpColorPicker();
  });
  
  
  var file_frame, media_frame;
        
    $( 'a#btnUp, input#btnUp' ).on('click', function() {   
      var tipo = $(this).attr('data-id');
      $("#tipo-img").val(tipo);
  		if ( file_frame ) {
  			file_frame.open();
  			return;
  		} 

		file_frame = wp.media.frames.file_frame = wp.media({
			title: "Select Media",
			button: {
				text: 'Insert media',
			},
			multiple: false 
		});

		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();
      var tipo = $("#tipo-img").val();
      
      if(tipo == 'img_box_login'){
       	$( 'input#img_box' ).val(attachment.url);
        $( 'img#img_box_p' ).attr('src', attachment.url);
      } else if(tipo == 'img_div_login'){
       	$( 'input#img_div' ).val(attachment.url);
        $( 'img#img_div_p' ).attr('src', attachment.url);
      } else if(tipo == 'btn_fundo_box'){
       	$( 'input#fundo_box' ).val(attachment.url);
        $( 'img#fundo_box_p' ).attr('src', attachment.url);
      } 
      console.log(tipo+' - '+attachment.url);
		});
    
		file_frame.open();

  });

  
  $( document ).on('click', 'input#btn_add_media', function(e) { 
    if ( media_frame ) {
			media_frame.open();
			return;
		}
    e.preventDefault();
  
        media_frame = wp.media({
            title: 'Select Gallery Images',
            library : {
                type : 'image'
            },
            multiple: true
        });

        media_frame.states.add([
            new wp.media.controller.Library({
                id:         'post-gallery',
                title:      '',
                priority:   20,
                toolbar:    'main-gallery',
                filterable: 'uploaded',
                library:    wp.media.query( media_frame.options.library ),
                multiple:   media_frame.options.multiple ? 'reset' : false,
                editable:   true,
                allowLocalEdits: true,
                displaySettings: true,
                displayUserSettings: true
            })
        ]);
         
        media_frame.on( 'select', function() {

            var ids = [], attachments_arr = [], imgs = '';

            attachments_arr = media_frame.state().get('selection').toJSON();
            $(attachments_arr).each(function(e){
                sep = ( e != ( attachments_arr.length - 1 ) ) ? ',' : '';
                ids += $(this)[0].id + sep;               
            });
            
            var iids = ids.split(',');
            ids = $.makeArray(iids);
            
            if(attachments_arr.length > 0){
              for(j=0; j < attachments_arr.length; j++){
                imgs += '<li><a class="delIMG" id="del_IMG" data-id="'+ids[j]+'">x</a><img class="box-preview-image" id="'+ids[j]+'" src="'+attachments_arr[j]['sizes']['thumbnail']['url']+'" style="width:100px"></li>';              
              } 
              console.log(imgs);
              $('#gallery ul').append(imgs);
              var new_IDS = '';
              var count_img = $(document).find("#gallery ul li img").length;
              
              $('#gallery ul li img').each(function(e){ 
                sep = ( e != ( count_img - 1 ) ) ? ',' : '';             
                new_IDS += $(this)[0].id + sep;
              }) ;
              $("input#gallery").val(new_IDS);
            }
        });
		media_frame.open();
  });		
  // DELETE IMG
  $(document).on('click', '#del_IMG',function(){
    var ID  = $(this).attr('data-id');
    var ARRAY = $('#gallery').val();
    var arr = ARRAY.split(',');
    var IDS = $.makeArray(arr);
    IDS.splice( $.inArray(ID, IDS), 1 );
    if (confirm('Are you sure you want to remove this image?')) {
      $(this).parent().remove();
    }
    $('input#gallery').val(IDS);
  });
  $(document).on('click', '#del_login',function(){
    var base_plugin = $("input#base_plugin").val();
    if (confirm('Are you sure you want to remove this image?')) {
      $("input#img_box").val('');
      $("#img_box_p").attr('src','');
    }
  });
  $(document).on('click', '#del_central',function(){
    var base_plugin = $("input#base_plugin").val();
    if (confirm('Are you sure you want to remove this image?')) {
      $("input#img_div").val('');
      $("#img_div_p").attr('src','');    
    }
  });
  $(document).on('click', '#del_bg',function(){
    var base_plugin = $("input#base_plugin").val();
    if (confirm('Are you sure you want to remove this image?')) {
      $("input#fundo_box").val('');
      $("#fundo_box_p").attr('src','');
    }
  });
})(jQuery); 