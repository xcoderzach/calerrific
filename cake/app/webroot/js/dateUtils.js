Date.prototype.getUTCWeek = function() {
  return Math.floor((this.getUTCDay() + this.getUTCDate() - 1) / 7)
}

Number.prototype.zeroPad = function(length) {
  var newString = this.toString()
  while(newString.length < length) {
    newString = ("0" + newString)
  }
  return newString
}

Date.prototype.getDaysPerCurrentMonth = function() {
  var nextMonth
  if(this.getMonth() != 11) {
    nextMonth = new Date((new Date(this.getFullYear(), this.getMonth() + 1, 1)) - 1).getDate()
  } else {
    nextMonth = new Date((new Date(this.getFullYear() + 1, 0, 1)) - 1).getDate()
  }
  return nextMonth
  
}

Date.prototype.getYYYYMMDD = function() {
    var year = this.getUTCFullYear()
    var month = (this.getUTCMonth()+1).zeroPad()
    var date = this.getUTCDate().zeroPad()
    return year + "-" + month + "-" + date
}

Date.prototype.normalize = function() {
		return new Date(this.getUTCFullYear()
                              , this.getUTCMonth()
                              , this.getUTCDate())
}
 
