// do admin related script here.

jQuery(document).ready(function () {
  jQuery(document).on("change", "#quiz_points_as_badgeos_points", function () {
    var quiz_points_as_badgeos_points = jQuery(
      "#quiz_points_as_badgeos_points"
    ).val();
    if (quiz_points_as_badgeos_points == "no") {
      jQuery(".quiz_points_as_badgeos_points_check").addClass(
        "quiz_points_as_badgeos_points_hide"
      );
    } else {
      jQuery(".quiz_points_as_badgeos_points_check").removeClass(
        "quiz_points_as_badgeos_points_hide"
      );
    }
  });
});
