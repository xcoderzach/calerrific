Date.prototype.getUTCWeek = function() {
  return Math.floor((this.getUTCDay() + this.getUTCDate() - 1) / 7)
}

Date.prototype.tomorrow = function() {
  return new Date(this.getTime() + 24 * 60 * 60 * 1000)
}

Date.prototype.secondsSinceMidnight = function() {
  return Math.floor((this.getTime() - (new Date(this.getUTCFullYear(), this.getUTCMonth(), this.getUTCDate())).getTime()) / 1000)
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

Date.prototype.getUTCWeekOfYear = function() {

  var onejan = new Date(this.getUTCFullYear(),0,1);
  return Math.floor((((this - onejan) / 86400000) + onejan.getUTCDay() + 1)/7) - 1;
} 

Date.prototype.getYYYYMMDD = function() {
    var year = this.getUTCFullYear()
    var month = (this.getUTCMonth()+1).zeroPad(2)
    var date = this.getUTCDate().zeroPad(2)
    return year + "-" + month + "-" + date
}

Date.prototype.normalize = function() {
		return new Date(this.getUTCFullYear()
                              , this.getUTCMonth()
                              , this.getUTCDate())
}
 
