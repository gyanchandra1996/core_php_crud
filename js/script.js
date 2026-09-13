$(document).ready(function () {

    $("#state_id").change(function () {

        var state_id = $(this).val();


        // Reset city

        $("#city_id").html(
            '<option value="">Loading...</option>'
        );


        // If no state selected

        if (state_id == "") {

            $("#city_id").html(
                '<option value="">Select City</option>'
            );

            return;

        }


        // AJAX only for State -> City

        $.ajax({

            url: "cities.php",

            type: "POST",

            data: {
                state_id: state_id
            },

            success: function (response) {

                $("#city_id").html(response);

            },

            error: function () {

                $("#city_id").html(
                    '<option value="">Error loading cities</option>'
                );

            }

        });

    });

});