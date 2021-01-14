(function ($) {
  "use strict";

  function buildJsonURL(perPage) {
    var jsonUrl = tuts_opt.jsonUrl;
    if (typeof perPage != "undefined" && perPage != null) {
      jsonUrl += "?per_page=" + perPage;
    }
    return jsonUrl;
  }

  $(".recent-tuts-wrapper").each(function () {
    // 1. Create all the required variables

    var $this = $(this),
      termFilter = $this.find(".term-filter"),
      recentTuts = $this.find(".recent-tuts"),
      layout = recentTuts.hasClass("grid") ? "grid" : "list",
      perPage = termFilter.data("per-page"),
      requestRunning = false;

    // 2. Term filter click event

    termFilter.find("a").on("click", function (e) {
      /* 
				3. Prevent link default
				   Make sure that the previous AJAX request is not ranning at the moment
				   Set a new requestRunning

			*/
      e.preventDefault();

      if (requestRunning) {
        return;
      }

      requestRunning = true;

      // 4. Remove current tuts from the tuts list to append requested tuts later

      recentTuts.addClass("loading");
      recentTuts.find("li").remove();

      // 5. Collect current filter data and toggle active class

      var currentFilter = $(this),
        currentFilterLink = currentFilter.attr("href"),
        currentFilterID = currentFilter.data("filter-id");

      currentFilter.addClass("active").siblings().removeClass("active");

      // 6. Build the json AJAX call URL

      var jsonUrl = buildJsonURL(perPage);

      if (typeof currentFilterID != "undefined" && currentFilterID != null) {
        jsonUrl += "&digiconf_category=" + currentFilterID;
      }

      // 7. Send AJAX request

      $.ajax({
        dataType: "json",
        url: jsonUrl,
      })
        .done(function (response) {
          // 8. If success loop with each responce object and create tuturial output

          var output = "";

          $.each(response, function (index, object) {
            output += "<li>";

            output +=
              '<img src="' +
              object.digiconf_image_src +
              '" alt="' +
              object.title.rendered +
              '" />';

            output += '<div class="digiconf-content">';

            output += '<div class="digiconf-category">';
            var digiconfCategories = object.digiconf_category_attr;
            for (var key in digiconfCategories) {
              output +=
                '<a href="' +
                digiconfCategories[key][1] +
                '" title="' +
                digiconfCategories[key][0] +
                '" rel="tag">' +
                digiconfCategories[key][0] +
                "</a> ";
            }
            output += "</div>";

            if ("" != object.title.rendered) {
              output += '<h4 class="digiconf-title entry-title">';
              output +=
                '<a href="' +
                object.link +
                '" title="' +
                object.title.rendered +
                '" rel="bookmark">';
              output += object.title.rendered;
              output += "</a>";
              output += "</h4>";
            }

            if ("" != object.excerpt.rendered && layout == "grid") {
              output +=
                '<div class="digiconf-excerpt">' +
                object.excerpt.rendered.replace(/(<([^>]+)>)/gi, "") +
                "</div>";
            }

            output += '<div class="digiconf-tag">';
            var digiconfTags = object.digiconf_tag_attr;
            for (var key in digiconfTags) {
              output +=
                '<a href="' +
                digiconfTags[key][1] +
                '" title="' +
                digiconfTags[key][0] +
                '" rel="tag">' +
                digiconfTags[key][0] +
                "</a> ";
            }
            output += "</div>";

            output += "</div>";

            output += "</li>";
          });

          // 9. If output is ready append new tuts into the tuts list

          if (output.length) {
            recentTuts.append(output);
            recentTuts.removeClass("loading");
          }
        })
        .fail(function (response) {
          // 10. If fail alert error message

          alert("Something went wront, can't fetch digiconfs");
        })
        .always(function (response) {
          // 11. Always reset the requestRunning to keep sending new AJAX requests

          requestRunning = false;
        });

      return false;
    });
  });
})(jQuery);
