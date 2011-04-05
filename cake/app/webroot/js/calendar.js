var monthView
  , weekView
  , eventView
  , now = new Date()
  , currentView
  , currentMonth = now.getUTCMonth() + 1
  , currentYear =  now.getUTCFullYear()
  , currentWeek = now.getUTCWeekOfYear()
  , months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
  , eventRegistry = {}

var getMonthName = function(month) {
  return months[month]
}

function getMonthRange(year, month) {
	var starting = year.zeroPad(4) + '-' + month.zeroPad(2) + '-01'
	var ending = (month === 12 ? year + 1 : year).zeroPad(4) + '-' + (month === 12 ? 1 : month + 1).zeroPad(2) + '-01'
	return [starting, ending]
}

function getWeekRange(year, week) {
  var startOfWeek = new Date(new Date(year, 0, 2).getTime() + week * 7 * 24 * 60 * 60 * 1000)
  var endOfWeek = new Date(new Date(year, 0, 2).getTime() + (week + 1) * 7 * 24 * 60 * 60 * 1000)
	return [startOfWeek.getYYYYMMDD(), endOfWeek.getYYYYMMDD()]
} 

function getDaysInRange(range) {
  return Math.floor((new Date(range[1]) - new Date(range[0]))/(1000*3600*24))
}

function generateRangeData(range, padding, callback) {
	var data = [];

	function processResult(res) {
		var dates = JSON.parse(res)
		var i
		var date
		var tmp
		var formatedDate
    var nextDate = new Date(range[0]) 
		var starting = nextDate.getUTCDate()-1

		for (i = starting ; i < starting + getDaysInRange(range) ; i++) {
			data.push({date: nextDate.getUTCDate(), events: []})
      nextDate = nextDate.tomorrow()
		}
		for (date in dates) {
			tmp = new Date(date)
      dates[date].forEach(function(evt) {
        var secondsSinceMidnight = (new Date(parseInt(evt.start_timestamp))).secondsSinceMidnight()
        var duration = parseInt(evt.end_timestamp) - parseInt(evt.start_timestamp)
        evt["week-event"] = {style: "top: " + secondsSinceMidnight / 860 + "%; height: " + duration / 860000 + "%;"}
        evt["event"] = {"data-event": evt.id}
        console.log((new Date(parseInt(evt.start_timestamp))).getHours())
        evt["start-time"] = (new Date(parseInt(evt.start_timestamp))).getHours() + ":" + (new Date(parseInt(evt.start_timestamp))).getMinutes().zeroPad(2)
        eventRegistry[evt.id] = evt
      })
			formatedDate = {date: tmp.getUTCDate(), events: dates[date]}
			data[tmp.getUTCDate() - starting - 1] = formatedDate
		}     		
	}

	$.ajax({ url: '/events?start=' + range[0] + '&end=' + range[1]})
  .success(function(res) {
     processResult(res)
     callback(padding.concat(data))
     currentYear = new Date(range[0]).getUTCFullYear();
     currentMonth = new Date(range[0]).getUTCMonth()+1;
     $(".current-month").html(getMonthName(currentMonth-1))
     $(".current-year").html(currentYear)
     $(".start-of-week").html(new Date(new Date(range[0]).getTime() + 24 * 60 * 60 * 1000).toDateString())
     $(".end-of-week").html(new Date(range[1]).toDateString())
  })
}

function padAndInitMonth(padding) {
  var data = [] 
    , i
	for (i = 0; i < padding; i++) {
		data.push({day: "&nbsp;"})
	}
  return data
}

// 1-based month
function generateMonthData(year, month, callback) {
	var range = getMonthRange(year, month)
	var padding = new Date(range[0]).getUTCDay()
	var data = padAndInitMonth(padding)

	generateRangeData(range, data, callback)
}

// 1-based month, 0-based week
function generateWeekData(year, week, callback) {
	var range = getWeekRange(year, week)
	var padding = new Date(range[0]).getUTCDay()
	var data = padAndInitMonth(padding)

	generateRangeData(range, data, callback)
}

function switchToMonth(year, month) {
  generateMonthData(year, month, function(data) {
    monthView.days.removeAll()
    monthView.days.create(data)
  })
}

function switchToWeek(year, week) {
  generateWeekData(year, week, function(data) {
    weekView.days.removeAll()
    weekView.days.create(data)
  })
}  

function switchToEvent() {
  generateEventData(evt, function(data) {
    weekView.days.removeAll()
    weekView.days.create(data)
  })
}  
 

function nextMonth() {
  if(currentMonth === 12) {
    currentMonth = 1
    currentYear++
  } else {
    currentMonth++
  }
	switchToMonth(currentYear, currentMonth)
}

function previousMonth() {
  if(currentMonth === 1) {
    currentMonth = 12
    currentYear--
  } else {
    --currentMonth
  }
	switchToMonth(currentYear, currentMonth)
}
 
function nextWeek() {
  if(currentWeek === 51) {
    currentWeek = 0
    currentYear++
  } else {
    currentWeek++
  }
	switchToWeek(currentYear, currentWeek)
}

function previousWeek() {
  if(currentWeek === 0) {
    currentWeek = 51
    currentYear--
  } else {
    currentWeek--
  }
	switchToWeek(currentYear, currentWeek)
}
 
function initWeekView() {
   generateWeekData(currentYear, currentWeek, function(data) {
		weekView = new LiveView($("#calendar-week"), {"days": data})
  })   
}

function initMonthView() {
	generateMonthData(currentYear, currentMonth, function(data) {
		monthView = new LiveView($("#calendar-month"), {"days": data})
  }) 
}
 
function initEventView() {
  eventView = new LiveView($("#calendar-event"), {"events": []})
}
 

function showView(view) {
  if(view === "month" && currentView !== "month") {
    $("#calendar-month").show()
    $("#month-controls").show()

    $("#calendar-event").hide()

    $("#calendar-week").hide()
    $("#week-controls").hide()
    switchToMonth(currentYear, currentMonth)
  } else if(view === "week" && currentView !== "week") {
    $("#calendar-month").hide()
    $("#month-controls").hide()

    $("#calendar-event").hide()

    $("#calendar-week").show()
    $("#week-controls").show()
    switchToWeek(currentYear, currentWeek)
  } else if(view === "event" && currentView !== "event") {
    $("#calendar-event").show()
    $("#calendar-month").hide()
    $("#calendar-week").hide()
    $("#month-controls").hide()
    $("#week-controls").hide()
  } 
  currentView = view
}

$(function() {
  initMonthView()
  setTimeout(initWeekView, 1000)
  initEventView()
  showView("month")
  $("#month-view").click(function() { showView("month") })
  $("#week-view").click(function() { showView("week") })
  $("#event-view").click(function() { showView("event") })
 
	$("#next-week").click(nextWeek)
	$("#previous-week").click(previousWeek) 

	$("#next-month").click(nextMonth)
	$("#previous-month").click(previousMonth)
})

$("#calendar-month .event, #calendar-week .event").live("click", function() {
  var id = $(this).attr("data-event")
  eventView.events.removeAll()
  eventView.events.create(eventRegistry[id])
  showView("event")
})

$("#search-box").live("click", function() {
  var id = $(this).attr("data-event")
  eventView.events.removeAll()
  eventView.events.create(eventRegistry[id])
  showView("event")
}) 
