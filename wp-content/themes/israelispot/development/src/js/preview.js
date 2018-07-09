'use strict';
jQuery(function ($) {
   var formAttractionNumber;
   var formActivityNumber;
   var direction;
   if($('body').hasClass('rtl')){
       direction = true;
       formAttractionNumber = 9;
       formActivityNumber = 11;
   } else {
       direction = false;
       formAttractionNumber = 8;
       formActivityNumber = 10;
   }

   $('#preview-attraction').click(function (e) {
       e.preventDefault();
       localStorage.clear();

       var previewParams = [];
       //title
       previewParams.push('?title='+$('#input_'+formAttractionNumber+'_1').val());

       //categories
       var previewCategories = $('#input_'+formAttractionNumber+'_6 input[type="checkbox"]:checked');
       var previewCategoriesArr = [];
       previewCategories.each(function (el) {
           previewCategoriesArr.push(parseInt(this.value));
       });
       previewParams.push('&categories='+JSON.stringify(previewCategoriesArr));
       previewParams.push('&region='+$('#input_'+formAttractionNumber+'_8').val());
       previewParams.push('&phone='+$('#input_'+formAttractionNumber+'_10').val());
       previewParams.push('&phone2='+$('#input_'+formAttractionNumber+'_11').val());
       previewParams.push('&webUrl='+$('#input_'+formAttractionNumber+'_13').val());
       previewParams.push('&credit='+$('#input_'+formAttractionNumber+'_16').val());
       previewParams.push('&map='+$('#input_'+formAttractionNumber+'_17').val());
       previewParams.push('&hours='+$('#input_'+formAttractionNumber+'_14').val().replace(/\r?\n/g, '<br />'));
       previewParams.push('&excerpt='+$('#input_'+formAttractionNumber+'_15').val().replace(/\r?\n/g, '<br />'));

       var html = tinymce.activeEditor.getContent();
       var data = { html: html };
       var json = JSON.stringify(data);

       localStorage.setItem("previewContent", json);

       previewParams = previewParams.join('');

       var previewUrl = $(this)[0].href;

       window.gfMultiFileUploader.uploaders['gform_multifile_upload_'+formAttractionNumber+'_4'].files.map(function (el, index) {
           var multiReader = new FileReader();
           multiReader.onload = function(){
               var dataURL = multiReader.result;
               localStorage.setItem("previewGallery_"+index, dataURL);
           };
           multiReader.readAsDataURL(el.getNative());
           localStorage.setItem("previewGalleryCount", index);

       });

       window.open(previewUrl+previewParams,'_blank');

   });

   $('#preview').click(function (e) {
       e.preventDefault();
       localStorage.clear();
       //image
       var bannerImage = $('#input_'+formActivityNumber+'_3');

       var reader = new FileReader();
       reader.onload = function(){
           var dataURL = reader.result;
           localStorage.setItem("previewImage", dataURL);
       };
       reader.readAsDataURL(bannerImage[0].files[0]);

       var previewParams = [];
       //title
       previewParams.push('?title='+$('#input_'+formActivityNumber+'_1').val());
       previewParams.push('&price='+$('#input_'+formActivityNumber+'_5').val());
       previewParams.push('&oldPrice='+$('#input_'+formActivityNumber+'_6').val());
       previewParams.push('&childPrice='+$('#input_'+formActivityNumber+'_7').val());
       previewParams.push('&oldChildPrice='+$('#input_'+formActivityNumber+'_8').val());
       previewParams.push('&priceDes='+$('#input_'+formActivityNumber+'_14').val().replace(/\r?\n/g, '<br />'));
       previewParams.push('&discount='+$('#input_'+formActivityNumber+'_9').val());
       previewParams.push('&discountDes='+$('#input_'+formActivityNumber+'_10').val().replace(/\r?\n/g, '<br />'));
       previewParams.push('&hours='+$('#input_'+formActivityNumber+'_11').val().replace(/\r?\n/g, '<br />'));
       previewParams.push('&excerpt='+$('#input_'+formActivityNumber+'_13').val().replace(/\r?\n/g, '<br />'));
       previewParams.push('&credit='+$('#input_'+formActivityNumber+'_15').val());

       var html = tinymce.activeEditor.getContent();
       var data = { html: html };
       var json = JSON.stringify(data);

       localStorage.setItem("previewContent", json);

       previewParams = previewParams.join('');

       var previewUrl = $(this)[0].href;


       window.gfMultiFileUploader.uploaders['gform_multifile_upload_'+formActivityNumber+'_12'].files.map(function (el, index) {
           var multiReader = new FileReader();
           multiReader.onload = function(){
               var dataURL = multiReader.result;
               localStorage.setItem("previewGallery_"+index, dataURL);
           };
           multiReader.readAsDataURL(el.getNative());
           localStorage.setItem("previewGalleryCount", index);
       });

       window.open(previewUrl+previewParams,'_blank');
    });



   if($('#preview-page').length !== 0){

       if($('#image_preview').length !==0 ){
           $('#image_preview').css({
               background: 'url('+localStorage.getItem('previewImage')+') center / cover'
           });

           for (var i = 0; i <= parseInt(localStorage.getItem('previewGalleryCount')) && i < 9 ; i++) {
               $('#preview_gallery').append('<li class="activity-gallery__item">'+
                                               '<a href=""' +
                                               'style="background: url('+localStorage.getItem('previewGallery_'+i)+') center / cover"' +
                                               'data-fancybox="gallery"' +
                                               'class="activity-gallery__link"' +
                                               '</a>' +
                                            '</li>');
           }


       } else {

           for (var i = 0; i <= parseInt(localStorage.getItem('previewGalleryCount')) && i < 9 ; i++) {
               $('#preview_gallery').append('<img src="'+localStorage.getItem('previewGallery_'+i)+'" alt="">');

           }

           $('#preview_gallery').slick({
               infinite: true,
               speed: 300,
               slidesToShow: 1,
               centerMode: true,
               variableWidth: true,
               rtl: direction
           });

       }

       $('#preview_content').append(JSON.parse(localStorage.getItem('previewContent')).html);
   }


   $(document).on("gform_confirmation_loaded", function (e, form_id) {
       $('.preview').remove();
   });
});