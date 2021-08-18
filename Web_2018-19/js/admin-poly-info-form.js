// this is the id of the form
$("#poly-info-form").submit(function (e) {

    var form = $(this);
    var url = form.attr('action');

    var parkSpaces = $('#park-spaces').val();
    var polyId = $('#poly-id').val();

    if (!(parkSpaces == parseInt(parkSpaces, 10) && parseInt(parkSpaces, 10) >= 0)) {
        alert("Παρακαλώ εισάγετε σωστό πλήθος θέσεων.");
    } else if (!polyId) {
        alert("Παρακαλώ επιλέξτε οικοδομικό τετράγωνο.");
    } else {
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function (data) {
                if (data == 'OK') {
                    alert('Το οικοδομικό τετράγωνο ενημερώθηκε');
                } else {
                    alert('Υπήρξε κάποιο σφάλμα. Προσπαθήστε αργότερα');
                }
            }
        });
    }

    e.preventDefault(); // avoid to execute the actual submit of the form.
});