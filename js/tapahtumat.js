// Function to toggle the visibility of the add event form popup
function togglePopup() {
    var popup = document.getElementById("addEventPopup");
    if (popup.style.display === "none" || popup.style.display === "") {
        popup.style.display = "block";
    } else {
        popup.style.display = "none";
    }
}

// Function to toggle the visibility of the edit event form popup and populate form fields
function toggleEditPopup(event) {
    document.getElementById("edit_event_id").value = event.id;
    document.getElementById("edit_title").value = event.title;
    document.getElementById("edit_description").value = event.description;
    document.getElementById("edit_start_date").value = event.start_date.replace(" ", "T");
    document.getElementById("edit_end_date").value = event.end_date.replace(" ", "T");
    document.getElementById("edit_location").value = event.location;
    document.getElementById("edit_participants").value = event.participants;

    // Handle additional fields for editing
    var editAdditionalFields = document.getElementById("edit_additionalFields");
    editAdditionalFields.innerHTML = ''; // Clear existing fields

    var additionalFields = JSON.parse(event.additional_fields);
    for (var key in additionalFields) {
        if (additionalFields.hasOwnProperty(key) && additionalFields[key] !== '') {
            var label = document.createElement("label");
            label.textContent = key.charAt(0).toUpperCase() + key.slice(1) + ":";

            var input = document.createElement("input");
            input.type = "text";
            input.name = "edit_" + key;
            input.value = additionalFields[key];

            editAdditionalFields.appendChild(label);
            editAdditionalFields.appendChild(input);
        }
    }

    var popup = document.getElementById("editEventPopup");
    if (popup.style.display === "none" || popup.style.display === "") {
        popup.style.display = "block";
    } else {
        popup.style.display = "none";
    }
}

// Function to cancel editing and hide the edit event form
function cancelEdit() {
    var popup = document.getElementById("editEventPopup");
    popup.style.display = "none";
}

function toggleAdditionalFields() {
    var additionalFields = document.getElementById("additionalFields");
    if (additionalFields.style.display === "none" || additionalFields.style.display === "") {
        additionalFields.style.display = "block";

        // Add labels for additional fields
        additionalFields.innerHTML = `
            <label for="location_additional">Tapahtumapaikka</label>
            <input type="text" name="location_additional"><br>
            <label for="start_date_additional">Aloituspäivämäärä ja -aika</label>
            <input type="datetime-local" name="start_date_additional"><br>
            <label for="end_date_additional">Loppumispäivämäärä ja -aika</label>
            <input type="datetime-local" name="end_date_additional"><br>
            <label for="participants_additional">Maksimi osallistujamäärä</label>
            <input type="number" name="participants_additional"><br>
        `;
    } else {
        additionalFields.style.display = "none";
        additionalFields.innerHTML = ""; // Clear the additional fields
    }
}

// Function to dynamically add more fields for start date, end date, location, and participants
function addMoreFields() {
    var container = document.getElementById("additionalFields");
    var newFields = document.createElement("div");

    newFields.innerHTML = `
        <label for="location_additional">Tapahtumapaikka</label>
        <input type="text" name="location_additional"><br>
        <label for="start_date_additional">Aloituspäivämäärä ja -aika</label>
        <input type="datetime-local" name="start_date_additional"><br>
        <label for="end_date_additional">Loppumispäivämäärä ja -aika</label>
        <input type="datetime-local" name="end_date_additional"><br>
        <label for="participants_additional">Maksimi osallistujamäärä</label>
        <input type="number" name="participants_additional"><br>
    `;

    container.appendChild(newFields);
}