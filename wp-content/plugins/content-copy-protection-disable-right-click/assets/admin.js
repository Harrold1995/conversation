document.addEventListener("DOMContentLoaded", function(event) {
  // Activate first tab
  var ccpdrcFirstTab = document.getElementById("ccpdrc-basic-tab");
  if (ccpdrcFirstTab) {
    ccpdrcFirstTab.classList.add("nav-tab-active");
  }

  // On click of tab activate it
  var ccpdrcTabs = document.querySelectorAll(".nav-tab-wrapper a");
  if (ccpdrcTabs.length) {
    ccpdrcTabs.forEach(function(tab) {
      tab.addEventListener("click", function(e) {
        e.preventDefault();

        // Remove nav-tab-active class
        document.querySelectorAll(".nav-tab-wrapper a").forEach(function(element) {
          element.classList.remove("nav-tab-active");
        });

        // Add class to clicked element
        e.currentTarget.classList.add("nav-tab-active");

        // Hide all tabs content
        document.querySelectorAll(".ccpdrc-tabs").forEach(function(element) {
          element.style.display = "none";
        });

        // Hide save button in case of help tab
        if (e.target.id == "ccpdrc-help-tab") {
          document.querySelectorAll('.ccpdrc-save-settings-container').forEach(function(element) {
            element.style.display = 'none';
          });
        } else {
          document.querySelectorAll('.ccpdrc-save-settings-container').forEach(function(element) {
            element.style.display = 'block';
          });
        }

        // Show content of clicked tab
        var clickedTabHref = e.currentTarget.getAttribute("data-tab");
        if (clickedTabHref) {
          document.getElementById(clickedTabHref).style.display = "block";
        }
      })
    });
  }

  // Send settings to backend on click of submit button
  document.getElementById("ccpdrc-save-settings").addEventListener("click", function(e) {
    e.preventDefault();

    var ccpdrcErrorMessage = document.getElementById("ccpdrc-error-message");
    var ccpdrcSaveSettingButton = document.getElementById("ccpdrc-save-settings");

    ccpdrcErrorMessage.innerHTML = "";
    ccpdrcSaveSettingButton.value = "Saving Settings ...";
    ccpdrcSaveSettingButton.classList.add("is-busy");

    var ccpdrcForm = document.getElementById("ccpdrc-settings-form");
    var ccpdrcData = new URLSearchParams(new FormData(ccpdrcForm));
    
    var ccpdrcXhr = new XMLHttpRequest();
    ccpdrcXhr.open("POST", ajaxurl, true);
    ccpdrcXhr.onreadystatechange = function() {
      if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
          var response = JSON.parse(ccpdrcXhr.response);
          if (response.status == "success") {
            ccpdrcErrorMessage.innerHTML = '<div class="updated"><p>' + response.message + '</p></div>';
          } else if (response.status == "error") {
            ccpdrcErrorMessage.innerHTML = '<div class="error"><p>' + response.message + '</p></div>';
          } else {
            ccpdrcErrorMessage.innerHTML = '<div class="error"><p>No settings were saved.</p></div>';
          }
        } else {
          ccpdrcErrorMessage.innerHTML = '<div class="error"><p>Error occurred while saving settings.</p></div>';
        }
        ccpdrcSaveSettingButton.value = "Save Settings";
        ccpdrcSaveSettingButton.classList.remove("is-busy");
      }
    }
    ccpdrcXhr.send(ccpdrcData);
  });
});
