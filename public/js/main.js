$(document).ready(function() {
  // Search Email Account
  $("#search_email_account").click(function() {
    var $term = $("#search_string").val();

    if (!$term) {
      alert("Sorry, You must enter a term to search.");
      return false;
    }

    // $app.showLoadingOverlay('#email-account-emails');

    $.get("/admin/integrations/google/search_email", { term: $term }, function(
      response
    ) {
      // $app.hideLoadingOverlay('#email-account-emails');

      $("#emails-table").html(response.html);
    });
  });

  // Remove Participant
  $(".view-email-contents").click(function() {
    var $id = $(this).attr("data-id");
    var $subject = $(this).html();

    if (!$id) {
      alert("Sorry, You must enter a term to search.");
      return false;
    }

    $("#internal").attr(
      "src",
      "/admin/integrations/google/view_email_message&id=" + $id
    );

    // Show modal block
    $("#email_view_modal_title").html($subject);
    $("#email_view_modal").modal();
  });

  $("iframe").load(function() {
    $("#internal")
      .contents()
      .find("body")
      .append($('<style type="text/css">p {margin:5px;}</style>'));

    var maxHeight = 500;
    var height = $(this)
      .contents()
      .innerHeight();

    if (height > maxHeight) {
      $("iframe").css({ height: parseInt(maxHeight) + "px" });
    } else if (
      height < maxHeight &&
      height >
        $("iframe")
          .parent()
          .innerHeight()
    ) {
      $("iframe").css({ height: parseInt(height + 80) + "px" });
    }
  });
});
