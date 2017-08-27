$.fn.start = function(rating,cb) {
    var length = $(this).children().length;
    var children = $(this).children();
    var childs = split(children);
    
    $.each(childs, function(i, children){
        //current index ,0 base
        var current = -1;
        var length = children.length; 

        if(typeof(rating) === 'function'){
            cb = rating;
        }else{
            if(rating <1 || rating > length){
                rating = -1;
            }
        }
        //init rating
        current = rating - 1;
        for (var j = 0; j <= current; j++) {
            $(children[j]).removeClass('jr-nomal jr-rating').addClass('jr-rating');
        }
        for (var i = 0; i < length; i++) {

            $(children[i]).bind('mouseover', function(event) {
                current = $(this).index(children[i]);

                $.each(childs, function(i, childrenAlt){
                    for (var j = 0; j <= current; j++) {
                        $(childrenAlt[j]).removeClass('jr-nomal jr-rating').addClass('jr-rating');
                    }
                    for (var j = current + 1; j < length; j++) {
                        $(childrenAlt[j]).removeClass('jr-nomal jr-rating').addClass('jr-nomal');
                    }

                    if (typeof(cb) === 'function') {

                        cb(current + 1);
                    }
                });
            });
        }
    })
}

function split(arr)
{
    var i, j;
    var result = [];
    var max = 5;

    for (i=0, j = arr.length; i < j; i+=max) {
        result.push(arr.slice(i, i + max));
    }

    return result;
}

$.fn.getCurrentRating = function(){
    var length = $(this).children().length;
    var children = $(this).children();
    var resulut = 0;

    return $(this).first().find('.jr-rating').length;

    // for (var i = 0; i < length; i++) {
    //     if($(children[i]).hasClass('jr-rating')){
    //         resulut +=1;
    //     }else{
    //         break;
    //     }
    // }
    // return resulut;
}