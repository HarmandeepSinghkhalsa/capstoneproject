$($(function() {
  $('#datetimepickerfrom').datetimepicker({
    stepping:30,
    format: "YYYY-MM-DD - hh:mm",
    minDate: new Date()
  });

  $('#datetimepickerto').datetimepicker({
    stepping: 30,
    format: "YYYY-MM-DD - hh:mm",
    useCurrent: false //Important! See issue #1075
  });

  $("#datetimepickerfrom").on("dp.change", function(e) {
    $('#datetimepickerto').data("DateTimePicker").minDate(e.date);
  });

  $("#datetimepicker10").on("dp.change", function(e) {
    $('#datetimepickerfrom').data("DateTimePicker").maxDate(e.date);
  });
});
);
