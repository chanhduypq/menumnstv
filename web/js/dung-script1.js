/**
 * when users purchase
 *    hide options that are not selected or purchased,
 *    lock options that have been selected
 */
function setOptionsAtConfirmcart(){
    $(".change-icon-save-mns:not(:checked)").parent().parent().parent().hide();
    $(".change-icon-save-mns:disabled").parent().parent().parent().hide();
    $(".change-icon-save-mns:checked").attr("disabled","disabled");
}
function numberWithCommas(x) {
    var parts = x.toString().split(",");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(",");
}
/**
 * khi user click vào button [thanh toán] tại popup-cart thi gọi đến function này
 */
function thanh_toan(){
    $.ajax({
        async: false,
        cache: false,                                
        url: '/confirmcart?button_thanh_toan_at_popup_cart_is_clicked=1',
        success: function(data, textStatus, jqXHR) {
            window.location="/confirmcart";
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
    });
}

function load_landscape(){
      $.ajax({ 
        async: false,
        cache: false,                                
        url: '/?resolution=1&q='+$("#frm-search").find("input").eq(0).val(),
        success: function(data, textStatus, jqXHR) {
            div=document.createElement("div");
            $(div).html(data);
            $("#wrapper-home .v2-detail").html($(div).find(".v2-detail").html());
            $("#wrapper-home #center-home").html($(div).find("#center-home").html());
            $('#frm-search').find('input[name="resolution"]').eq(0).val('1');
            
            $("#content-slider").lightSlider({
                loop: true,
                keyPress: true
            });
            $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
                effect: 'fade',
                testMode: true,
                onChange: function(evt){
                    load_portrait();
                    return true;
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
      });
  }
  
  function load_portrait(){
      $.ajax({ 
        async: false,
        cache: false,                                
        url: '/?resolution=2&q='+$("#frm-search").find("input").eq(0).val(),
        success: function(data, textStatus, jqXHR) {
            div=document.createElement("div");
            $(div).html(data);
            $("#wrapper-home .v2-detail").html($(div).find(".v2-detail").html());
            $("#wrapper-home #center-home").html($(div).find("#center-home").html());
            $('#frm-search').find('input[name="resolution"]').eq(0).val('2');
            
            $("#content-slider").lightSlider({
                loop: true,
                keyPress: true
            });
            $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
                effect: 'fade',
                testMode: true,
                onChange: function(evt){
                    load_landscape();
                    return true;
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
      });
  }
  function changeValueCartAtDetail(id,this_element) {
      prices=0;
      service_ids="";
      if($(".css-checkbox.change-icon-detail:checked").length>0){
          service_ids+="&service_ids=";
          services=$(".css-checkbox.change-icon-detail:checked");
          for(i=0;i<services.length;i++){
              service_ids+=$(services[i]).next().next().val()+",";
              temp=$(services[i]).parents(".left").next().find("span").eq(1).html();
              
              temp=parseFloat(temp.substr(1));
              prices+=temp;
          }
          
      }
      $.ajax({ 
        async: false,
        cache: false,                                
        url: '/ordersession?tid='+id+service_ids,
        success: function(data, textStatus, jqXHR) {
            
            //
            if($("#number-cart-template").length>0){
                number=document.getElementById("number-cart-template").innerHTML;
                number=parseInt(number);
                number++;
                document.getElementById("number-cart-template").innerHTML=number;
            }
            
            if($(".css-checkbox.change-icon-detail:checked").length>0&&$("#number-cart-option").length>0){
                number=document.getElementById("number-cart-option").innerHTML;
                number=parseInt(number);
                number+=$(".css-checkbox.change-icon-detail:checked").length;
                document.getElementById("number-cart-option").innerHTML=number;
            }
            
            $("#content-popup-shop-cart").html(data);
            
            
            var eMouseOver = false;
      
              $(".popup-shop-cart").show();
              $(".popup-shop-cart").hover(function(){
               $('.popup-shop-cart').addClass('_s');
              });

              $(".popup-shop-cart").mouseleave(function(){
                $('.popup-shop-cart').removeClass('_s');
                });

              var intervals = setInterval(function(){
                if(!$('.popup-shop-cart').hasClass('_s')){
                $(".popup-shop-cart").fadeOut();
                clearInterval(intervals);

              }

              }, 4000);

//              intervals;
              return false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
    });

  $(this_element).html('<img src="/images/v2-btn-update-cart.png" class="img-responsive">');
  $(this_element).attr('onclick','updateValueCartAtDetail('+id+',this)');
  
  if($.trim($(".v2-price-detail-l").find("span").eq(1).html())!="FREE") 
    subtotal_price=prices+parseFloat($(".v2-price-detail-l").find("span").eq(1).html().substr(1));
    else 
        subtotal_price=prices;
    
    $(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").html("$"+subtotal_price.toFixed(2));
  
  $("#id").val(0); 
  }
  
  function updateValueCartAtDetail(id,this_element) {
      prices=0;
      
      $.ajax({async: false,cache: false,url: "/deleteordersession?out_id=" + id, success: function(result){   
        result=$.parseJSON(result);    
        document.getElementById("number-cart-option").innerHTML = result.count_cart_option;
        document.getElementById("number-cart-template").innerHTML = result.count_cart_template;
        
       
    }});
      
      service_ids="";
      if($(".css-checkbox.change-icon-detail:checked").length>0){
          service_ids+="&service_ids=";
          services=$(".css-checkbox.change-icon-detail:checked");
          for(i=0;i<services.length;i++){
              service_ids+=$(services[i]).next().next().val()+",";
              temp=$(services[i]).parents(".left").next().find("span").eq(1).html();
              temp=parseFloat(temp.substr(1));
              prices+=temp;
          }
          
      }
      $.ajax({ 
        async: false,
        cache: false,                                
        url: '/ordersession?tid='+id+service_ids,
        success: function(data, textStatus, jqXHR) {
            
            if($("#number-cart-template").length>0){
                number=document.getElementById("number-cart-template").innerHTML;
                number=parseInt(number);
                number++;
                document.getElementById("number-cart-template").innerHTML=number;
            }
            
            if($(".css-checkbox.change-icon-detail:checked").length>0&&$("#number-cart-option").length>0){
                number=document.getElementById("number-cart-option").innerHTML;
                number=parseInt(number);
                number+=$(".css-checkbox.change-icon-detail:checked").length;
                document.getElementById("number-cart-option").innerHTML=number;
            }
            
                       
            $("#content-popup-shop-cart").html(data);
            
            
            var eMouseOver = false;
      
              $(".popup-shop-cart").show();
              $(".popup-shop-cart").hover(function(){
               $('.popup-shop-cart').addClass('_s');
              });

              $(".popup-shop-cart").mouseleave(function(){
                $('.popup-shop-cart').removeClass('_s');
                });

              var intervals = setInterval(function(){
                if(!$('.popup-shop-cart').hasClass('_s')){
                $(".popup-shop-cart").fadeOut();
                clearInterval(intervals);

              }

              }, 4000);

//              intervals;
              return false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
    });
if($.trim($(".v2-price-detail-l").find("span").eq(1).html())!="FREE") 
  subtotal_price=prices+parseFloat($(".v2-price-detail-l").find("span").eq(1).html().substr(1));
    
    else 
        subtotal_price=prices;
    $(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").html("$"+subtotal_price.toFixed(2));
  
  $("#id").val(0); 
  }
//thay đổi giá trị của popup cart
 function changeValueCart(id,this_element) {
     $.ajax({ 
        async: false,
        cache: false,                                
        url: '/ordersession?tid='+id,
        success: function(data, textStatus, jqXHR) {
            //đổi icon
            html='<div class="v2-service-mns'+($(this_element).parent().attr('class')=='add-to-cart-p'?'-portrait':'')+'"><a href="javascript:void(0);" class="mns-payment"><img src="/images/mns-payment.png" class="img-responsive">'+($(this_element).parent().parent().parent().parent().parent().hasClass('hover-cart-portrait-detail')||$(this_element).parent().parent().parent().parent().parent().hasClass('hover-cart-portrait')?'<i>(payment)</i>':'')+'</a>';
            services=getServices();
            for(i=0;i<services.length;i++){
                data_tipso='<div>'+
                                '<strong>'+services[i].service_name+'</strong>'+
                                '<p>'+services[i].description+'</p>'+                                                            
                                '<strong>'+lang_cost+'&nbsp;'+services[i].price+'</strong>'+
                            '</div>';
                html+='<a class="bottom tipso_style" data-tipso="'+data_tipso+'" onclick="addOption('+id+','+services[i].service_id+',\'/'+services[i].checked_icon_path+'\',\'/'+services[i].uncheck_icon_path+'\',this);" href="javascript:void(0);" class="mns-service">';
                html+='<img src="/'+services[i].uncheck_icon_path+'" class="img-responsive">';
                if($(this_element).parent().parent().parent().parent().parent().hasClass('hover-cart-portrait-detail')||$(this_element).parent().parent().parent().parent().parent().hasClass('hover-cart-portrait')){
                    html+='<i>('+services[i].service_name+')</i>';
                }
                html+='</a>';
            }
            html+='</div>';
//            $(this_element).parent().html(html).attr("class","dowload-template");
            hiddens=$(".hidden_"+id);
            for(i=0;i<hiddens.length;i++){
                //landscape
                $(hiddens[i]).parent().find("div.description-price >div").eq(1).html(html).attr("class","dowload-template");
                //portrait
                $(hiddens[i]).parent().find("div.v2-description-price-p >div").eq(2).html(html).attr("class","dowload-template-portrait");
                $(hiddens[i]).parent().find("div.v2-description-price-p >div").eq(1).remove();
            }
            jQuery('.bottom').tipso({
                position: 'bottom',
                background: 'rgba(0,0,0,0.75)',
                useTitle: false,
            }).css('cursor','pointer'); 
            //
            if($("#number-cart-template").length>0){
                number=document.getElementById("number-cart-template").innerHTML;
                number=parseInt(number);
                number++;
                document.getElementById("number-cart-template").innerHTML=number;
            }
            
            $("#content-popup-shop-cart").html(data);
            
            
            var eMouseOver = false;
      
              $(".popup-shop-cart").show();
              $(".popup-shop-cart").hover(function(){
               $('.popup-shop-cart').addClass('_s');
              });

              $(".popup-shop-cart").mouseleave(function(){
                $('.popup-shop-cart').removeClass('_s');
                });

              var intervals = setInterval(function(){
                if(!$('.popup-shop-cart').hasClass('_s')){
                $(".popup-shop-cart").fadeOut();
                clearInterval(intervals);

              }

              }, 4000);

//              intervals;
              return false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
    });

  
  $("#id").val(0); 
  }
  
  //thay đổi giá trị của popup cart
 function changeValueCart1(id,this_element) {
     $.ajax({ 
        async: false,
        cache: false,                                
        url: '/ordersession?tid='+id,
        success: function(data, textStatus, jqXHR) {
            //đổi icon
            html='<div class="v2-service-mns"><a href="javascript:void(0);"><img src="/images/mns-payment.png" class="img-responsive"></a>';
            services=getServices();
            for(i=0;i<services.length;i++){
                data_tipso='<div>'+
                                '<strong>'+services[i].service_name+'</strong>'+
                                '<p>'+services[i].description+'</p>'+                                                            
                                '<strong>'+lang_cost+'&nbsp;'+services[i].price+'</strong>'+
                            '</div>';
                html+='<a class="bottom tipso_style" data-tipso="'+data_tipso+'" onclick="addOption('+id+','+services[i].service_id+',\'/'+services[i].checked_icon_path+'\',\'/'+services[i].uncheck_icon_path+'\',this);" href="javascript:void(0);">';
                html+='<img src="/'+services[i].uncheck_icon_path+'" class="img-responsive">';
                html+='</a>';
            }
            html+='</div>';

            hiddens=$(".hidden_"+id);
            for(i=0;i<hiddens.length;i++){
                $(hiddens[i]).parent().find("div.v2-btn-plaza-l-list").eq(0).html(html);
                $(hiddens[i]).parent().find("div.v2-btn-plaza-p-list").eq(0).html(html);
            }
            jQuery('.bottom').tipso({
                position: 'bottom',
                background: 'rgba(0,0,0,0.75)',
                useTitle: false,
            }).css('cursor','pointer'); 
            //
            if($("#number-cart-template").length>0){
                number=document.getElementById("number-cart-template").innerHTML;
                number=parseInt(number);
                number++;
                document.getElementById("number-cart-template").innerHTML=number;
            }
            
            $("#content-popup-shop-cart").html(data);
            
            
            var eMouseOver = false;
      
              $(".popup-shop-cart").show();
              $(".popup-shop-cart").hover(function(){
               $('.popup-shop-cart').addClass('_s');
              });

              $(".popup-shop-cart").mouseleave(function(){
                $('.popup-shop-cart').removeClass('_s');
                });

              var intervals = setInterval(function(){
                if(!$('.popup-shop-cart').hasClass('_s')){
                $(".popup-shop-cart").fadeOut();
                clearInterval(intervals);

              }

              }, 4000);

//              intervals;
              return false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
    });

  
  $("#id").val(0); 
  }
  
  function addOption(out_id,service_id,icon_check,icon_uncheck,this_element){

    if($(this_element).find("img").eq(0).attr("src")==icon_check){
        action="delete";
    }
    else{
        action="add";
    }
    //
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/ordersessionoption?out_id='+out_id+'&service_id='+service_id+'&action='+action,
        success: function(data, textStatus, jqXHR) {
            if($("#number-cart-option").length>0){
                number=document.getElementById("number-cart-option").innerHTML;
                number=parseInt(number);
                if(action=="delete"){
                    number--;    
                }
                else{
                    number++;
                }
                document.getElementById("number-cart-option").innerHTML=number;
            }
            hiddens=$(".hidden_"+out_id);
            

            if(action=="add"){
                for(i=0;i<hiddens.length;i++){
                    $(hiddens[i]).parent().find("div.dowload-template").find("div").eq(0).find("a").eq(service_id-1).addClass("check-mns-service");
                    $(hiddens[i]).parent().find("div.dowload-template").find("div").eq(0).find("a").eq(service_id-1).find("img").eq(0).attr("src",icon_check);
                    
                    $(hiddens[i]).parent().find("div.v2-service-mns").eq(0).find("a").eq(service_id-1).addClass("check-mns-service");
                    $(hiddens[i]).parent().find("div.v2-service-mns").eq(0).find("a").eq(service_id-1).find("img").eq(0).attr("src",icon_check);
                    
                    $(hiddens[i]).parent().find("div.v2-service-mns-portrait").eq(0).find("a").eq(service_id-1).addClass("check-mns-service");
                    $(hiddens[i]).parent().find("div.v2-service-mns-portrait").eq(0).find("a").eq(service_id-1).find("img").eq(0).attr("src",icon_check);
                }
            }
            else{
                for(i=0;i<hiddens.length;i++){
                    $(hiddens[i]).parent().find("div.dowload-template").find("div").eq(0).find("a").eq(service_id-1).removeClass("check-mns-service");
                    $(hiddens[i]).parent().find("div.dowload-template").find("div").eq(0).find("a").eq(service_id-1).find("img").eq(0).attr("src",icon_uncheck);
                    
                    $(hiddens[i]).parent().find("div.v2-service-mns").eq(0).find("a").eq(service_id-1).removeClass("check-mns-service");
                    $(hiddens[i]).parent().find("div.v2-service-mns").eq(0).find("a").eq(service_id-1).find("img").eq(0).attr("src",icon_uncheck);
                    
                    $(hiddens[i]).parent().find("div.v2-service-mns-portrait").eq(0).find("a").eq(service_id-1).removeClass("check-mns-service");
                    $(hiddens[i]).parent().find("div.v2-service-mns-portrait").eq(0).find("a").eq(service_id-1).find("img").eq(0).attr("src",icon_uncheck);
                }  
            }
            /**
             * hiển thị pop-up cart
             */
            //Trường hợp remove hết item
            if($("#number-cart-option").length>0&&$("#number-cart-template").length>0&&document.getElementById("number-cart-option").innerHTML == 0&&document.getElementById("number-cart-template").innerHTML==0) {
                $('.popup-shop-cart').fadeOut();
               return;

            }
            //
            $("#content-popup-shop-cart").html(data);
            
            var eMouseOver = false;
      
              $(".popup-shop-cart").show();
              $(".popup-shop-cart").hover(function(){
               $('.popup-shop-cart').addClass('_s');
              });

              $(".popup-shop-cart").mouseleave(function(){
                $('.popup-shop-cart').removeClass('_s');
                });

              var intervals = setInterval(function(){
                if(!$('.popup-shop-cart').hasClass('_s')){
                $(".popup-shop-cart").fadeOut();
                clearInterval(intervals);

              }

              }, 4000);

//              intervals;
              return false;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
    });
    
}
  
  function getServices(){
    services='';
    $.ajax({ 
        async: false,
        cache: false,                                
        url: '/getservices',
        success: function(data, textStatus, jqXHR) {
            services=$.parseJSON($.trim(data));
        }
    });
    return services;
}

function changeValueVideoPopup(url) {
       
        var vid = document.getElementById("video_tag1");
        if(vid==null){
            return;
        }
   	vid.src = url;
   	vid.load();
   }
   
   function changeMainVideo(url) {
		var vid = document.getElementById("main_video_tag");
		vid.src = url;
		vid.load();
	}
// xóa sản phẩm trong phần giỏ hàng
   function removeOrderSession(out_id) {
    $.ajax({async: false,cache: false,url: "/deleteordersession?out_id=" + out_id, success: function(result){   
        result=$.parseJSON(result);    
        document.getElementById("number-cart-option").innerHTML = result.count_cart_option;
        document.getElementById("number-cart-template").innerHTML = result.count_cart_template;
        
        if(result.count_cart_option=='0'&&result.count_cart_template=='0'&&$(".v2-border-box-cart").find(".v2-cart-content").length==1){
            window.location="/plaza";
        }
    }});
    sum_all=$("#sum_all").val();
    sum_all=parseFloat(sum_all);
    sum=$("#"+out_id).find('input[name="sum"]').eq(0).val();
    sum=parseFloat(sum);
    sum_all-=sum; 
    
    
    sum_all=sum_all.toFixed(2);
    sum_all_show=sum_all.toString();
    if(sum_all_show.indexOf(".")!=-1){
        temp=sum_all_show.split(".");
        sum_all_show=numberWithCommas(temp[0])+"."+temp[1];
    }
    else{
        sum_all_show=numberWithCommas(sum_all_show);
    }
    
    $("#sum_all").prev().find(".v2-number-total-price").eq(0).find("span").eq(0).html(money_unit+sum_all_show);
    //
    $("#sum_all").val(sum_all);
    $("#total_price").val(sum_all);
    $("#"+out_id).remove();
    
  }
  
   //thay đổi giá trị của popup cart
 function removeValueCart(id) {
  /*
      * nếu đang đứng tai trang detail
      * mà remove 1 item trong popup-cart chính là template này
      * thi button [Mua hàng] phải hiển thị trở lại, và button [Dịch vụ] phải được ẩn đi
      */
     if($(".btn-oder-shop").length>0){//nếu đang đứng tai trang detail
         out_id=$(".div-check-box-name-function-detail").find('input[name="out_id"]').val();
         if(out_id==id){//nếu remove 1 item trong popup-cart chính là template này
             if($(".btn-oder-shop").find('a').length>0){
                 $(".btn-oder-shop").find('a').show();
                 $(".btn-service-shop").hide();
             }
         }
         
     }
     $.ajax({url: "/deleteordersession?out_id=" + id, success: function(result){
             
        result=$.parseJSON(result);    
        $("#number-cart-option").html(result.count_cart_option);
        $("#number-cart-template").html(result.count_cart_template);
        template=result.template;        
        
        hiddens=$(".hidden_"+id);
        for(i=0;i<hiddens.length;i++){
            $(hiddens[i]).parent().find("div.description-price >div").eq(1).html('<a onclick="changeValueCart('+id+',this);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/v2-add-cart.png" class="img-responsive"></a>').removeClass("dowload-template").addClass("add-to-cart");
            
            $(hiddens[i]).parent().find("div.v2-description-price-p >div").eq(1).remove();
            html=$(hiddens[i]).parent().find("div.v2-description-price-p").html();
            html+='<div class="v2-name-price v2-margin-home-p">'+
                                                '<div class="v2-right-n-p-p">'+
                                                    '<span'+(template.old_price!='&nbsp;&nbsp;'?' class="v2-cost-p"':'')+'>'+template.old_price+'</span>'+
                                                    '<span class="v2-cost-sale-p">'+template.new_price+'</span>'+
                                                '</div>'+
                                            '</div>'+
                   '<div class="add-to-cart-p"><a onclick="changeValueCart('+id+',this);" href="javascript:void(0);" class="add-to-cart1"><img src="/images/v2-add-cart.png" class="img-responsive"></i></a></div>';
            $(hiddens[i]).parent().find("div.v2-description-price-p").html(html);
            
            html=  '<a href="/detail/'+template.slug+'">'+
                        '<img src="/images/v2-icon-readmore.png" class="img-responsive">'+
                    '</a>'+
                    '<a onclick="changeValueCart1('+template.template_id+',this);" href="javascript:void(0);" class="add-to-cart1">'+
                    '<img src="/images/v2-add-cart.png" class="img-responsive">'+
                    '</a>';
            $(hiddens[i]).parent().find("div.v2-detail-list >div").eq(3).html(html).attr("class","v2-btn-plaza-l-list");
            
            html=  '<a href="/detail/'+template.slug+'">'+
                        '<img src="/images/v2-icon-readmore.png" class="img-responsive">'+
                    '</a>'+
                    '<a onclick="changeValueCart1('+template.template_id+',this);" href="javascript:void(0);" class="add-to-cart1">'+
                    '<img src="/images/v2-add-cart.png" class="img-responsive">'+
                    '</a>';
            $(hiddens[i]).parent().find("div.v2-padding-detail-p-list >div").eq(4).html(html).attr("class","v2-btn-plaza-p-list");
        }
        
        $(".v2-btn-add-cart-dl a").show();
        
        //Trường hợp remove hết item
        if(result.count_cart_option == 0&&result.count_cart_template==0) {
            $('.popup-shop-cart').fadeOut();
           return;
           
        }
        else{
            sum_all=$("#sum_all").val();
            sum_all=parseFloat(sum_all);
            sum=$("#"+id).parent().parent().parent().find('input[name="sum"]').eq(0).val();            
            sum=parseFloat(sum);
            sum_all-=parseFloat(sum);  
            
            if(money_unit=='$'){//nếu đang ở mode tiếng Anh
                sum_all=sum_all.toFixed(2);
                sum_all=sum_all.toString();
                if(sum_all.indexOf(".")!=-1){
                    temp=sum_all.split(".");
                    temp=numberWithCommas(temp[0])+","+temp[1];

                }
                else{
                    temp=numberWithCommas(sum_all)+",00";
                }
                $("#sum_all").prev().find("td").eq(1).html(money_unit+temp);
            }
            else{//nếu đang ở mode tiếng Việt
                $("#sum_all").prev().find("td").eq(1).html(numberWithCommas(sum_all)+" "+money_unit);
            }
                    
            $("#sum_all").val(sum_all);
            $("#"+id).parent().parent().parent().remove();
        }
        var eMouseOver = false;

    
    //Hiển thị popup
      $(".popup-shop-cart").show();
      $(".popup-shop-cart").hover(function(){
       $('.popup-shop-cart').addClass('_s');
      });
      
      $(".popup-shop-cart").mouseleave(function(){
        $('.popup-shop-cart').removeClass('_s');
        });

      var intervals = setInterval(function(){
        if(!$('.popup-shop-cart').hasClass('_s')){
        $(".popup-shop-cart").fadeOut();
        clearInterval(intervals);

      }
      
      }, 4000);
     
//      intervals;
      return false;
    }});
  
  $("#id").val(0); //Trả giá trị 0 lại cho tag [id]
  //=========================================================== QV ED
  }

  // 2016.06.12 ktd edit
  // lấy kích thước của thumb ngang để tính height cho thumb đứng
  function resizeThumbail(){
     
    var thumb = $('.hover-cart-landscape').first().actual( "width" );
    
    $('.portrait>a>img').css("height",thumb+"px");
    $('.img-col-2').css("height",thumb+"px");
  } 


$(document).ready(function(){
    
    //go to top
  $(window).scroll(function() {
    if($(this).scrollTop() > 50){
      $('#go-top').stop().animate({
        bottom: '50px'
      }, 500);
    }
    else{
      $('#go-top').stop().animate({
        bottom: '-50px'
      }, 500);
    }
  });
  $('#go-top').click(function() {
    $('html, body').stop().animate({
      scrollTop: 0
    }, 500, function() {
      $('#go-top').stop().animate({
        bottom: '-50px'
      }, 500);
    });
  });
// ------
    
    $( "body" ).delegate( ".add-to-cart1.remove_value_cart", "click", function() {

        id=$(this).attr('id');
        removeValueCart(id);        
        
        
    });
     // show menu mobile
    $('.show-mm').click(function(){
          $('#menumm').toggle('slow');
      });
  // hide menu mobile
    $('.close-mm').click(function(){
          $('#menumm').toggle('slow');
      });
  // show categories
    $('#menu-categories').click(function(){
          $('.v2-dashboard-land').toggle('slow');
      });
  // hide menu-mobile when click login
    $('.v2-login').click(function(){
      $('.v2-box-login').slideToggle("slow");
      $('#menumm').hide('slow');
    });
  // gọi hàm resize để thu nhỏ kích thước ảnh
//  resizeThumbail();
//  $(window).resize(function(){
//    resizeThumbail();
//  });
  // hiển thị form admin
  $('.admin').click(function(){
    $('.box_admin').slideToggle("slow");
  });
  $('.hide-box-admin').click(function(){
    $('.box_admin').hide("slow");
  });

  // hiển thị danh sách sắp xếp
  $('.arrange-mobile').click(function(){
    $('.icon-arrange').slideToggle("slow");
  });

    window.Object.defineProperty( Element.prototype, 'documentOffsetTop', {
    get: function () { 
        return this.offsetTop + ( this.offsetParent ? this.offsetParent.documentOffsetTop : 0 );
        }
    } );

    window.Object.defineProperty( Element.prototype, 'documentOffsetLeft', {
    get: function () { 
        return this.offsetLeft + ( this.offsetParent ? this.offsetParent.documentOffsetLeft : 0);
        }
    } );

  var oX = $('.panel-container1').width()+20;
  var oY = $('.panel-container1').height()+20;
  var ox1 = oX/2;
  var oy1 = oY/2;
  var pop_width = 300;
  // 2016.11.01 KTD EDIT
  // tính độ rộng của class margin-bottom-col
  //var w1 = $('.margin-bottom-col').width();
  
  $( "body" ).delegate( ".hover-cart-landscape", "mouseenter", function() {
        var vid = document.getElementById("landscape_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        $('.popup-video-template-landscape').css("display","block");
        // 2016.11.02 video sẽ chạy khi hover vào template
        $('.popup-video-template-landscape video')[0].play();
    var x = document.getElementById(this.id).documentOffsetLeft;
        var y = document.getElementById(this.id).documentOffsetTop;
        if(x < ox1){
      var w1 = document.getElementById(this.id).offsetWidth;
            $(".popup-video-template-landscape").css({"top": y+"px", "left": (x+w1+5)+"px"}); // Left the div + Width thẻ div 
        }
        else{
      var w1 = $('.popup-video-template-landscape video')[0].offsetWidth; 
            $(".popup-video-template-landscape").css({"top": y+"px", "left": (x-w1-55)+"px"}); //Left the div - Width POPUP 
        }
    });

 
 $( "body" ).delegate( ".hover-cart-landscape", "mouseleave", function() {
      $('.popup-video-template-landscape').css("display","none");
      // 2016.11.02 video sẽ ngưng chạy khi thoát hover
      $('.popup-video-template-landscape video')[0].pause();
  });
  
  $( "body" ).delegate( ".hover-cart-portrait-detail", "mouseenter", function() {
        var vid = document.getElementById("portrait_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        $('.popup-video-template-portrait').css("display","block");
        // 2016.11.02 video sẽ chạy khi hover vào template
        $('.popup-video-template-portrait video')[0].play();
        if($(".hover-cart-portrait-detail").length>=2){
            var x = document.getElementById(this.id).documentOffsetLeft;
            var y = document.getElementById(this.id).documentOffsetTop;
            if($(this).parent().parent().hasClass('active')){
                var w1 = document.getElementById(this.id).offsetWidth;
                console.log(ox1,w1);
                $(".popup-video-template-portrait").css({"top": 55+"px", "left": (ox1-20)+"px"}); // Left the div + Width thẻ div 
            }
            else{
                var w1 = $('.popup-video-template-portrait video')[0].offsetWidth; 
                $(".popup-video-template-portrait").css({"top": 55+"px", "left": (ox1-w1-50)+"px"}); //Left the div - Width POPUP 
            }
        }
        else{
//            var x = document.getElementById(this.id).documentOffsetLeft;
//            var y = document.getElementById(this.id).documentOffsetTop;
//            var w1 = ($('.popup-video-template-portrait video')[0].offsetWidth)/2; 
//            $(".popup-video-template-portrait").css({"top": 55+"px", "left": (ox1+w1+80)+"px"});
            
            var w1 = (document.getElementById(this.id).offsetWidth)/2;            
            $(".popup-video-template-portrait").css({"top": 55+"px", "left": (ox1+w1)+"px"});
            
        }
   
    });

 
 $( "body" ).delegate( ".hover-cart-portrait-detail", "mouseleave", function() {
      $('.popup-video-template-portrait').css("display","none");
      // 2016.11.02 video sẽ ngưng chạy khi thoát hover
      $('.popup-video-template-portrait video')[0].pause();
  });
  
  $( "body" ).delegate( ".hover-cart-portrait", "mouseenter", function() {
      var vid = document.getElementById("portrait_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        $('.popup-video-template-portrait').css("display","block");
        // 2016.11.02 video sẽ chạy khi hover vào template
        $('.popup-video-template-portrait video')[0].play();
    var x = document.getElementById(this.id).documentOffsetLeft;
        var y = document.getElementById(this.id).documentOffsetTop;
        if(x < ox1){
      var w1 = document.getElementById(this.id).offsetWidth;
            $(".popup-video-template-portrait").css({"top": y+"px", "left": (x+w1+5)+"px"}); // Left the div + Width thẻ div 
        }
        else{
      var w1 = $('.popup-video-template-portrait video')[0].offsetWidth; 
            $(".popup-video-template-portrait").css({"top": y+"px", "left": (x-w1-55)+"px"}); //Left the div - Width POPUP 
        }
    });
 
$( "body" ).delegate( ".hover-cart-portrait", "mouseleave", function() {
      $('.popup-video-template-portrait').css("display","none");
      // 2016.11.02 video sẽ ngưng chạy khi thoát hover
      $('.popup-video-template-portrait video')[0].pause();
  });
  
  
  $( "body" ).delegate( ".v2-img-land-list", "mouseenter", function() {
        var vid = document.getElementById("landscape_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        $('.popup-video-template-landscape').css("display","block");
        // 2016.11.02 video sẽ chạy khi hover vào template
        $('.popup-video-template-landscape video')[0].play();
        
        var x = document.getElementById(this.id).documentOffsetLeft;
        var y = document.getElementById(this.id).documentOffsetTop;
        var w1 = document.getElementById(this.id).offsetWidth;
        $(".popup-video-template-landscape").css({"top": (y-5)+"px", "left": (x+w1+5)+"px"}); // Left the div + Width thẻ div 
    });

 
 $( "body" ).delegate( ".v2-img-land-list", "mouseleave", function() {
      $('.popup-video-template-landscape').css("display","none");
      // 2016.11.02 video sẽ ngưng chạy khi thoát hover
      $('.popup-video-template-landscape video')[0].pause();
  });
  
  $( "body" ).delegate( ".v2-img-col-md5-list", "mouseenter", function() {
        var vid = document.getElementById("portrait_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        $('.popup-video-template-portrait').css("display","block");
        // 2016.11.02 video sẽ chạy khi hover vào template
        $('.popup-video-template-portrait video')[0].play();
        
        var x = document.getElementById(this.id).documentOffsetLeft;
        var y = document.getElementById(this.id).documentOffsetTop;
        var w1 = document.getElementById(this.id).offsetWidth;
        $(".popup-video-template-portrait").css({"top": (y)+"px", "left": (x+w1+5)+"px"}); // Left the div + Width thẻ div 
    });

 
 $( "body" ).delegate( ".v2-img-col-md5-list", "mouseleave", function() {
      $('.popup-video-template-portrait').css("display","none");
      // 2016.11.02 video sẽ ngưng chạy khi thoát hover
      $('.popup-video-template-portrait video')[0].pause();
  });
  
//   var $listFirst = $("#menumm");
//    $listFirst.children("ul").find("li").on("click", function(e) {
//    e.preventDefault();
//    var $subMenus = $(this).children("#sub-menu");
//    var $allSubMenus = $(this).find("#sub-menu");
//        if ($subMenus.length > 0) {
//           if ($subMenus.css("display") === "none") {
//             $subMenus.slideDown();
//             $(this).siblings().find("#sub-menu").slideUp();
//             return false;
//           } else {
//             $(this).find("#sub-menu").slideUp();
//           }
//         } 
//      });
    
    var $listFirst = $("#menumm");
    $listFirst.children("ul").find("li a").on("click", function(e) {
      e.preventDefault();
      var self = $(this).parent();
      var $subMenus = self.children("#sub-menu");
      var $allSubMenus = self.find("#sub-menu");
      if ($subMenus.length > 0) {
         if ($subMenus.css("display") === "none") {
           $subMenus.slideDown();
           self.siblings().find("#sub-menu").slideUp();
           return false;
         } else {
           self.find("#sub-menu").slideUp();
         }
       }
      else {
         window.location.href = self.children("a").attr("href"); 
       }
    });
    
//  $( "body" ).delegate( ".load_landscape", "click", function() {
//      $.ajax({ 
//        async: false,
//        cache: false,                                
//        url: '/?resolution=1&q='+$("#frm-search").find("input").eq(0).val(),
//        success: function(data, textStatus, jqXHR) {
//            div=document.createElement("div");
//            $(div).html(data);
//            $("#wrapper-home .v2-detail").html($(div).find(".v2-detail").html());
//            $("#wrapper-home #center-home").html($(div).find("#center-home").html());
//            $('#frm-search').find('input[name="resolution"]').eq(0).val('1');
//            
//            $("#content-slider").lightSlider({
//                loop: true,
//                keyPress: true
//            });
//            $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
//                effect: 'fade',
//                testMode: true,
//                onChange: function(evt){
//                    load_portrait();
//                    return true;
//                }
//            });
//        },
//        error: function (jqXHR, textStatus, errorThrown) {
//            console.log(jqXHR.responseText);
//        }
//     });
//  });
//  
//  $( "body" ).delegate( ".load_portrait", "click", function() {
//      $.ajax({ 
//        async: false,
//        cache: false,                                
//        url: '/?resolution=2&q='+$("#frm-search").find("input").eq(0).val(),
//        success: function(data, textStatus, jqXHR) {
//            div=document.createElement("div");
//            $(div).html(data);
//            $("#wrapper-home .v2-detail").html($(div).find(".v2-detail").html());
//            $("#wrapper-home #center-home").html($(div).find("#center-home").html());
//            $('#frm-search').find('input[name="resolution"]').eq(0).val('2');
//            
//            $("#content-slider").lightSlider({
//                loop: true,
//                keyPress: true
//            });
//            $('#polyglotLanguageSwitcher').polyglotLanguageSwitcher({
//                effect: 'fade',
//                testMode: true,
//                onChange: function(evt){
//                    load_landscape();
//                    return true;
//                }
//            });
//        },
//        error: function (jqXHR, textStatus, errorThrown) {
//            console.log(jqXHR.responseText);
//        }
//     });
//  });
  
  $(".change-icon-detail").parent().parent().parent().click(function (e){
     if($(this).find('input').eq(0).is(':disabled')) return;
     if ( $(e.target).is('input[type="checkbox"]') ) return;
    $(this).find('input').eq(0).prop('checked', function( foo, oldValue ) {return !oldValue}).change();

});
 // 2016.12.22 ktd edit, js thêm mới phần detail
 $( "body" ).delegate( ".change-icon-detail", "change", function() {
     if($(".v2-btn-add-cart-dl .add-to-cart1").length>0){
         return;
     }
     out_id=$(this).parent().find('input[name="out_id"]').eq(0).val();
      service_id=$(this).parent().find('input[name="service_id"]').eq(0).val();
      price=$(this).parent().find('input[name="price"]').eq(0).val();
      
     if( $(this).is(':checked') ) {
        $(this).parent().next().find('img').eq(0).css("display","none");
        $(this).parent().next().find('img').eq(1).css("display","inherit");
//        $(this).parents('.div-check-box-name-function-detail').css("background-color","#fff");
        if($(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").length==0){
            $(this).parents('.v2-row-service').css("background-color","rgba(222,167,2,0.5)");
        }
        else{
            subtotal_price=$(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").html();
            subtotal_price=parseFloat(subtotal_price.substr(1));
            subtotal_price+=parseFloat(price);
            $(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").html("$"+subtotal_price.toFixed(2));
        }
        
        action="add";
        
        number=document.getElementById("number-cart-option").innerHTML;
        number=parseInt(number);
        number++;
        document.getElementById("number-cart-option").innerHTML=number;
        
        
        
      }else{
        $(this).parent().next().find('img').eq(1).css("display","none");
        $(this).parent().next().find('img').eq(0).css("display","inherit");
        if($(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").length==0){
            $(this).parents('.v2-row-service').css("background-color","initial");
        }
        else{
            subtotal_price=$(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").html();
            subtotal_price=parseFloat(subtotal_price.substr(1));
            subtotal_price-=parseFloat(price);
            $(".content-box-v2-rdl.float-ctb-v2-rdl .v2-number-sub-tol").html("$"+subtotal_price.toFixed(2));
        }
        
        action="delete";
        
        number=document.getElementById("number-cart-option").innerHTML;
        number=parseInt(number);
        number--;
        document.getElementById("number-cart-option").innerHTML=number;
     }
     $.ajax({ 
        async: false,
        cache: false,                                
        url: '/ordersessionoption?out_id='+out_id+'&service_id='+service_id+'&action='+action,
        success: function(data, textStatus, jqXHR) {
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
        }
    });
  });


  // khi click vào shop-cart sẽ hiển thị ra popup trong 4s
  $('.add-to-cart1').click(function(){
      var eMouseOver = false;
      
//      $(".popup-shop-cart").show();
      $(".popup-shop-cart").hover(function(){
         $('.popup-shop-cart').addClass('_s');
        });
      
      $(".popup-shop-cart").mouseleave(function(){
            $('.popup-shop-cart').removeClass('_s');
          });

      var intervals = setInterval(function(){
          if(!$('.popup-shop-cart').hasClass('_s')){
            $(".popup-shop-cart").fadeOut();
clearInterval(intervals);
        }
      
      }, 4000);
     
//      intervals;
      return false;
  });
  // 2016.03.12 KTD edit, khi click vào đây popup cart sẽ ẩn đi
  $('.close-popup-cart').click(function(){
      $('.popup-shop-cart').fadeOut();
  });
  
  
  // hiển thị popup đăng ký đăng nhập khi người dùng nhấn vào dùng thử
  var modal1 = $('#popup-new-question');
  var modal2 = $('#popup-reply-question');
  var modal3 = $('#popup-comment-rating');
  // var modal4 = $('#popup-thanks');
   var modal5 = $('#popup-forgot-password');
  // var modal6 = $('#popup-login-login');
   var modalv7 = $('.v2-box-login');
  var span1 = $('.close_1');
  span1.click(function(){
       modal1.css("display","none");
       modal2.css("display","none");
       modal3.css("display","none");
        modal5.css("display","none");
       // modal6.css("display","none");
        modalv7.css("display","none");
       $(".error").hide();
  });

  //hiển thị popup đăng ký khi nhấn vào nút đăng ký
  // var btn15 = $('.show-register-popup');
  // btn15.click(function(){
  //      modal5.css("display","block");
  //      $(".error").hide();
  //      $(".addclass-icon").addClass("icon-menu6");    
  // });

  // //hiển thị popup đăng ký khi nhấn vào nút đăng ký
  // var btn16 = $('.show-login-popup1');
  // btn16.click(function(){
  //      modal6.css("display","block");
  //      $(".error").hide();
  //      btn16.addClass("hover-popup");
  //      $(".addclass-icon").addClass("icon-menu6");
  //      $("#menumm").hide();    
  // });
  
  var btn15 = $('.v2-forgot-pass');
  btn15.click(function(){
       modal5.css("display","block");
       modalv7.css("display","none");
       $(".error").hide();
  });

  //hiển thị popup đăng ký, đăng nhập khi nhấn vào nút dùng thử
  var btn1 = $('.click-show-popup-rating');
  btn1.click(function(){
      if(voted==true){
          jAlert("You can't vote again!");
          return;
      }
      
      if($("#user_id").val()==""){
          jAlert("Please login");
          return;
      }
      $("input[name='value']").val($(".v2-number-click-ranting").find(".jr-ratenode.jr-rating:not(.jr-nomal)").length);
       modal3.css("display","block");
       $(".error").hide();
  });

  //hiển thị popup quên mật khẩu khi nhấp vào quên mật khẩu trên popup đăng ký, đăng nhập
  var btn2 = $('.click-show-popup-qs');
  btn2.click(function(){
      if($("#user_id").val()==""){
          jAlert("Please login");
          return;
      }
       modal1.css("display","block");
       $(".error").hide();
  });

  //hiển thị popup đăng ký đăng nhập khi nhấp vào đăng ký đăng nhập trên popup quên mật khẩu
  var btn3 = $('.v2-show-popup-reply');
  btn3.click(function(){
      if($("#user_id").val()==""){
          jAlert("Please login");
          return;
      }
       $('#v2-questions-ct').html($(this).parent().prev().find("span").eq(1).html());
       $('#popup-reply-question').find('input[name="parent_id"]').eq(0).val($(this).next().val());
       modal2.css("display","block");
       $(".error").hide();
  });
  
//  // hiển thị popup đăng ký đăng nhập khi người dùng nhấn vào dùng thử
//  
//  var modal2 = $('#popup-message');
//  var modal3 = $('#popup-forgot-password');
//  var modal4 = $('#popup-thanks');
//  var modal5 = $('#popup-register-login');
//  var modal6 = $('#popup-login-login');
//  var modalv7 = $('.v2-box-login');
//  var span1 = $('.close_1');
//  span1.click(function(){
//       
//       modal2.css("display","none");
//       modal3.css("display","none");
//       modal5.css("display","none");
//       modal6.css("display","none");
//       modalv7.css("display","none");
//       $(".error").hide();
//  });
//
//  //hiển thị popup đăng ký khi nhấn vào nút đăng ký
//  var btn15 = $('.show-register-popup');
//  btn15.click(function(){
//       modal5.css("display","block");
//       $(".error").hide();
//       $(".addclass-icon").addClass("icon-menu6");    
//  });
//
//  //hiển thị popup đăng ký khi nhấn vào nút đăng ký
//  var btn16 = $('.show-login-popup1');
//  btn16.click(function(){
//       modal6.css("display","block");
//       $(".error").hide();
//       btn16.addClass("hover-popup");
//       $(".addclass-icon").addClass("icon-menu6");
//       $("#menumm").hide();    
//  });
//
//  //hiển thị popup đăng ký, đăng nhập khi nhấn vào nút dùng thử
//  var btn1 = $('.btn-trial');
//  btn1.click(function(){
//       modal6.css("display","block");
//       $(".error").hide();
//  });
//
//  //hiển thị popup quên mật khẩu khi nhấp vào quên mật khẩu trên popup đăng ký, đăng nhập
//  var btn2 = $('.click-password-form');
//  btn2.click(function(){
//       modal3.css("display","block");
//       
//       modal6.css("display","none");
//       $(".error").hide();
//  });
//
//  //hiển thị popup đăng ký đăng nhập khi nhấp vào đăng ký đăng nhập trên popup quên mật khẩu
//  var btn3 = $('.login-click-show');
//  btn3.click(function(){
//       modal6.css("display","block");
//       modal3.css("display","none");
//       $(".error").hide();
//  });

  //hiển thị popup quên mật khẩu khi nhấn vào link quên mật khẩu trên popup login
  var btn4 = $('.show-login-popup');
  btn4.click(function(){
      //nếu user chưa chọn template và click vào icon-cart trên menu
      if(icon_view_cart_on_menu_clicked==true&&($.trim($("#number-cart").html())=="0"||$.trim($("#number-cart").html())=="")){
          icon_view_cart_on_menu_clicked=false;
          alert("Bạn chưa chọn sản phẩm");
          return;
      }
       modal6.css("display","block");
       $(".error").hide();
  });

  var btn5 = $('.show-thank');
  btn5.click(function(){
       //modal4.css("display","block");
       //$(".error").hide();
     $.ajax({url: "/confirmcart?f=1", success: function(result){
      if(result == '0') {
        modal4.css("display","block");
        $(".error").hide();
      }
                        else{
                            alert("Có một số template bạn đã mua. Vui lòng xóa đi.")
                            result=result.split(",");
                            for(i=1;i<result.length;i++){
                                $("#"+result[i]).css("border","red solid 2px");
                            }
                        }
    }});
  });
  // 2016.11.02 KTD EDIT
  // hiển thị thông báo khi khách hàng chưa thanh toán
  var btn6 = $('.show-thank-dowload');
  btn6.click(function(){
       modal4.css("display","block");
       $(".error").hide();
  });
  $(".popup").click(function(){
       btn16.removeClass("hover-popup");
       $(".addclass-icon").removeClass("icon-menu6");
  });

  // ------ end an hien popup--------
  
  //Thêm đoạn code này vào để change value

     $('.edit-tt').click(function(){
        $('.change-edit').slideToggle("slow");
    });
    $('.save-change').click(function(){
        $('.change-edit').slideUp('slow');
    });

    $('.close-edit').click(function(){
        $('.change-edit').slideUp('slow');
    });

 //khi click qua lại giữa 2 tab đăng ký, đăng nhập trong popup đăng ký/đăng nhập   
    $("a[href='#register-m'],a[href='#login-m']").click(function(){        
        $(".error").hide();
    });
  
  // js phần detail Dũng edit
  // hiển thị bài viết khi click vào thanh xem chi tiết
   $('.click-show-detail').click(function(){
        $('.content-detail').slideDown('slow');
        $('.click-show-detail').css("display","none");
        $('.hide-detail').css("display","block");
    });
  // ẩn bài viết khi nhấn vào nút ẩn trên bài viết
    $('.hide-detail').click(function(){
        $('.content-detail').slideUp('slow');
        $('.click-show-detail').css("display","block");
        $('.hide-detail').css("display","none");
    });
//  // hiển thị menu mobile
//    $('.show-mm').click(function(){
//          $('#menumm').toggle('slow');
//      });
//  // hiển thị menu mobile
//    $('.close-mm').click(function(){
//          $('#menumm').toggle('slow');
//      });
  // lấy tọa độ hiển thị video trong trang detail
//    $('.thumb-detail').hover(function(){
//        var x = document.getElementById(this.id).documentOffsetLeft;
//        var y = document.getElementById(this.id).documentOffsetTop;
//        // 2016.12.06 ktd edit, lấy kích thước của window để hiển thị popup
//        var zWindow = $( window ).width();
//        //------
//        $('.popup-video-template-dt-landscape').css("display","block");
//        // 2016.11.02 video sẽ bắt đầu chạy khi hover vào template
//        $('.popup-video-template-dt-landscape video')[0].play();
//        if(zWindow < 1370){
//            $(".popup-video-template-dt-landscape").css({"top": y+"px", "left": (x-335)+"px"});
//        }
//        else{
//            $(".popup-video-template-dt-landscape").css({"top": y+"px", "left": (x-440)+"px"});
//        }
//    });
//
//    $(".thumb-detail").mouseleave(function(){
//      $('.popup-video-template-dt-landscape').css("display","none");
//      // 2016.11.02 video sẽ dừng lại khi không hover vào template
//      $('.popup-video-template-dt-landscape video')[0].pause()
//    });
//
//    $('.thumb-detail-p').hover(function(){
//        var x = document.getElementById(this.id).documentOffsetLeft;
//        var y = document.getElementById(this.id).documentOffsetTop;
//       // 2016.12.06 ktd edit, lấy kích thước của window để hiển thị popup
//        var zWindow = $( window ).width();
//        //------
//        $('.popup-video-template-dt-portrait').css("display","block");
//        // 2016.11.02 video sẽ bắt đầu chạy khi hover vào template
//        $('.popup-video-template-dt-portrait video')[0].play();
//        if(zWindow < 1370){
//          $(".popup-video-template-dt-portrait").css({"top": y+"px", "left": (x-205)+"px"});
//        }
//        else{
//          $(".popup-video-template-dt-portrait").css({"top": y+"px", "left": (x-255)+"px"});
//        }
//    });
//
//    $(".thumb-detail-p").mouseleave(function(){
//      $('.popup-video-template-dt-portrait').css("display","none");
//      // 2016.11.02 video sẽ dừng lại khi không hover vào template
//      $('.popup-video-template-dt-portrait video')[0].pause()
//  });

    // 2016.12.10 ktd edit
    // lấy tọa độ của item trong confirm-cart landscape sau đó show ra popup video ngang   
    $( "body" ).delegate( ".thumb-confirm-l", "mouseenter", function() {
        var vid = document.getElementById("landscape_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        var x = document.getElementById(this.id).documentOffsetLeft;
        var y = document.getElementById(this.id).documentOffsetTop;
        $('.popup-video-template-confirm-landscape').css("display","block");
        // 2016.11.02 video sẽ bắt đầu chạy khi hover vào template
        $('.popup-video-template-confirm-landscape video')[0].play();
        $(".popup-video-template-confirm-landscape").css({"top": (y-30)+"px", "left": (x+169)+"px"});
    });
    // khi đưa chuột ra khỏi item video sẽ ẩn đi
    $( "body" ).delegate( ".thumb-confirm-l", "mouseleave", function() {
      $('.popup-video-template-confirm-landscape').css("display","none");
      // 2016.11.02 video sẽ dừng lại khi không hover vào template
      $('.popup-video-template-confirm-landscape video')[0].pause()
    });

    // lấy tọa độ của item trong confirm-cart portraint sau đó show ra popup video đứng
    $( "body" ).delegate( ".thumb-confirm-p", "mouseenter", function() {
        var vid = document.getElementById("portrait_video_tag");
   	vid.src = $(this).find("input").eq(0).val();
   	vid.load();
        
        var x = document.getElementById(this.id).documentOffsetLeft;
        var y = document.getElementById(this.id).documentOffsetTop;
        $('.popup-video-template-confirm-portrait').css("display","block");
        // 2016.11.02 video sẽ bắt đầu chạy khi hover vào template
        $('.popup-video-template-confirm-portrait video')[0].play();
        $(".popup-video-template-confirm-portrait").css({"top": (y-50)+"px", "left": (x+169)+"px"});
    });
    // khi đưa chuột ra khỏi item video sẽ ẩn đi
    $( "body" ).delegate( ".thumb-confirm-p", "mouseleave", function() {
      $('.popup-video-template-confirm-portrait').css("display","none");
      // 2016.11.02 video sẽ dừng lại khi không hover vào template
      $('.popup-video-template-confirm-portrait video')[0].pause()
    });

  //2016.11.10 KTD EDIT
  $('.show-register-popup').hover(function(){
    $('.icon-menu2').css("background-position","-49px 0");
  });
   $('.show-register-popup').mouseleave(function(){
    $('.icon-menu2').css("background-position","0 0");
  });
   $('.show-login-popup1').hover(function(){
    $('.icon-menu2').css("background-position","-49px 0");
  });
   $('.show-login-popup1').mouseleave(function(){
    $('.icon-menu2').css("background-position","0 0");
  });

  $('.show-register-popup').hover(function(){
    $('.icon-menu2').css("background-position","-49px 0");
  });
   $('.show-register-popup').mouseleave(function(){
    $('.icon-menu2').css("background-position","0 0");
  });
   $('.show-login-popup').hover(function(){
    $('.icon-menu2').css("background-position","-49px 0");
  });
   $('.show-login-popup').mouseleave(function(){
    $('.icon-menu2').css("background-position","0 0");
  });

//  $(".thumb-detail").click(function () {
//      var dir = $(this).data("slide-index");
//      $('#video1').attr("src", dir);
//  });
//   $(".thumb-detail-p").click(function () {
//      var dir = $(this).data("slide-index");
//      $('#video2').attr("src", dir);
//  });

// script v2.0 dùng cho popup detail
  // do dùng slide nên phải viết lại
  $( "body" ).delegate( ".hover-cart-landscape-detail", "mouseenter", function() {
        var vid = document.getElementById("landscape_video_tag");
    vid.src = $(this).find("input").eq(0).val();
    vid.load();
        
        $('.popup-video-template-landscape').css("display","block");
        // 2016.11.02 video sẽ chạy khi hover vào template
        $('.popup-video-template-landscape video')[0].play();
        
        
        
        if($(".hover-cart-landscape-detail").length>=2){
            var x = document.getElementById(this.id).documentOffsetLeft;
            var y = document.getElementById(this.id).documentOffsetTop;
            if($(this).parent().parent().hasClass('active')){
                var w1 = document.getElementById(this.id).offsetWidth;
                $(".popup-video-template-landscape").css({"top": 55+"px", "left": (ox1-20)+"px"}); // Left the div + Width thẻ div 
            }
            else{
                var w1 = $('.popup-video-template-landscape video')[0].offsetWidth; 
                $(".popup-video-template-landscape").css({"top": 55+"px", "left": (ox1-w1-50)+"px"}); //Left the div - Width POPUP 
            }
        }
        else{
//            var x = document.getElementById(this.id).documentOffsetLeft;
//            var y = document.getElementById(this.id).documentOffsetTop;
//            var w1 = ($('.popup-video-template-landscape video')[0].offsetWidth)/2; 
//            $(".popup-video-template-landscape").css({"top": 55+"px", "left": (ox1+w1)+"px"});
            var w1 = (document.getElementById(this.id).offsetWidth)/2;            
            $(".popup-video-template-landscape").css({"top": 55+"px", "left": (ox1+w1)+"px"});
        }
    });
 
  $( "body" ).delegate( ".hover-cart-landscape-detail", "mouseleave", function() {
      $('.popup-video-template-landscape').css("display","none");
      // 2016.11.02 video sẽ ngưng chạy khi thoát hover
      $('.popup-video-template-landscape video')[0].pause();
  });
  // ---------------
$(".thumb-detail, .thumb-detail-p").click(function () {
      var dir = $(this).data("slide-index");
      $("#video1").css('opacity',0);
      
      wait(100).then(function(){
          $('#video1').attr("src", dir);
      })
      wait(100).then(function(){
          $("#video1").css('opacity',1);
      })
  });
  // đợi 0.1s cho hết vệt đen rùi mới xuất hiện
  function wait(duration){
      var d = jQuery.Deferred();
      setTimeout(function(){
        d.resolve();
      }, duration);
      return d.promise();
  };

  // 2016.19.12 ktd them moi
  $(".change-icon-save-mns").parent().parent().parent().click(function (e){
        if($(this).find('input').eq(0).is(':disabled')) return;
         if ( $(e.target).is('input[type="checkbox"]') ) return;
        $(this).find('input').eq(0).prop('checked', function( foo, oldValue ) {return !oldValue}).change();

    });
  $(".change-icon-save-mns").change( function(){
      
      out_id=$(this).parent().find('input[name="out_id"]').eq(0).val();
      service_id=$(this).parent().find('input[name="service_id"]').eq(0).val();
      price=$(this).parent().find('input[name="price"]').eq(0).val();
      
     if( $(this).is(':checked') ) {
         $(this).parents('.left-function').parents('.div-check-box-name-function').addClass('bg-check');
        $(this).parents('.left-function').parents('.box-v2-cart-content').find('.'+$(this).attr("name")).css("visibility","inherit");
       
        action="add";
        
        $.ajax({ 
            async: false,
            cache: false,                                
            url: 'ordersessionoption?out_id='+out_id+'&service_id='+service_id+'&action='+action,
            success: function(data, textStatus, jqXHR) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        });
        
        sum_all=$("#sum_all").val();
        sum_all=parseFloat(sum_all);
        sum=$(this).parents(".v2-cart-content").find('input[name="sum"]').eq(0).val();
        sum=parseFloat(sum);
        if(parseFloat(price)>0){
            sum_all+=parseFloat(price);
            sum+=parseFloat(price);
        }  
        
        sum_all=sum_all.toFixed(2);
        sum_all_show=sum_all.toString();
        if(sum_all_show.indexOf(".")!=-1){
            temp=sum_all_show.split(".");
            sum_all_show=numberWithCommas(temp[0])+"."+temp[1];
        }
        else{
            sum_all_show=numberWithCommas(sum_all_show)+".00";
        }
        $("#sum_all").prev().find(".v2-number-total-price").eq(0).find("span").eq(0).html(money_unit+sum_all_show);

        sum=sum.toFixed(2);
        sum_show=sum.toString();
        if(sum_show.indexOf(".")!=-1){
            temp=sum_show.split(".");
            sum_show=numberWithCommas(temp[0])+"."+temp[1];
        }
        else{
            sum_show=numberWithCommas(sum_show)+".00";
        }
        
//        $(this).parents(".v2-cart-content").find('p.price-cart_').eq(0).html(money_unit+sum_show);
        
        
        $("#sum_all").val(sum_all);
        $("#total_price").val(sum_all);
        $(this).parents(".v2-cart-content").find('input[name="sum"]').eq(0).val(sum);
        
        number=document.getElementById("number-cart-option").innerHTML;
        number=parseInt(number);
        number++;
        document.getElementById("number-cart-option").innerHTML=number;
      }else{
        $(this).parents('.left-function').parents('.div-check-box-name-function').removeClass('bg-check').css('background-color','#cccccc');
        $(this).parents('.left-function').parents('.box-v2-cart-content').find('.'+$(this).attr("name")).css("visibility","hidden");
        action="delete";
        
        $.ajax({ 
            async: false,
            cache: false,                                
            url: 'ordersessionoption?out_id='+out_id+'&service_id='+service_id+'&action='+action,
            success: function(data, textStatus, jqXHR) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        });
        
        sum_all=$("#sum_all").val();
        sum_all=parseFloat(sum_all);
        sum=$(this).parents(".v2-cart-content").find('input[name="sum"]').eq(0).val();
        sum=parseFloat(sum);
        if(parseFloat(price)>0){
            sum_all-=parseFloat(price);
            sum-=parseFloat(price);
        }
        //
        sum_all=sum_all.toFixed(2);
        sum_all_show=sum_all.toString();
        if(sum_all_show.indexOf(".")!=-1){
            temp=sum_all_show.split(".");
            sum_all_show=numberWithCommas(temp[0])+"."+temp[1];
        }
        else{
            sum_all_show=numberWithCommas(sum_all_show)+".00";
        }
        $("#sum_all").prev().find(".v2-number-total-price").eq(0).find("span").eq(0).html(money_unit+sum_all_show);

        sum=sum.toFixed(2);
        sum_show=sum.toString();
        if(sum_show.indexOf(".")!=-1){
            temp=sum_show.split(".");
            sum_show=numberWithCommas(temp[0])+"."+temp[1];
        }
        else{
            sum_show=numberWithCommas(sum_show)+".00";
        }
        //
//        $(this).parents(".v2-cart-content").find('p.price-cart_').eq(0).html(money_unit+sum_show);
        
        
        $("#sum_all").val(sum_all);
        $("#total_price").val(sum_all);
        $(this).parents(".v2-cart-content").find('input[name="sum"]').eq(0).val(sum);
        
        number=document.getElementById("number-cart-option").innerHTML;
        number=parseInt(number);
        number--;
        document.getElementById("number-cart-option").innerHTML=number;
     }
     
  });

  
  $('.click-pay-show').click(function(){
      
      
      count_cart_option=document.getElementById("number-cart-option").innerHTML;
        count_cart_option=$.trim(count_cart_option);

        count_cart_template=document.getElementById("number-cart-template").innerHTML;
        count_cart_template=$.trim(count_cart_template);

      if(count_cart_option=='0'&&count_cart_template=='0'){
            jAlert(error_cart_empty,"");
            return;
      }
      
      $('.v2-infomation-payment').slideDown("slow");
      $('.pay-order-cart').slideDown("slow");
      $('.v2-btn-payment_').hide();
      
//      $('.hide-pay-order-cart').slideDown("slow");
//      $('.pay-order-cart').slideDown("slow");
//      $('.btn-order-cart').hide();
      
      
      setOptionsAtConfirmcart();
  });
  // ---------------------------------------
  // 2016.12.22 ktd edit, js thêm mới phần detail
    $(".change-icon-save-detail").change( function(){
     if( $(this).is(':checked') ) {
        $(this).parents().parents().find('.icon-save-bg').css("display","none");
        $(this).parents().parents().find('.hover-icon-save-bg').css("display","inherit");
        $(this).parents('.div-check-box-name-function-detail').css("background-color","#fff");
      }else{
        $(this).parents().parents().find('.icon-save-bg').css("display","inherit");
        $(this).parents().parents().find('.hover-icon-save-bg').css("display","none");
        $(this).parents('.div-check-box-name-function-detail').css("background-color","#cccccc");
     }
  });
  // Khi tick vào checkbox thì icon edit sẽ sáng, bỏ tick thì sẽ hết sáng
  $(".change-icon-edit-detail").change( function(){
     if( $(this).is(':checked') ) {
        $(this).parents().parents().find('.icon-edit-bg').css("display","none");
        $(this).parents().parents().find('.hover-icon-edit-bg').css("display","inherit");
        $(this).parents('.div-check-box-name-function-detail').css("background-color","#fff");
      }else{
        $(this).parents().parents().find('.icon-edit-bg').css("display","inherit");
        $(this).parents().parents().find('.hover-icon-edit-bg').css("display","none");
        $(this).parents('.div-check-box-name-function-detail').css("background-color","#cccccc");
     }
  });
  // Khi tick vào checkbox thì icon setting sẽ sáng, bỏ tick thì sẽ hết sáng
  $(".change-icon-setting-detail").change( function(){
    if( $(this).is(':checked') ) {
        $(this).parents().parents().find('.icon-setting-bg').css("display","none");
        $(this).parents().parents().find('.hover-icon-setting-bg').css("display","inherit");
        $(this).parents('.div-check-box-name-function-detail').css("background-color","#fff");
      }else{
        $(this).parents().parents().find('.icon-setting-bg').css("display","inherit");
        $(this).parents().parents().find('.hover-icon-setting-bg').css("display","none");
        $(this).parents('.div-check-box-name-function-detail').css("background-color","#cccccc");
     }
  });
  
  $(".v2-visa").hover(function(){
      $('.popup_paypal').css('display','none');
      $('.v2-paypal').removeClass('active-hover');
      $('.popup_visa').css('display','block');
      $('.v2-visa').addClass('active-hover');
  });
  $(".v2-visa").mouseleave(function(){
      $('.popup_paypal').css('display','block');
      $('.v2-paypal').addClass('active-hover');
      $('.popup_visa').css('display','none');
      $('.v2-visa').removeClass('active-hover');
  });
  $(".v2-mastercard").hover(function(){
      $('.popup_paypal').css('display','none');
      $('.v2-paypal').removeClass('active-hover');
      $('.popup_mastercard').css('display','block');
      $('.v2-mastercard').addClass('active-hover');
  });
  $(".v2-mastercard").mouseleave(function(){
      $('.popup_paypal').css('display','block');
      $('.v2-paypal').addClass('active-hover');
      $('.popup_mastercard').css('display','none');
      $('.v2-mastercard').removeClass('active-hover');
  });

  // Khi nhấp chuột vào id (class) lệnh waiting sẽ được gọi
  $(".ajax-waiting").on("click", function(){
      $body = $("body");
      $(document).on({
          ajaxStart: function() { $body.addClass("loading");    },
           ajaxStop: function() { $body.removeClass("loading"); }    
      });
      $.get("/mockjax");        
  });
  // plugin waiting
  /*!
   * MockJax - jQuery Plugin to Mock Ajax requests
   *
   * Version:  1.5.3
   * Released:
   * Home:   http://github.com/appendto/jquery-mockjax
   * Author:   Jonathan Sharp (http://jdsharp.com)
   * License:  MIT,GPL
   *
   * Copyright (c) 2011 appendTo LLC.
   * Dual licensed under the MIT or GPL licenses.
   * http://appendto.com/open-source-licenses
   */ (function($) {
      var _ajax = $.ajax,
          mockHandlers = [],
          mockedAjaxCalls = [],
          CALLBACK_REGEX = /=\?(&|$)/,
          jsc = (new Date()).getTime();


      // Parse the given XML string.
      function parseXML(xml) {
          if (window.DOMParser == undefined && window.ActiveXObject) {
              DOMParser = function() {};
              DOMParser.prototype.parseFromString = function(xmlString) {
                  var doc = new ActiveXObject('Microsoft.XMLDOM');
                  doc.async = 'false';
                  doc.loadXML(xmlString);
                  return doc;
              };
          }

          try {
              var xmlDoc = (new DOMParser()).parseFromString(xml, 'text/xml');
              if ($.isXMLDoc(xmlDoc)) {
                  var err = $('parsererror', xmlDoc);
                  if (err.length == 1) {
                      throw ('Error: ' + $(xmlDoc).text());
                  }
              } else {
                  throw ('Unable to parse XML');
              }
              return xmlDoc;
          } catch (e) {
              var msg = (e.name == undefined ? e : e.name + ': ' + e.message);
              $(document).trigger('xmlParseError', [msg]);
              return undefined;
          }
      }

      // Trigger a jQuery event
      function trigger(s, type, args) {
          (s.context ? $(s.context) : $.event).trigger(type, args);
      }

      // Check if the data field on the mock handler and the request match. This
      // can be used to restrict a mock handler to being used only when a certain
      // set of data is passed to it.
      function isMockDataEqual(mock, live) {
          var identical = true;
          // Test for situations where the data is a querystring (not an object)
          if (typeof live === 'string') {
              // Querystring may be a regex
              return $.isFunction(mock.test) ? mock.test(live) : mock == live;
          }
          $.each(mock, function(k) {
              if (live[k] === undefined) {
                  identical = false;
                  return identical;
              } else {
                  // This will allow to compare Arrays
                  if (typeof live[k] === 'object' && live[k] !== null) {
                      identical = identical && isMockDataEqual(mock[k], live[k]);
                  } else {
                      if (mock[k] && $.isFunction(mock[k].test)) {
                          identical = identical && mock[k].test(live[k]);
                      } else {
                          identical = identical && (mock[k] == live[k]);
                      }
                  }
              }
          });

          return identical;
      }

      // See if a mock handler property matches the default settings
      function isDefaultSetting(handler, property) {
          return handler[property] === $.mockjaxSettings[property];
      }

      // Check the given handler should mock the given request
      function getMockForRequest(handler, requestSettings) {
          // If the mock was registered with a function, let the function decide if we
          // want to mock this request
          if ($.isFunction(handler)) {
              return handler(requestSettings);
          }

          // Inspect the URL of the request and check if the mock handler's url
          // matches the url for this ajax request
          if ($.isFunction(handler.url.test)) {
              // The user provided a regex for the url, test it
              if (!handler.url.test(requestSettings.url)) {
                  return null;
              }
          } else {
              // Look for a simple wildcard '*' or a direct URL match
              var star = handler.url.indexOf('*');
              if (handler.url !== requestSettings.url && star === -1 || !new RegExp(handler.url.replace(/[-[\]{}()+?.,\\^$|#\s]/g, "\\$&").replace(/\*/g, '.+')).test(requestSettings.url)) {
                  return null;
              }
          }

          // Inspect the data submitted in the request (either POST body or GET query string)
          if (handler.data && requestSettings.data) {
              if (!isMockDataEqual(handler.data, requestSettings.data)) {
                  // They're not identical, do not mock this request
                  return null;
              }
          }
          // Inspect the request type
          if (handler && handler.type && handler.type.toLowerCase() != requestSettings.type.toLowerCase()) {
              // The request type doesn't match (GET vs. POST)
              return null;
          }

          return handler;
      }

      // Process the xhr objects send operation
      function _xhrSend(mockHandler, requestSettings, origSettings) {

          // This is a substitute for < 1.4 which lacks $.proxy
          var process = (function(that) {
              return function() {
                  return (function() {
                      var onReady;

                      // The request has returned
                      this.status = mockHandler.status;
                      this.statusText = mockHandler.statusText;
                      this.readyState = 4;

                      // We have an executable function, call it to give
                      // the mock handler a chance to update it's data
                      if ($.isFunction(mockHandler.response)) {
                          mockHandler.response(origSettings);
                      }
                      // Copy over our mock to our xhr object before passing control back to
                      // jQuery's onreadystatechange callback
                      if (requestSettings.dataType == 'json' && (typeof mockHandler.responseText == 'object')) {
                          this.responseText = JSON.stringify(mockHandler.responseText);
                      } else if (requestSettings.dataType == 'xml') {
                          if (typeof mockHandler.responseXML == 'string') {
                              this.responseXML = parseXML(mockHandler.responseXML);
                              //in jQuery 1.9.1+, responseXML is processed differently and relies on responseText
                              this.responseText = mockHandler.responseXML;
                          } else {
                              this.responseXML = mockHandler.responseXML;
                          }
                      } else {
                          this.responseText = mockHandler.responseText;
                      }
                      if (typeof mockHandler.status == 'number' || typeof mockHandler.status == 'string') {
                          this.status = mockHandler.status;
                      }
                      if (typeof mockHandler.statusText === "string") {
                          this.statusText = mockHandler.statusText;
                      }
                      // jQuery 2.0 renamed onreadystatechange to onload
                      onReady = this.onreadystatechange || this.onload;

                      // jQuery < 1.4 doesn't have onreadystate change for xhr
                      if ($.isFunction(onReady)) {
                          if (mockHandler.isTimeout) {
                              this.status = -1;
                          }
                          onReady.call(this, mockHandler.isTimeout ? 'timeout' : undefined);
                      } else if (mockHandler.isTimeout) {
                          // Fix for 1.3.2 timeout to keep success from firing.
                          this.status = -1;
                      }
                  }).apply(that);
              };
          })(this);

          if (mockHandler.proxy) {
              // We're proxying this request and loading in an external file instead
              _ajax({
                  global: false,
                  url: mockHandler.proxy,
                  type: mockHandler.proxyType,
                  data: mockHandler.data,
                  dataType: requestSettings.dataType === "script" ? "text/plain" : requestSettings.dataType,
                  complete: function(xhr) {
                      mockHandler.responseXML = xhr.responseXML;
                      mockHandler.responseText = xhr.responseText;
                      // Don't override the handler status/statusText if it's specified by the config
                      if (isDefaultSetting(mockHandler, 'status')) {
                          mockHandler.status = xhr.status;
                      }
                      if (isDefaultSetting(mockHandler, 'statusText')) {
                          mockHandler.statusText = xhr.statusText;
                      }

                      this.responseTimer = setTimeout(process, mockHandler.responseTime || 0);
                  }
              });
          } else {
              // type == 'POST' || 'GET' || 'DELETE'
              if (requestSettings.async === false) {
                  // TODO: Blocking delay
                  process();
              } else {
                  this.responseTimer = setTimeout(process, mockHandler.responseTime || 50);
              }
          }
      }

      // Construct a mocked XHR Object
      function xhr(mockHandler, requestSettings, origSettings, origHandler) {
          // Extend with our default mockjax settings
          mockHandler = $.extend(true, {}, $.mockjaxSettings, mockHandler);

          if (typeof mockHandler.headers === 'undefined') {
              mockHandler.headers = {};
          }
          if (mockHandler.contentType) {
              mockHandler.headers['content-type'] = mockHandler.contentType;
          }

          return {
              status: mockHandler.status,
              statusText: mockHandler.statusText,
              readyState: 1,
              open: function() {},
              send: function() {
                  origHandler.fired = true;
                  _xhrSend.call(this, mockHandler, requestSettings, origSettings);
              },
              abort: function() {
                  clearTimeout(this.responseTimer);
              },
              setRequestHeader: function(header, value) {
                  mockHandler.headers[header] = value;
              },
              getResponseHeader: function(header) {
                  // 'Last-modified', 'Etag', 'content-type' are all checked by jQuery
                  if (mockHandler.headers && mockHandler.headers[header]) {
                      // Return arbitrary headers
                      return mockHandler.headers[header];
                  } else if (header.toLowerCase() == 'last-modified') {
                      return mockHandler.lastModified || (new Date()).toString();
                  } else if (header.toLowerCase() == 'etag') {
                      return mockHandler.etag || '';
                  } else if (header.toLowerCase() == 'content-type') {
                      return mockHandler.contentType || 'text/plain';
                  }
              },
              getAllResponseHeaders: function() {
                  var headers = '';
                  $.each(mockHandler.headers, function(k, v) {
                      headers += k + ': ' + v + "\n";
                  });
                  return headers;
              }
          };
      }

      // Process a JSONP mock request.
      function processJsonpMock(requestSettings, mockHandler, origSettings) {
          // Handle JSONP Parameter Callbacks, we need to replicate some of the jQuery core here
          // because there isn't an easy hook for the cross domain script tag of jsonp

          processJsonpUrl(requestSettings);

          requestSettings.dataType = "json";
          if (requestSettings.data && CALLBACK_REGEX.test(requestSettings.data) || CALLBACK_REGEX.test(requestSettings.url)) {
              createJsonpCallback(requestSettings, mockHandler, origSettings);

              // We need to make sure
              // that a JSONP style response is executed properly

              var rurl = /^(\w+:)?\/\/([^\/?#]+)/,
                  parts = rurl.exec(requestSettings.url),
                  remote = parts && (parts[1] && parts[1] !== location.protocol || parts[2] !== location.host);

              requestSettings.dataType = "script";
              if (requestSettings.type.toUpperCase() === "GET" && remote) {
                  var newMockReturn = processJsonpRequest(requestSettings, mockHandler, origSettings);

                  // Check if we are supposed to return a Deferred back to the mock call, or just
                  // signal success
                  if (newMockReturn) {
                      return newMockReturn;
                  } else {
                      return true;
                  }
              }
          }
          return null;
      }

      // Append the required callback parameter to the end of the request URL, for a JSONP request
      function processJsonpUrl(requestSettings) {
          if (requestSettings.type.toUpperCase() === "GET") {
              if (!CALLBACK_REGEX.test(requestSettings.url)) {
                  requestSettings.url += (/\?/.test(requestSettings.url) ? "&" : "?") + (requestSettings.jsonp || "callback") + "=?";
              }
          } else if (!requestSettings.data || !CALLBACK_REGEX.test(requestSettings.data)) {
              requestSettings.data = (requestSettings.data ? requestSettings.data + "&" : "") + (requestSettings.jsonp || "callback") + "=?";
          }
      }

      // Process a JSONP request by evaluating the mocked response text
      function processJsonpRequest(requestSettings, mockHandler, origSettings) {
          // Synthesize the mock request for adding a script tag
          var callbackContext = origSettings && origSettings.context || requestSettings,
              newMock = null;


          // If the response handler on the moock is a function, call it
          if (mockHandler.response && $.isFunction(mockHandler.response)) {
              mockHandler.response(origSettings);
          } else {

              // Evaluate the responseText javascript in a global context
              if (typeof mockHandler.responseText === 'object') {
                  $.globalEval('(' + JSON.stringify(mockHandler.responseText) + ')');
              } else {
                  $.globalEval('(' + mockHandler.responseText + ')');
              }
          }

          // Successful response
          jsonpSuccess(requestSettings, callbackContext, mockHandler);
          jsonpComplete(requestSettings, callbackContext, mockHandler);

          // If we are running under jQuery 1.5+, return a deferred object
          if ($.Deferred) {
              newMock = new $.Deferred();
              if (typeof mockHandler.responseText == "object") {
                  newMock.resolveWith(callbackContext, [mockHandler.responseText]);
              } else {
                  newMock.resolveWith(callbackContext, [$.parseJSON(mockHandler.responseText)]);
              }
          }
          return newMock;
      }


      // Create the required JSONP callback function for the request
      function createJsonpCallback(requestSettings, mockHandler, origSettings) {
          var callbackContext = origSettings && origSettings.context || requestSettings;
          var jsonp = requestSettings.jsonpCallback || ("jsonp" + jsc++);

          // Replace the =? sequence both in the query string and the data
          if (requestSettings.data) {
              requestSettings.data = (requestSettings.data + "").replace(CALLBACK_REGEX, "=" + jsonp + "$1");
          }

          requestSettings.url = requestSettings.url.replace(CALLBACK_REGEX, "=" + jsonp + "$1");


          // Handle JSONP-style loading
          window[jsonp] = window[jsonp] || function(tmp) {
              data = tmp;
              jsonpSuccess(requestSettings, callbackContext, mockHandler);
              jsonpComplete(requestSettings, callbackContext, mockHandler);
              // Garbage collect
              window[jsonp] = undefined;

              try {
                  delete window[jsonp];
              } catch (e) {}

              if (head) {
                  head.removeChild(script);
              }
          };
      }

      // The JSONP request was successful
      function jsonpSuccess(requestSettings, callbackContext, mockHandler) {
          // If a local callback was specified, fire it and pass it the data
          if (requestSettings.success) {
              requestSettings.success.call(callbackContext, mockHandler.responseText || "", status, {});
          }

          // Fire the global callback
          if (requestSettings.global) {
              trigger(requestSettings, "ajaxSuccess", [{},
              requestSettings]);
          }
      }

      // The JSONP request was completed
      function jsonpComplete(requestSettings, callbackContext) {
          // Process result
          if (requestSettings.complete) {
              requestSettings.complete.call(callbackContext, {}, status);
          }

          // The request was completed
          if (requestSettings.global) {
              trigger("ajaxComplete", [{},
              requestSettings]);
          }

          // Handle the global AJAX counter
          if (requestSettings.global && !--$.active) {
              $.event.trigger("ajaxStop");
          }
      }


      // The core $.ajax replacement.
      function handleAjax(url, origSettings) {
          var mockRequest, requestSettings, mockHandler;

          // If url is an object, simulate pre-1.5 signature
          if (typeof url === "object") {
              origSettings = url;
              url = undefined;
          } else {
              // work around to support 1.5 signature
              origSettings.url = url;
          }

          // Extend the original settings for the request
          requestSettings = $.extend(true, {}, $.ajaxSettings, origSettings);

          // Iterate over our mock handlers (in registration order) until we find
          // one that is willing to intercept the request
          for (var k = 0; k < mockHandlers.length; k++) {
              if (!mockHandlers[k]) {
                  continue;
              }

              mockHandler = getMockForRequest(mockHandlers[k], requestSettings);
              if (!mockHandler) {
                  // No valid mock found for this request
                  continue;
              }

              mockedAjaxCalls.push(requestSettings);

              // If logging is enabled, log the mock to the console
              $.mockjaxSettings.log(mockHandler, requestSettings);


              if (requestSettings.dataType === "jsonp") {
                  if ((mockRequest = processJsonpMock(requestSettings, mockHandler, origSettings))) {
                      // This mock will handle the JSONP request
                      return mockRequest;
                  }
              }


              // Removed to fix #54 - keep the mocking data object intact
              //mockHandler.data = requestSettings.data;

              mockHandler.cache = requestSettings.cache;
              mockHandler.timeout = requestSettings.timeout;
              mockHandler.global = requestSettings.global;

              copyUrlParameters(mockHandler, origSettings);

              (function(mockHandler, requestSettings, origSettings, origHandler) {
                  mockRequest = _ajax.call($, $.extend(true, {}, origSettings, {
                      // Mock the XHR object
                      xhr: function() {
                          return xhr(mockHandler, requestSettings, origSettings, origHandler);
                      }
                  }));
              })(mockHandler, requestSettings, origSettings, mockHandlers[k]);

              return mockRequest;
          }

          // We don't have a mock request
          if ($.mockjaxSettings.throwUnmocked === true) {
              throw ('AJAX not mocked: ' + origSettings.url);
          } else { // trigger a normal request
              return _ajax.apply($, [origSettings]);
          }
      }

      /**
       * Copies URL parameter values if they were captured by a regular expression
       * @param {Object} mockHandler
       * @param {Object} origSettings
       */
      function copyUrlParameters(mockHandler, origSettings) {
          //parameters aren't captured if the URL isn't a RegExp
          if (!(mockHandler.url instanceof RegExp)) {
              return;
          }
          //if no URL params were defined on the handler, don't attempt a capture
          if (!mockHandler.hasOwnProperty('urlParams')) {
              return;
          }
          var captures = mockHandler.url.exec(origSettings.url);
          //the whole RegExp match is always the first value in the capture results
          if (captures.length === 1) {
              return;
          }
          captures.shift();
          //use handler params as keys and capture resuts as values
          var i = 0,
              capturesLength = captures.length,
              paramsLength = mockHandler.urlParams.length,
              //in case the number of params specified is less than actual captures
              maxIterations = Math.min(capturesLength, paramsLength),
              paramValues = {};
          for (i; i < maxIterations; i++) {
              var key = mockHandler.urlParams[i];
              paramValues[key] = captures[i];
          }
          origSettings.urlParams = paramValues;
      }


      // Public

      $.extend({
          ajax: handleAjax
      });

      $.mockjaxSettings = {
          //url:        null,
          //type:       'GET',
          log: function(mockHandler, requestSettings) {
              if (mockHandler.logging === false || (typeof mockHandler.logging === 'undefined' && $.mockjaxSettings.logging === false)) {
                  return;
              }
              if (window.console && console.log) {
                  var message = 'MOCK ' + requestSettings.type.toUpperCase() + ': ' + requestSettings.url;
                  var request = $.extend({}, requestSettings);

                  if (typeof console.log === 'function') {
                      console.log(message, request);
                  } else {
                      try {
                          console.log(message + ' ' + JSON.stringify(request));
                      } catch (e) {
                          console.log(message);
                      }
                  }
              }
          },
          logging: true,
          status: 200,
          statusText: "OK",
          responseTime: 500,
          isTimeout: false,
          throwUnmocked: false,
          contentType: 'text/plain',
          response: '',
          responseText: '',
          responseXML: '',
          proxy: '',
          proxyType: 'GET',

          lastModified: null,
          etag: '',
          headers: {
              etag: 'IJF@H#@923uf8023hFO@I#H#',
              'content-type': 'text/plain'
          }
      };

      $.mockjax = function(settings) {
          var i = mockHandlers.length;
          mockHandlers[i] = settings;
          return i;
      };
      $.mockjaxClear = function(i) {
          if (arguments.length == 1) {
              mockHandlers[i] = null;
          } else {
              mockHandlers = [];
          }
          mockedAjaxCalls = [];
      };
      $.mockjax.handler = function(i) {
          if (arguments.length == 1) {
              return mockHandlers[i];
          }
      };
      $.mockjax.mockedAjaxCalls = function() {
          return mockedAjaxCalls;
      };
  })(jQuery);

  $.mockjax({ url: "/mockjax", responseTime: 1000 });

});
