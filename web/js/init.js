/**
 * Created by angel on 11/10/15.
 */
$(document).ready(function () {
    $('.collapsible').collapsible({
        accordion: true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
});
$('.dropdown-button').dropdown({
        inDuration: 300,
        outDuration: 225,
        constrain_width: false, // Does not change width of dropdown to that of the activator
        hover: true, // Activate on hover
        gutter: 0, // Spacing from edge
        belowOrigin: false, // Displays dropdown below the button
        alignment: 'left' // Displays dropdown with edge aligned to the left of button
    }
);

$(document).ready(function () {
    $('ul.tabs').tabs();
});

$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 5 // Creates a dropdown of 15 years to control year
});

$(".button-collapse").sideNav();

$(document).ready(function () {
    $('select').material_select();
});
