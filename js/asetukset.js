function editItem(itemId) {
    var newName = prompt("Edit item name:");
    if (newName !== null && newName.trim() !== "") {
        var formData = new FormData();
        formData.append("itemId", itemId);
        formData.append("newName", newName);

        fetch(window.location.href, { // Send the request to the current page
            method: "POST",
            body: formData,
        })
        .then(response => response.text())
        .then(result => {
            if (result === "Item edited successfully.") {
                alert("Item edited successfully.");
                location.reload();
            } else {
                alert("Error editing item.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
}