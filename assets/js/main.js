// Access the appURL value from the data attribute
var appURL = document.currentScript.getAttribute("data-appurl");

$(document).ready(function () {
  let siteId = null;
  let collectionId = null;
  let createCollectionData = {};
  let createCollectionFieldData = {};

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
    let slugToCheck = "redirect-management";
    $.ajax({
      url: appURL + "CallApi.php",
      type: "POST",
      data: data,
      success: function (data) {
        console.log("list of collections", data.collections);

        var result = $.grep(data.collections, function (obj) {
          return obj.slug === slugToCheck;
        });

        console.log("result", result);

        if (result.length > 0) {
          collectionId = result[0].id;
          // get the collection items
          getCollectionItems(collectionId);
          console.log("String exists in the array of objects");
        } else {
          dataTable.clear().draw();

          createCollection(siteId, function (createdCollectionId) {
            if (createdCollectionId) {
              // get the collection items using the created collection ID
              console.log("Collection created with ID:", createdCollectionId);
              createCollectionField(createdCollectionId);
            } else {
              console.log("Failed to create the collection");
              return false;
            }
          });
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

  function createCollection(siteId, callback) {
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
        callback(response.id);
      },
      error: function (xhr, status, error) {
        console.error("Error:", error);
      },
    });
  }

  function createCollectionField(collectionId) {
    console.log("collection ID", collectionId);
    let fieldDisplayNames = ["From", "To"];
    for (let i = 0; i < fieldDisplayNames.length; i++) {
      createCollectionFieldData = {
        isRequired: true,
        type: "PlainText",
        displayName: fieldDisplayNames[i],
        helpText: "Enter the " + fieldDisplayNames[i] + " URL here.",
      };
      let data = {
        endPoint: "collections/" + collectionId + "/fields",
        method: "POST",
        params: JSON.stringify(createCollectionFieldData),
      };
      $.ajax({
        url: appURL + "CallApi.php",
        type: "POST",
        data: data,
        success: function (data) {
          console.log("create collection fields", data);
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        },
      });
    }
  }

  // Delete the item list
  $("#collectionEditor tbody").on("click", ".delete-item", function () {
    let itemId = dataTable.row($(this).parents("tr")).data().id;

    let data = {
      endPoint: "collections/" + collectionId + "/items/" + itemId,
      method: "DELETE",
    };

    // Make AJAX call to delete the item
    if (confirm("Are you sure you want to delete this item?")) {
      $.ajax({
        url: appURL + "CallApi.php",
        type: "POST",
        data: data,
        success: function (response) {
          // alert(response);
          console.log("JSON.parse(response)", response.code);
          if (response.code == 200) {
            dataTable.ajax.reload();
            // dataTable.ajax.reload(null, false);
            // dataTable.fnDraw();

            // dataTable.row($(this).parents("tr")).remove().draw(false);
            // dataTable.ajax.reload()
            // dataTable.row($(this).parents("tr")).remove().draw();
            // dataTable.clear();
          } else {
            return false;
          }
        },
        error: function (xhr, status, error) {
          console.error("Error:", error);
        },
      });
    }
  });
});
