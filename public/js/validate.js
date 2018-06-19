function assignError(element,errorMsg) {
  element.addClass("is-invalid");
  element.after("<div style='display:block' class='invalid-feedback'>"+errorMsg+"</div>");
}
//validate empty string
function validateNotEmpty(str) {
  if(str.length>0)
  return true;
  else
  return false;
}
//validate a username
function validateUname(uname) {
  return uname.match(/^([a-zA-Z]+([\._@\-][a-zA-Z]+)*){3,}$/);
}
//validate Alphabet
function validateAlphabet(str) {
  return str.match(/[a-zA-Z\s_]+/);
}
//validate Date
function validateDate(date){
  return date.match(/^([0-9]{4}\-((01|03|05|07|08|10|12)\-(0[0-9]|1[0-9]|2[0-9]|3[0-1])|(04|06|09|11)\-(0[0-9]|1[0-9]|2[0-9]|30)|(02)\-(0[0-9]|1[0-9]|2[0-9])))$/);
}
//validate phone number
function validatePhone(phone) {
  return phone.match(/^(\+)?[0-9]{8,15}$/);
}
//validate bool enum
function validateBoolEnum(enumValue) {
  return enumValue.match(/^(0|1)$/);
}
//validate bool enum
function validateBloodEnum(enumValue) {
  return enumValue.match(/^(low|normal|high)$/);
}
// validate Photo
function validatePhoto(photo) {
  var ext = photo.split(".").pop().toLowerCase();
  switch (ext) {
    case "png":
      return true;
      break;
    case "jpg":
      return true;
      break;
    case "jpeg":
      return true;
      break;
    case "git":
      return true;
      break;
    default:
      return false;
  }
}
