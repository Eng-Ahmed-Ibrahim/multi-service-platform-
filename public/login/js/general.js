"use strict";
var KTSigninGeneral = (function () {
    var e, t, i;
    return {
        init: function () {
            (e = document.querySelector("#kt_sign_in_form")),
                (t = document.querySelector("#kt_sign_in_submit")),
                (i = FormValidation.formValidation(e, {
                    fields: {
                        email: {
                            validators: {
                                regexp: {
                                    regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                    message:
                                        "The value is not a valid email address",
                                },
                                notEmpty: {
                                    message: "Email address is required",
                                },
                            },
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "The password is required",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: ".fv-row",
                            eleInvalidClass: "",
                            eleValidClass: "",
                        }),
                    },
                })),
                t.addEventListener("click", function (n) {
                    n.preventDefault(),
                   
                    console.log(t.removeAttribute("data-kt-indicator")),
                        i.validate().then(function (i) {
                            "Valid" == i
                                ? (t.setAttribute("data-kt-indicator", "on"),
                                  (t.disabled = !0),
                                  setTimeout(function () {
                                   
                                      t.removeAttribute("data-kt-indicator"),
                                          (t.disabled = !1),
                                          Swal.fire({
                                              text: "Confirm login!",
                                              icon: "success",
                                              buttonsStyling: !1,
                                              confirmButtonText: "Ok, got it!",
                                              customClass: {
                                                  confirmButton:
                                                      "btn btn-primary",
                                              },
                                          }).then(function (t) {
                                           
                                              if (t.isConfirmed) {
                                                var urlForm = $('.form-ajax').data('action');
                                                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                                // var dataSend = new FormData($('.form-ajax'));
                                                var dataSend = {
                                                    email: $("#email").val(),
                                                    password: $("#password").val(),
                                                    remember_me: $("#remember_me").val(),
                                                    _token: CSRF_TOKEN,
                                                };
                                                $.ajax({
                                                    type: 'POST',
                                                    url: urlForm,
                                                    data: dataSend,
                                                    dataType: 'JSON',
                                                    success: function (results) {
                                                   
                                                        if (results.login.success === true) {
                                                            swal.fire("Done!", results.login.message, "success");
                                                            // refresh page after 2 seconds
                                                            setTimeout(function(){
                                                                window.location.replace(results.login.route);
                                                            },2000);
                                                        } else {
                                                            console.log('Error');
                                                            swal.fire("Error!", results.message, "error");
                                                        }
                                                    },
                                                    error: function(error) {
                                                        if(error.status == 422){
                                                            console.log('Error 422');
                                                            $('.error-content').empty();
                                                            var response = $.parseJSON(error.responseText);
                                                            $.each(response.errors, function(key, value) {
                                                                var newKey = key.split('.').join("");
                                                                // $('#error-' + newKey).text(value);
                                                                toastr.error(value, Error, {
                                                                    CloseButton: true,
                                                                    ProgressBar: true
                                                                });
                                                            });
                                                            // swal.fire("Error!", error.responseText, "error");
                                                        }else {
                                                            console.log('Error');
                                                            swal.fire("Error!", error.responseText, "error");
                                                        }
                                                    }
                                                });
                                              }

                                          });
                                  }, 500))
                                : Swal.fire({
                                      text: "Sorry, looks like there are some errors detected, please try again.",
                                      icon: "error",
                                      buttonsStyling: !1,
                                      confirmButtonText: "Ok, got it!",
                                      customClass: {
                                          confirmButton: "btn btn-primary",
                                      },
                                  });
                        });
                });
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTSigninGeneral.init();
});
