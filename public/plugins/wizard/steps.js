$(document).ready(function() {
    var btnFinish = $('<button></button>').text('Envoyer')
        .addClass('btn btn-info btn-finish btn-lg')
        .on('click', function() {
            if (!$(this).hasClass('disabled')) {
                var elmForm = $("#step");
                if (elmForm) {
                    elmForm.validator('validate');
                    var elmErr = elmForm.find('.has-error');
                    if (elmErr && elmErr.length > 0) {
                        swal("Erreur", "Une erreur est survenue nous ne pouvons pas envoyer votre formulaire d'intervention. Merci de vérifier les informations saisies", "warning");
                        return false;
                    } else {
                        //swal("Bon travail!", "Votre formulaire d'intervention a bien été sauvegardé.", "success");
                        elmForm.submit();
                        return false;
                    }
                }
            }
        });
    var btnCancel = $('<button></button>').text('Annuler')
        .addClass('btn btn-danger btn-end btn-lg')
        .on('click', function() {
            $('#smartwizard').smartWizard("reset");
            $('#step').find("input, textarea").val("");
        });
    $('#smartwizard').smartWizard({
        selected: 0,
        keyNavigation:true,
        enableFinishButton: false,
        autoAdjustHeight:true,
        transitionEffect: 'fade',
        transitionSpeed: '400',
        transitionEffect: 'fade',
        toolbarSettings: {
            toolbarPosition: 'bottom',
            toolbarExtraButtons: [btnCancel, btnFinish]
        },
        anchorSettings: {
            markDoneStep: true, // add done css
            markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
            removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
            enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
        }
    });
    $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
        var elmForm = $("#form-step-" + stepNumber);
        // stepDirection === 'forward' :- this condition allows to do the form validation
        // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
        if (stepDirection === 'forward' && elmForm) {
            elmForm.validator('validate');
            var elmErr = elmForm.children('.has-error');
            if (elmErr && elmErr.length > 0) {
                // Form validation failed
                return false;
            }
        }
        return true;
    });
    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
        if($('button.sw-btn-next').hasClass('disabled')){
            $('.sw-btn-group-extra').show(); // show the button extra only in the last page
            $('.sw-btn-group').hide();

        }else{
            $('.sw-btn-group-extra').hide();
            $('.sw-btn-group').show();
        }
    });

});