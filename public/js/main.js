var favicon = new Favico({
    bgColor: '#dc0000',
    textColor: '#fff',
    animation: 'slide',
    fontStyle: 'bold',
    fontFamily: 'sans',
    type: 'circle'
});

// Fancybox.bind("[data-fancybox]", {});
// Fancybox.bind("img.data-fancybox", {});
// Fancybox.bind(".data-fancybox img", {});
$('.asideToggle').on('click', function() {
    $('.aside').toggleClass('active');
    $('.aside').toggleClass('in-active');
    $('.main-content').toggleClass('active');
    $('.main-content').toggleClass('in-active');
});
/*
$("a[href='" + window.location.href + "'] >div").addClass('active');

$("a[href='" + window.location.href + "']").parent().parent().prev().children().addClass('active');
$("a[href='" + window.location.href + "']").parent().parent().parent().click();
*/


$('.alert-click-hide').on('click', function() {
    $(this).fadeOut();
});
toastr.options = {progressBar:true,preventDuplicates:true,newestOnTop:true,positionClass:'toast-top-left',timeOut:10000}
let smart_alert = toastr;
