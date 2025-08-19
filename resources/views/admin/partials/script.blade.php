<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        ['#courses', '#products', '#categories'].forEach(function(selector) {
            $(selector).select2({
                placeholder: "Select " + selector.replace('#', ''),
                allowClear: true,
                width: '100%'
            });
        });

        try {
            if (typeof $.fn.tooltip !== 'undefined') {
                $('[data-toggle="tooltip"]').tooltip();
            }
        } catch (error) {
            console.log('Tooltip error:', error);
        }

        try {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
            }
        } catch (error) {
            console.log('Select2 error:', error);
        }

        // Datepicker for start_date and end_date
        function customizeDatepickerButtons(input, inst) {
            setTimeout(function() {
                var buttonPane = $(inst.dpDiv).find('.ui-datepicker-buttonpane');
                buttonPane.find('.ui-datepicker-close').hide();
                buttonPane.find('.ui-datepicker-current').hide();
                buttonPane.find('.ui-datepicker-clear').remove();
                $('<button type="button" class="ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all" style="margin-left:8px;">Clear</button>')
                    .appendTo(buttonPane)
                    .on('click', function() {
                        $(input).val('');
                        $(input).datepicker('hide');
                    });
            }, 1);
        }
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');
        var minDate = yyyy + '-' + mm + '-' + dd;
        $('#start_date, #end_date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            autoclose: true,
            minDate: minDate,
            beforeShow: customizeDatepickerButtons,
            onChangeMonthYear: function(year, month, inst) {
                var input = inst.input ? inst.input[0] : null;
                if (input) customizeDatepickerButtons(input, inst);
            },
            onSelect: function(dateText, inst) {
                var input = inst.input ? inst.input[0] : null;
                if (input) customizeDatepickerButtons(input, inst);
            }
        });
        $('#start_date, #end_date').on('focus click', function() {
            $(this).datepicker('show');
        });

        // Helper functions for error display
        function showError(inputId, message) {
            const input = $('#' + inputId);
            // If it's a Select2 element, place error after the container
            if (input.hasClass('select2')) {
                const container = input.next('.select2'); // Select2 container
                let errorDiv = container.next('.client-validation-error');

                if (errorDiv.length) {
                    errorDiv.text(message);
                } else {
                    container.after('<div class="text-danger client-validation-error mt-1" style="font-size: 12px;">' + message + '</div>');
                }
            } else {
                // Normal input case
                let errorDiv = input.next('.client-validation-error');
                if (errorDiv.length) {
                    errorDiv.text(message);
                } else {
                    input.after('<div class="text-danger client-validation-error mt-1" style="font-size: 12px;">' + message + '</div>');
                }
            }
        }

        function clearError(inputId) {
            const input = $('#' + inputId);
            input.next('.client-validation-error').remove();
        }

        // Real-time validation for Coupon Code
        $('#code').on('blur keyup', function() {
            const value = $(this).val().trim();
            if (value === '') {
                showError('code', 'Coupon code is required.');
            } else if (value.length < 2) {
                showError('code', 'Coupon code must be at least 2 characters.');
            } else if (value.length > 50) {
                showError('code', 'Coupon code must not exceed 50 characters.');
            } else {
                clearError('code');
            }
        });

        // Real-time validation for Discount Type
        $('#type').on('blur change', function() {
            const value = $(this).val();
            if (value === '') {
                showError('type', 'Discount type is required.');
            } else {
                clearError('type');
            }
        });

        // Real-time validation for Discount Value
        $('#amount').on('blur keyup', function() {
            const value = $(this).val();
            if (value === '') {
                showError('amount', 'Discount value is required.');
            } else if (parseFloat(value) < 0) {
                showError('amount', 'Discount value must be at least 0.');
            } else {
                clearError('amount');
            }
        });

        // Real-time validation for Maximum Uses
        $('#max_uses').on('blur keyup', function() {
            const value = $(this).val();
            if (value !== '' && parseInt(value) < 1) {
                showError('max_uses', 'Maximum uses must be at least 1.');
            } else {
                clearError('max_uses');
            }
        });

        // Real-time validation for Status
        $('#status').on('blur change', function() {
            const value = $(this).val();
            if (value === '') {
                showError('status', 'Status is required.');
            } else {
                clearError('status');
            }
        });

        // Real-time validation for Notes
        $('#notes').on('blur keyup', function() {
            const value = $(this).val().trim();
            if (value.length > 500) {
                showError('notes', 'Notes must not exceed 500 characters.');
            } else {
                clearError('notes');
            }
        });

        // Form submission validation
        $('#couponForm').on('submit', function(e) {
            $('.client-validation-error').remove();
            var hasErrors = false;
            if ($('#code').val().trim() === '') {
                showError('code', 'Coupon code is required.');
                hasErrors = true;
            }
            if ($('#type').val() === '') {
                showError('type', 'Discount type is required.');
                hasErrors = true;
            }
            if ($('#amount').val() === '') {
                showError('amount', 'Discount value is required.');
                hasErrors = true;
            }
            if ($('#status').val() === '') {
                showError('status', 'Status is required.');
                hasErrors = true;
            }
            if ($('#start_date').val() !== '' && !/^\d{4}-\d{2}-\d{2}$/.test($('#start_date').val())) {
                showError('start_date', 'Start date format must be YYYY-MM-DD.');
                hasErrors = true;
            }
            if ($('#end_date').val() !== '' && !/^\d{4}-\d{2}-\d{2}$/.test($('#end_date').val())) {
                showError('end_date', 'End date format must be YYYY-MM-DD.');
                hasErrors = true;
            }
            if (hasErrors) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.client-validation-error').first().offset().top - 100
                }, 500);
                return false;
            }
            $('#saveBtn').prop('disabled', true)
                .html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');
        });
    });
</script>