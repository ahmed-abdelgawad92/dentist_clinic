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
//Validate number
function validateNumber(number){
  return number.match(/^[0-9]+(\.?[0-9]+)?$/);
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
function getToothName(tooth) {
  console.log("called");
  switch (tooth) {
    case 1:
      return "Upper Right Quadrant: Wisdom Tooth (3rd Molar)";
    break;
    case 2:
      return "Upper Right Quadrant: Molar (2nd Molar)";
    break;
    case 3:
      return "Upper Right Quadrant: Molar (1st Molar)";
    break;
    case 4:
      return "Upper Right Quadrant: Bicuspid (2nd)";
    break;
    case 5:
      return "Upper Right Quadrant: Bicuspid (1st)";
    break;
    case 6:
      return "Upper Right Quadrant: Canine (Eye tooth / Cuspid)";
    break;
    case 7:
      return "Upper Right Quadrant: Incisor (Lateral)";
    break;
    case 8:
      return "Upper Right Quadrant: Incisor (Central)";
    break;
    case 9:
      return "Upper Left Quadrant: Incisor (Central)";
    break;
    case 10:
      return "Upper Left Quadrant: Incisor (Lateral)";
    break;
    case 11:
      return "Upper Left Quadrant: Canine (Eye tooth / Cuspid)";
    break;
    case 12:
      return "Upper Left Quadrant: Bicuspid (1st)";
    break;
    case 13:
      return "Upper Left Quadrant: Bicuspid (2nd)";
    break;
    case 14:
      return "Upper Left Quadrant: Molar (1st)";
    break;
    case 15:
      return "Upper Left Quadrant: Molar (2nd)";
    break;
    case 16:
      return "Upper Left Quadrant: Wisdom Tooth (3rd Molar)";
    break;
    case 17:
      return "Lower Left Quadrant: Wisdom Tooth (3rd Molar)";
    break;
    case 18:
      return "Lower Left Quadrant: Molar (2nd Molar)";
    break;
    case 19:
      return "Lower Left Quadrant: Molar (1st Molar)";
    break;
    case 20:
      return "Lower Left Quadrant: Bicuspid (2nd)";
    break;
    case 21:
      return "Lower Left Quadrant: Bicuspid (1st)";
    break;
    case 22:
      return "Lower Left Quadrant: Canine (Eye tooth / Cuspid)";
    break;
    case 23:
      return "Lower Left Quadrant: Incisor (Lateral)";
    break;
    case 24:
      return "Lower Left Quadrant: Incisor (Central)";
    break;
    case 25:
      return "Lower Right Quadrant: Incisor (Central)";
    break;
    case 26:
      return "Lower Right Quadrant: Incisor (Lateral)";
    break;
    case 27:
      return "Lower Right Quadrant: Canine (Eye tooth / Cuspid)";
    break;
    case 28:
      return "Lower Right Quadrant: Bicuspid (1st)";
    break;
    case 29:
      return "Lower Right Quadrant: Bicuspid (2nd)";
    break;
    case 30:
      return "Lower Right Quadrant: Molar (1st Molar)";
    break;
    case 31:
      return "Lower Right Quadrant: Molar (2nd Molar)";
    break;
    case 32:
      return "Lower Right Quadrant: Wisdom Tooth (3rd Molar)";
    break;
  }

}
