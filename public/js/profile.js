// update email
var KTUsersUpdateEmail = function () {
    // Shared variables
    const element = document.getElementById('zero_modal_user_update_email');
    const form = element.querySelector('#zero_modal_user_update_email_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdateEmail = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'profile_email': {
                        validators: {
                            notEmpty: {
                                message: i18next.t('email address is required')
                            },
                            emailAddress: {
                                message: i18next.t('the value is not a valid email address')
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        // Show loading indication
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        // Disable button to avoid multiple click 
                        submitButton.disabled = true;

                        // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        setTimeout(function () {

                            // Show popup confirmation 
                            $.ajax({
                                type: "POST",
                                url: "/user/update_profile/email",
                                dataType: "json",
                                data: {
                                    newemail: $("#profile_email").val()
                                },
                                success: function(data) {
                                    if(data.ret === 1) {
                                        Swal.fire({
                                            text: data.msg,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        getResult(data.msg, '', 'error');
                                        // Remove loading indication
                                        submitButton.removeAttribute('data-kt-indicator');

                                        // Enable button
                                        submitButton.disabled = false;
                                    }
                                }
                            });
                        }, 2000);
                    }
                });
            }
        });
    }

    return {
        // Public functions
        init: function () {
            initUpdateEmail();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateEmail.init();
});

// update password
var KTUsersUpdatePassword = function () {
    // Shared variables
    const element = document.getElementById('zero_modal_user_update_password');
    const form = element.querySelector('#zero_modal_user_update_password_form');
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initUpdatePassword = () => {

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'current_password': {
                        validators: {
                            notEmpty: {
                                message: i18next.t('password is required')
                            }
                        }
                    },
                    'password': {
                        validators: {
                            notEmpty: {
                                message: i18next.t('password is required')
                            },
                            callback: {
                                message: i18next.t('please enter valid password'),
                                callback: function (input) {
                                    if (input.value.length > 0) {
                                        return validatePassword();
                                    }
                                }
                            }
                        }
                    },
                    'confirm_password': {
                        validators: {
                            notEmpty: {
                                message: i18next.t('password confirmation is required')
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="new_password"]').value;
                                },
                                message: i18next.t('password and its confirm are not the same')
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        // Submit button handler
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            if (validator) {
                validator.validate().then(function (status) {
                    console.log('validated!');

                    if (status == 'Valid') {
                        submitButton.setAttribute('data-kt-indicator', 'on');

                        submitButton.disabled = true;

                        setTimeout(function () {
                            
                            $.ajax({
                                type: "POST",
                                url: "/user/update_profile/password",
                                dataType: "json",
                                data: {
                                    current_password: $("#current_password").val(),
                                    new_password: $("#new_password").val()
                                },
                                success: function(data) {
                                    if(data.ret === 1) {
                                        Swal.fire({
                                            text: data.msg,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                modal.hide();
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        getResult(data.msg, '', 'error');
                                        submitButton.removeAttribute('data-kt-indicator');
                                        submitButton.disabled = false;
                                    }
                                }
                            });

                            //form.submit(); // Submit form
                        }, 2000);
                    }
                });
            }
        });
    }

    return {
        // Public functions
        init: function () {
            initUpdatePassword();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePassword.init();
});

// enable notify 
function KTUsersEnableNotify(type) {
    if (document.getElementById('notify_email').checked) {
        var types = type;
    }else if (document.getElementById('notify_telegram').checked) {
        var types = type;
    }
    $.ajax({
        type: "POST",
        url: "/user/enable_notify",
        dataType: "json",
        data: {
            notify_type: types
        },
        success: function(data) {}
    });
}

//reset ss connet passwd
function KTUsersResetPasswd() {
    $.ajax({
        type: "POST",
        url: "/user/update_profile/passwd",
        dataType: "json",
        data: {},
        success: function(data) {
            if(data.ret === 1) {
                Swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                getResult(data.msg, '', 'error');
            }
        }
    });
}

// reset uuid
function KTUsersResetUUID() {
    $.ajax({
        type: "POST",
        url: "/user/update_profile/uuid",
        dataType: "json",
        data: {},
        success: function(data) {
            if(data.ret === 1) {
                Swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                getResult(data.msg, '', 'error');
            }
        }
    });
}

// reset sub link
function KTUsersResetSubLink() {
    $.ajax({
        type: "POST",
        url: "/user/update_profile/sub_token",
        dataType: "json",
        data: {},
        success: function(data) {
            if(data.ret === 1) {
                Swal.fire({
                    text: data.msg,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                getResult(data.msg, '', 'error');
            }
        }
    });
}