var calendar;
var Calendar = FullCalendar.Calendar;
var events = [];

$(function () {
    if (!!scheds) {
        Object.keys(scheds).map(k => {
            var row = scheds[k]
            events.push({
                id: row.id,
                form_type: row.form_type,
                title: row.title,
                start: row.start_datetime,
                end: row.end_datetime,
                job_position_1: row.job_position_1,
                job_position_2: row.job_position_2,
                job_position_3: row.job_position_3,
                job_position_4: row.job_position_4,
                job_position_5: row.job_position_5,
                job_position_6: row.job_position_6
            });
        })
    }
    var date = new Date()
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear()

    $.getScript('../assets/fullcalendar/lib/locales/th.js', function () {
        calendar = new Calendar(document.getElementById('calendar'), {
            headerToolbar: {
                left: 'prev,next today',
                right: 'dayGridMonth,dayGridWeek,list',
                center: 'title',
            },
            selectable: true,
            themeSystem: 'bootstrap',
            //Random default events
            events: events,
            eventClick: function (info) {
                var _details = $('#event-details-modal')
                var id = info.event.id
                if (!!scheds[id]) {

                    const WorkD = document.getElementById('work-day');
                    const bt = document.getElementById('leave-bt');

                    if (scheds[id].form_type == '0') {
                        WorkD.style.display = 'none';
                        bt.style.display = 'block';
                    } else if (scheds[id].form_type == '1') {
                        WorkD.style.display = 'block';
                        bt.style.display = 'block';
                    } else if (scheds[id].form_type == '3') {
                        WorkD.style.display = 'none';
                        bt.style.display = 'none';
                    } else {
                        WorkD.style.display = 'none';
                        bt.style.display = 'block';
                    }
                    _details.find('#title').text(scheds[id].title)
                    _details.find('#description').text(scheds[id].description)
                    _details.find('#job_position_1').text(scheds[id].job_position_1)
                    _details.find('#job_position_2').text(scheds[id].job_position_2)
                    _details.find('#job_position_3').text(scheds[id].job_position_3)
                    _details.find('#job_position_4').text(scheds[id].job_position_4)
                    _details.find('#job_position_5').text(scheds[id].job_position_5)
                    _details.find('#job_position_6').text(scheds[id].job_position_6)
                    _details.find('#start').text(scheds[id].sdate)
                    _details.find('#end').text(scheds[id].edate)
                    _details.find('#edit,#delete').attr('data-id', id)
                    _details.modal('show')
                } else {
                    alert("Event is undefined");
                }
            },
            eventDidMount: function (info) {
                // Do Something after events mounted
            },
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            drop: false,

            // Set language to Thai
            locale: 'th',

            // Add custom CSS class to weekend cells
            dayCellClassNames: function (arg) {
                var day = arg.date.getDay();
                if (day === 6 || day === 0) { // 6 is Saturday, 0 is Sunday
                    return 'highlight-weekend';
                }
                return '';
            },
        });

        calendar.render();
    });

    // Edit Button
    $('#edit').click(function () {
        // Get the id from the data-id attribute of the clicked button
        var id = $(this).attr('data-id');

        // Check if the event with the given id exists in scheds
        if (!!scheds[id]) {
            var _form = $('#schedule-form');

            // Update form fields with event data
            _form.find('[name="id"]').val(id);
            _form.find('[name="form_type"]').val(scheds[id].form_type);
            _form.find('[name="description"]').val(scheds[id].description);
            _form.find('[name="job_position_1"]').val(scheds[id].job_position_1);
            _form.find('[name="job_position_2"]').val(scheds[id].job_position_2);
            _form.find('[name="job_position_3"]').val(scheds[id].job_position_3);
            _form.find('[name="job_position_4"]').val(scheds[id].job_position_4);
            _form.find('[name="job_position_5"]').val(scheds[id].job_position_5);
            _form.find('[name="job_position_6"]').val(scheds[id].job_position_6);

            // Format and set datetime values
            var start_date = new Date(scheds[id].start_datetime);
            var end_date = new Date(scheds[id].end_datetime);

            var start_time = start_date.toTimeString().split(' ')[0];
            var end_time = end_date.toTimeString().split(' ')[0];

            var start_iso_date = start_date.toISOString().split('T')[0];
            var end_iso_date = end_date.toISOString().split('T')[0];

            _form.find('[name="date"]').val(start_iso_date);
            _form.find('[name="start_time"]').val(start_time);
            _form.find('[name="end_time"]').val(end_time);

            _form.find('[name="start_datetime"]').val(start_iso_date);
            _form.find('[name="end_datetime"]').val(end_iso_date);

            // Hide the event details modal
            $('#event-details-modal').modal('hide');

            // Focus on the title input field for better user experience
            _form.find('[name="title"]').focus();

            const formTypeSelect = document.querySelector('select[name="form_type"]');
            const jobForm = document.getElementById('job-po');
            const Form = document.getElementById('form');
            const Form2 = document.getElementById('form2');

            formTypeSelect.addEventListener('change', function () {
                const selectedValue2 = this.value;

                if (selectedValue2 === '0') {
                    Form.style.display = 'none';
                    Form2.style.display = 'none';
                } else if (selectedValue2 === '1') {
                    Form.style.display = 'block';
                    Form2.style.display = 'block';
                    jobForm.style.display = 'block'; // แสดง form ที่มี id "job-po"
                } else {
                    Form.style.display = 'block';
                    Form2.style.display = 'block';
                    jobForm.style.display = 'none'; // ซ่อน form ที่มี id "job-po"
                }
            });
            // เรียกใช้ฟังก์ชันเพื่อตรวจสอบค่าเริ่มต้น
            formTypeSelect.dispatchEvent(new Event('change'));
            _form.find('[name="title"]').val(scheds[id].title);
        } else {
            // Notify the user if the event is undefined
            alert("Event is undefined");
        }
    });

    // Delete Button / Deleting an Event
    $('#delete').click(function () {
        var id = $(this).attr('data-id')
        if (!!scheds[id]) {
            var _conf = confirm("คุณแน่ใจหรือว่าจะลบกำหนดการเวลานี้ ?");
            if (_conf === true) {
                location.href = "schedule/delete_schedule.php?id=" + id;
            }
        } else {
            alert("Event is undefined");
        }
    })
})