// Access the appURL value from the data attribute
var appURL = document.currentScript.getAttribute("data-appurl");

$(document).ready(function () {
  let siteId = null;
  let collectionId = null;
  let createCollectionData = {};
  // Initialize an empty DataTable
  var dataTable = $("#collectionEditor").DataTable({
    columns: [
      {
        data: "id",
      },
      {
        data: "name",
      },
      {
        data: "slug",
      },
      {
        data: "from",
      },
      {
        data: "to",
      },
      {
        // Action column
        orderable: false,
        targets: -1, // Target the last column
        render: function (data, type, full, meta) {
          return (
            '<button class="btn btn-danger delete-item" data-id="' +
            full.id +
            '">Delete</button>'
          );
        },
      },
    ],
  });

  // Get the list of sites
  function listOfSites() {
    let data = {
      endPoint: "sites",
    };

    $.ajax({
      url: appURL + "CallApi.php",
      type: "POST",
      data: data,
      success: function (data) {
        console.log("list of sites", data.sites);
        for (var i = 0; i < data.sites.length; i++) {
          $("#selectSite").append(
            '<option value="' +
              data.sites[i].id +
              '">' +
              data.sites[i].displayName +
              "</option>"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }
  listOfSites();

  // Get the collection for the selected site.
  $("#selectSite").on("change", function () {
    siteId = $(this).val();
    getCollection(siteId);
  });

  function getCollection(siteId) {
    let data = {
      endPoint: "sites/" + siteId + "/collections",
      params: siteId,
    };

    $.ajax({
      url: appURL + "CallApi.php",
      type: "POST",
      data: data,
      success: function (data) {
        console.log("list of collections", data.collections);

        for (var i = 0; i < data.collections.length; i++) {
          if (data.collections[i].slug == "redirect-management") {
            collectionId = data.collections[i].id;
            // get the collection items
            getCollectionItems(collectionId);
          } else {
            // create the collection with above slug
            let asad = createCollection(siteId);
            console.log("created col id", asad);
          }
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  function getCollectionItems(collectionId) {
    console.log("collection ID", collectionId);
    $.ajax({
      url: appURL + "CallApi.php",
      type: "POST",
      data: {
        endPoint: "collections/" + collectionId + "/items/",
      },
      success: function (response) {
        console.log("get CollectionItems successful", response.items);
        let rows = [];
        for (let i = 0; i < response.items.length; i++) {
          rows.push({
            id: response.items[i].id,
            name: response.items[i].fieldData.name,
            slug: response.items[i].fieldData.slug,
            from: response.items[i].fieldData.from,
            to: response.items[i].fieldData.to,
          });
        }

        // Clear existing DataTable and add new rows
        dataTable.clear();
        dataTable.rows.add(rows);
        dataTable.draw();
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  function createCollection(siteId) {
    createCollectionData = {
      displayName: "Redirect Managements",
      singularName: "Redirect Management",
    };
    let data = {
      endPoint: "sites/" + siteId + "/collections",
      method: "POST",
      params: JSON.stringify(createCollectionData),
    };

    console.log("created data", data);

    $.ajax({
      url: appURL + "CallApi.php",
      type: "POST",
      data: data,
      success: function (response) {
        console.log("response create", response);
        if (response.code == "duplicate_collection") {
          return false;
        }

        if (response && response.id) {
          return response.id;
        }
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  function createCollectionItem(collectionId) {}

  // Delete the item list
  $("#collectionEditor tbody").on("click", ".delete-item", function () {
    let itemId = dataTable.row($(this).parents("tr")).data().id;
    // Make AJAX call to delete the item
    if (confirm("Are you sure you want to delete this item?")) {
      $.ajax({
        url: appURL + "CallApi.php",
        type: "DELETE",
        data: {
          endPoint: "collections/" + collectionId + "/items/" + itemId,
          method: "DELETE",
        },
        success: function (response) {
          alert("asad" + response);
          // dataTable.row($(this).parents("tr")).remove().draw();
          dataTable.ajax.reload();
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        },
      });
    }
  });
});
