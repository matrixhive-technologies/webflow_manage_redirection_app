// Access the appURL value from the data attribute
var appURL = document.currentScript.getAttribute("data-appurl");

$(document).ready(function () {
  let siteId = null;
  let collectionId = null;
  let createCollectionData = {};
  let createCollectionFieldData = {};
  let createdItemData = {};

  // Initialize the DataTable
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
    $(".add-collection-item").css("display", "block");
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
          if (response.code == 200) {
            getCollectionItems(collectionId);
            dataTable.row($(this).parents("tr")).remove().draw(false);
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

  $("#submitItem").on("click", function () {
    var validationMessage = $(".validation-message");
    /* Validation starts here */
    if (!$("#itemName").val()) {
      $("#itemName").focus();
      validationMessage.text("Item Name is required.");
      return;
    } else {
      validationMessage.text("");
    }

    if (!$("#itemFrom").val()) {
      $("#itemFrom").focus();
      validationMessage.text("From is required.");
      return;
    } else {
      validationMessage.text("");
    }

    if (!$("#itemTo").val()) {
      $("#itemTo").focus();
      validationMessage.text("To is required.");
      return;
    } else {
      validationMessage.text("");
    }
    /* validation ends here */

    itemName = $("#itemName").val();
    itemSlug = $("#itemName").val();
    itemFrom = $("#itemFrom").val();
    itemTo = $("#itemTo").val();

    createdItemData = {
      isArchived: false,
      isDraft: false,
      fieldData: {
        name: itemName,
        slug: itemName.replace(/\s+/g, "-").toLowerCase(),
        from: itemFrom,
        to: itemTo,
      },
    };

    let data = {
      endPoint: "collections/" + collectionId + "/items",
      params: JSON.stringify(createdItemData),
      method: "POST",
    };

    $("#submitItem").prop("disabled", true);
    $.ajax({
      url: appURL + "CallApi.php",
      type: "POST",
      data: data,
      success: function (response) {
        console.log("Success:", response.id);
        if (response.id) {
          $("#submitItem").prop("disabled", false);
          $("#addItemModal").modal("hide");
          getCollectionItems(collectionId);
        } else {
          return false;
        }
      },
      error: function (xhr, status, error) {
        $("#submitItem").prop("disabled", false);
        console.error("Error:", error);
      },
    });
  });

  // Clear the modal popup on re-click
  $("#addItemModal").on("show.bs.modal", function (e) {
    $("#addItemForm")[0].reset();
    $(".validation-message").text("");
  });
});
