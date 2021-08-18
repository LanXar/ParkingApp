/**
 * Increment button
 */
$("#btn-inc").on('click', function () {

    var time = $('#sim-time').val();
    var step = $('#sim-step').val();

    var re = /^[0-9]{2}\:[0-9]{2}$/;

    if (!re.test(time)) {
        alert('Παρακαλώ εισάγετε σωστά την ώρα εξομοίωσης (ΩΩ:ΛΛ)');
    } else if (!re.test(step)) {
        alert('Παρακαλώ εισάγετε σωστά το βήμα (ΩΩ:ΛΛ)');
    } else {

        var t = time.split(':');
        t_hr = parseInt(t[0], 10);
        t_min = parseInt(t[1], 10);

        var st = step.split(':');
        st_hr = parseInt(st[0], 10);
        st_min = parseInt(st[1], 10);

        if (t_hr < 0 || t_hr > 23) {
            alert('Παρακαλώ εισάγετε σωστή ώρα στο πεδίο ώρας εξομοίωσης (τιμές 00-23)');
        } else if (t_min < 0 || t_min > 59) {
            alert('Παρακαλώ εισάγετε σωστά λεπτά στο πεδίο ώρας εξομοίωσης (τιμές 00-59)');
        } else if (st_hr < 0 || st_hr > 23) {
            alert('Παρακαλώ εισάγετε σωστή ώρα στο πεδίο βήματος (τιμές 00-23)');
        } else if (st_min < 0 || st_min > 59) {
            alert('Παρακαλώ εισάγετε σωστά λεπτά στο πεδίο βήματος (τιμές 00-59)');
        } else {
            var m = t_min + st_min;
            var h = Math.floor(m / 60);
            h += t_hr + st_hr;

            m %= 60;
            h %= 24;

            m = m.toString();
            h = h.toString();

            if (m.length == 1) m = "0" + m;
            if (h.length == 1) h = "0" + h;

            $('#sim-time').val(h + ":" + m);
        }

    }

});

/**
 * Decrement button
 */
$("#btn-dec").on('click', function () {

    var time = $('#sim-time').val();
    var step = $('#sim-step').val();

    var re = /^[0-9]{2}\:[0-9]{2}$/;

    if (!re.test(time)) {
        alert('Παρακαλώ εισάγετε σωστά την ώρα εξομοίωσης (ΩΩ:ΛΛ)');
    } else if (!re.test(step)) {
        alert('Παρακαλώ εισάγετε σωστά το βήμα (ΩΩ:ΛΛ)');
    } else {

        var t = time.split(':');
        t_hr = parseInt(t[0], 10);
        t_min = parseInt(t[1], 10);

        var st = step.split(':');
        st_hr = parseInt(st[0], 10);
        st_min = parseInt(st[1], 10);

        if (t_hr < 0 || t_hr > 23) {
            alert('Παρακαλώ εισάγετε σωστή ώρα στο πεδίο ώρας εξομοίωσης (τιμές 00-23)');
        } else if (t_min < 0 || t_min > 59) {
            alert('Παρακαλώ εισάγετε σωστά λεπτά στο πεδίο ώρας εξομοίωσης (τιμές 00-59)');
        } else if (st_hr < 0 || st_hr > 23) {
            alert('Παρακαλώ εισάγετε σωστή ώρα στο πεδίο βήματος (τιμές 00-23)');
        } else if (st_min < 0 || st_min > 59) {
            alert('Παρακαλώ εισάγετε σωστά λεπτά στο πεδίο βήματος (τιμές 00-59)');
        } else {
            var m = t_min - st_min;
            if (m < 0) {
                m += 60;
                st_hr += 1;
            }

            var h = t_hr - st_hr;
            if (h < 0) {
                h += 24;
            }

            m = m.toString();
            h = h.toString();

            if (m.length == 1) m = "0" + m;
            if (h.length == 1) h = "0" + h;

            $('#sim-time').val(h + ":" + m);
        }

    }

});

/**
 * Simulation start
 */
$("#sim-info-form").submit(function (e) {

    var form = $(this);
    var url = form.attr('action');
    var time = $('#sim-time').val();

    var re = /^[0-9]{2}\:[0-9]{2}$/;

    if (!re.test(time)) {
        alert('Παρακαλώ εισάγετε σωστά την ώρα εξομοίωσης (ΩΩ:ΛΛ)');
    } else {

        var t = time.split(':');
        t_hr = parseInt(t[0], 10);
        t_min = parseInt(t[1], 10);

        if (t_hr < 0 || t_hr > 23) {
            alert('Παρακαλώ εισάγετε σωστή ώρα στο πεδίο ώρας εξομοίωσης (τιμές 00-23)');
        } else if (t_min < 0 || t_min > 59) {
            alert('Παρακαλώ εισάγετε σωστά λεπτά στο πεδίο ώρας εξομοίωσης (τιμές 00-59)');
        } else {
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function (data) {
                    polygon_probabilities = JSON.parse(data);
                    updatePolygons(polygons, polygon_probabilities);
                }
            });
        }

    }

    e.preventDefault(); // avoid to execute the actual submit of the form.
});