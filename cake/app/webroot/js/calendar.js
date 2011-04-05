var calendar
  , now = new Date()
  , currentMonth = now.getUTCMonth() + 1
  , currentYear =  now.getUTCFullYear()
  , months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]

var getMonthName = function(month) {
  return months[month]
}

function getMonthRange(year, month) {
	var starting = year.zeroPad(4) + '-' + month.zeroPad(2) + '-01'
	var ending = (month === 12 ? year + 1 : year).zeroPad(4) + '-' + (month === 12 ? 1 : month + 1).zeroPad(2) + '-01'
	return [starting, ending]
}

function getWeekRange(year, month, week) {
	var monthRange = getMonthRange(year, month)
	var startDate = new Date(monthRange[0])
	var maxDate = startDate.normalize().getDaysPerCurrentMonth()
	var dateOffset = startDate.getUTCDate() - startDate.getUTCDay() + week*7
	var starting = year.zeroPad(4) + '-' + month.zeroPad(2) + '-' + (dateOffset <= 0 ? 1 : dateOffset).zeroPad(2)
	var ending = year.zeroPad(4) + '-' + month.zeroPad(2) + '-' + (dateOffset + 7).zeroPad(2)
	if (dateOffset + 7 >= maxDate) {
		ending = monthRange[1]
	}
	return [starting, ending]
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
		var starting = new Date(range[0]).getUTCDate()-1

		for (i = starting; i < starting + getDaysInRange(range); i++) {
			data.push({date: i + 1})
		}
		for (date in dates) {
			tmp = new Date(date)
			formatedDate = {date: tmp.getUTCDate(),
							events: dates[date]}
			data[tmp.getUTCDate()-starting-1] = formatedDate
		}      		
	}

	$.ajax({ url: 'http://localhost:3000/events?start=' + range[0] + '&end=' + range[1]
         , success: function(res) {
						 processResult(res)
						 callback(padding.concat(data))
						 currentYear = new Date(range[0]).getUTCFullYear();
						 currentMonth = new Date(range[0]).getUTCMonth()+1;
						 $(".current-month").html(getMonthName(currentMonth-1))
						 $(".current-year").html(currentYear)
         }
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
function generateWeekData(year, month, week, callback) {
	var range = getWeekRange(year, month, week)
	var padding = new Date(range[0]).getUTCDay()
	var data = padAndInitMonth(padding)

	generateRangeData(range, data, callback)
}

function switchToMonth(year, month) {
  var data = generateMonthData(year, month, function(data) {
    calendar.days.removeAll()
    calendar.days.create(data)
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

$(function() {
	generateMonthData(currentYear, currentMonth, function(data) {
		calendar = new LiveView($("#calendar-month"), {"days": data})

    $(".current-month").html(getMonthName(currentMonth))
    $(".current-year").html(currentYear)

		$("#next-month").click(nextMonth)
		$("#previous-month").click(previousMonth)
  })
})
