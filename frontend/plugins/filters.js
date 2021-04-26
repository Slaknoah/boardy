import Vue from 'vue'

Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  const arr = value.split(' ')
  const capitalized_array = []
  arr.forEach((word) => {
    const capitalized = word.charAt(0).toUpperCase() + word.slice(1)
    capitalized_array.push(capitalized)
  })
  return capitalized_array.join(' ')
})

Vue.filter('title', function (value, replacer = '_') {
  if (!value) return ''
  value = value.toString()

  const arr = value.split(replacer)
  const capitalized_array = []
  arr.forEach((word) => {
    const capitalized = word.charAt(0).toUpperCase() + word.slice(1)
    capitalized_array.push(capitalized)
  })
  return capitalized_array.join(' ')
})

Vue.filter('truncate', function (value, limit) {
  return value.substring(0, limit)
})

Vue.filter('tailing', function (value, tail) {
  return value + tail
})

Vue.filter('time', function (value, is24HrFormat = false) {
  if (value) {
    const date = new Date(Date.parse(value))
    let hours = date.getHours()
    const min = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()
    if (!is24HrFormat) {
      const time = hours > 12 ? 'AM' : 'PM'
      hours = hours % 12 || 12
      return `${hours}:${min} ${time}`
    }
    return `${hours}:${min}`
  }
})

Vue.filter('date', function (value, fullDate = false) {
  value = String(value)
  const date = value.slice(8, 10).trim()
  const month = value.slice(4, 7).trim()
  const year = value.slice(11, 15)

  if (!fullDate) return `${date} ${month}`
  else return `${date} ${month} ${year}`
})

Vue.filter('initials', function (string) {
  var names = string.split(' '),
  initials = names[0].substring(0, 1).toUpperCase();

  if (names.length > 1) {
    initials += names[names.length - 1].substring(0, 1).toUpperCase();
  }
  return initials;
})

Vue.filter('formatCurrency', function( number, cur = "TNG" ) {
  const decimal=0;
  const separator=' ';
  const decpoint = '.';
  const format_string = '# ₸';

  var r=parseFloat(number)

  var exp10=Math.pow(10,decimal);// приводим к правильному множителю
  r=Math.round(r*exp10)/exp10;// округляем до необходимого числа знаков после запятой

  const rr=Number(r).toFixed(decimal).toString().split('.');

  const b = rr[0].replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1"+separator);

  r = (rr[1]?b+ decpoint +rr[1]:b);

  return format_string.replace('#', r);
})
